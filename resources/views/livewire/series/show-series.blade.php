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
            <div class="text-end">
                @if (config('app.debug'))
                    <span class="badge bg-secondary">ID: {{ $series->id }}</span>
                    <span class="badge bg-secondary">MP-ID: {{ $series->mangapassion_id ?? 'NULL' }}</span>
                @endif
                <a href="{{ route('series.edit', [$category, $series]) }}" class="btn btn-link py-0 px-2"><span class="fas fa-edit"></span></a>
                <a href="#" class="dropdown-toggle text-decoration-none" id="series-search" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="fas fa-search"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="series-search">
                    <li><a class="dropdown-item" href="https://www.amazon.de/s?k={{ urlencode($series->name) }}&i=stripbooks&s=date-desc-rank" target="_blank">Amazon.de</a></li>
                    <li><a class="dropdown-item" href="https://www.bookdepository.com/search?searchTerm={{ urlencode($series->name) }}&ageRangesTotal=0&searchSortBy=pubdate_high_low" target="_blank">Book Depository</a></li>
                    <li><a class="dropdown-item" href="https://www.thalia.de/suche?sq={{ urlencode($series->name) }}&sort=sfed&allayout=FLAT" target="_blank">Thalia.de</a></li>
                    @if (!empty($series->mangapassion_id))
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="https://www.manga-passion.de/editions/{{ $series->mangapassion_id }}" target="_blank">Manga Passion</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <h1 class="my-0">{{ $series->name }}</h1>
                @if (auth()->user()->secondary_title_preference == 1)
                    @if (!empty($series->source_name))
                        <span class="text-secondary my-0 fs-4" data-bs-toggle="tooltip" title="{{ $series->source_name_romaji }}">{{ $series->source_name }}</span>
                    @endif
                @elseif (auth()->user()->secondary_title_preference == 2)
                    @if (!empty($series->source_name_romaji))
                        <span class="text-secondary my-0 fs-4" data-bs-toggle="tooltip" title="{{ $series->source_name }}">{{ $series->source_name_romaji }}</span>
                    @endif
                @endif
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
