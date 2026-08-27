<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo-1.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<<<<<<< HEAD
    <!-- Fonts: League Gothic & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Gothic&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
=======
>>>>>>> parent of ffcc090 (<fix>: importando as fontes no guest.php)

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/guest.css', 'resources/js/guest.js'])
</head>

<body class="guest-body">
    <div class="guest-container">
        <a href="/" class="guest-logo-a">
            <x-application-logo class="guest-logo" />
        </a>


        <div class="guest-card">
            {{ $slot }}
        </div>
    </div>
</body>

</html>