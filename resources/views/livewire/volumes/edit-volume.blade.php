<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('series.show', [$category, $series]) }}">{{ $series->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Volume :number', ['number' => $volume->number]) }}</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Volume :number', ['number' => $volume->number]) }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent="save">
                        @csrf
                        <input id="series_id" type="hidden" name="series_id" wire:model='volume.series_id' />
                        <div class="mb-3 row">
                            <label for="isbn" class="col-md-2 col-form-label text-md-right">{{ __('ISBN') }}</label>
                            <div class="col-md-10">
                                <input id="isbn" type="text" class="form-control @error('volume.isbn') is-invalid @enderror" name="isbn" wire:model='volume.isbn' required autocomplete="isbn" autofocus>
                                @error('volume.isbn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="publish_date" class="col-md-2 col-form-label text-md-right">{{ __('Publish Date') }}</label>
                            <div class="col-md-10">
                                <input id="publish_date" type="date" class="form-control @error('volume.publish_date') is-invalid @enderror" name="publish_date" wire:model='volume.publish_date' required autofocus>
                                @error('volume.publish_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="status" class="col-md-2 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-10">
                                <select class="form-select @error('volume.status') is-invalid @enderror" name="status" wire:model='volume.status' required>
                                    <option value="0">{{ __('New') }}</option>
                                    <option value="1">{{ __('Ordered') }}</option>
                                    <option value="2">{{ __('Shipped') }}</option>
                                    <option value="3">{{ __('Delivered') }}</option>
                                </select>
                                @error('volume.status')
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
