<div class="bg-white shadow-sm rounded my-2 px-3 py-2">
    <div class="my-2">
        <h1 style="display: inline;">
            <a class="text-dark text-decoration-none" href="{{ route('categories.show', [$category]) }}">
                {{ $category->name }}
                <small class="text-muted">({{ count($series) }} {{ __('SeriesPlural') }})</small>
            </a>
        </h1>
        <div class="float-end">
            @if ($category->type == 0)
                <a href="{{ route('series.create', [$category]) }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            @elseif ($category->type == 1)
                <a href="{{ route('article.create', [$category]) }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            @endif
            <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-link"><span class="fas fa-edit"></span></a>
        </div>
    </div>
    <div class="row mt-2">
        @if (count($series) == 0)
            <div class="text-center">
                {{ __('No data') }}
            </div>
        @else
            @foreach ($series as $item)
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch @if (!empty($item) && $item->status == 4) opacity-50 @endif">
                    <div class="card shadow-sm mb-4" style="width: 30rem;">
                        <img src="{{ $item->image }}" alt="{{ $item->name }}" class="card-img-top" style="height: 400px; object-fit: contain;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-uppercase">{{ $item->name }}</h5>
                            <div class="mt-auto">
                                <div>
                                    {{ $item->publisher?->name }}
                                </div>
                                <div class="float-start">
                                    <span class="{{ $item->status_class }}">{{ $item->status_name }}</span>
                                </div>
                                <div class="float-end">
                                    <span class="{{ $item->completion_status_class }}">{{ $item->completion_status_name }}</span>
                                </div>
                            </div>
                            <a href="{{ route('series.show', [$category, $item]) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
