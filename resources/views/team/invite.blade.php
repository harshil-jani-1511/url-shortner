
@extends('layouts.app')

@section('title', 'Invite Team Member')

@section('content')
<div class="page-header">
    <div>
        <h1>Invite Team Member</h1>
        <p>Invite a new member to your company.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <h2>Invite Team Member</h2>

    <form method="POST" action="{{ route('team.invitations.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">User Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter user's full name"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter email address"
                required
            >
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">Select role</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
                <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>
                    Member
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Send Invitation
        </button>
    </form>
</div>
@endsection
