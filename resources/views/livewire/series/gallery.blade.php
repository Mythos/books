<div class="bg-white shadow-sm rounded my-2 px-3 py-2">
    <div class="my-2">
        <h1 style="display: inline;">{{ $category->name }} <small class="text-muted">({{ count($series) }} {{ __('SeriesPlural') }})</small></h1>
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
                @include('series.card', [$category, $item])
            @endforeach
        @endif
    </div>
</div>
