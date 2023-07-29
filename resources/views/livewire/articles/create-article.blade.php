@section('title')
    {{ __('Create Article') }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', [$category]) }}">{{ $category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create Article') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <input id="category_id" type="hidden" name="category_id" wire:model='article.category_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Create Article') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="article.name" class="col-form-label required">{{ __('Name') }}</label>
                            <input id="article.name" name="article.name" type="text" class="form-control @error('article.name') is-invalid @enderror" wire:model='article.name' autofocus>
                            @error('article.name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="article.release_date" class="col-form-label">{{ __('Release Date') }}</label>
                            <input id="article.release_date" name="article.release_date" type="date" class="form-control @error('article.release_date') is-invalid @enderror" wire:model='article.release_date'>
                            @error('article.release_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="article.price" class="col-form-label">{{ __('Price') }}</label>
                            <div class="input-group">
                                <input id="article.price" name="article.price" type="text" class="form-control @error('article.price') is-invalid @enderror" wire:model='article.price'>
                                <span class="input-group-text">{{ config('app.currency') }}</span>
                                @error('article.price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="article.status" class="col-form-label required">{{ __('Status') }}</label>
                            <select id="article.status" name="article.status" class="form-select @error('article.status') is-invalid @enderror" wire:model='article.status' required>
                                <option value="0">{{ __('New') }}</option>
                                <option value="1">{{ __('Ordered') }}</option>
                                <option value="2">{{ __('Shipped') }}</option>
                                <option value="3">{{ __('Delivered') }}</option>
                            </select>
                            @error('article.status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="article.image_url" class="col-form-label">{{ __('Image URL') }}</label>
                            <input id="article.image_url" name="article.image_url" type="text" class="form-control @error('article.image_url') is-invalid @enderror" wire:model='article.image_url'>
                            @error('article.image_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-end">
                            <button class="btn btn-primary float-end mb-2" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('scripts.select2')
