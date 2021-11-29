@extends('layouts.app')

@section('content')
    <div class="container">
        <livewire:series.upcoming-series />
        @foreach ($categories as $category)
            @include('categories.gallery', [$category])
        @endforeach
    </div>
@endsection
