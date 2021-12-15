<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ $series->name }}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-3 d-flex align-self-stretch justify-content-center my-2">
            <img src="{{ $series->image }}" alt="{{ $series->image }}" class="rounded" style="max-height: 400px; object-fit: contain;">
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 my-2 pl-4">
            <div>
                <h1 style="display: inline;">{{ $series->name }}</h1>
                <div class="float-end" style="display: inline;">
                    <a href="{{ route('series.edit', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-edit"></i></a>
                    <a href="https://www.thalia.de/suche?sq={{ urlencode($series->name) }}&sort=sfed&allayout=FLAT" class="btn btn-link" target="_blank"><i class="fas fa-search"></i></a>
                    <a href="https://www.amazon.de/s?k={{ urlencode($series->name) }}&i=stripbooks&s=date-desc-rank" class="btn btn-link" target="_blank"><i class="fab fa-amazon"></i></a>
                </div>
            </div>
            <div class="mt-3">
                <div>{{ __('Status') }}: <span class="{{ $series->status_class }}">{{ $series->status_name }}</span></div>
                <div>{{ __('New') }}: <span class="badge rounded-pill bg-danger">{{ $series->new_books_count }}</span></div>
                <div>{{ __('Ordered') }}: <span class="badge rounded-pill bg-warning">{{ $series->ordered_books_count }}</span></div>
                <div>{{ __('Delivered') }}: <span class="badge rounded-pill bg-success">{{ $series->delivered_books_count }}</span></div>
                <div>{{ __('Total') }}: {{ isset($series->total) ? $series->total : '?' }}</div>
            </div>
            @livewire('series.books-table', [$category, $series])
        </div>
    </div>
</div>
