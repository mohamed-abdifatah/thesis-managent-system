<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Thesis Management System') }}</title>

        <script>
            (() => {
                const storageKey = 'app-skin-dark';
                try {
                    const stored = localStorage.getItem(storageKey);
                    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const isDark = stored ? stored === 'app-skin-dark' : prefersDark;
                    document.documentElement.classList.toggle('app-skin-dark', isDark);
                } catch (_) {
                    // Ignore localStorage access failures
                }
            })();
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />

        <style>
            :root {
                --ink: #1b2230;
                --muted: #63758c;
                --paper: #f6f0e7;
                --card: #ffffff;
                --line: rgba(27, 34, 48, 0.14);
                --accent: #d94f20;
                --accent-strong: #b63b14;
                --teal: #0f8a96;
                --header-glass: rgba(255, 255, 255, 0.78);
                --hero-glow-a: rgba(217, 79, 32, 0.19);
                --hero-glow-b: rgba(15, 138, 150, 0.16);
                --panel-soft: rgba(255, 255, 255, 0.76);
            }

            html.app-skin-dark body {
                --ink: #e8eef8;
                --muted: #9eb0c8;
                --paper: #0d141d;
                --card: #151e2b;
                --line: rgba(224, 235, 250, 0.14);
                --accent: #ff8d5f;
                --accent-strong: #ff6e3b;
                --teal: #72d9e3;
                --header-glass: rgba(16, 24, 37, 0.72);
                --hero-glow-a: rgba(255, 141, 95, 0.2);
                --hero-glow-b: rgba(114, 217, 227, 0.16);
                --panel-soft: rgba(21, 31, 44, 0.76);
            }

            * {
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                margin: 0;
                color: var(--ink);
                background:
                    radial-gradient(900px 460px at 8% -2%, var(--hero-glow-a), transparent 60%),
                    radial-gradient(760px 420px at 92% 0%, var(--hero-glow-b), transparent 58%),
                    linear-gradient(150deg, #fefaf3 0%, var(--paper) 100%);
                font-family: "Space Grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
                line-height: 1.56;
                transition: color 0.25s ease, background 0.25s ease;
            }

            html.app-skin-dark body {
                background:
                    radial-gradient(900px 460px at 8% -2%, var(--hero-glow-a), transparent 60%),
                    radial-gradient(760px 420px at 92% 0%, var(--hero-glow-b), transparent 58%),
                    linear-gradient(150deg, #0c131c 0%, var(--paper) 100%);
            }

            .noise {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.95' numOctaves='2' stitchTiles='stitch'/></filter><rect width='140' height='140' filter='url(%23n)' opacity='0.07'/></svg>");
                pointer-events: none;
                mix-blend-mode: multiply;
                z-index: 0;
            }

            html.app-skin-dark body .noise {
                mix-blend-mode: screen;
                opacity: 0.34;
            }

            .page {
                position: relative;
                z-index: 1;
                width: min(1220px, 100%);
                margin: 0 auto;
                padding: 22px 18px 48px;
            }

            .ta-skip-link {
                position: fixed;
                left: 14px;
                top: 10px;
                transform: translateY(-150%);
                z-index: 3000;
                border-radius: 10px;
                padding: 8px 12px;
                background: #0f172a;
                color: #ffffff;
                font-size: 0.78rem;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease;
            }

            .ta-skip-link:focus {
                transform: translateY(0);
            }

            .site-header {
                position: sticky;
                top: 14px;
                z-index: 30;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                border: 1px solid var(--line);
                background: var(--header-glass);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border-radius: 18px;
                padding: 10px 12px;
                margin-bottom: 20px;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
                color: var(--ink);
                font-weight: 700;
                min-width: 0;
            }

            .brand-mark {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                display: grid;
                place-items: center;
                color: #ffffff;
                font-family: "Fraunces", serif;
                font-size: 1.08rem;
                background: linear-gradient(145deg, var(--accent), #ffbc60);
                box-shadow: 0 12px 24px rgba(217, 79, 32, 0.34);
            }

            .brand-copy {
                display: flex;
                flex-direction: column;
                line-height: 1.12;
            }

            .brand-copy strong {
                font-size: 0.95rem;
                letter-spacing: -0.02em;
            }

            .brand-copy span {
                font-size: 0.69rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--muted);
                margin-top: 3px;
            }

            .site-nav {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 8px;
            }

            .nav-btn {
                border: 1px solid transparent;
                border-radius: 999px;
                min-height: 40px;
                padding: 9px 16px;
                font-size: 0.82rem;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .nav-btn.ghost {
                color: var(--ink);
                background: rgba(255, 255, 255, 0.6);
                border-color: var(--line);
            }

            html.app-skin-dark body .nav-btn.ghost {
                background: rgba(17, 27, 40, 0.72);
            }

            .nav-btn.ghost:hover {
                transform: translateY(-1px);
                border-color: rgba(159, 178, 206, 0.5);
            }

            .nav-btn.primary {
                color: #ffffff;
                background: linear-gradient(145deg, var(--accent), var(--accent-strong));
                box-shadow: 0 14px 26px rgba(217, 79, 32, 0.3);
            }

            .nav-btn.primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 30px rgba(217, 79, 32, 0.35);
            }

            .auth-stage {
                display: grid;
                grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
                gap: 18px;
                align-items: stretch;
            }

            .auth-info,
            .auth-card {
                border: 1px solid var(--line);
                border-radius: 24px;
                background: var(--card);
                box-shadow: 0 20px 42px rgba(20, 31, 49, 0.1);
            }

            .auth-info {
                position: relative;
                overflow: hidden;
                padding: clamp(20px, 3vw, 30px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                min-height: 100%;
            }

            .auth-info::before {
                content: "";
                position: absolute;
                width: 240px;
                height: 240px;
                border-radius: 999px;
                right: -110px;
                top: -110px;
                background: radial-gradient(circle, rgba(15, 138, 150, 0.18) 0%, transparent 70%);
                pointer-events: none;
            }

            .auth-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--teal);
                font-size: 0.72rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                font-weight: 800;
                margin-bottom: 10px;
            }

            .auth-kicker::before {
                content: "";
                width: 22px;
                height: 2px;
                border-radius: 999px;
                background: var(--teal);
            }

            .auth-title {
                margin: 0;
                font-family: "Fraunces", serif;
                letter-spacing: -0.03em;
                line-height: 1.08;
                font-size: clamp(1.9rem, 3.4vw, 2.85rem);
                max-width: 540px;
            }

            .auth-copy {
                margin-top: 12px;
                max-width: 520px;
                color: var(--muted);
                font-size: 0.92rem;
                line-height: 1.78;
            }

            .auth-points {
                margin-top: 18px;
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
                max-width: 440px;
            }

            .auth-point {
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 12px;
                background: var(--panel-soft);
            }

            .auth-point strong {
                display: block;
                font-size: 0.9rem;
                letter-spacing: -0.01em;
            }

            .auth-point span {
                display: block;
                margin-top: 4px;
                font-size: 0.78rem;
                color: var(--muted);
            }

            .auth-card {
                padding: clamp(20px, 3vw, 34px);
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .auth-card-body {
                width: min(460px, 100%);
                margin: 0 auto;
            }

            .ta-auth-status {
                border-radius: 12px;
                border: 1px solid rgba(15, 138, 150, 0.36);
                background: rgba(15, 138, 150, 0.14);
                color: #145661;
                padding: 10px 12px;
                font-size: 0.84rem;
                margin-bottom: 12px;
            }

            html.app-skin-dark body .ta-auth-status {
                color: #9de7ef;
            }

            .ta-error-summary {
                border: 1px solid rgba(216, 68, 68, 0.4);
                background: rgba(216, 68, 68, 0.12);
                color: #902b2b;
                border-radius: 12px;
                padding: 10px 12px;
                margin-bottom: 12px;
            }

            html.app-skin-dark body .ta-error-summary {
                color: #ffb9b9;
            }

            .ta-error-summary ul {
                margin: 8px 0 0;
                padding-left: 18px;
            }

            .auth-card .form-label,
            .auth-card label {
                margin-bottom: 6px;
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: var(--muted);
            }

            .auth-card .form-control,
            .auth-card .form-select,
            .auth-card input:not([type='checkbox']):not([type='radio']) {
                min-height: 46px;
                border-radius: 12px;
                border: 1px solid var(--line);
                color: var(--ink);
                font-weight: 600;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: none;
            }

            html.app-skin-dark body .auth-card .form-control,
            html.app-skin-dark body .auth-card .form-select,
            html.app-skin-dark body .auth-card input:not([type='checkbox']):not([type='radio']) {
                background: rgba(18, 29, 44, 0.92);
            }

            .auth-card .form-control::placeholder,
            .auth-card .form-select::placeholder,
            .auth-card input::placeholder {
                color: #8a9ab0;
                font-weight: 500;
            }

            .auth-card .form-control:focus,
            .auth-card .form-select:focus,
            .auth-card input:focus {
                border-color: #4e7cff;
                box-shadow: 0 0 0 4px rgba(78, 124, 255, 0.14);
            }

            .auth-card .btn,
            .auth-card button[type='submit'] {
                border-radius: 12px;
                min-height: 42px;
                font-weight: 700;
            }

            .auth-card .btn-dark {
                border: 0;
                background: linear-gradient(145deg, var(--accent), var(--accent-strong));
                box-shadow: 0 14px 24px rgba(217, 79, 32, 0.26);
            }

            .auth-card .btn-dark:hover,
            .auth-card .btn-dark:focus {
                background: linear-gradient(145deg, #df612f, #bd3f17);
                box-shadow: 0 18px 26px rgba(217, 79, 32, 0.3);
            }

            .auth-card .text-muted {
                color: var(--muted) !important;
            }

            .auth-card .small,
            .auth-card small,
            .auth-card .text-danger {
                font-size: 0.82rem;
            }

            .auth-card a:not(.nav-btn) {
                color: #1f58d8;
                font-weight: 700;
                text-decoration: none;
            }

            .auth-card a:not(.nav-btn):hover {
                color: #1b45af;
                text-decoration: underline;
            }

            @media (max-width: 1024px) {
                .auth-stage {
                    grid-template-columns: 1fr;
                }

                .auth-info,
                .auth-card {
                    border-radius: 20px;
                }

                .auth-points {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    max-width: none;
                }
            }

            @media (max-width: 640px) {
                .page {
                    padding: 14px 12px 36px;
                }

                .site-header {
                    top: 10px;
                    border-radius: 14px;
                }

                .brand-copy strong {
                    font-size: 0.88rem;
                }

                .site-nav {
                    width: 100%;
                }

                .nav-btn {
                    flex: 1 1 auto;
                    text-align: center;
                }

                .auth-info,
                .auth-card {
                    border-radius: 18px;
                }

                .auth-points {
                    grid-template-columns: 1fr;
                }

                .auth-card {
                    padding: 18px 14px;
                }

                .auth-title {
                    font-size: clamp(1.68rem, 9vw, 2.15rem);
                }
            }
        </style>
    </head>
    <body>
        <a href="#guest-main-content" class="ta-skip-link">Skip to content</a>
        <div class="noise"></div>

        @php
            $isLoginRoute = request()->routeIs('login');
            $isRegisterRoute = request()->routeIs('register');
            $isForgotPasswordRoute = request()->routeIs('password.request');
            $isResetPasswordRoute = request()->routeIs('password.reset');
            $isVerifyRoute = request()->routeIs('verification.notice');
            $isConfirmRoute = request()->routeIs('password.confirm');

            $authKicker = 'Secure Academic Access';
            $authTitle = 'Continue your thesis journey with confidence.';
            $authCopy = 'Your workspace keeps proposals, feedback loops, and defense milestones connected so every role can move forward with clarity.';

            if ($isLoginRoute) {
                $authTitle = 'Welcome back. Let us pick up where your research left off.';
                $authCopy = 'Sign in to manage proposals, thesis versions, and defense workflows in one place.';
            } elseif ($isRegisterRoute) {
                $authTitle = 'Create your account and start managing thesis work in one flow.';
                $authCopy = 'Register once to access a role-aware dashboard built for students, supervisors, committee members, and librarians.';
            } elseif ($isForgotPasswordRoute || $isResetPasswordRoute) {
                $authTitle = 'Recover access without losing momentum.';
                $authCopy = 'Use the secure reset flow to regain entry and continue your thesis timeline.';
            } elseif ($isVerifyRoute) {
                $authTitle = 'Verify your email to activate collaboration features.';
                $authCopy = 'Email verification keeps thesis communication trusted and trackable across all academic roles.';
            } elseif ($isConfirmRoute) {
                $authTitle = 'Confirm your identity to proceed securely.';
                $authCopy = 'This checkpoint protects critical actions and keeps your academic data safe.';
            }
        @endphp

        <div class="page">
            @include('partials.public-header')

            <main class="auth-stage" id="guest-main-content">
                <section class="auth-info" aria-label="Authentication context">
                    <div>
                        <span class="auth-kicker">{{ $authKicker }}</span>
                        <h1 class="auth-title">{{ $authTitle }}</h1>
                        <p class="auth-copy">{{ $authCopy }}</p>
                    </div>

                    <div class="auth-points" aria-hidden="true">
                        <div class="auth-point">
                            <strong>Unified Workflow</strong>
                            <span>From proposal to public catalog with one connected timeline.</span>
                        </div>
                        <div class="auth-point">
                            <strong>Role-Aware Access</strong>
                            <span>Students, supervisors, committees, and admins use focused tools.</span>
                        </div>
                        <div class="auth-point">
                            <strong>Clear Milestones</strong>
                            <span>Status updates and decisions remain transparent from start to defense.</span>
                        </div>
                    </div>
                </section>

                <section class="auth-card" aria-label="Authentication form area">
                    <div class="auth-card-body">
                        @if (session('status'))
                            <div class="ta-auth-status" role="status">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="ta-error-summary" role="alert">
                                <strong class="d-block">Please review the highlighted fields:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </section>
            </main>
        </div>

        <script>
            (() => {
                const root = document.documentElement;
                const button = document.getElementById('themeToggle');
                const storageKey = 'app-skin-dark';

                const applyTheme = (theme) => {
                    const dark = theme === 'dark';
                    root.classList.toggle('app-skin-dark', dark);
                    if (button) {
                        button.textContent = dark ? 'Light theme' : 'Dark theme';
                        button.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    }
                    try {
                        localStorage.setItem(storageKey, dark ? 'app-skin-dark' : 'app-skin-light');
                    } catch (_) {
                        // Ignore localStorage access failures
                    }
                };

                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                let savedTheme = null;
                try {
                    savedTheme = localStorage.getItem(storageKey);
                } catch (_) {
                    savedTheme = null;
                }

                const initialTheme = savedTheme ? (savedTheme === 'app-skin-dark' ? 'dark' : 'light') : (prefersDark ? 'dark' : 'light');
                applyTheme(initialTheme);

                if (button) {
                    button.addEventListener('click', () => {
                        const isDark = root.classList.contains('app-skin-dark');
                        applyTheme(isDark ? 'light' : 'dark');
                    });
                }
            })();
        </script>
    </body>
</html>
