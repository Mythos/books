<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Profile') }}</li>
        </ol>
    </nav>
    <form method="POST" wire:submit.prevent='save'>
        <div class="row bg-white rounded">
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
                            <label for="name" class="col-form-label required">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" wire:model='name'>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <label for="email" class="col-form-label required">{{ __('E-Mail Address') }}</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" wire:model='email'>
                            @error('email')
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
