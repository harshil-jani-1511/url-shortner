@if($urls->isEmpty())
    <p class="empty-message">No URLs found for this period.</p>
@else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    @if($showCompany)
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th>Hits</th>
                        <th>Company Name</th>
                        <th>Created On</th>
                    @else
                        <th>Created By</th>
                        <th>Original URL</th>
                        <th>Short URL</th>
                        <th>Clicks</th>
                        <th>Created At</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($urls as $url)
                    <tr>
                        @if($showCompany)
                            <td>
                                <a href="{{ route('short-urls.redirect', ['shortCode' => $url->short_code]) }}"
                                   target="_blank">
                                    {{ url('/s/' . $url->short_code) }}
                                </a>
                            </td>

                            <td style="max-width: 220px; overflow-wrap: anywhere;">
                                {{ $url->original_url }}
                            </td>

                            <td>{{ $url->clicks_count }}</td>
                            <td>{{ $url->company?->name ?? 'N/A' }}</td>
                            <td>{{ $url->created_at?->format('d M Y') }}</td>
                        @else
                            <td>{{ $url->user?->name ?? 'N/A' }}</td>

                            <td style="max-width: 220px; overflow-wrap: anywhere;">
                                {{ $url->original_url }}
                            </td>

                            <td>
                                <a href="{{ route('short-urls.redirect', ['shortCode' => $url->short_code]) }}"
                                   target="_blank">
                                    {{ route('short-urls.redirect', ['shortCode' => $url->short_code]) }}
                                </a>
                            </td>

                            <td>{{ $url->clicks_count }}</td>
                            <td>{{ $url->created_at?->format('d M Y') }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif