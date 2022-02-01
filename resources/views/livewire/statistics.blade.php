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
    <div class="row bg-white shadow-sm rounded my-2 py-2">
        <div class="col-sm-12 col-md-12 col-lg-6">
            <h2>{{ __('Unread series') }}</h2>
            <div class="row">
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" style="min-width: 10rem;">{{ __('Series') }}</th>
                                <th scope="col" class="text-end" style="width: 7rem; min-width: 7rem;">{{ __('Unread') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unreadSeries as $series)
                                <tr>
                                    <td><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></td>
                                    <td class="text-end">{{ $series->unread_volumes_count }} {{ __('Volumes') }}</td>
                                </tr>
                            @endforeach
                            @if ($unreadSeries->count() == 0)
                                <tr>
                                    <td colspan="5" style="text-align: center;">{{ __('No data') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $unreadSeries->links() }}
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <h2>{{ __('Most read series') }}</h2>
            <div class="row">
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" style="min-width: 10rem;">{{ __('Series') }}</th>
                                <th scope="col" class="text-end" style="width: 7rem; min-width: 7rem;">{{ __('Read') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mostReadSeries as $series)
                                <tr>
                                    <td><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></td>
                                    <td class="text-end">{{ $series->read_volumes_count }} {{ __('Volumes') }}</td>
                                </tr>
                            @endforeach
                            @if ($mostReadSeries->count() == 0)
                                <tr>
                                    <td colspan="5" style="text-align: center;">{{ __('No data') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $mostReadSeries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
