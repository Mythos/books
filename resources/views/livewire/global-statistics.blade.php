<div class="col-sm-12 col-md-12 col-lg-3">
    <div class="card shadow-sm mb-2">
        <div class="card-header">{{ __('Statistics') }}</div>
        <div class="card-body d-flex flex-column table-responsive p-0" style="height: 250px; overflow-y: scroll;">
            <table class="table table-hover mb-0">
                <tbody>
                    <tr class="table-danger">
                        <td>{{ __('New') }}</td>
                        <td>{{ $new }}</td>
                    </tr>
                    <tr class="table-warning">
                        <td>{{ __('Ordered') }}</td>
                        <td>{{ $ordered }}</td>
                    </tr>
                    <tr class="table-info">
                        <td>{{ __('Shipped') }}</td>
                        <td>{{ $shipped }}</td>
                    </tr>
                    <tr class="table-success">
                        <td>{{ __('Delivered') }}</td>
                        <td>{{ $delivered }}</td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>{{ __('Total') }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
