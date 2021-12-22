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
                            <label for="name" class="col-md-2 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('category.name') is-invalid @enderror" name="name" wire:model="category.name" required autocomplete="name" autofocus>
                                @error('category.name')
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
