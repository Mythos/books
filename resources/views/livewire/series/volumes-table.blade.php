<div class="mt-3">
    <div>
        <h2 style="display: inline;">{{ __('Volumes') }} ({{ count($volumes) }})</h2>
        <div class="float-end" style="display: inline;">
            <a href="{{ route('volumes.bulk-update', [$category, $series]) }}" class="btn btn-link" title="{{ __('Update Volumes') }}"><span class="fas fa-sliders"></span></a>
            <a wire:click.prevent='toggle_reordering' href="#" title="{{ __('Reorder volumes') }}"><span class="fa fa-sort"></span></a>
            <a href="{{ route('volumes.create', [$category, $series]) }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
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
                    <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                    <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Publish Date') }}</th>
                    <th scope="col" style="min-width: 8rem;">{{ __('ISBN') }}</th>
                    <th scope="col" class="text-end" style="width: 5rem; min-width: 5rem;">{{ __('Price') }}</th>
                    <th scope="col" class="text-end" style="width: 7rem; min-width: 7rem;">{{ __('Pages') }}</th>
                    <th scope="col" class="text-center" style="width: 5rem; min-width: 5rem;">{{ __('Status') }}</th>
                    <th scope="col" class="text-center" style="min-width: 3rem;">{{ __('Reading Stack') }}</th>
                    <th scope="col" class="text-center" style="min-width: 4rem;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($volumes as $volume)
                    <tr class="{{ $volume->status_class }}">
                        @if ($enable_reordering)
                            <td class="text-center">
                                @if ($volume->number > 1)
                                    <a wire:click.prevent='move_up({{ $volume->id }})' href="#" title="{{ __('Moves the volume up') }}"><span class="fa fa-arrow-up"></span></a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($volume->number < $volumes->max('number'))
                                    <a wire:click.prevent='move_down({{ $volume->id }})' href="#" title="{{ __('Moves the volume down') }}"><span class="fa fa-arrow-down"></span></a>
                                @endif
                            </td>
                        @endif
                        <th scope="row" class="text-end">{{ $volume->number }}</th>
                        <td class="text-center"><a href="{{ route('volumes.edit', [$category, $series, $volume->number]) }}"><span class="fa fa-edit"></span></a></td>
                        <td class="text-center" style="padding: 3px;">
                            <img src="{{ $volume->image_thumbnail }}" alt="{{ $volume->name }}" class="volume-cover" style="max-height: 33px; object-fit: contain;" data-image-url="{{ $volume->image }}" loading="lazy" decoding="async">
                        </td>
                        <td class="text-center">{{ $volume->publish_date_formatted }}</td>
                        <td>{{ $volume->isbn_formatted }}</td>
                        <td class="text-end">{{ number_format($volume->price, 2) }} {{ config('app.currency') }}</td>
                        <td class="text-end">{{ $volume->pages ?? '?' }}</td>
                        <td class="text-center">{{ $volume->status_name }}</td>
                        <td class="text-center">
                            @if ($volume->status == App\Constants\VolumeStatus::DELIVERED)
                                @if ($volume->plan_to_read)
                                    <a wire:click.prevent='unplan({{ $volume->id }})' href="#" title="{{ __('Add to reading stack') }}"><span class="fa fa-check"></span></a>
                                @else
                                    <a wire:click.prevent='plan({{ $volume->id }})' href="#" title="{{ __('Remove reading stack') }}"><span class="fa fa-xmark"></span></a>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($volume->status == App\Constants\VolumeStatus::NEW)
                                <a wire:click.prevent='ordered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Ordered') }}"><span class="fa fa-shopping-cart"></span></a>
                            @endif
                            @if ($volume->status == App\Constants\VolumeStatus::ORDERED)
                                <a wire:click.prevent='shipped({{ $volume->id }})' href="#" title="{{ __('Sets the status to Shipped') }}"><span class="fa fa-truck"></span></a>
                            @endif
                            @if ($volume->status == App\Constants\VolumeStatus::SHIPPED)
                                <a wire:click.prevent='delivered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Delivered') }}"><span class="fa fa-check"></span></a>
                            @endif
                            @if ($volume->status == App\Constants\VolumeStatus::DELIVERED)
                                <a wire:click.prevent='read({{ $volume->id }})' href="#" title="{{ __('Sets the status to Read') }}"><span class="fa fa-book"></span></a>
                            @endif
                            @if ($volume->status == App\Constants\VolumeStatus::ORDERED || $volume->status == App\Constants\VolumeStatus::SHIPPED || $volume->status == App\Constants\VolumeStatus::DELIVERED || $volume->status == App\Constants\VolumeStatus::READ)
                                <a wire:click.prevent='canceled({{ $volume->id }})' href="#" title="{{ __('Sets the status to New') }}"><span class="fa fa-ban"></span></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($volumes->count() == 0)
                    <tr>
                        <td colspan="@if ($enable_reordering) 9 @else 8 @endif" style="text-align: center;">{{ __('No data') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
