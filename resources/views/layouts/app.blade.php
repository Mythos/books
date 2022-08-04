<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
        <title>
            @yield('title') - {{ config('app.name', 'Laravel') }}
        </title>
    @else
        <title>
            {{ config('app.name', 'Laravel') }}
        </title>
    @endif

    <link rel="apple-touch-icon" sizes="180x180" href="{{ mix('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ mix('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ mix('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ mix('site.webmanifest') }}">
    <meta name="theme-color" content="#3c8dbc">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    @livewireStyles
    @flasher_render
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-dark bg-dark navbar-expand-md fixed-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="d-block d-md-none my-1" style="width: 100%;">
                    @livewire('search')
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __('Administration') }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('genres.index') }}">
                                            {{ __('Genres') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('publishers.index') }}">
                                            {{ __('Publishers') }}
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                                            {{ __('Administration') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('statistics') }}">{{ __('Statistics') }}</a>
                            </li>
                        @endauth
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (config('auth.registration_enabled') == true && Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item d-none d-md-block">
                                @livewire('search')
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="fas fa-plus"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('categories.create') }}">
                                        {{ __('Category') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img class="rounded-circle" width="20px" src="{{ auth()->user()->avatar_navbar }}" alt="{{ auth()->user()->email }}" loading="lazy" decoding="async">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">{{ __('Profile') }}</a></li>
                                    <li>@livewire('nsfw-toggle')</li>
                                    <li>@livewire('canceled-series-toggle')</li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="pb-4 padding-content-container" style="background-color: #F0F0F0;">
            @yield('content')
        </main>
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top">
            <div class="col-md-4 d-flex align-items-center">
            </div>

            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <li class="me-3">
                    @auth
                        @livewire('version')
                    @endauth
                </li>
            </ul>
        </footer>
    </div>
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("service-worker.js").then(function(reg) {}).catch(function(err) {});
        }
    </script>
    @livewireScripts
    <x-livewire-alert::scripts />
    @stack('scripts')
</body>

</html>
