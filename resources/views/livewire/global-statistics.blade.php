<div class="col-sm-12 col-md-12 col-lg-3">
    <div class="card shadow-sm mb-2">
        <div class="card-header">{{ __('Statistics') }}</div>
        <div class="card-body d-flex flex-column table-responsive p-0" style="height: 250px; overflow-y: scroll;">
            <table class="table table-hover mb-0">
                <tbody>
                    <tr class="table-danger">
                        <td>{{ __('New') }}</td>
                        <td class="text-end">{{ $volumeStatistics['new'] }} {{ __('Volumes') }} / {{ $articleStatistics['new'] }} {{ __('Articles') }}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>{{ __('Ordered') }}</td>
                        <td class="text-end">{{ $volumeStatistics['ordered'] }} {{ __('Volumes') }} / {{ $articleStatistics['ordered'] }} {{ __('Articles') }}</td>
                    </tr>
                    <tr class="table-info">
                        <td>{{ __('Shipped') }}</td>
                        <td class="text-end">{{ $volumeStatistics['shipped'] }} {{ __('Volumes') }} / {{ $articleStatistics['shipped'] }} {{ __('Articles') }}</td>
                    </tr>
                    <tr class="table-primary">
                        <td>{{ __('Delivered') }}</td>
                        <td class="text-end">{{ $volumeStatistics['delivered'] }} {{ __('Volumes') }} / {{ $articleStatistics['delivered'] }} {{ __('Articles') }}</td>
                    </tr>
                    <tr class="table-success">
                        <td>{{ __('Read') }}</td>
                        <td class="text-end">{{ $volumeStatistics['read'] }} {{ __('Volumes') }}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>{{ __('Total') }}</td>
                        <td class="text-end">{{ $volumeStatistics['total'] }} {{ __('Volumes') }} / {{ $articleStatistics['total'] }} {{ __('Articles') }}</td>
                    </tr>
                    <tr class="table-secondary">
                        <td>{{ __('Total Worth') }}</td>
                        <td class="text-end">{{ number_format($volumeStatistics['price'] + $articleStatistics['price'], 2) }} {{ config('app.currency') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
