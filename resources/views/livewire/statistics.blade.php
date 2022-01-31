<div class="container">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Statistics') }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded py-2">
        <div class="col-sm-12 col-md-12 col-lg-6">
            <h2>{{ __('Volumes per status') }}</h2>
            <div class="row py-3 px-1">
                @include('livewire.statistics.partials.volumes-status-chart')
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <h2>{{ __('Series per publisher') }}</h2>
            <div class="row py-3 px-1">
                @include('livewire.statistics.partials.series-publisher-chart')
            </div>
        </div>
    </div>
</div>
