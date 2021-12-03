<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create Book') }}</div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent="save">
                        @csrf
                        <input id="series_id" type="hidden" name="series_id" wire:model='series_id' />
                        <div class="mb-3 row">
                            <label for="isbn" class="col-md-2 col-form-label text-md-right">{{ __('ISBN') }}</label>
                            <div class="col-md-10">
                                <input id="isbn" type="text" class="form-control @error('isbn') is-invalid @enderror" name="isbn" wire:model='isbn' required autocomplete="isbn" autofocus>
                                @error('isbn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="publish_date" class="col-md-2 col-form-label text-md-right">{{ __('Publish Date') }}</label>
                            <div class="col-md-10">
                                <input id="publish_date" type="date" class="form-control @error('publish_date') is-invalid @enderror" name="publish_date" wire:model='publish_date' required autofocus>
                                @error('publish_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="status" class="col-md-2 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-10">
                                <select class="form-select @error('status') is-invalid @enderror" name="status" wire:model='status' required>
                                    <option value="0">{{ __('New') }}</option>
                                    <option value="1">{{ __('Ordered') }}</option>
                                    <option value="2">{{ __('Delivered') }}</option>
                                </select>
                                @error('status')
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