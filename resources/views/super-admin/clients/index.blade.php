@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="page-header">
   <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>

    <a href="{{ route('super-admin.clients.create') }}" class="btn btn-primary">
        Invite
    </a>
</div>

<div class="card">
    <h2>All Clients</h2>

    @if ($clients->isEmpty())
        <p>No clients found.</p>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Total Users</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->users_count }}</td>
                            <td>{{ $client->urls_count }}</td>
                            <td>{{ $client->url_clicks_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection