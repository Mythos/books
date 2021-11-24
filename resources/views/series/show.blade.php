@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="padding: 1rem 0;">
            <div class="col-sm-12 col-md-12 col-lg-4 d-flex align-self-stretch">
                <img src="{{ $series->image }}" alt="{{ $series->image }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
            </div>
            <div class="col-sm-12 col-md-12 col-lg-8">
                <div>
                    <h1 style="display: inline;">{{ $series->name }}</h1>
                    <div class="float-right" style="display: inline;">
                        <a href="{{ route('series.edit', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-edit"></i></a>
                    </div>
                </div>
                <div style="padding: 1rem 0;">
                    {{ __('Status') }}: <span class="{{ $series->status_class }}">{{ $series->status_name }}</span>
                    {{ __('Total') }}: {{ isset($series->total) ? $series->total : '?' }}
                </div>
                <div style="padding: 1rem 0;">
                    <div>
                        <h2 style="display: inline;">{{ __('Volumes') }}</h2>
                        <div class="float-right" style="display: inline;">
                            <a href="{{ route('books.create', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    {{-- <h2>{{ __('Volumes') }}</h2> --}}
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Publish date</th>
                                    <th scope="col">ISBN</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($series->books()->orderBy('publish_date')->get() as $book)
                                    <tr class="{{ $book->status_class }}">
                                        <th scope="row">{{ $book->number }}</th>
                                        <td>{{ $book->publish_date }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ $book->status_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
