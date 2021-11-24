@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach ($categories as $category)
                <div>
                    <h1 style="display: inline;">{{ $category->name }}</h1>
                    <div class="float-right">
                        <a href="{{ route('series.create', [$category]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
                        <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-link"><i class="fas fa-edit"></i></a>
                    </div>
                </div>
                <div class="row" style="padding: 1rem 0;">
                    @foreach ($category->series()->orderBy('name')->get() as $series)
                        <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
                            <div class="card shadow-sm mb-4" style="width: 30rem;">
                                <img src="{{ $series->image }}" alt="{{ $series->image }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-uppercase">{{ $series->name }}</h5>
                                    <div class="mt-auto">
                                        <div class="float-left">
                                            <span class="{{ $series->status_class }}">{{ $series->status_name }}</span>
                                        </div>
                                        <div class="float-right">
                                            <span class="@if ($series->new_books_count > 0) new @endif">@if ($series->new_books_count > 0) {{ $series->new_books_count }} @else - @endif</span>
                                            / <span class="@if ($series->ordered_books_count > 0) ordered @endif">@if ($series->ordered_books_count > 0) {{ $series->ordered_books_count }} @else - @endif</span>
                                            / <span class="@if ($series->delivered_books_count > 0) delivered @endif">@if ($series->delivered_books_count > 0) {{ $series->delivered_books_count }} @else - @endif</span>
                                            / <span class="total">{{ isset($series->total) ? $series->total : '?' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('series.show', [$category, $series]) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        @endforeach
    </div>

    <style>
        .new {
            color: red;
        }
        .ordered {
            color: orange;
        }
        .delivered {
            color: green;
        }
    </style>
@endsection
