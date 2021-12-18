<div>
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
                                        @if ($book->status == 0)<a wire:click.prevent='ordered({{ $book->id }})' href="#" title="{{ __('Sets the status to Ordered') }}"><i class="fa fa-shopping-cart"></i></a>@endif
                                        @if ($book->status == 1)<a wire:click.prevent='delivered({{ $book->id }})' href="#" title="{{ __('Sets the status to Delivered') }}"><i class="fa fa-check"></i></a>@endif
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

</div>
