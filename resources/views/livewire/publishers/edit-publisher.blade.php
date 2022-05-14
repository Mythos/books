@section('title')
    {{ $publisher->name }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item">{{ __('Administration') }}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('publishers.index') }}">{{ __('Publishers') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $publisher->name }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <input id="category_id" type="hidden" name="category_id" wire:model='series.category_id' />
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Edit Series') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="publisher.name" class="col-form-label required">{{ __('Name') }}</label>
                            <input id="publisher.name" name="publisher.name" type="text" class="form-control @error('publisher.name') is-invalid @enderror" wire:model='publisher.name' autofocus>
                            @error('publisher.name')
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
