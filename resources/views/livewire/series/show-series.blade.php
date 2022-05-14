@section('title')
    {{ $series->name }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$category]) }}">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $series->name }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded py-2">
        <div class="col-sm-12 col-md-12 col-lg-3 d-flex flex-column align-items-center text-center my-2">
            <img src="{{ $series->image }}" alt="{{ $series->name }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
            <span class="mt-2 fs-4">{{ $series->publisher?->name }}</span>
            <span class="{{ $series->status_class }}">{{ $series->status_name }}</span>
            @if ($series->subscription_active)
                <span class="badge bg-success mt-1 fs-9">{{ __('Subscription active') }}</span>
            @endif
            @if (!empty($series->demographics))
                <span class="mt-4 {{ $series->demographics->type_class }}">{{ $series->demographics->name }}</span>
            @endif
            @if ($series->genre_tags->count() > 0)
                <span class="mt-2">
                    @foreach ($series->genre_tags as $genre)
                        <span class="{{ $genre->type_class }}">{{ $genre->name }}</span>
                    @endforeach
                </span>
            @endif
            @if (!empty($series->mangapassion_id))
                <button class="btn btn-primary btn-sm mt-3" wire:click="update">{{ __('Update') }}</button>
            @endif
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 my-2 pl-4">
            <div>
                <h1 style="display: inline;">{{ $series->name }}</h1>
                <div class="float-end" style="display: inline;">
                    @if (config('app.debug'))
                        <span class="badge bg-secondary">ID: {{ $series->id }}</span>
                        <span class="badge bg-secondary">MP-ID: {{ $series->mangapassion_id }}</span>
                    @endif
                    <a href="{{ route('series.edit', [$category, $series]) }}" class="btn btn-link"><span class="fas fa-edit"></span></a>
                    @if (!empty($series->mangapassion_id))
                        <a href="https://www.manga-passion.de/editions/{{ $series->mangapassion_id }}" class="btn btn-link" target="_blank"><span class="fas fa-book"></span></a>
                    @endif
                    <a href="https://www.thalia.de/suche?sq={{ urlencode($series->name) }}&sort=sfed&allayout=FLAT" class="btn btn-link" target="_blank"><span class="fas fa-search"></span></a>
                    <a href="https://www.amazon.de/s?k={{ urlencode($series->name) }}&i=stripbooks&s=date-desc-rank" class="btn btn-link" target="_blank"><span class="fab fa-amazon"></span></a>
                </div>
            </div>
            <div class="row" style="width: 100%;">
                <div class="mt-3 col-sm-12 col-md-12 col-lg-8">
                    @if (!empty($series->description))
                        <p class="pe-3">
                            {{ $series->description }}
                        </p>
                    @endif
                    <div>{{ __('Total Worth') }}: {{ number_format($series->total_worth, 2) }} {{ config('app.currency') }}</div>
                </div>
                <div class="mt-3 col-sm-12 col-md-12 col-lg-4">
                    @include('livewire.series.partials.volume-statistics')
                </div>
            </div>
            @include('livewire.series.volumes-table', [$category, $series, $volumes])
        </div>
    </div>
</div>
