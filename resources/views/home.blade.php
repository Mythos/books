@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('Home') }}</li>
            </ol>
        </nav>
        <livewire:series.upcoming-series />
        @foreach ($categories as $category)
            @livewire('series.gallery', [$category])
        @endforeach
    </div>
@endsection
