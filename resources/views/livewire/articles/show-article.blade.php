<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$category]) }}">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $article->name }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded py-2">
        <div class="col-sm-12 col-md-12 col-lg-3 d-flex align-self-stretch justify-content-center my-2">
            <img src="{{ $article->image }}" alt="{{ $article->name }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 my-2 pl-4">
            <div>
                <h1 style="display: inline;">{{ $article->name }}</h1>
                <div class="float-end" style="display: inline;">
                    <a href="{{ route('article.edit', [$category, $article]) }}" class="btn btn-link"><span class="fas fa-edit"></span></a>
                    <a href="https://www.amazon.de/s?k={{ urlencode($article->name) }}" class="btn btn-link" target="_blank"><span class="fab fa-amazon"></span></a>
                </div>
            </div>
            <div class="mt-3">
                <div>{{ __('Price') }}: <span>{{ number_format($article->price, 2) }} {{ config('app.currency') }}</span></div>
                <div>{{ __('Release Date') }}: <span>{{ $article->release_date }}</span></div>
                <div>{{ __('Status') }}: <span class="{{ $article->status_class }}">{{ $article->status_name }}</span></div>
            </div>
        </div>
    </div>
</div>
