<div class="bg-white shadow-sm rounded my-2 px-3 py-2">
    <div class="my-2">
        <h1 style="display: inline;">{{ $category->name }}</h1>
        <div class="float-end">
            @if ($category->type == 0)
                <a href="{{ route('series.create', [$category]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
            @elseif ($category->type == 1)
                <a href="{{ route('article.create', [$category]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
            @endif
            <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-link"><i class="fas fa-edit"></i></a>
        </div>
    </div>
    <div class="row mt-2">
        @if (count($series) == 0)
            <div class="text-center">
                {{ __('No data') }}
            </div>
        @else
            @foreach ($series as $item)
                @include('series.card', [$item])
            @endforeach
        @endif
    </div>
</div>
