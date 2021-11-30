<div class="mt-3">
    <div>
        <h2 style="display: inline;">{{ __('Volumes') }}</h2>
        <div class="float-end" style="display: inline;">
            <a href="{{ route('books.create', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
        </div>
    </div>
    <div class="table-responsive" style="width: 100%;">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="min-width: 2rem;">#</th>
                    <th scope="col" style="min-width: 7rem;">{{ __('Publish date') }}</th>
                    <th scope="col" style="min-width: 10rem;">{{ __('ISBN') }}</th>
                    <th scope="col" style="min-width: 7rem;">{{ __('Status') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr class="{{ $book->status_class }}">
                        <th scope="row">{{ $book->number }}</th>
                        <td>{{ $book->publish_date }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->status_name }}</td>
                        <td>
                            @if ($book->status == 0)<a data-type="book-set-status" data-status="1" data-context="upcoming" wire:click.prevent='ordered({{ $book->id }})' href="#"><i class="fa fa-shopping-cart"></i></a>@endif
                            @if ($book->status == 1)<a data-type="book-set-status" data-status="2" data-context="upcoming" wire:click.prevent='delivered({{ $book->id }})' href="#"><i class="fa fa-check"></i></a>@endif
                        </td>
                    </tr>
                @endforeach
                @if($books->count() == 0)
                    <tr>
                        <td colspan="5" style="text-align: center;">No data</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
