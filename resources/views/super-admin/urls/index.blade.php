
@extends('layouts.app')

@section('title', 'All URLs')
@section('page-heading', 'Clients')
@section('content')
<div class="page-header">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Generated Short URLs</h2>
    
        @if ($urls->isEmpty())
            <p>No URLs found.</p>
        @else
        <div>
            <form method="GET" action="{{ route('super-admin.urls.index') }}" class="filter-form">
                <div class="form-group">
                    <!-- <label for="date_filter">Date Filter</label> -->
                    <select name="date_filter" id="date_filter" onchange="this.form.submit()">
                        <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_week" {{ $dateFilter === 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="last_month" {{ $dateFilter === 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="custom" {{ $dateFilter === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                @if ($dateFilter === 'custom')
                    <div class="form-group">
                        <label>From</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" required>
                    </div>

                    <div class="form-group">
                        <label>To</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Apply</button>
                @endif
            </form>
            <a href="{{ route('super-admin.urls.export', request()->query()) }}" class="btn btn-primary">
                Download
            </a>
        </div>
    </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Original URL</th>
                        <th>Short URL</th>
                        <th>Company</th>
                        <th>Created By</th>
                        <th>Clicks</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($urls as $url)
                        <tr>
                            <td style="max-width: 250px; overflow-wrap: anywhere;">
                                {{ $url->original_url }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('short-urls.redirect', ['shortCode' => $url->short_code]) }}"
                                    target="_blank"
                                >
                                    {{ url('/s/' . $url->short_code) }}
                                </a>
                            </td>

                            <td>{{ $url->company?->name ?? 'N/A' }}</td>

                            <td>{{ $url->user?->name ?? 'N/A' }}</td>

                            <td>{{ $url->clicks_count }}</td>

                            <td>{{ $url->created_at?->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination pt-3">
            {{ $urls->links() }}
        </div>
    @endif
</div>
@endsection
