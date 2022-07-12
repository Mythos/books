<div class="bg-white shadow-sm rounded my-2 px-3 py-2">
    <div class="my-2">
        <h1 style="display: inline;">
            <a class="text-dark text-decoration-none" href="{{ route('categories.show', [$category]) }}">
                {{ $category->name }}

                <small class="text-muted">({{ $total }} {{ __('Articles') }})</small>
            </a>
        </h1>
        <div class="float-end">
            @if ($category->type == 0)
                <a href="{{ route('articles.create', [$category]) }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            @elseif ($category->type == 1)
                <a href="{{ route('article.create', [$category]) }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            @endif
            <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-link"><span class="fas fa-edit"></span></a>
        </div>
    </div>
    <div class="row mt-2">
        @if (count($articles) == 0)
            <div class="text-center">
                {{ __('No data') }}
            </div>
        @else
            @foreach ($articles as $article)
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
                    <div class="card shadow-sm mb-4" style="width: 30rem;">
                        <img src="{{ $article->image }}" alt="{{ $article->name }}" class="card-img-top" style="height: 400px; object-fit: contain;" loading="lazy" decoding="async">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-uppercase">{{ $article->name }}</h5>
                            <div class="mt-auto">
                                <div class="float-start">
                                    <span class="{{ $article->status_class }}">{{ $article->status_name }}</span>
                                </div>
                            </div>
                            <a href="{{ route('article.show', [$category, $article]) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if (!empty($category->page_size))
                {{ $articles->links() }}
            @endif
        @endif
    </div>
</div>
