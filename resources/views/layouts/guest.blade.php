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
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap');

            :root {
                --guest-bg: #eef3f9;
                --guest-ink: #111928;
                --guest-muted: #5c6b7f;
                --guest-surface: #ffffff;
                --guest-border: #dbe4f1;
                --guest-primary: #375dfb;
                --guest-primary-deep: #1939b7;
                --guest-shadow: 0 35px 80px rgba(16, 42, 78, 0.16);
            }

            * {
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                margin: 0;
                color: var(--guest-ink);
                background:
                    radial-gradient(circle at 84% 6%, rgba(59, 130, 246, 0.14), transparent 34%),
                    radial-gradient(circle at 12% 88%, rgba(79, 70, 229, 0.1), transparent 36%),
                    var(--guest-bg);
                font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .guest-shell {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px 16px;
            }

            .guest-card {
                width: 100%;
                max-width: 460px;
                border-radius: 24px;
                background: var(--guest-surface);
                border: 1px solid var(--guest-border);
                padding: 34px 30px;
                box-shadow: var(--guest-shadow);
            }

            .guest-shell.guest-shell-login {
                width: min(1160px, 100%);
                display: grid;
                grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
                border-radius: 30px;
                border: 1px solid var(--guest-border);
                background: var(--guest-surface);
                box-shadow: var(--guest-shadow);
                overflow: hidden;
                padding: 0;
            }

            .login-hero {
                position: relative;
                padding: 44px;
                color: #f8fbff;
                background-image:
                    linear-gradient(168deg, rgba(13, 40, 83, 0.83) 0%, rgba(24, 56, 130, 0.78) 45%, rgba(44, 103, 210, 0.72) 100%),
                    url('{{ asset('assets/images/banner/6.jpg') }}');
                background-size: cover;
                background-position: center;
                isolation: isolate;
            }

            .login-hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(112deg, rgba(255, 255, 255, 0.08) 0%, transparent 52%),
                    linear-gradient(0deg, rgba(13, 40, 83, 0.18), rgba(13, 40, 83, 0.18));
                z-index: 0;
            }

            .login-hero > * {
                position: relative;
                z-index: 1;
            }

            .login-hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                border: 1px solid rgba(255, 255, 255, 0.32);
                background: rgba(255, 255, 255, 0.16);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }

            .login-hero h2 {
                margin: 18px 0 10px;
                font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
                font-size: clamp(2rem, 2.4vw, 2.7rem);
                line-height: 1.1;
                letter-spacing: -0.03em;
                max-width: 420px;
            }

            .login-hero p {
                margin: 0;
                color: rgba(241, 247, 255, 0.93);
                max-width: 430px;
                line-height: 1.58;
                font-size: 0.94rem;
            }

            .login-hero-stats {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
                margin-top: 28px;
                max-width: 360px;
            }

            .login-hero-stat {
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.28);
                border-radius: 16px;
                padding: 12px 14px;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
            }

            .login-hero-stat strong {
                display: block;
                font-size: 1.2rem;
                font-weight: 800;
                letter-spacing: -0.02em;
                color: #ffffff;
            }

            .login-hero-stat span {
                display: block;
                margin-top: 4px;
                font-size: 12px;
                color: rgba(240, 247, 255, 0.9);
            }

            .guest-card.guest-card-login {
                max-width: none;
                border: 0;
                box-shadow: none;
                border-radius: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 42px 34px;
                background: linear-gradient(160deg, #ffffff 0%, #f8fbff 100%);
            }

            @media (max-width: 1024px) {
                .guest-shell.guest-shell-login {
                    grid-template-columns: 1fr;
                    border-radius: 24px;
                }

                .login-hero {
                    min-height: 290px;
                    padding: 30px;
                }

                .guest-card.guest-card-login {
                    padding: 28px 20px;
                }
            }

            @media (max-width: 640px) {
                .guest-shell {
                    padding: 14px;
                }

                .guest-card {
                    padding: 26px 18px;
                }

                .login-hero h2 {
                    font-size: clamp(1.62rem, 8.5vw, 2.25rem);
                }

                .login-hero-stats {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        @php($isLoginRoute = request()->routeIs('login'))

        <div class="guest-shell {{ $isLoginRoute ? 'guest-shell-login' : '' }}">
            @if($isLoginRoute)
                <section class="login-hero">
                    <span class="login-hero-badge">
                        <i class="feather-shield"></i>
                        Thesis Management System
                    </span>
                    <h2>Welcome to your smart thesis workspace.</h2>
                    <p>Manage supervision, review submissions, and keep every milestone on track with an admin dashboard inspired by TailAdmin.</p>
                    <div class="login-hero-stats">
                        <div class="login-hero-stat">
                            <strong>All Roles</strong>
                            <span>Admin, supervisor, student, examiner</span>
                        </div>
                        <div class="login-hero-stat">
                            <strong>One Hub</strong>
                            <span>Proposals, defenses, thesis versions</span>
                        </div>
                    </div>
                </section>
            @endif

            <div class="guest-card {{ $isLoginRoute ? 'guest-card-login' : '' }}">
                @unless($isLoginRoute)
                    <div class="text-center mb-4">
                        <a href="/" class="d-inline-flex align-items-center justify-content-center">
                            <x-application-logo class="w-20 h-20" />
                        </a>
                    </div>
                @endunless
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
