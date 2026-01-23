<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 't-quest' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/map-component.css'])
    @livewireStyles
    @stack('styles')

</head>

<body>
    <main>
        @yield('content')
    </main>

    <x-navigation-menu />

    <x-toast />

    <x-leaflet-assets />

    @livewireScripts
    @stack('scripts')
</body>

</html>