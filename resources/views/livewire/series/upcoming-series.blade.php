<div class="col-sm-12 col-md-12 col-lg-9" wire:init="load">
    <div class="card shadow-sm mb-2">
        <div class="card-header">{{ __('Upcoming Releases') }}</div>
        <div class="card-body d-flex flex-column table-responsive p-0" style="height: 280px; overflow-y: scroll;">
            <table class="table table-hover mb-0">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Publish Date') }}</th>
                        <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                        <th scope="col" style="min-width: 21rem;">{{ __('Title') }}</th>
                        <th scope="col" class="text-center"></th>
                        <th scope="col" style="min-width: 7rem;">{{ __('ISBN') }}</th>
                        <th scope="col" class="text-end" style="min-width: 5rem;">{{ __('Price') }}</th>
                        <th scope="col" class="text-center">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($loaded)
                        @foreach ($upcoming as $volume)
                            <tr class="{{ $volume->status_class }}">
                                <th scope="row" class="text-center">{{ $volume->publish_date_formatted }}</th>
                                <td class="text-center" style="padding: 3px;">
                                    @if ($volume->image_exists)
                                        <img src="{{ $volume->image_thumbnail }}" alt="{{ $volume->name }}" class="volume-cover" style="max-height: 33px; object-fit: contain;" data-image-url="{{ $volume->image }}">
                                    @endif
                                </td>
                                <td>
                                    @if ($volume->series->subscription_active)
                                        <span class="badge rounded-pill bg-success mt-1" data-bs-toggle="tooltip" title="{{ __('Subscription active') }}">{{ Str::substr(__('Subscription active'), 0, 1) }}</span>
                                    @endif
                                    {{ $volume->name }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('series.show', [$volume->series->category, $volume->series]) }}"><span class="fa fa-book"></span></a>
                                </td>
                                <td>{{ $volume->isbn_formatted }}</td>
                                <td class="text-end">{{ number_format($volume->price, 2) }} {{ config('app.currency') }}</td>
                                <td class="text-center">
                                    @if ($volume->status == 0)
                                        <a wire:click.prevent='ordered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Ordered') }}"><span class="fa fa-shopping-cart"></span></a>
                                    @endif
                                    @if ($volume->status == 1)
                                        <a wire:click.prevent='shipped({{ $volume->id }})' href="#" title="{{ __('Sets the status to Shipped') }}"><span class="fa fa-truck"></span></a>
                                    @endif
                                    @if ($volume->status == 2)
                                        <a wire:click.prevent='delivered({{ $volume->id }})' href="#" title="{{ __('Sets the status to Delivered') }}"><span class="fa fa-check"></span></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if (count($upcoming) == 0)
                            <tr>
                                <td colspan="7" style="text-align: center;">{{ __('No data') }}</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="7" style="text-align: center;">{{ __('Loading...') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
