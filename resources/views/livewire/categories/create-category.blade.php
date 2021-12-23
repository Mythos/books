<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Category') }}</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Category') }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent='save'>
                        @csrf
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label required text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" wire:model='name' required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="sort_index" class="col-md-2 col-form-label required text-md-end">{{ __('Sort Index') }}</label>
                            <div class="col-md-10">
                                <input id="sort_index" type="number" min="0" class="form-control @error('sort_index') is-invalid @enderror" name="sort_index" wire:model='sort_index' required autocomplete="sort_index" autofocus>
                                @error('sort_index')
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
