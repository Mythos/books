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
                    <div>{{ __('Status') }}: <span class="{{ $series->status_class }}">{{ $series->status_name }}</span></div>
                    <div>{{ __('New') }}: <span class="badge badge-pill badge-danger">{{ $series->new_books_count }}</span></div>
                    <div>{{ __('Ordered') }}: <span class="badge badge-pill badge-warning">{{ $series->ordered_books_count }}</span></div>
                    <div>{{ __('Delivered') }}: <span class="badge badge-pill badge-success">{{ $series->delivered_books_count }}</span></div>
                    <div>{{ __('Total') }}: {{ isset($series->total) ? $series->total : '?' }}</div>
                </div>
                <div style="padding: 1rem 0;">
                    <div>
                        <h2 style="display: inline;">{{ __('Volumes') }}</h2>
                        <div class="float-right" style="display: inline;">
                            <a href="{{ route('books.create', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Publish date</th>
                                    <th scope="col">ISBN</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($series->books->sortBy('publish_date') as $book)
                                    <tr class="{{ $book->status_class }}">
                                        <th scope="row">{{ $book->number }}</th>
                                        <td>{{ $book->publish_date }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ $book->status_name }}</td>
                                    </tr>
                                @endforeach
                                @if($series->books->count() == 0)
                                    <tr>
                                        <td colspan="4" style="text-align: center;">No data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
