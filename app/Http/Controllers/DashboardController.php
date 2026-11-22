<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Url;
use App\Models\UrlClick;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date_filter' => [
                'nullable',
                'in:all,today,last_week,last_month,this_month,custom',
            ],
            'from_date' => [
                'nullable',
                'required_if:date_filter,custom',
                'date',
            ],
            'to_date' => [
                'nullable',
                'required_if:date_filter,custom',
                'date',
                'after_or_equal:from_date',
            ],
        ]);

        $user = auth()->user();

        $dateFilter = $validated['date_filter'] ?? 'all';
        $fromDate = $validated['from_date'] ?? null;
        $toDate = $validated['to_date'] ?? null;

        $startDate = null;
        $endDate = null;

        switch ($dateFilter) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;

            case 'last_week':
                $startDate = now()->startOfWeek()->subWeek();
                $endDate = now()->startOfWeek()->subSecond();
                break;

            case 'last_month':
                $startDate = now()->subMonthNoOverflow()->startOfMonth();
                $endDate = now()->startOfMonth()->subSecond();
                break;

            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;

            case 'custom':
                $startDate = Carbon::parse($fromDate)->startOfDay();
                $endDate = Carbon::parse($toDate)->endOfDay();
                break;
        }

        // Get URLs
        $urlQuery = Url::query();

        if ($user->isAdmin()) {
            $urlQuery->where('company_id', $user->company_id);
        }

        if ($user->isMember()) {
            $urlQuery->where('user_id', $user->id);
        }

        if ($startDate && $endDate) {
            $urlQuery->whereBetween('created_at', [
                $startDate,
                $endDate,
            ]);
        }

        // Get clicks
        $clickQuery = UrlClick::whereHas('url', function ($query) use ($user) {
            if ($user->isAdmin()) {
                $query->where('company_id', $user->company_id);
            }

            if ($user->isMember()) {
                $query->where('user_id', $user->id);
            }
        });

        if ($startDate && $endDate) {
            $clickQuery->whereBetween('clicked_at', [
                $startDate,
                $endDate,
            ]);
        }

        $totalUrls = (clone $urlQuery)->count();
        $totalClicks = (clone $clickQuery)->count();

        // recent urls for admin
        $recentUrls = (clone $urlQuery)
            ->with(['company', 'user'])
            ->withCount([
                'clicks' => function ($query) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        $query->whereBetween('clicked_at', [
                            $startDate,
                            $endDate,
                        ]);
                    }
                },
            ])
            ->latest()
            ->take(3)
            ->get();

        // member urls
        $memberUrls = $user->isMember()
            ? (clone $urlQuery)
                ->with(['company', 'user'])
                ->withCount([
                    'clicks' => function ($query) use ($startDate, $endDate) {
                        if ($startDate && $endDate) {
                            $query->whereBetween('clicked_at', [
                                $startDate,
                                $endDate,
                            ]);
                        }
                    },
                ])
                ->latest()
                ->paginate(3)
                ->withQueryString()
            : collect();


        // get clients
        $clients = $user->isSuperAdmin()
            ? Company::withCount([
                'users',
                'urls',
                'urlClicks',
            ])->latest()->take(3)->get()
            : collect();

        // get team members
        $teamMembers = $user->isAdmin()
            ? User::where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->latest()
                ->take(3)
                ->get()
            : collect();

        $totalClients = $user->isSuperAdmin()
            ? Company::count()
            : null;

        return view('dashboard', compact(
            'totalUrls',
            'totalClicks',
            'recentUrls',
            'totalClients',
            'clients',
            'teamMembers',
            'dateFilter',
            'fromDate',
            'toDate',
            'memberUrls'
        ));
    }
}
