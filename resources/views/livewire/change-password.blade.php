<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{ __('Profile') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white shadow-sm rounded">
            <div class="col-md-12">
                <div class="p-3 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-right">{{ __('Change Password') }}</h4>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="current_password" class="col-form-label required">{{ __('Current Password') }}</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" wire:model='current_password' autofocus>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="new_password" class="col-form-label required">{{ __('New Password') }}</label>
                            <input id="new_password" name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" wire:model='new_password'>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="new_password_confirmation" class="col-form-label required">{{ __('Confirm Password') }}</label>
                            <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" wire:model='new_password_confirmation'>
                            @error('new_password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button class="btn btn-danger" type="submit">{{ __('Change Password') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
