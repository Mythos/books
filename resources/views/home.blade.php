@extends('layouts.app')

@section('content')
    <div class="container">
        @include('upcoming', [$upcoming])
        @foreach ($categories as $category)
            @include('categories.gallery', [$category])
        @endforeach
    </div>
@endsection
