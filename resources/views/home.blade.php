@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('Home') }}</li>
            </ol>
        </nav>
        <div class="row mb-3">
            <livewire:series.upcoming-series />
            <livewire:global-statistics />
        </div>
        @foreach ($categories as $category)
            @livewire('series.gallery', [$category])
        @endforeach
    </div>
@endsection
