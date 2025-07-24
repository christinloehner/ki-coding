<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>@yield('title', config('app.name', 'Laravel'))</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.bunny.net">
            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

            <!-- CRITICAL: Dark Mode Theme Detection - Must run before CSS loads to prevent flash -->
            <script>
                (function() {
                // Theme detection ONLY: Keine Systempräferenz mehr! Immer Light, wenn keine Präferenz gespeichert
                    const savedTheme = localStorage.getItem('theme');
                    const theme = savedTheme || 'light';
                    document.documentElement.setAttribute('data-theme', theme);
                })();
            </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dark-mode.js', 'resources/js/category-colors.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Dark Mode Toggle für Guest Pages -->
        <div class="fixed top-4 right-4 z-50">
            <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Dark Mode umschalten" title="Dark Mode umschalten">
                <!-- Sun Icon (Light Mode) -->
                <svg id="sunIcon" class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <!-- Moon Icon (Dark Mode) -->
                <svg id="moonIcon" class="w-5 h-5 transition-all duration-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="{{ route('home') }}" aria-label="Zur KI-Coding Startseite">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <main class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
