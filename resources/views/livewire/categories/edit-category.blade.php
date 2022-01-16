<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Category') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Create Category') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="category.name" class="col-form-label required">{{ __('Name') }}</label>
                            <input id="category.name" name="category.name" type="text" class="form-control @error('category.name') is-invalid @enderror" wire:model='category.name' autofocus>
                            @error('category.name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="category.sort_index" class="col-form-label required">{{ __('Sort Index') }}</label>
                            <input id="category.sort_index" name="category.sort_index" type="number" min="0" class="form-control @error('category.sort_index') is-invalid @enderror" wire:model='category.sort_index'>
                            @error('category.sort_index')
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
