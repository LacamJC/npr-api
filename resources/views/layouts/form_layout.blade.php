<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/header/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Footer/Footer.css') }}">
    @yield('head')
</head>

<body>
    <main class="">
        <div class="logo">
            <img src="{{ asset('assets/img/logosimp.svg') }}" alt="Logo da NPR">
        </div>
        <main class="main_content row w-100">

            <div class="col col-md-6 left-side">
                @yield('content')
            </div>

            <div class="col col-md-6 right-side">

            </div>

        </main>

    </main>

    <x-layout.footer />
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/ShowPass.js') }}"></script>

</body>

</html>
