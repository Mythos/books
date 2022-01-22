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
        <input id="series_id" type="hidden" name="series_id" wire:model='series_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Create Volume') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="isbn" class="col-form-label required">{{ __('ISBN') }}</label>
                            <input id="isbn" name="isbn" type="text" class="form-control @error('isbn') is-invalid @enderror" wire:model='isbn' autofocus>
                            @error('isbn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="publish_date" class="col-form-label">{{ __('Publish Date') }}</label>
                            <input id="publish_date" name="publish_date" type="date" class="form-control @error('publish_date') is-invalid @enderror" wire:model='publish_date' autofocus>
                            @error('publish_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="price" class="col-form-label">{{ __('Price') }}</label>
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
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="status" class="col-form-label required">{{ __('Status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" wire:model='status' required>
                                <option value="0">{{ __('New') }}</option>
                                <option value="1">{{ __('Ordered') }}</option>
                                <option value="2">{{ __('Shipped') }}</option>
                                <option value="3">{{ __('Delivered') }}</option>
                                <option value="4">{{ __('Read') }}</option>
                            </select>
                            @error('status')
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
</div>
