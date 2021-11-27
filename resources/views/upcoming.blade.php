<div class="row mb-3">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card shadow-sm mb-12">
            <div class="card-header">{{ __('Upcoming Releases') }}</div>
            <div class="card-body d-flex flex-column table-responsive p-0" style="height: 250px; overflow-y: scroll;">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="min-width: 7rem;">{{ __('Publish date') }}</th>
                            <th scope="col" style="min-width: 25rem;">{{ __('Title') }}</th>
                            <th scope="col" style="min-width: 11rem;">{{ __('ISBN') }}</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming as $book)
                            <tr class="{{ $book->status_class }}">
                                <th scope="row">{{ $book->publish_date }}</th>
                                <td>{{ $book->series->name }} {{ $book->number }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>
                                    @if ($book->status == 0)<a data-type="book-set-status" data-status="1" data-context="upcoming" href="{{ route('books.ordered', [$book->series->category, $book->series, $book->number]) }}"><i class="fa fa-shopping-cart"></i></a>@endif
                                    @if ($book->status == 1)<a data-type="book-set-status" data-status="2" data-context="upcoming" href="{{ route('books.delivered', [$book->series->category, $book->series, $book->number]) }}"><i class="fa fa-check"></i></a>@endif
                                </td>
                            </tr>
                        @endforeach
                        @if (count($upcoming) == 0)
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
