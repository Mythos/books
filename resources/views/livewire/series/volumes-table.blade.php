<div class="mt-3">
    <div>
        <h2 style="display: inline;">{{ __('Volumes') }}</h2>
        <div class="float-end" style="display: inline;">
            <a wire:click.prevent='toggle_reordering' href="#" title="{{ __('Reorder volumes') }}"><i class="fa fa-sort"></i></a>
            <a href="{{ route('volumes.create', [$category, $series]) }}" class="btn btn-link"><i class="fas fa-plus-circle"></i></a>
        </div>
    </div>
    <div class="table-responsive" style="width: 100%;">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    @if ($enable_reordering)
                        <th scope="col" class="text-center" style="width: 1rem; min-width: 1rem;"></th>
                        <th scope="col" class="text-center" style="width: 1rem; min-width: 1rem;"></th>
                    @endif
                    <th scope="col" class="text-end" style="width: 2rem; min-width: 2rem;">{{ __('#') }}</th>
                    <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                    <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Publish Date') }}</th>
                    <th scope="col" style="min-width: 10rem;">{{ __('ISBN') }}</th>
                    <th scope="col" class="text-end" style="width: 5rem; min-width: 5rem;">{{ __('Price') }}</th>
                    <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Status') }}</th>
                    <th scope="col" class="text-center" style="min-width: 4rem;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($volumes as $volume)
                    <tr class="{{ $volume->status_class }}">
                        @if ($enable_reordering)
                            <td class="text-center">
                                @if ($volume->number > 1)
                                    <a wire:click.prevent='move_up({{ $volume->id }})' href="#" title="{{ __('Moves the volume up') }}"><i class="fa fa-arrow-up"></i></a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($volume->number < $volumes->max('number'))
                                    <a wire:click.prevent='move_down({{ $volume->id }})' href="#" title="{{ __('Moves the volume down') }}"><i class="fa fa-arrow-down"></i></a>
                                @endif
                            </td>
                        @endif
                        <th scope="row" class="text-end">{{ $volume->number }}</th>
                        <td class="text-center"><a href="{{ route('volumes.edit', [$category, $series, $volume->number]) }}"><i class="fa fa-edit"></i></a></td>
                        <td class="text-center">{{ $volume->publish_date }}</td>
                        <td>{{ $volume->isbn_formatted }}</td>
                        <td class="text-end">{{ number_format($volume->price, 2) }} {{ config('app.currency') }}</td>
                        <td class="text-center">{{ $volume->status_name }}</td>
                        <td class="text-center">
                            @if ($volume->status == 0)<a wire:click.prevent='ordered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Ordered') }}"><i class="fa fa-shopping-cart"></i></a>@endif
                            @if ($volume->status == 1)<a wire:click.prevent='shipped({{ $volume->id }})' href="#" title="{{ __('Sets the status to Shipped') }}"><i class="fa fa-truck"></i></a>@endif
                            @if ($volume->status == 2)<a wire:click.prevent='delivered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Delivered') }}"><i class="fa fa-check"></i></a>@endif
                            @if ($volume->status == 3)<a wire:click.prevent='read({{ $volume->id }})' href="#" title="{{ __('Sets the status to Read') }}"><i class="fa fa-book"></i></a>@endif
                            @if ($volume->status == 1 || $volume->status == 2 || $volume->status == 3 || $volume->status == 4)<a wire:click.prevent='canceled({{ $volume->id }})' href="#" title="{{ __('Sets the status to New') }}"><i class="fa fa-ban"></i></a>@endif
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
