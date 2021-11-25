<div>
    <h1 style="display: inline;">{{ __('Upcoming Releases') }}</h1>
</div>
<div class="row" style="padding: 1rem 0;">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card shadow-sm mb-12">
            <div class="card-body d-flex flex-column table-responsive" style="height: 250px; overflow-y: scroll;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Publish date</th>
                            <th scope="col">Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming as $book)
                        <tr class="{{ $book->status_class }}">
                            <th scope="row">{{ $book->publish_date }}</th>
                            <td>{{ $book->series->name }} {{ $book->number }}</td>
                        </tr>
                        @endforeach
                        @if(count($upcoming) == 0)
                            <tr>
                                <td colspan="2" style="text-align: center;">No data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
