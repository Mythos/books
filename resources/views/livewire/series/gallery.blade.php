<div class="my-2">
    <h1 style="display: inline;">{{ $category->name }}</h1>
    <div class="float-right">
        <a href="{{ route('series.create', [$category]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
        <a href="{{ route('categories.edit', [$category]) }}" class="btn btn-link"><i class="fas fa-edit"></i></a>
    </div>
</div>
<div class="row mt-2">
    @foreach ($series as $item)
        @include('series.card', [$item])
    @endforeach
</div>
