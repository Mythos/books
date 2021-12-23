<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Category') }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent="save">
                        @csrf
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label required text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('category.name') is-invalid @enderror" name="name" wire:model="category.name" required autocomplete="name" autofocus>
                                @error('category.name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="sort_index" class="col-md-2 col-form-label required text-md-end">{{ __('Sort Index') }}</label>
                            <div class="col-md-10">
                                <input id="sort_index" type="number" min="0" class="form-control @error('category.sort_index') is-invalid @enderror" name="sort_index" wire:model='category.sort_index' required autocomplete="sort_index" autofocus>
                                @error('category.sort_index')
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
