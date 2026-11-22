<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuperAdminUrlController extends Controller
{
    
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date_filter' => ['nullable', 'in:all,today,last_week,last_month,this_month,custom'],
            'from_date' => ['nullable', 'required_if:date_filter,custom', 'date'],
            'to_date' => ['nullable', 'required_if:date_filter,custom', 'date', 'after_or_equal:from_date'],
        ]);

        $fromDate = $validated['from_date'] ?? null;
        $toDate = $validated['to_date'] ?? null;

        $dateFilter = $request->query('date_filter', 'all');

        $query = Url::with([
            'company',
            'user',
        ])->withCount('clicks');

        switch ($dateFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;

            case 'last_week':
                $startOfLastWeek = now()->startOfWeek()->subWeek();
                $endOfLastWeek = now()->startOfWeek()->subSecond();

                $query->whereBetween('created_at', [
                    $startOfLastWeek,
                    $endOfLastWeek,
                ]);
                break;

            case 'last_month':
                $startOfLastMonth = now()->subMonthNoOverflow()->startOfMonth();
                $endOfLastMonth = now()->startOfMonth()->subSecond();

                $query->whereBetween('created_at', [
                    $startOfLastMonth,
                    $endOfLastMonth,
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
                    \Carbon\Carbon::parse($fromDate)->startOfDay(),
                    \Carbon\Carbon::parse($toDate)->endOfDay(),
                ]);
                break;

            case 'all':
            default:
                $dateFilter = 'all';
                break;
        }

        $urls = $query->latest()
            ->paginate(3)
            ->withQueryString();

        return view('super-admin.urls.index', compact(
            'urls',
            'dateFilter'
        ));
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

        $dateFilter = $validated['date_filter'] ?? 'all';

        $query = Url::with(['company', 'user'])
            ->withCount('clicks');

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

            // CSV column headings
            fputcsv($file, [
                'Client',
                'Created By',
                'Original URL',
                'Short Code',
                'Short URL',
                'Total Clicks',
                'Created At',
            ]);

            // chunk for download
            $query->chunk(500, function ($urls) use ($file) {

                foreach ($urls as $url) {
                    fputcsv($file, [
                        $url->company?->name ?? 'N/A',
                        $url->user?->name ?? 'N/A',
                        $url->original_url,
                        $url->short_code,
                        route('short-urls.redirect', ['shortCode' => $url->short_code]),
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
