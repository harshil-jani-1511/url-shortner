
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $isSuperAdmin = auth()->user()->isSuperAdmin();
    $isAdmin = auth()->user()->isAdmin();
    $isMember = auth()->user()->isMember();
@endphp

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome, {{ auth()->user()->name }}.</p>
    </div>
</div>

{{-- SUPER ADMIN DASHBOARD --}}
@if($isSuperAdmin)

    {{-- Clients Section --}}
    <div class="card">
        <div class="section-header">
            <h2>Clients</h2>

            <a href="{{ route('super-admin.clients.create') }}" class="btn btn-primary">
                Invite
            </a>
        </div>

        @if ($clients->isEmpty())
            <p class="empty-message">No clients found.</p>
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
                        @foreach ($clients->take(2) as $client)
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

            @if ($clients->count() > 2)
                <div class="section-footer">
                    <a href="{{ route('super-admin.clients.index') }}">View All Clients</a>
                </div>
            @endif
        @endif
    </div>

    {{-- Generated URLs Section --}}
    <div class="card">
        <div class="section-header">
            <h2>Generated Short URLs</h2>
            @include('dashboard.partials.url-filters', [
                'downloadRoute' => route('super-admin.urls.export')
            ])
        </div>


        @include('dashboard.partials.url-list', [
            'urls' => $recentUrls->take(2),
            'showCompany' => true
        ])

        @if ($recentUrls->count() > 2)
            <div class="section-footer">
                <a href="{{ route('super-admin.urls.index', request()->query()) }}">
                    View All URLs
                </a>
            </div>
        @endif
    </div>

@endif

{{-- ADMIN DASHBOARD --}}
@if($isAdmin)

    {{-- Generated URLs Section --}}
    <div class="card">
        
        <div class="section-header">
            <div class="header-content-admin">
                <h2>Generated Short URLs</h2>
                <a href="{{ route('app.urls.index') }}" class="btn btn-primary">
                    Generate
                </a>
            </div>
            @include('dashboard.partials.url-filters', [
                'downloadRoute' => route('app.urls.export')
            ])
        </div>

        @include('dashboard.partials.url-list', [
            'urls' => $recentUrls,
            'showCompany' => false
        ])

        <div class="section-footer">
            <a href="{{ route('app.urls.all') }}">View All URLs</a>
        </div>
    </div>

    {{-- Team Members Section --}}
    <div class="card">
        <div class="section-header">
            <h2>Team Members</h2>
            <a href="{{ route('team.invite') }}" class="btn btn-primary">
                Invite
            </a>
        </div>

        @if($teamMembers->isEmpty())
            <p class="empty-message">No team members found.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teamMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ ucfirst($member->role) }}</td>
                                <td>{{ $member->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="section-footer">
            <a href="{{ route('team.members.index') }}">View All</a>
        </div>
    </div>

@endif

{{-- MEMBER DASHBOARD --}}
@if($isMember)

    <div class="card">
        <div class="section-header">
            <div class="header-content-admin">
                <h2>Generated Short URLs</h2>
                <a href="{{ route('app.urls.index') }}" class="btn btn-primary">
                    Generate
                </a>
            </div>
            @include('dashboard.partials.url-filters', [
                'downloadRoute' => route('app.urls.export')
            ])
        </div>

        @include('dashboard.partials.url-list', [
            'urls' => $memberUrls,
            'showCompany' => false
        ])
        <div class="pagination pt-3">
            {{ $memberUrls->links() }}
        </div>
    </div>

@endif

@endsection
