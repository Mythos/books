<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$series->category]) }}">{{ $series->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('series.show', [$series->category, $series]) }}">{{ $series->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Volume :number', ['number' => $volume->number]) }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <input id="series_id" type="hidden" name="series_id" wire:model='series_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
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
                            <input id="volume.isbn" name="volume.isbn" type="text" class="form-control @error('volume.isbn') is-invalid @enderror" wire:model='volume.isbn' autofocus>
                            @error('volume.isbn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                            <label for="volume.status" class="col-form-label required">{{ __('Status') }}</label>
                            <select class="form-select @error('volume.status') is-invalid @enderror" name="status" wire:model='volume.status' required>
                                <option value="0">{{ __('New') }}</option>
                                <option value="1">{{ __('Ordered') }}</option>
                                <option value="2">{{ __('Shipped') }}</option>
                                <option value="3">{{ __('Delivered') }}</option>
                                <option value="4">{{ __('Read') }}</option>
                            </select>
                            @error('volume.status')
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
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
