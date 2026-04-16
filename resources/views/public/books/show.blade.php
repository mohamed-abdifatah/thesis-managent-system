<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $thesis->title }} | Thesis Books</title>

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

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700&display=swap" rel="stylesheet">

        <style>
            :root {
                --ink: #111827;
                --muted: #5f6672;
                --paper: #f4f1ec;
                --surface: #ffffff;
                --surface-soft: #f7f5f1;
                --accent: #e4572e;
                --accent-dark: #c7421d;
                --sea: #2f8f9d;
                --line: rgba(15, 23, 42, 0.1);
                --shadow: 0 18px 45px rgba(18, 20, 23, 0.12);
            }

            html.app-skin-dark body {
                --ink: #f1f1f1;
                --muted: #a4adbb;
                --paper: #101419;
                --surface: #151a21;
                --surface-soft: #1a2028;
                --accent: #ff8a5b;
                --accent-dark: #ff6a32;
                --sea: #6ad0dd;
                --line: rgba(255, 255, 255, 0.12);
                --shadow: 0 24px 60px rgba(0, 0, 0, 0.42);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Space Grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
                line-height: 1.55;
                color: var(--ink);
                background:
                    radial-gradient(circle at 12% 6%, rgba(228, 87, 46, 0.12), transparent 38%),
                    radial-gradient(circle at 86% 12%, rgba(47, 143, 157, 0.16), transparent 35%),
                    linear-gradient(130deg, #fcf8f3 0%, var(--paper) 100%);
                transition: background 0.3s ease, color 0.3s ease;
            }

            html.app-skin-dark body {
                background:
                    radial-gradient(circle at 14% 6%, rgba(255, 138, 91, 0.14), transparent 38%),
                    radial-gradient(circle at 86% 12%, rgba(106, 208, 221, 0.14), transparent 35%),
                    linear-gradient(130deg, #0f141b 0%, #0a0f14 100%);
            }

            .noise {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/></filter><rect width='160' height='160' filter='url(%23n)' opacity='0.08'/></svg>");
                pointer-events: none;
                mix-blend-mode: multiply;
            }

            html.app-skin-dark body .noise {
                mix-blend-mode: screen;
                opacity: 0.38;
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

            .book-shell {
                width: min(1140px, 100%);
                margin: 0 auto;
                padding: 28px 16px 38px;
                position: relative;
                z-index: 1;
            }

            .book-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 22px;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 700;
                letter-spacing: 0.02em;
            }

            .logo-mark {
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: linear-gradient(135deg, var(--accent), #ffba5a);
                display: grid;
                place-items: center;
                color: white;
                font-family: "Fraunces", serif;
                font-size: 20px;
                box-shadow: 0 10px 30px rgba(228, 87, 46, 0.35);
            }

            .book-header nav {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .hero-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 18px;
                border-radius: 999px;
                border: 1px solid transparent;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.2s ease;
                background: transparent;
                cursor: pointer;
                font-size: 0.92rem;
            }

            .hero-btn-ghost {
                border-color: var(--line);
                color: var(--ink);
                background: rgba(255, 255, 255, 0.6);
            }

            html.app-skin-dark body .hero-btn-ghost {
                background: rgba(18, 22, 28, 0.75);
            }

            .hero-btn-ghost:hover {
                border-color: rgba(255, 255, 255, 0.42);
                transform: translateY(-1px);
            }

            .hero-btn-primary {
                background: var(--accent);
                color: #fff;
                box-shadow: 0 14px 30px rgba(228, 87, 46, 0.35);
            }

            .hero-btn-primary:hover {
                background: var(--accent-dark);
                color: #fff;
                transform: translateY(-1px);
            }

            .book-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 14px;
            }

            .book-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 11px;
                border-radius: 999px;
                font-size: 0.74rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                background: rgba(106, 208, 221, 0.18);
                color: var(--sea);
                border: 1px solid rgba(106, 208, 221, 0.28);
            }

            .book-layout {
                display: grid;
                grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
                gap: 16px;
            }

            .book-panel {
                background: var(--surface);
                border: 1px solid var(--line);
                border-radius: 22px;
                padding: 22px;
                box-shadow: var(--shadow);
            }

            .book-kicker {
                color: var(--sea);
                text-transform: uppercase;
                letter-spacing: 0.08em;
                font-size: 0.7rem;
                font-weight: 700;
            }

            .book-title {
                margin: 10px 0 0;
                font-family: "Fraunces", serif;
                font-size: clamp(1.5rem, 3vw, 2.2rem);
                letter-spacing: -0.02em;
                line-height: 1.1;
            }

            .book-sub {
                margin-top: 12px;
                color: var(--muted);
                line-height: 1.7;
            }

            .book-download {
                margin-top: 10px;
            }

            .book-download .hero-btn {
                min-height: 44px;
            }

            .meta-list {
                display: grid;
                gap: 10px;
            }

            .meta-item {
                border: 1px solid var(--line);
                border-radius: 12px;
                padding: 11px 12px;
                background: var(--surface-soft);
            }

            .meta-item span {
                display: block;
                color: var(--muted);
                font-size: 0.76rem;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }

            .meta-item strong {
                display: block;
                margin-top: 4px;
                font-size: 0.95rem;
                color: var(--ink);
            }

            .activity-list {
                display: grid;
                gap: 8px;
            }

            .activity-item {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                padding: 10px 2px;
                border-bottom: 1px solid var(--line);
            }

            .activity-item:last-child {
                border-bottom: 0;
                padding-bottom: 0;
            }

            .activity-title {
                font-weight: 700;
                line-height: 1.3;
            }

            .activity-note,
            .activity-by,
            .activity-time,
            .book-empty {
                color: var(--muted);
                font-size: 0.86rem;
            }

            #ta-back-to-top {
                position: fixed;
                right: 18px;
                bottom: 18px;
                z-index: 2000;
                width: 42px;
                height: 42px;
                border: 1px solid var(--line);
                border-radius: 999px;
                background: var(--surface);
                color: var(--sea);
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transform: translateY(10px);
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            #ta-back-to-top.is-visible {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            @media (max-width: 900px) {
                .book-layout {
                    grid-template-columns: 1fr;
                }

                .book-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            @media (max-width: 640px) {
                .book-shell {
                    padding: 22px 12px 30px;
                }

                .book-panel {
                    padding: 16px;
                    border-radius: 18px;
                }

                .book-header nav {
                    width: 100%;
                }

                .book-header nav .hero-btn {
                    flex: 1 1 auto;
                }

                .book-toolbar {
                    flex-direction: column;
                    align-items: stretch;
                }

                .book-toolbar .hero-btn {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <a href="#book-main-content" class="ta-skip-link">Skip to content</a>
        <div class="noise"></div>

        @php
            $owner = $thesis->group
                ? $thesis->group->name
                : ($thesis->student->user->name ?? 'Unknown');
            $latestProposal = $thesis->proposals->sortByDesc('created_at')->first();
        @endphp

        <div class="book-shell" id="book-main-content">
            <header class="book-header">
                <div class="logo">
                    <div class="logo-mark">T</div>
                    <span>{{ config('app.name', 'Thesis Management System') }}</span>
                </div>
                <nav>
                    <button class="hero-btn hero-btn-ghost" id="themeToggle" type="button" aria-pressed="true">
                        Light theme
                    </button>
                    <a href="{{ route('books.index') }}" class="hero-btn hero-btn-ghost">Browse thesis books</a>
                    @if (Route::has('login'))
                        @auth
                            <a class="hero-btn hero-btn-primary" href="{{ url('/dashboard') }}">Go to dashboard</a>
                        @else
                            <a class="hero-btn hero-btn-ghost" href="{{ route('login') }}">Log in</a>
                            @if (Route::has('register'))
                                <a class="hero-btn hero-btn-primary" href="{{ route('register') }}">Create account</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <div class="book-toolbar">
                <a href="{{ route('books.index') }}" class="hero-btn hero-btn-ghost">Back to catalog</a>
                <span class="book-status">
                    <i class="feather-check-circle"></i>
                    Published Thesis
                </span>
            </div>

            <div class="book-layout">
                <section class="book-panel">
                    <span class="book-kicker">Public Thesis Book</span>
                    <h1 class="book-title">{{ $thesis->title }}</h1>
                    <p class="book-sub">
                        {{ $latestProposal?->abstract ?: 'No abstract was provided for this thesis.' }}
                    </p>

                    @if($publicFinalVersion)
                        <div class="book-download">
                            <a href="{{ route('books.download', $thesis) }}" class="hero-btn hero-btn-primary">
                                <i class="feather-download me-1"></i>
                                Download Final Thesis (v{{ $publicFinalVersion->version_number }})
                            </a>
                        </div>
                    @endif
                </section>

                <aside class="book-panel">
                    <h2 class="h5 fw-bold mb-3">Book Metadata</h2>
                    <div class="meta-list">
                        <div class="meta-item">
                            <span>Owner</span>
                            <strong>{{ $owner }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Supervisor</span>
                            <strong>{{ $thesis->supervisor->user->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Library Validated</span>
                            <strong>{{ optional($thesis->library_approved_at)->format('M d, Y') ?? 'N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Published</span>
                            <strong>{{ optional($thesis->published_at)->format('M d, Y') ?? 'N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Published By</span>
                            <strong>{{ $thesis->publisher->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Final Thesis Version</span>
                            <strong>v{{ $publicFinalVersion->version_number ?? 'N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Total Downloads</span>
                            <strong>{{ number_format((int) ($thesis->public_downloads ?? 0)) }}</strong>
                        </div>
                    </div>
                </aside>
            </div>

            <section class="book-panel mt-3">
                <h2 class="h5 fw-bold mb-3">Catalog Activity</h2>
                <div class="activity-list">
                    @forelse($recentEvents as $event)
                        <div class="activity-item">
                            <div>
                                <div class="activity-title">{{ ucfirst($event->action) }}</div>
                                <div class="activity-by">By {{ $event->user->name ?? 'System' }}</div>
                                @if($event->notes)
                                    <div class="activity-note mt-1">{{ \Illuminate\Support\Str::limit($event->notes, 140) }}</div>
                                @endif
                            </div>
                            <span class="activity-time">{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="book-empty mb-0">No activity records available for this thesis.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <button type="button" id="ta-back-to-top" aria-label="Back to top">
            <i class="feather-chevron-up"></i>
        </button>

        <script>
            (() => {
                const root = document.documentElement;
                const toggleButton = document.getElementById('themeToggle');
                const storageKey = 'app-skin-dark';

                const applyTheme = (theme) => {
                    const dark = theme === 'dark';
                    root.classList.toggle('app-skin-dark', dark);

                    if (toggleButton) {
                        toggleButton.textContent = dark ? 'Light theme' : 'Dark theme';
                        toggleButton.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    }

                    try {
                        localStorage.setItem(storageKey, dark ? 'app-skin-dark' : 'app-skin-light');
                    } catch (_) {
                        // Ignore localStorage access failures
                    }
                };

                let savedTheme = null;
                try {
                    savedTheme = localStorage.getItem(storageKey);
                } catch (_) {
                    savedTheme = null;
                }
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const initialTheme = savedTheme
                    ? (savedTheme === 'app-skin-dark' ? 'dark' : 'light')
                    : (prefersDark ? 'dark' : 'light');

                applyTheme(initialTheme);

                if (toggleButton) {
                    toggleButton.addEventListener('click', () => {
                        const dark = root.classList.contains('app-skin-dark');
                        applyTheme(dark ? 'light' : 'dark');
                    });
                }

                const backButton = document.getElementById('ta-back-to-top');
                if (!backButton) return;

                const handleScroll = () => {
                    backButton.classList.toggle('is-visible', window.scrollY > 420);
                };

                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll();

                backButton.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            })();
        </script>
    </body>
</html>