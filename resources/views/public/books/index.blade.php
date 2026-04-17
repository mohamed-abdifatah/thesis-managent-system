<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Public Thesis Books | {{ config('app.name', 'Thesis Management System') }}</title>

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
                --books-bg: #f3eee4;
                --books-ink: #1b2735;
                --books-muted: #5f7388;
                --books-panel: #fffcf6;
                --books-panel-alt: #f8f1e4;
                --books-border: #deceb3;
                --books-accent: #0f6a75;
                --books-accent-strong: #0a4d55;
                --books-warm: #be6a19;
                --books-shadow: 0 22px 46px rgba(49, 39, 21, 0.12);
                --books-line: rgba(23, 37, 53, 0.14);
                --books-overlay: #ffffff;
                --books-top-btn-bg: rgba(255, 255, 255, 0.74);

                /* Shared header and hero tokens used by welcome page styles */
                --ink: var(--books-ink);
                --muted: var(--books-muted);
                --line: var(--books-line);
                --accent: #e4572e;
                --accent-strong: #c7421d;
                --teal: var(--books-accent);
                --card: var(--books-panel);
                --header-glass: rgba(255, 255, 255, 0.72);
            }

            html.app-skin-dark body {
                --books-bg: #10151e;
                --books-ink: #e8eef7;
                --books-muted: #a7b4c6;
                --books-panel: #151d28;
                --books-panel-alt: #1a2331;
                --books-border: #304156;
                --books-accent: #6ad0dd;
                --books-accent-strong: #2f8f9d;
                --books-warm: #ff9f70;
                --books-shadow: 0 22px 46px rgba(0, 0, 0, 0.45);
                --books-line: rgba(206, 220, 238, 0.16);
                --books-overlay: #111a25;
                --books-top-btn-bg: rgba(18, 24, 34, 0.74);

                --ink: var(--books-ink);
                --muted: var(--books-muted);
                --line: var(--books-line);
                --accent: #ff8a5b;
                --accent-strong: #ff6a32;
                --teal: var(--books-accent);
                --card: var(--books-panel);
                --header-glass: rgba(16, 24, 37, 0.72);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                color: var(--books-ink);
                background:
                    radial-gradient(1000px 440px at 102% -3%, rgba(15, 106, 117, 0.16), transparent 62%),
                    radial-gradient(680px 360px at -12% 16%, rgba(190, 106, 25, 0.14), transparent 60%),
                    linear-gradient(160deg, #f7f1e6 0%, #eee5d8 100%);
                font-family: "Space Grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
                line-height: 1.56;
                transition: background 0.3s ease, color 0.3s ease;
            }

            html.app-skin-dark body {
                background:
                    radial-gradient(1000px 440px at 102% -3%, rgba(106, 208, 221, 0.17), transparent 62%),
                    radial-gradient(680px 360px at -12% 16%, rgba(255, 159, 112, 0.14), transparent 60%),
                    linear-gradient(160deg, #0f141b 0%, #0a0f14 100%);
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

            .books-shell {
                width: min(1280px, 100%);
                margin: 0 auto;
                padding: 28px 18px 44px;
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
                animation: fadeSlide 0.45s ease both;
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
                margin-bottom: 16px;
            }

            .hero-main,
            .hero-side {
                position: relative;
                border: 1px solid var(--books-border);
                border-radius: 26px;
                background:
                    linear-gradient(120deg, rgba(255, 255, 255, 0.88) 0%, rgba(248, 241, 228, 0.9) 52%, rgba(236, 246, 248, 0.9) 100%);
                box-shadow: var(--books-shadow);
                padding: clamp(20px, 3vw, 34px);
                animation: fadeSlide 0.52s ease both;
            }

            .hero-main {
                overflow: hidden;
            }

            html.app-skin-dark body .hero-main,
            html.app-skin-dark body .hero-side {
                background:
                    linear-gradient(120deg, rgba(20, 28, 40, 0.92) 0%, rgba(26, 35, 49, 0.92) 52%, rgba(17, 27, 37, 0.9) 100%);
            }

            .hero-main::before,
            .hero-main::after {
                content: '';
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
            }

            .hero-main::before {
                width: 220px;
                height: 220px;
                right: -48px;
                top: -78px;
                background: radial-gradient(circle, rgba(15, 106, 117, 0.22) 0%, transparent 72%);
            }

            .hero-main::after {
                width: 200px;
                height: 200px;
                left: -70px;
                bottom: -90px;
                background: radial-gradient(circle, rgba(190, 106, 25, 0.18) 0%, transparent 72%);
            }

            .hero-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 8px;
                color: #2a5f6d;
                font-size: 0.72rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }

            html.app-skin-dark body .hero-kicker {
                color: #7ed4e0;
            }

            .hero-kicker::before {
                content: '';
                width: 24px;
                height: 2px;
                border-radius: 99px;
                background: #2a5f6d;
            }

            html.app-skin-dark body .hero-kicker::before {
                background: #7ed4e0;
            }

            .hero-title {
                margin: 0;
                font-family: 'Cormorant Garamond', serif;
                font-size: clamp(1.95rem, 4.2vw, 3.1rem);
                line-height: 1.14;
                letter-spacing: -0.02em;
                max-width: 920px;
            }

            .hero-text {
                margin: 12px 0 0;
                max-width: 820px;
                color: #4e647c;
                font-size: 0.92rem;
                line-height: 1.72;
            }

            html.app-skin-dark body .hero-text {
                color: #afbdd0;
            }

            .hero-side {
                display: grid;
                grid-template-rows: auto 1fr;
                gap: 14px;
            }

            .hero-side h2 {
                margin: 0;
                font-size: 1.1rem;
                letter-spacing: -0.02em;
            }

            .hero-side p {
                margin: 6px 0 0;
                color: var(--muted);
                font-size: 0.88rem;
            }

            .books-search {
                margin-top: 24px;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 10px;
                max-width: 760px;
            }

            .books-search input,
            .books-search .form-select {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid #d5c7b1;
                background: #fffdf8;
                box-shadow: none;
                font-weight: 600;
            }

            html.app-skin-dark body .books-search input,
            html.app-skin-dark body .books-search .form-select {
                border-color: #3a4c63;
                background: #111b28;
                color: #e8eef7;
            }

            .books-search input::placeholder {
                color: #8b8f98;
                font-weight: 500;
            }

            html.app-skin-dark body .books-search input::placeholder {
                color: #7d8ca3;
            }

            .books-search input:focus,
            .books-search .form-select:focus {
                border-color: #4e7cff;
                box-shadow: 0 0 0 4px rgba(78, 124, 255, 0.14);
            }

            .books-search .btn {
                min-height: 48px;
                border-radius: 14px;
                border: 0;
                font-weight: 700;
                background: linear-gradient(140deg, var(--books-accent), var(--books-accent-strong));
                box-shadow: 0 10px 22px rgba(15, 106, 117, 0.22);
            }

            .books-search .btn:hover {
                transform: translateY(-1px);
            }

            .books-insights {
                margin: 0;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .books-insight {
                border: 1px solid #dacdb7;
                border-radius: 16px;
                background: linear-gradient(165deg, #fffefb 0%, var(--books-panel-alt) 100%);
                padding: 14px;
                box-shadow: 0 10px 20px rgba(49, 39, 21, 0.07);
                animation: fadeSlide 0.52s ease both;
            }

            html.app-skin-dark body .books-insight {
                border-color: #2f4155;
                background: linear-gradient(165deg, #161f2b 0%, #1d2837 100%);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.32);
            }

            .books-insight:nth-child(2) {
                animation-delay: 0.06s;
            }

            .books-insight:nth-child(3) {
                animation-delay: 0.12s;
            }

            .books-insight .label {
                display: block;
                font-size: 0.72rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: #607387;
                margin-bottom: 6px;
            }

            html.app-skin-dark body .books-insight .label {
                color: #8fa5bc;
            }

            .books-insight .value {
                display: block;
                font-size: 1.3rem;
                line-height: 1.2;
                font-weight: 800;
                color: #173549;
                letter-spacing: -0.02em;
            }

            html.app-skin-dark body .books-insight .value {
                color: #e5eef8;
            }

            .books-insight .sub {
                display: block;
                margin-top: 6px;
                font-size: 0.8rem;
                color: #6c7f93;
            }

            html.app-skin-dark body .books-insight .sub {
                color: #a5b7cc;
            }

            .books-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(286px, 1fr));
                gap: 16px;
            }

            .book-card {
                position: relative;
                border: 1px solid var(--books-border);
                border-radius: 20px;
                background: var(--books-panel);
                box-shadow: 0 14px 30px rgba(31, 41, 55, 0.1);
                padding: 18px;
                min-height: 252px;
                display: flex;
                flex-direction: column;
                gap: 10px;
                transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
                animation: fadeSlide 0.52s ease both;
            }

            .book-card::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 20px;
                pointer-events: none;
                border: 1px solid transparent;
                transition: border-color 0.2s ease;
            }

            .book-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 20px 34px rgba(31, 41, 55, 0.14);
                border-color: #c8d9de;
            }

            html.app-skin-dark body .book-card:hover {
                box-shadow: 0 20px 34px rgba(0, 0, 0, 0.38);
                border-color: #3e5670;
            }

            .book-card:hover::before {
                border-color: rgba(15, 106, 117, 0.24);
            }

            .book-badges {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            .book-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                border-radius: 999px;
                padding: 4px 9px;
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.02em;
                border: 1px solid transparent;
            }

            .book-badge.primary {
                background: #eaf7fa;
                color: #0a4e59;
                border-color: #b3d9df;
            }

            html.app-skin-dark body .book-badge.primary {
                background: rgba(106, 208, 221, 0.16);
                color: #8fe3ee;
                border-color: rgba(106, 208, 221, 0.28);
            }

            .book-badge.warm {
                background: #fff2e6;
                color: #8f4a12;
                border-color: #f0c9a9;
            }

            html.app-skin-dark body .book-badge.warm {
                background: rgba(255, 159, 112, 0.16);
                color: #ffc8ad;
                border-color: rgba(255, 159, 112, 0.28);
            }

            .book-title {
                margin: 0;
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.2rem;
                line-height: 1.38;
                letter-spacing: -0.01em;
            }

            .book-meta {
                display: flex;
                align-items: baseline;
                gap: 6px;
                color: var(--books-muted);
                font-size: 0.82rem;
            }

            .book-meta strong {
                min-width: 78px;
                color: #2e4356;
                font-size: 0.79rem;
            }

            html.app-skin-dark body .book-meta strong {
                color: #d2deec;
            }

            .book-footer {
                margin-top: auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                padding-top: 8px;
            }

            .book-footer .btn {
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: 700;
            }

            .books-empty {
                border: 1px solid var(--books-border);
                border-radius: 20px;
                background: linear-gradient(150deg, #fffefb 0%, #f6edde 100%);
                box-shadow: 0 12px 28px rgba(45, 37, 21, 0.08);
            }

            html.app-skin-dark body .books-empty {
                background: linear-gradient(150deg, #151e2a 0%, #1a2634 100%);
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.32);
            }

            .books-empty h3 {
                margin: 0;
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.45rem;
            }

            .books-empty p {
                margin: 8px 0 0;
                color: #617288;
                max-width: 740px;
            }

            html.app-skin-dark body .books-empty p {
                color: #a7b6c9;
            }

            .books-pagination {
                margin-top: 22px;
            }

            #ta-back-to-top {
                position: fixed;
                right: 18px;
                bottom: 18px;
                z-index: 2000;
                width: 42px;
                height: 42px;
                border: 1px solid #c7d7ea;
                border-radius: 999px;
                background: #ffffff;
                color: #1d4ed8;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transform: translateY(10px);
                pointer-events: none;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            html.app-skin-dark body #ta-back-to-top {
                border-color: #3a4d63;
                background: #141c27;
                color: #8fdaeb;
            }

            #ta-back-to-top.is-visible {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            @keyframes fadeSlide {
                from {
                    opacity: 0;
                    transform: translateY(14px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 900px) {
                .hero {
                    grid-template-columns: 1fr;
                }

                .books-insights {
                    grid-template-columns: 1fr;
                }

                .books-search {
                    grid-template-columns: 1fr;
                    max-width: 100%;
                }
            }

            @media (max-width: 640px) {
                .books-shell {
                    padding: 20px 12px 32px;
                }

                .hero-main,
                .hero-side {
                    border-radius: 22px;
                    padding: 20px 16px;
                }

                .book-card {
                    border-radius: 16px;
                    min-height: 220px;
                }

                .book-card::before {
                    border-radius: 16px;
                }

                .book-footer {
                    flex-direction: column;
                    align-items: stretch;
                }

                .book-footer .btn {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <a href="#books-main-content" class="ta-skip-link">Skip to content</a>
        <div class="noise"></div>

        @php
            $pageCount = $books->count();
            $totalCount = $books->total();
            $maxDownloads = $books->count() ? (int) $books->max('public_downloads') : 0;
            $sortLabel = ($sort ?? 'newest') === 'popular' ? 'Most Downloaded' : 'Newest First';
        @endphp

        <div class="books-shell" id="books-main-content">
            @include('partials.public-header')

            <section class="hero" aria-label="Books page title and summary">
                <article class="hero-main">
                    <span class="hero-kicker">Open Knowledge Collection</span>
                    <h1 class="hero-title">Discover final thesis books curated for public access.</h1>
                    <p class="hero-text">Explore research that has completed defense, final unit selection, and publication review. Search by title, student, or group and open the approved final manuscript.</p>

                    <form method="GET" action="{{ route('books.index') }}" class="books-search">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Search title, student, or group" aria-label="Search books" />
                        <div class="d-flex gap-2">
                            <select name="sort" class="form-select" style="min-width: 170px;" aria-label="Sort books">
                                <option value="newest" @selected(($sort ?? 'newest') === 'newest')>Newest First</option>
                                <option value="popular" @selected(($sort ?? 'newest') === 'popular')>Most Downloaded</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </form>
                </article>

                <aside class="hero-side" aria-label="Catalog insights">
                    <div>
                        <h2>Catalog Snapshot</h2>
                        <p>A quick view of current catalog volume and what this page is showing.</p>
                    </div>

                    <section class="books-insights">
                        <article class="books-insight">
                            <span class="label">Public Books</span>
                            <span class="value">{{ number_format($totalCount) }}</span>
                            <span class="sub">Final published records in repository</span>
                        </article>
                        <article class="books-insight">
                            <span class="label">Visible On This Page</span>
                            <span class="value">{{ number_format($pageCount) }}</span>
                            <span class="sub">Page {{ $books->currentPage() }} of {{ max($books->lastPage(), 1) }}</span>
                        </article>
                        <article class="books-insight">
                            <span class="label">Current Sort</span>
                            <span class="value">{{ $sortLabel }}</span>
                            <span class="sub">Peak downloads in view: {{ number_format($maxDownloads) }}</span>
                        </article>
                    </section>
                </aside>
            </section>

            @if($books->count() > 0)
                <section class="books-grid">
                    @foreach($books as $book)
                        @php
                            $owner = $book->group
                                ? $book->group->name
                                : ($book->student->user->name ?? 'Unknown');
                        @endphp

                        <article class="book-card" style="animation-delay: {{ min($loop->index * 0.06, 0.42) }}s;">
                            <div class="book-badges">
                                <span class="book-badge primary">
                                    <i class="feather-check-circle"></i>
                                    Final {{ $book->finalThesisVersion?->unit_label ?? 'Unit N/A' }}
                                </span>
                                <span class="book-badge warm">
                                    <i class="feather-download"></i>
                                    {{ number_format((int) ($book->public_downloads ?? 0)) }} downloads
                                </span>
                            </div>

                            <h3 class="book-title">{{ \Illuminate\Support\Str::limit($book->title, 88) }}</h3>

                            <div class="book-meta"><strong>Owner</strong><span>{{ $owner }}</span></div>
                            <div class="book-meta"><strong>Supervisor</strong><span>{{ $book->supervisor->user->name ?? 'N/A' }}</span></div>
                            <div class="book-meta"><strong>Published</strong><span>{{ optional($book->published_at)->format('M d, Y') ?? 'N/A' }}</span></div>

                            <div class="book-footer">
                                <span class="badge bg-soft-success text-success">Public Record</span>
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-secondary">Read Details</a>
                            </div>
                        </article>
                    @endforeach
                </section>

                <div class="books-pagination">
                    {{ $books->links() }}
                </div>
            @else
                <div class="books-empty card border-0 mt-2">
                    <div class="card-body py-5 text-center px-4">
                        <h3>No books visible yet{{ $search ? ' for this search' : '' }}.</h3>
                        <p>
                            A thesis appears here only after defense completion, final unit selection, and publication approval.
                            Try changing search/sort, or publish a completed thesis from the library workspace.
                        </p>
                    </div>
                </div>
            @endif
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