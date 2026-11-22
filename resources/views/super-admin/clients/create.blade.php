@extends('layouts.app')

@section('title', 'Invite Client')

@section('content')
<div class="page-header">
    <div>
        <h1>Invite Client</h1>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('super-admin.clients.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Client Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter client or company name"
                required
            >
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Client Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter administrator email"
                required
            >
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Invite
        </button>
    </form>
</div>
@endsection