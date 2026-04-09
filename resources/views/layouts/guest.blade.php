<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body.app-skin-dark {
                background: #0f141b;
                color: #e6edf3;
            }

            .theme-toggle {
                position: fixed;
                top: 18px;
                right: 18px;
                z-index: 50;
                border-radius: 999px;
                padding: 8px 14px;
                border: 1px solid rgba(0, 0, 0, 0.2);
                background: #ffffff;
                font-weight: 600;
                font-size: 13px;
            }

            body.app-skin-dark .theme-toggle {
                background: #141b23;
                color: #e6edf3;
                border-color: rgba(255, 255, 255, 0.18);
            }

            body.app-skin-dark .guest-shell {
                background: #0f141b;
            }

            body.app-skin-dark .guest-card {
                background: #141b23;
                color: #e6edf3;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45);
            }

            body.app-skin-dark .bg-white {
                background-color: #141b23 !important;
            }

            body.app-skin-dark .text-gray-900,
            body.app-skin-dark .text-gray-800,
            body.app-skin-dark .text-gray-700,
            body.app-skin-dark .text-gray-600,
            body.app-skin-dark .text-gray-500 {
                color: #e6edf3 !important;
            }

            body.app-skin-dark .border-gray-100,
            body.app-skin-dark .border-gray-200,
            body.app-skin-dark .border-gray-300 {
                border-color: rgba(255, 255, 255, 0.12) !important;
            }

            body.app-skin-dark input,
            body.app-skin-dark textarea,
            body.app-skin-dark select {
                background-color: #1b212b !important;
                color: #e6edf3 !important;
                border-color: rgba(255, 255, 255, 0.12) !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <button class="theme-toggle" type="button" id="themeToggleGuest">Dark theme</button>
        <div class="guest-shell min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="guest-card w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <script>
            (() => {
                const storageKey = 'app-skin-dark';
                const button = document.getElementById('themeToggleGuest');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const stored = localStorage.getItem(storageKey);
                const initialDark = stored ? stored === 'app-skin-dark' : prefersDark;

                const applyTheme = (isDark) => {
                    document.body.classList.toggle('app-skin-dark', isDark);
                    button.textContent = isDark ? 'Light theme' : 'Dark theme';
                    button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                    localStorage.setItem(storageKey, isDark ? 'app-skin-dark' : 'app-skin-light');
                };

                applyTheme(initialDark);

                button.addEventListener('click', () => {
                    const isDark = document.body.classList.contains('app-skin-dark');
                    applyTheme(!isDark);
                });
            })();
        </script>
    </body>
</html>
