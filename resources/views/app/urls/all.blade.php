@extends('layouts.app')

@section('title', 'All Generated URLs')

@section('content')
<div class="page-header">

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="section-header">
        <h1>Generated Short URLs</h1>
        {{-- Date Filter and Download Button --}}
        @include('dashboard.partials.url-filters', [
            'downloadRoute' => route('app.urls.export')
        ])
    </div>


    {{-- All Generated URLs --}}
    @include('dashboard.partials.url-list', [
        'urls' => $urls,
        'showCompany' => false
    ])

    {{-- Pagination --}}
    <div class="pagination pt-3">
        {{ $urls->links() }}
    </div>

</div>
@endsection