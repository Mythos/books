@section('title')
    {{ __('Publishers') }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item">{{ __('Administration') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Publishers') }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded p-3">
        <div class="my-2">
            <h1 style="display: inline;">
                {{ __('Publishers') }}
            </h1>
            <div class="float-end">
                <a href="{{ route('publishers.create') }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            </div>
        </div>
        <div class="row mt-2">
            @if (count($publishers) == 0)
                <div class="text-center">
                    {{ __('No data') }}
                </div>
            @else
                @foreach ($publishers as $publisher)
                    <div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
                        <div class="card shadow-sm mb-4" style="width: 30rem;">
                            <img src="{{ $publisher->image }}" alt="{{ $publisher->name }}" class="card-img-top" style="height: 150px; object-fit: contain;" loading="lazy" decoding="async">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-uppercase">{{ $publisher->name }}</h5>
                                <div>
                                    <div class="float-end"><span class="badge bg-primary">{{ $publisher->series->count() }} {{ __('Volumes') }}</span></div>
                                </div>
                                <a href="{{ route('publishers.edit', [$publisher]) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
