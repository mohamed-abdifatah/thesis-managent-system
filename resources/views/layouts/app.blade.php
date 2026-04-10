<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />

        <style>
            html.app-skin-dark body {
                background-color: #0f141b;
                color: #e6edf3;
            }

            html.app-skin-dark .nxl-container,
            html.app-skin-dark .nxl-content,
            html.app-skin-dark .main-content {
                background-color: transparent;
            }

            html.app-skin-dark .card,
            html.app-skin-dark .nxl-card,
            html.app-skin-dark .modal-content,
            html.app-skin-dark .dropdown-menu,
            html.app-skin-dark .table,
            html.app-skin-dark .bg-white {
                background-color: #151a21 !important;
                color: #e6edf3;
                border-color: rgba(255, 255, 255, 0.08);
            }

            html.app-skin-dark .text-dark {
                color: #e6edf3 !important;
            }

            html.app-skin-dark .text-muted {
                color: #9aa4b2 !important;
            }

            html.app-skin-dark .b-brand .logo,
            html.app-skin-dark .b-brand .logo-lg,
            html.app-skin-dark .b-brand .logo-sm {
                color: #e6edf3 !important;
            }

            html.app-skin-dark .b-brand h1,
            html.app-skin-dark .b-brand h2,
            html.app-skin-dark .b-brand h3,
            html.app-skin-dark .b-brand h4,
            html.app-skin-dark .b-brand h5,
            html.app-skin-dark .b-brand h6 {
                color: #e6edf3 !important;
            }

            html.app-skin-dark .b-brand .logo-lg,
            html.app-skin-dark .b-brand .logo-sm,
            html.app-skin-dark .b-brand .logo {
                color: #e6edf3 !important;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.35);
            }

            html.app-skin-dark .nxl-navigation .b-brand .logo.logo-lg {
                color: #e6edf3 !important;
            }

            .app-brand-title {
                color: #1b1f24;
            }

            html.app-skin-dark .app-brand-title {
                color: #e6edf3 !important;
            }

            html.app-skin-dark .bg-light {
                background-color: #151a21 !important;
            }

            html.app-skin-dark .card-footer {
                border-color: rgba(255, 255, 255, 0.08);
            }

            html.app-skin-dark .form-control,
            html.app-skin-dark .form-select,
            html.app-skin-dark .input-group-text {
                background-color: #1b212b;
                color: #e6edf3;
                border-color: rgba(255, 255, 255, 0.12);
            }

            html.app-skin-dark .table > :not(caption) > * > * {
                background-color: transparent;
                color: inherit;
                border-color: rgba(255, 255, 255, 0.08);
            }

            .modal-backdrop {
                display: none !important;
                background-color: transparent !important;
                opacity: 0 !important;
                filter: none !important;
                backdrop-filter: none !important;
            }

            body.modal-open {
                overflow: auto !important;
                padding-right: 0 !important;
            }
        </style>
        
        <!-- Scripts -->
        <!-- We are using the template scripts instead of Vite for now as requested -->
    </head>
    <body class="bg-gray-100">
        <!-- Navigation Menu -->
        @include('layouts.sidebar')

        <!-- Header -->
        @include('layouts.topbar')

        <!-- Main Container -->
        <main class="nxl-container">
            <div class="nxl-content">
                <!-- Page Content -->
                <div class="main-content">
                    {{ $slot }}
                </div>
            </div>
            
             <!-- Footer -->
            <footer class="footer">
                <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
                    <span>Copyright © {{ date('Y') }}</span>
                </p>
                <div class="d-flex align-items-center gap-4">
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Help</a>
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Terms</a>
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Privacy</a>
                </div>
            </footer>
        </main>

        <!-- Vendors JS -->
        <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>

        <!-- Bootstrap JS (modal, dropdown, etc.) -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        
        <!-- Common Init JS -->
        <script src="{{ asset('assets/js/common-init.min.js') }}"></script>

        <script>
            (() => {
                const storageKey = 'app-skin-dark';
                const html = document.documentElement;
                const darkButton = document.querySelector('.dark-button');
                const lightButton = document.querySelector('.light-button');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const stored = localStorage.getItem(storageKey);
                const initialDark = stored ? stored === 'app-skin-dark' : prefersDark;

                const applyTheme = (isDark) => {
                    html.classList.toggle('app-skin-dark', isDark);
                    if (darkButton && lightButton) {
                        darkButton.style.display = isDark ? 'none' : 'inline-flex';
                        lightButton.style.display = isDark ? 'inline-flex' : 'none';
                    }
                    localStorage.setItem(storageKey, isDark ? 'app-skin-dark' : 'app-skin-light');
                };

                applyTheme(initialDark);

                if (darkButton) {
                    darkButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        applyTheme(true);
                    });
                }

                if (lightButton) {
                    lightButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        applyTheme(false);
                    });
                }
            })();
        </script>
        
        <!-- Custom Scripts if any -->
        @stack('modals')
        @stack('scripts')
    </body>
</html>
