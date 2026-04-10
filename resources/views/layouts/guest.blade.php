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
            body {
                background: #f5f6fa;
                color: #1b1f24;
            }

            .guest-shell {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 32px 16px;
            }

            .guest-card {
                width: 100%;
                max-width: 420px;
                background: #ffffff;
                border-radius: 14px;
                padding: 24px;
                border: 1px solid rgba(27, 31, 36, 0.08);
                box-shadow: 0 18px 40px rgba(15, 20, 27, 0.08);
            }
        </style>
    </head>
    <body>
        <div class="guest-shell">
            <div class="guest-card">
                <div class="text-center mb-4">
                    <a href="/">
                        <x-application-logo class="w-20 h-20" />
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
