<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>night-q</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full max-w-[1200px] text-sm mb-6 not-has-[nav]:hidden">
        <nav class="flex items-center justify-begin gap-4 dark:text-[#EDEDEC]">
            @auth
                @if (Route::is('profile'))
                    <livewire:account-name />
                @else
                    <a class="hover:underline font-bold" href="{{ route('profile') }}">
                        {{ auth()->user()->name }}
                    </a>
                @endif
            @else
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Register
                </a>
            @endif
            @endauth
        </nav>
    </header>
    <main
        class="max-w-[1200px] flex-1 flex-col items-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        @yield('content')
    </main>

    <footer class="p-10 w-full text-center dark:text-[#EDEDEC]">{{ now()->year }}</footer>

    @auth
        <x-navigation-menu />
    @endauth

    <x-toast />

    @livewireScripts
    @stack('scripts')
</body>

</html>