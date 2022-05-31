@section('title')
    {{ __('Create Series') }}
@endsection


<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$category]) }}">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Series') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <input id="category_id" type="hidden" name="category_id" wire:model='series.category_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Create Series') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.name" class="col-form-label required">{{ __('Name') }}</label>
                            <div class="input-group">
                                <input id="series.name" name="series.name" type="text" class="form-control @error('series.name') is-invalid @enderror" wire:model='series.name' autofocus>
                                <button class="btn btn-outline-secondary" type="button" wire:click="fetchdata"><span class="fa fa-search"></span></button>
                                @error('series.name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.source_name" class="col-form-label required">{{ __('Original Title') }}</label>
                            <div class="input-group">
                                <input id="series.source_name" name="series.source_name" type="text" class="form-control @error('series.source_name') is-invalid @enderror" wire:model='series.source_name' autofocus>
                                @error('series.source_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.source_name_romaji" class="col-form-label required">{{ __('Original Title (Romaji)') }}</label>
                            <div class="input-group">
                                <input id="series.source_name_romaji" name="series.source_name_romaji" type="text" class="form-control @error('series.source_name_romaji') is-invalid @enderror" wire:model='series.source_name_romaji' autofocus>
                                @error('series.source_name_romaji')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.description" class="col-form-label required">{{ __('Description') }}</label>
                            <textarea id="series.description" name="series.description" type="text" class="form-control @error('series.description') is-invalid @enderror" wire:model='series.description'>
                            </textarea>
                            @error('series.description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.publisher_id" class="col-form-label">{{ __('Publisher') }}</label>
                            <select id="series.publisher_id" name="series.publisher_id" class="form-select @error('series.publisher_id') is-invalid @enderror" wire:model='series.publisher_id' data-allow-clear="true">
                                <option></option>
                                @foreach ($publishers as $publisher)
                                    <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                            @error('series.publisher_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.status" class="col-form-label required">{{ __('Status') }}</label>
                            <select id="series.status" name="series.status" class="form-select @error('series.status') is-invalid @enderror" wire:model='series.status' required>
                                <option value="{{ App\Constants\SeriesStatus::ANNOUNCED }}">{{ __('Announced') }}</option>
                                <option value="{{ App\Constants\SeriesStatus::ONGOING }}">{{ __('Ongoing') }}</option>
                                <option value="{{ App\Constants\SeriesStatus::FINISHED }}">{{ __('Finished') }}</option>
                                <option value="{{ App\Constants\SeriesStatus::CANCELED }}">{{ __('Canceled') }}</option>
                                <option value="{{ App\Constants\SeriesStatus::PAUSED }}">{{ __('Paused') }}</option>
                            </select>
                            @error('series.status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.default_price" class="col-form-label">{{ __('Default price') }}</label>
                            <div class="input-group">
                                <input id="series.default_price" name="series.default_price" type="text" class="form-control @error('series.default_price') is-invalid @enderror" wire:model='series.default_price'>
                                <span class="input-group-text">{{ config('app.currency') }}</span>
                                @error('series.default_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.total" class="col-form-label">{{ __('Volumes (total)') }}</label>
                            <input id="series.total" name="series.total" type="number" class="form-control @error('series.total') is-invalid @enderror" wire:model='series.total'>
                            @error('series.total')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="series.image_url" class="col-form-label required">{{ __('Image URL') }}</label>
                            <input id="series.image_url" name="series.image_url" type="text" class="form-control @error('series.image_url') is-invalid @enderror" wire:model='series.image_url'>
                            @error('series.image_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input id="series.subscription_active" type="checkbox" class="form-check-input @error('series.subscription_active') is-invalid @enderror" name="subscription_active" wire:model='series.subscription_active' @if ($series->status == App\Constants\SeriesStatus::CANCELED) disabled @endif>
                                <label for="series.subscription_active" class="form-check-label">{{ __('Subscription active') }}</label>
                            </div>
                            @error('series.subscription_active')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input id="series.is_nsfw" type="checkbox" class="form-check-input @error('series.is_nsfw') is-invalid @enderror" name="is_nsfw" wire:model='series.is_nsfw'>
                                <label for="series.is_nsfw" class="form-check-label">{{ __('NSFW') }}</label>
                            </div>
                            @error('series.is_nsfw')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-end">
                            <button class="btn btn-primary float-end mb-2" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('scripts.select2')
