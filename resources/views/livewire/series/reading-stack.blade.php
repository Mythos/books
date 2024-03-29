<div class="col-sm-12 col-md-12 col-lg-6">
    <div class="card shadow-sm mb-2">
        <div class="card-header">
            <span>{{ __('Planned') }}</span>
            <div class="float-end">
                <a href="#" wire:click.prevent="expand">
                    @if ($expanded)
                        <span class="fas fa-compress" data-bs-toggle="tooltip" title="{{ __('Shrink') }}"></span>
                    @else
                        <span class="fas fa-expand" data-bs-toggle="tooltip" title="{{ __('Expand') }}"></span>
                    @endif
                </a>
            </div>
        </div>
        <div class="card-body d-flex flex-column table-responsive p-0"@if (!$expanded) style="height: 280px; overflow-y: scroll;" @endif>
            <table class="table table-hover mb-0">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                        <th scope="col" style="min-width: 21rem;">{{ __('Title') }}</th>
                        <th scope="col" class="text-center"></th>
                        <th scope="col" class="text-center" style="width: 5rem; min-width: 5rem;">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($volumes as $volume)
                        <tr class="{{ $volume->status_class }}">
                            <td class="text-center ps-2" style="padding: 3px;">
                                <img src="{{ $volume->image_thumbnail }}" alt="{{ $volume->name }}" class="volume-cover" style="max-height: 33px; object-fit: contain;" data-image-url="{{ $volume->image }}" loading="lazy" decoding="async">
                            </td>
                            <td>
                                {{ $volume->name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('series.show', [$volume->series->category, $volume->series]) }}" data-bs-toggle="tooltip" title="{{ __('Open series') }}"><span class="fa fa-book"></span></a>
                            </td>
                            <td class="text-center">
                                <a class="px-2" wire:click.prevent='read({{ $volume->id }})' href="#" data-bs-toggle="tooltip" title="{{ __('Sets the status to Read') }}"><span class="fa fa-check"></span></a>
                                <a class="px-2" wire:click.prevent='unplan({{ $volume->id }})' href="#" data-bs-toggle="tooltip" title="{{ __('Remove reading stack') }}"><span class="fa fa-minus"></span></a>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($volumes) == 0)
                        <tr>
                            <td colspan="7" style="text-align: center;">{{ __('No data') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
