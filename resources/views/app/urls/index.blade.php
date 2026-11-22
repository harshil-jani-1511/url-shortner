@extends('layouts.app')

@section('title', 'Generate URL')

@section('content')
<div class="page-header">
    <div>
        <h1>Generate Short URL</h1>
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
    <form method="POST" action="{{ route('app.urls.store') }}">
        @csrf

        <div class="form-group">
            <label for="original_url">Long URL</label>
            <input
                type="url"
                id="original_url"
                name="original_url"
                value="{{ old('original_url') }}"
                placeholder="https://example.com"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Generate
        </button>
    </form>
</div>
@endsection