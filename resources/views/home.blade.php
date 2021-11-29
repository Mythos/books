@extends('layouts.app')

@section('content')
    <div class="container">
        <livewire:series.upcoming-series />
        @foreach ($categories as $category)
            @livewire('series.gallery', [$category])
        @endforeach
    </div>
@endsection
