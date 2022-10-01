@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('Home') }}</li>
            </ol>
        </nav>
        <div class="row mb-3">
            @livewire('series.upcoming-series')
            @livewire('overview')
        </div>
        <div class="row mb-3">
            @livewire('series.reading-stack')
        </div>
        @foreach ($categories as $category)
            @if ($category->type == 0)
                @livewire('series.gallery', [$category])
            @elseif($category->type == 1)
                @livewire('articles.gallery', [$category])
            @endif
        @endforeach
    </div>
@endsection
