<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UrlController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $query = Url::with(['user'])->withCount('clicks');

        if ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        } elseif ($user->isMember()) {
            $query->where('user_id', $user->id);
        } else {
            abort(403, 'You are not authorized to view URLs.');
        }

        $urls = $query->latest()->paginate(3);

        return view('app.urls.index', compact('urls'));
    }

    public function all(Request $request): View
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

        $query = Url::with(['user'])->withCount('clicks');

        if ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        } elseif ($user->isMember()) {
            $query->where('user_id', $user->id);
        } else {
            abort(403, 'You are not authorized to view URLs.');
        }

        $dateFilter = $validated['date_filter'] ?? 'all';
        $fromDate = $validated['from_date'] ?? null;
        $toDate = $validated['to_date'] ?? null;
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;

            case 'last_week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek()->subWeek(),
                    now()->startOfWeek()->subSecond(),
                ]);
                break;

            case 'last_month':
                $query->whereBetween('created_at', [
                    now()->subMonthNoOverflow()->startOfMonth(),
                    now()->startOfMonth()->subSecond(),
                ]);
                break;

            case 'this_month':
                $query->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]);
                break;

            case 'custom':
                $query->whereBetween('created_at', [
                    Carbon::parse($validated['from_date'])->startOfDay(),
                    Carbon::parse($validated['to_date'])->endOfDay(),
                ]);
                break;
        }

        $urls = $query->latest()
            ->paginate(3)
            ->withQueryString();

        return view('app.urls.all', compact(
            'urls',
            'dateFilter',
            'fromDate',
            'toDate'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        $user = auth()->user();

        do {
            $shortCode = Str::random(8);
        } while (Url::withTrashed()->where('short_code', $shortCode)->exists());

        Url::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
        ]);

        $shortUrl = url('/s/' . $shortCode);

        return redirect()
            ->route('app.urls.index')
            ->with('success', 'Short URL created successfully.')
            ->with('short_url', $shortUrl);
    }

    public function export(Request $request): StreamedResponse
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

        // Restrict exported URLse.
        $query = Url::with(['company', 'user'])
            ->withCount('clicks');

        if ($user->isAdmin()) {
            $query->where('company_id', $user->company_id);
        } elseif ($user->isMember()) {
            $query->where('user_id', $user->id);
        } else {
            abort(403, 'You are not authorized to export URLs.');
        }

        $dateFilter = $validated['date_filter'] ?? 'all';

        switch ($dateFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;

            case 'last_week':
                $query->whereBetween('created_at', [
                    now()->startOfWeek()->subWeek(),
                    now()->startOfWeek()->subSecond(),
                ]);
                break;

            case 'last_month':
                $query->whereBetween('created_at', [
                    now()->subMonthNoOverflow()->startOfMonth(),
                    now()->startOfMonth()->subSecond(),
                ]);
                break;

            case 'this_month':
                $query->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]);
                break;

            case 'custom':
                $query->whereBetween('created_at', [
                    Carbon::parse($validated['from_date'])->startOfDay(),
                    Carbon::parse($validated['to_date'])->endOfDay(),
                ]);
                break;
        }

        return response()->streamDownload(function () use ($query) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Client',
                'Created By',
                'Original URL',
                'Short Code',
                'Short URL',
                'Total Clicks',
                'Created At',
            ]);

            $query->chunk(500, function ($urls) use ($file) {

                foreach ($urls as $url) {
                    fputcsv($file, [
                        $url->company?->name ?? 'N/A',
                        $url->user?->name ?? 'N/A',
                        $url->original_url,
                        $url->short_code,
                        route('short-urls.redirect', [
                            'shortCode' => $url->short_code,
                        ]),
                        $url->clicks_count,
                        $url->created_at,
                    ]);
                }
            });

            fclose($file);

        }, 'generated-urls-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
