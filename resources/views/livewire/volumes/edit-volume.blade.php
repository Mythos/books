@section('title')
    {{ __('Volume :number', ['number' => $volume->number]) }} - {{ $series->name }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$series->category]) }}">{{ $series->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Volume :number', ['number' => $volume->number]) }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='@if (!empty($nextVolume)) saveAndContinue @else save @endif'>
        <input id="series_id" type="hidden" name="series_id" wire:model='series_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-sm-12 col-md-12 col-lg-3 d-flex flex-column align-items-center text-center my-2">
                @if (!empty($image_preview))
                    <img src="{{ $image_preview }}" class="card-img-top" style="max-height: 400px; object-fit: contain;" loading="lazy" decoding="async" onerror="this.src='{{ url('images/placeholder.png') }}';this.onerror='';">
                @else
                    <img src="{{ url('images/placeholder.png') }}" class="card-img-top" style="max-height: 400px; object-fit: contain;" loading="lazy" decoding="async">
                @endif
                <span class="mt-2 fs-4">{{ $volume->name }}</span>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-9 mb-2 pl-4">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Edit Series') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="volume.number" class="col-form-label">{{ __('Number') }}</label>
                            <input id="volume.number" name="volume.number" type="number" class="form-control @error('volume.number') is-invalid @enderror" wire:model='volume.number'>
                            @error('volume.number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
                            <input id="volume.publish_date" name="volume.publish_date" type="date" class="form-control @error('volume.publish_date') is-invalid @enderror" wire:model='volume.publish_date'>
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
                        <div class="float-start mb-3">
                            <button type="button" class="btn btn-danger" wire:click='delete'>{{ __('Delete') }}</button>
                        </div>
                        <div class="float-end mb-3">
                            @if (!empty($nextVolume))
                                <button class="btn btn-secondary" type="button" wire:click='save'>{{ __('Save') }}</button>
                                <button class="btn btn-primary" type="submit">{{ __('Save and Continue') }}</button>
                            @else
                                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('livewire.volumes.barcodescanner')
</div>
@include('scripts.select2')
