<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Series') }}</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Series') }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent='save'>
                        @csrf
                        <input id="category_id" type="hidden" name="category_id" wire:model='series.category_id' />
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>
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
                            <label for="status" class="col-md-2 col-form-label text-md-right">{{ __('Status') }}</label>
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
                            <label for="total" class="col-md-2 col-form-label text-md-right">{{ __('Total') }}</label>
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
                            <label for="language" class="col-md-2 col-form-label text-md-right">{{ __('Language') }}</label>
                            <div class="col-md-10">
                                <input id="language" type="text" class="form-control @error('series.language') is-invalid @enderror" name="language" wire:model='series.language' autocomplete="language" autofocus>
                                @error('series.language')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="image" class="col-md-2 col-form-label text-md-right">{{ __('Image') }}</label>
                            <div class="col-md-10">
                                <input id="image_url" type="text" class="form-control @error('image_url') is-invalid @enderror" name="image_url" wire:model='image_url' autocomplete="image_url" autofocus>
                                @error('image_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0 float-end">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
