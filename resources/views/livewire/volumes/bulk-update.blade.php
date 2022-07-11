@section('title')
    {{ __('Update Volumes') }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$series->category]) }}">{{ $series->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Update Volumes') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Update Volumes') }}</h4>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">{{ __('Status') }}</label>
                        <div class="col-sm-10">
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" wire:model='status' data-allow-clear="true">
                                <option></option>
                                <option value="{{ App\Constants\VolumeStatus::NEW }}">{{ __('New') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::ORDERED }}">{{ __('Ordered') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::SHIPPED }}">{{ __('Shipped') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::DELIVERED }}">{{ __('Delivered') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::READ }}">{{ __('Read') }}</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="price" class="col-sm-2 col-form-label">{{ __('Price') }}</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input id="price" name="price" type="text" class="form-control @error('price') is-invalid @enderror" wire:model='price'>
                                <span class="input-group-text">{{ config('app.currency') }}</span>
                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" wire:click.prevent='selectPublished'>{{ __('Published Volumes') }}</button>
                        <button type="button" class="btn btn-secondary" wire:click.prevent='selectUnpublished'>{{ __('Unpublished Volumes') }}</button>
                    </div>
                    <div class="mt-3">
                        <div class="table-responsive" style="width: 100%;">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="text-end" style="width: 2rem; min-width: 2rem;"><input type="checkbox" wire:model='selectAll'></th>
                                        <th scope="col" class="text-end" style="width: 2rem; min-width: 2rem;">{{ __('#') }}</th>
                                        <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                                        <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Publish Date') }}</th>
                                        <th scope="col" style="min-width: 10rem;">{{ __('ISBN') }}</th>
                                        <th scope="col" class="text-end" style="width: 5rem; min-width: 5rem;">{{ __('Price') }}</th>
                                        <th scope="col" class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volumes as $index => $volume)
                                        <tr class="{{ $volume->status_class }}">
                                            <th scope="row" class="text-end"><input type="checkbox" wire:model='selectedVolumes' value="{{ $volume->id }}"></th>
                                            <th scope="row" class="text-end">{{ $volume->number }}</th>
                                            <td class="text-center" style="padding: 3px;">
                                                @if ($volume->image_exists)
                                                    <img src="{{ $volume->image_thumbnail }}" alt="{{ $volume->name }}" class="volume-cover" style="max-height: 33px; object-fit: contain;" data-image-url="{{ $volume->image }}">
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $volume->publish_date_formatted }}</td>
                                            <td>{{ $volume->isbn_formatted }}</td>
                                            <td class="text-end">{{ number_format($volume->price, 2) }} {{ config('app.currency') }}</td>
                                            <td class="text-center">{{ $volume->status_name }}</td>
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
                    <div class="mt-3">
                        <div class="float-end mb-3">
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('scripts.select2')
