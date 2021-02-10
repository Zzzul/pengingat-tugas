<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />

    @livewireStyles
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-none d-md-block">
            <div class="container">
                <a class="navbar-brand{{ request()->is('home') ? ' active' : '' }}" href="{{ url('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link{{ request()->is('tugas') ? ' active' : '' }}" href="tugas">Tugas</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link{{ request()->is('matkul') ? ' active' : '' }}" href="matkul">Mata
                                Kuliah</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link{{ request()->is('semester') ? ' active' : '' }}"
                                href="semester">Semester</a>
                        </li>

                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Bottom Navbar -->
        <nav class="navbar navbar-light bg-white navbar-expand fixed-bottom d-md-none d-lg-none d-xl-none">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a class="mb-0 pb-0 nav-link{{ request()->is('home') ? ' active' : '' }}" href="home">
                        <svg width="1.3em" height="1.3em" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                            <path fill-rule="evenodd"
                                d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                        </svg>
                        <br>
                        <small class="m-0 p-0">Home</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="mb-0 pb-0 nav-link{{ request()->is('tugas') ? ' active' : '' }}" href="tugas">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" fill="currentColor"
                            class="bi bi-card-checklist" viewBox="0 0 16 16">
                            <path
                                d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                            <path
                                d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
                        </svg>
                        <br>
                        <small class="m-0 p-0">Tugas</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="mb-0 pb-0 nav-link{{ request()->is('matkul') ? ' active' : '' }}" href="matkul">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" fill="currentColor"
                            class="bi bi-book" viewBox="0 0 16 16">
                            <path
                                d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                        </svg>
                        <br>
                        <small class="m-0 p-0">Mata Kuliah</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="mb-0 pb-0 nav-link{{ request()->is('semester') ? ' active' : '' }}" href="semester">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" fill="currentColor"
                            class="bi bi-clipboard" viewBox="0 0 16 16">
                            <path
                                d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                            <path
                                d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                        </svg>
                        <br>
                        <small class="m-0 p-0">Semester</small>
                    </a>
                </li>
            </ul>
        </nav>

        <main class="py-4">
            @yield('content')
            {{ isset($slot) ? $slot : null }}
        </main>
    </div>

    <footer class="mb-5">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12">
                    <hr>
                    <p class="text-center">Created by <a href="https://github.com/zzzul/" class="text-info"
                            target="blank">Mohammad
                            Zulfahmi</a> Made
                        with <i class="fas fa-heart-broken text-danger"></i></p>
                </div>
            </div>
            {{-- end of row --}}
        </div>
    </footer>
    <br>
    @livewireScripts
    <x-livewire-alert::scripts />
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false"></script>
    <script>
        var map = {};
        onkeydown = onkeyup = function(e){
            e = e || event;

            map[e.keyCode] = e.type == 'keydown';

            // 191 = /
            if(map["191"]==true){
                e.preventDefault();
                var elm=document.getElementById('search');
                elm.focus();
            }

        }
    </script>
</body>

</body>

</html>
