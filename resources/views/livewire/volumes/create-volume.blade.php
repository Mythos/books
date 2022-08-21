@section('title')
    {{ __('Create Volume') }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$series->category]) }}">{{ $series->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Volume') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Create Volume') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.isbn" class="col-form-label">{{ __('ISBN') }}</label>
                            <div class="input-group">
                                <input id="volume.isbn" name="volume.isbn" type="text" class="form-control @error('volume.isbn') is-invalid @enderror" wire:model='volume.isbn' autofocus>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#livestream_scanner"><span class="fa fa-barcode"></span></button>
                                @error('volume.isbn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.publish_date" class="col-form-label">{{ __('Publish Date') }}</label>
                            <input id="volume.publish_date" name="volume.publish_date" type="date" class="form-control @error('volume.publish_date') is-invalid @enderror" wire:model='volume.publish_date' autofocus>
                            @error('volume.publish_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.price" class="col-form-label">{{ __('Price') }}</label>
                            <div class="input-group">
                                <input id="volume.price" name="volume.price" type="text" class="form-control @error('volume.price') is-invalid @enderror" wire:model='volume.price'>
                                <span class="input-group-text">{{ config('app.currency') }}</span>
                                @error('volume.price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.pages" class="col-form-label">{{ __('Pages') }}</label>
                            <input id="volume.pages" name="volume.pages" type="number" class="form-control @error('volume.pages') is-invalid @enderror" wire:model='volume.pages'>
                            @error('volume.pages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.chapters" class="col-form-label">{{ __('Chapters') }}</label>
                            <input id="volume.chapters" name="volume.chapters" type="number" class="form-control @error('volume.chapters') is-invalid @enderror" wire:model='volume.chapters'>
                            @error('volume.chapters')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.status" class="col-form-label required">{{ __('Status') }}</label>
                            <select id="volume.status" name="volume.status" class="form-select @error('volume.status') is-invalid @enderror" wire:model='volume.status' required>
                                <option value="{{ App\Constants\VolumeStatus::NEW }}">{{ __('New') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::ORDERED }}">{{ __('Ordered') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::SHIPPED }}">{{ __('Shipped') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::DELIVERED }}">{{ __('Delivered') }}</option>
                                <option value="{{ App\Constants\VolumeStatus::READ }}">{{ __('Read') }}</option>
                            </select>
                            @error('volume.status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.image_url" class="col-form-label">{{ __('Image URL') }}</label>
                            <input id="volume.image_url" name="volume.image_url" type="text" class="form-control @error('volume.image_url') is-invalid @enderror" wire:model='volume.image_url'>
                            @error('volume.image_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input id="volume.ignore_in_upcoming" type="checkbox" class="form-check-input @error('volume.ignore_in_upcoming') is-invalid @enderror" name="volume.ignore_in_upcoming" wire:model='volume.ignore_in_upcoming'>
                                <label for="volume.ignore_in_upcoming" class="form-check-label">{{ __('Hide in upcoming releases') }}</label>
                            </div>
                            @error('volume.ignore_in_upcoming')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
    @include('livewire.volumes.barcodescanner')
</div>
@include('scripts.select2')
