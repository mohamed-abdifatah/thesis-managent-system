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
        
        <!-- Common Init JS -->
        <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
        
        <!-- Custom Scripts if any -->
        @stack('scripts')
    </body>
</html>
