@section('title')
    {{ $article->name }}
@endsection

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
        <div class="col-sm-12 col-md-12 col-lg-9 mb-2 pl-4">
            <div class="text-end">
                @if (config('app.debug'))
                    <span class="badge bg-secondary">ID: {{ $article->id }}</span>
                @endif
                <a href="{{ route('article.edit', [$category, $article]) }}" class="btn btn-link py-0 px-2"><span class="fas fa-edit"></span></a>
                <a href="https://www.amazon.de/s?k={{ urlencode($article->name) }}" class="btn btn-link py-0 px-2" target="_blank"><span class="fab fa-amazon"></span></a>
            </div>
            <div>
                <h1 class="my-0">{{ $article->name }}</h1>
            </div>
            <div class="mt-3">
                <div>{{ __('Price') }}: <span>{{ number_format($article->price, 2) }} {{ config('app.currency') }}</span></div>
                <div>{{ __('Release Date') }}: <span>{{ $article->release_date_formatted }}</span></div>
                <div>{{ __('Status') }}: <span class="{{ $article->status_class }}">{{ $article->status_name }}</span></div>
            </div>
        </div>
    </div>
</div>
