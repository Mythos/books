<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
    <div class="card shadow-sm mb-4" style="width: 30rem;">
        <img src="{{ $article->image }}" alt="{{ $article->name }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
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
