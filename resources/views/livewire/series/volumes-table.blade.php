<div class="mt-3">
    <div>
        <h2 style="display: inline;">{{ __('Volumes') }}</h2>
        <div class="float-end" style="display: inline;">
            <a href="{{ route('volumes.create', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
        </div>
    </div>
    <div class="table-responsive" style="width: 100%;">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="min-width: 2rem;">#</th>
                    <th scope="col" style="min-width: 1rem;"></th>
                    <th scope="col" style="min-width: 7rem;">{{ __('Publish date') }}</th>
                    <th scope="col" style="min-width: 10rem;">{{ __('ISBN') }}</th>
                    <th scope="col" style="min-width: 7rem;">{{ __('Status') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($volumes as $volume)
                    <tr class="{{ $volume->status_class }}">
                        <th scope="row">{{ $volume->number }}</th>
                        <td><a href="{{ route('volumes.edit', [$category, $series, $volume->number]) }}"><i class="fa fa-edit"></i></a></td>
                        <td>{{ $volume->publish_date }}</td>
                        <td>{{ $volume->isbn_formatted }}</td>
                        <td>{{ $volume->status_name }}</td>
                        <td>
                            @if ($volume->status == 0)<a wire:click.prevent='ordered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Ordered') }}"><i class="fa fa-shopping-cart"></i></a>@endif
                            @if ($volume->status == 1)<a wire:click.prevent='shipped({{ $volume->id }})' href="#" title="{{ __('Sets the status to Shipped') }}"><i class="fa fa-truck"></i></a>@endif
                            @if ($volume->status == 2)<a wire:click.prevent='delivered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Delivered') }}"><i class="fa fa-check"></i></a>@endif
                            @if ($volume->status == 1 || $volume->status == 2 || $volume->status == 3)<a wire:click.prevent='canceled({{ $volume->id }})' href="#" title="{{ __('Sets the status to New') }}"><i class="fa fa-ban"></i></a>@endif
                        </td>
                    </tr>
                @endforeach
                @if ($volumes->count() == 0)
                    <tr>
                        <td colspan="5" style="text-align: center;">No data</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
