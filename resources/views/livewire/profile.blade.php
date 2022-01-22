<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Profile') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-3">
                <div class="d-flex flex-column align-items-center text-center p-3 py-3">
                    <img class="rounded-circle" width="150px" src="https://www.gravatar.com/avatar/{{ md5(auth()->user()->email) }}?s=150">
                    <span class="font-weight-bold">{{ auth()->user()->name }}</span>
                    <span class="text-black-50">{{ auth()->user()->email }}</span>
                </div>
            </div>
            <div class="col-md-9">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">{{ __('Profile') }}</h4>
                        <a class="btn btn-danger" href="{{ route('change-password') }}">{{ __('Change password') }}</a>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="user.name" class="col-form-label required">{{ __('Name') }}</label>
                            <input id="user.name" name="user.name" type="text" class="form-control @error('user.name') is-invalid @enderror" wire:model='user.name'>
                            @error('user.name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="user.email" class="col-form-label required">{{ __('E-Mail Address') }}</label>
                            <input id="user.email" name="user.email" type="email" class="form-control @error('user.email') is-invalid @enderror" wire:model='user.email'>
                            @error('user.email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input id="user.is_nsfw" name="user.is_nsfw" type="checkbox" class="form-check-input @error('user.format_isbns_enabled') is-invalid @enderror" wire:model='user.format_isbns_enabled'>
                                <label for="user.is_nsfw" class="form-check-label">{{ __('Format ISBNs') }}</label>
                            </div>
                            @error('user.format_isbns_enabled')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary" type="submit">Save Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>