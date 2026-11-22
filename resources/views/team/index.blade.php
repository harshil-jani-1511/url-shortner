@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
<div class="page-header">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

<div class="card">
    <h2>Team Members</h2>

    @if ($members->isEmpty())
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
                    @foreach ($members as $member)
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
        <div class="pagination pt-3">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection