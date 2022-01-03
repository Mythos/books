<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$series->category]) }}">{{ $series->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $series->name }}</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Series') }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent='save'>
                        @csrf
                        <input id="category_id" type="hidden" name="category_id" wire:model='series_id' />
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label required text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('series.name') is-invalid @enderror" name="name" wire:model='series.name' required autocomplete="name" autofocus>
                                @error('series.name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="status" class="col-md-2 col-form-label text-md-end">{{ __('Status') }}</label>
                            <div class="col-md-10">
                                <select class="form-select @error('series.status') is-invalid @enderror" name="status" wire:model='series.status' required>
                                    <option value="0">{{ __('New') }}</option>
                                    <option value="1">{{ __('Ongoing') }}</option>
                                    <option value="2">{{ __('Finished') }}</option>
                                </select>
                                @error('series.status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="default_price" class="col-md-2 col-form-label text-md-end">{{ __('Default Price') }}</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input id="default_price" type="text" class="form-control @error('series.default_price') is-invalid @enderror" name="default_price" wire:model='series.default_price' autocomplete="default_price" autofocus>
                                    <span class="input-group-text">{{ config('app.currency') }}</span>
                                    @error('series.default_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="total" class="col-md-2 col-form-label text-md-end">{{ __('Total') }}</label>
                            <div class="col-md-10">
                                <input id="total" type="number" min="0" class="form-control @error('series.total') is-invalid @enderror" name="total" wire:model='series.total' autocomplete="total" autofocus>
                                @error('series.total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="image" class="col-md-2 col-form-label text-md-end">{{ __('Image') }}</label>
                            <div class="col-md-10">
                                <input id="image_url" type="text" class="form-control @error('image_url') is-invalid @enderror" name="image_url" wire:model='image_url' autocomplete="image_url" autofocus>
                                @error('image_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="is_nsfw" class="col-md-2 col-form-label text-md-end">{{ __('NSFW') }}</label>
                            <div class="col-md-10">
                                <div class="form-check">
                                    <input id="is_nsfw" type="checkbox" class="form-check-input @error('series.is_nsfw') is-invalid @enderror" name="is_nsfw" wire:model='series.is_nsfw' autocomplete="series.is_nsfw" autofocus>
                                </div>
                                @error('series.is_nsfw')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0 float-end">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" wire:click='delete'>
                                    {{ __('Delete') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
