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
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap-lumen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />

    @livewireStyles

</head>

<body>
    <div id="app">
        @include('layouts.navigation')

        <main class="pt-4 pb-0">
            @yield('content')
            {{ isset($slot) ? $slot : null }}
        </main>
    </div>

    {{-- footer for desktop --}}
    <div class="d-none d-md-block">
        <footer>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-12">
                        <hr>
                        <p class="text-center font-weight-bold">Made with <i class="fas fa-heart text-danger"></i> by
                            Kelompok 8
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- footer for mobile --}}
    <div class=" d-md-none d-lg-none d-xl-none">
        <footer class="mb-5">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-12">
                        <hr>
                        <p class="text-center font-weight-bold">Made with <i class="fas fa-heart text-danger"></i>
                            by Kelompok 8
                    </div>
                </div>
            </div>
        </footer>
        <br>
    </div>

    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <x-livewire-alert::scripts />

    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>

    <script>
        var map = {};
        onkeydown = onkeyup = function(e) {
            e = e || event;
            map[e.keyCode] = e.type == 'keydown';
            // 191 = /
            if (map["191"] == true) {
                e.preventDefault();
                var elm = document.getElementById('search');
                elm.focus();
            }
        }

    </script>

    <script>
        window.addEventListener('close-modal', event => {
            $('#exampleModal').modal('hide');
        })

    </script>
</body>

</body>

</html>
