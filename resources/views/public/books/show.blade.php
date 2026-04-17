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
                    const isDark = stored ? stored === 'app-skin-dark' : true;
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
                --accent-strong: var(--accent-dark);
                --teal: var(--sea);
                --card: var(--surface);
                --header-glass: rgba(255, 255, 255, 0.72);
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
                --accent-strong: var(--accent-dark);
                --teal: var(--sea);
                --card: var(--surface);
                --header-glass: rgba(16, 24, 37, 0.72);
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
                overflow: hidden;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #ffffff;
                border: 1px solid var(--line);
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12);
            }

            .brand-mark img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
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

            .hero {
                display: grid;
                grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
                gap: 16px;
                margin-bottom: 14px;
            }

            .hero-main,
            .hero-side,
            .book-panel {
                border: 1px solid var(--line);
                border-radius: 22px;
                background: var(--card);
                box-shadow: var(--shadow);
                padding: 22px;
            }

            .hero-main {
                position: relative;
                overflow: hidden;
            }

            .hero-main::before {
                content: '';
                position: absolute;
                width: 260px;
                height: 260px;
                border-radius: 999px;
                right: -120px;
                top: -120px;
                background: radial-gradient(circle, rgba(47, 143, 157, 0.22) 0%, transparent 72%);
                pointer-events: none;
            }

            .hero-kicker {
                color: var(--teal);
                text-transform: uppercase;
                letter-spacing: 0.08em;
                font-size: 0.7rem;
                font-weight: 700;
            }

            .hero-title {
                margin: 10px 0 0;
                font-family: "Fraunces", serif;
                font-size: clamp(1.75rem, 3.8vw, 2.65rem);
                letter-spacing: -0.02em;
                line-height: 1.12;
            }

            .hero-text {
                margin-top: 12px;
                color: var(--muted);
                line-height: 1.7;
            }

            .hero-actions {
                margin-top: 16px;
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
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

            .hero-side h2 {
                margin: 0;
                font-size: 1.1rem;
                letter-spacing: -0.02em;
            }

            .hero-side p {
                margin: 6px 0 14px;
                color: var(--muted);
                font-size: 0.88rem;
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
                .hero {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 640px) {
                .book-shell {
                    padding: 22px 12px 30px;
                }

                .hero-main,
                .hero-side,
                .book-panel {
                    padding: 16px;
                    border-radius: 18px;
                }

                .hero-actions .nav-btn {
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
            @include('partials.public-header')

            <section class="hero" aria-label="Book title and metadata">
                <article class="hero-main">
                    <span class="hero-kicker">Public Thesis Book</span>
                    <h1 class="hero-title">{{ $thesis->title }}</h1>
                    <p class="hero-text">
                        {{ $latestProposal?->abstract ?: 'No abstract was provided for this thesis.' }}
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('books.index') }}" class="nav-btn ghost">Back to catalog</a>
                        <span class="book-status">
                            <i class="feather-check-circle"></i>
                            Published Thesis
                        </span>

                        @if($publicFinalVersion)
                            <a href="{{ route('books.download', $thesis) }}" class="nav-btn primary">
                                <i class="feather-download me-1"></i>
                                Download Final Thesis ({{ $publicFinalVersion->unit_label }})
                            </a>
                        @endif
                    </div>
                </article>

                <aside class="hero-side">
                    <h2>Book Metadata</h2>
                    <p>Verified publication details from the public thesis catalog.</p>
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
                            <span>Final Thesis Unit</span>
                            <strong>{{ $publicFinalVersion?->unit_label ?? 'Unit N/A' }}</strong>
                        </div>
                        <div class="meta-item">
                            <span>Total Downloads</span>
                            <strong>{{ number_format((int) ($thesis->public_downloads ?? 0)) }}</strong>
                        </div>
                    </div>
                </aside>
            </section>

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
                const buttonLabel = toggleButton ? toggleButton.querySelector('[data-theme-label]') : null;
                const storageKey = 'app-skin-dark';

                const applyTheme = (theme) => {
                    const dark = theme === 'dark';
                    root.classList.toggle('app-skin-dark', dark);

                    if (toggleButton) {
                        if (buttonLabel) {
                            buttonLabel.textContent = dark ? 'Light theme' : 'Dark theme';
                        } else {
                            toggleButton.textContent = dark ? 'Light theme' : 'Dark theme';
                        }
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
                const initialTheme = savedTheme
                    ? (savedTheme === 'app-skin-dark' ? 'dark' : 'light')
                    : 'dark';

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