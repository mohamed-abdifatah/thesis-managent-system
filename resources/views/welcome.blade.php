<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Thesis Management System') }}</title>

        <script>
            (() => {
                const storageKey = 'app-skin-dark';
                try {
                    const stored = localStorage.getItem(storageKey);
                    const isDark = stored ? stored === 'app-skin-dark' : false;
                    document.documentElement.classList.toggle('app-skin-dark', isDark);
                } catch (_) {
                    // Ignore localStorage access failures
                }
            })();
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700&display=swap" rel="stylesheet">

        <style>
            :root {
                --ink: #152234;
                --muted: #5e7188;
                --paper: #eff3f2;
                --card: #ffffff;
                --line: rgba(21, 34, 52, 0.14);
                --accent: #cb5728;
                --accent-strong: #a84319;
                --teal: #0c7d8b;
                --header-glass: rgba(255, 255, 255, 0.84);
                --hero-overlay-light: linear-gradient(145deg, rgba(255, 255, 255, 0.72) 0%, rgba(250, 252, 255, 0.66) 58%, rgba(255, 255, 255, 0.78) 100%);
                --hero-overlay-dark: linear-gradient(145deg, rgba(12, 20, 32, 0.72) 0%, rgba(12, 20, 32, 0.6) 58%, rgba(12, 20, 32, 0.8) 100%);
            }

            html.app-skin-dark body {
                --ink: #e7eef8;
                --muted: #9db0c8;
                --paper: #0c141e;
                --card: #141f2d;
                --line: rgba(225, 236, 250, 0.15);
                --accent: #ff9364;
                --accent-strong: #ff6f3f;
                --teal: #77dae5;
                --header-glass: rgba(15, 24, 37, 0.76);
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                min-height: 100vh;
                font-family: "Space Grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
                color: var(--ink);
                line-height: 1.56;
                transition: color 0.25s ease, background 0.25s ease;
                position: relative;
                overflow-x: hidden;
                isolation: isolate;
                background: transparent;
            }

            .page-bg {
                content: "";
                position: fixed;
                inset: -24px;
                background: center center / cover no-repeat url("{{ asset('assets/images/background.png') }}");
                filter: blur(6px) saturate(0.95) brightness(0.9);
                transform: scale(1.04);
                pointer-events: none;
                z-index: 0;
            }

            .page-tint {
                position: fixed;
                inset: 0;
                background:
                    radial-gradient(900px 460px at 4% -4%, rgba(203, 87, 40, 0.18), transparent 58%),
                    radial-gradient(760px 420px at 96% 0%, rgba(12, 125, 139, 0.16), transparent 58%),
                    linear-gradient(150deg, rgba(244, 248, 250, 0.58) 0%, rgba(235, 242, 247, 0.62) 100%);
                pointer-events: none;
                z-index: 1;
            }

            html.app-skin-dark .page-tint {
                background:
                    radial-gradient(900px 460px at 4% -4%, rgba(255, 147, 100, 0.2), transparent 58%),
                    radial-gradient(760px 420px at 96% 0%, rgba(119, 218, 229, 0.16), transparent 58%),
                    linear-gradient(150deg, rgba(12, 19, 28, 0.66) 0%, rgba(11, 18, 27, 0.72) 100%);
            }

            .noise {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.95' numOctaves='2' stitchTiles='stitch'/></filter><rect width='140' height='140' filter='url(%23n)' opacity='0.06'/></svg>");
                pointer-events: none;
                mix-blend-mode: multiply;
                z-index: 2;
            }

            html.app-skin-dark body .noise {
                mix-blend-mode: screen;
                opacity: 0.3;
            }

            .page {
                position: relative;
                z-index: 3;
                width: min(1240px, 100%);
                margin: 0 auto;
                padding: 22px 18px 72px;
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
                backdrop-filter: blur(9px);
                -webkit-backdrop-filter: blur(9px);
                border-radius: 18px;
                padding: 10px 12px;
                margin-bottom: 24px;
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
                background: rgba(255, 255, 255, 0.66);
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
                box-shadow: 0 14px 26px rgba(203, 87, 40, 0.3);
            }

            .nav-btn.primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 18px 30px rgba(203, 87, 40, 0.35);
            }

            .hero {
                display: grid;
                grid-template-columns: minmax(0, 1.12fr) minmax(0, 0.88fr);
                gap: 16px;
                margin-bottom: 18px;
            }

            .hero-main,
            .hero-side {
                border: 1px solid var(--line);
                border-radius: 24px;
                background: var(--card);
                box-shadow: 0 20px 42px rgba(20, 31, 49, 0.1);
                animation: rise-in 0.55s ease both;
            }

            .hero-side {
                animation-delay: 0.08s;
            }

            .hero-main {
                position: relative;
                overflow: hidden;
                padding: clamp(22px, 3.2vw, 34px);
                background:
                    var(--hero-main-overlay-light, var(--hero-overlay-light)),
                    var(--hero-main-bg, none);
                background-size: cover, cover;
                background-position: center, center;
                background-repeat: no-repeat, no-repeat;
            }

            .hero-main > * {
                position: relative;
                z-index: 2;
            }

            html.app-skin-dark body .hero-main {
                background:
                    var(--hero-main-overlay-dark, var(--hero-overlay-dark)),
                    var(--hero-main-bg, none);
                background-size: cover, cover;
                background-position: center, center;
                background-repeat: no-repeat, no-repeat;
            }

            .hero-main::before {
                content: "";
                position: absolute;
                width: 270px;
                height: 270px;
                border-radius: 999px;
                right: -120px;
                top: -120px;
                background: radial-gradient(circle, rgba(12, 125, 139, 0.2) 0%, transparent 72%);
                pointer-events: none;
                z-index: 1;
            }

            .hero-main::after {
                content: "";
                position: absolute;
                inset: 0;
                background: var(--hero-main-bg, none);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                filter: blur(0.8px) saturate(1.14) contrast(1.03);
                transform: scale(1.018);
                opacity: 0.3;
                pointer-events: none;
                z-index: 0;
            }

            html.app-skin-dark body .hero-main::after {
                filter: blur(1px) saturate(1.1) brightness(0.92);
                opacity: 0.26;
            }

            .hero-kicker {
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

            .hero-kicker::before {
                content: "";
                width: 22px;
                height: 2px;
                border-radius: 999px;
                background: var(--teal);
            }

            .hero-title {
                margin: 0;
                font-family: "Fraunces", serif;
                letter-spacing: -0.03em;
                line-height: 1.1;
                font-size: clamp(1.7rem, 3.8vw, 3rem);
                max-width: 720px;
                text-shadow: 0 1px 2px rgba(255, 255, 255, 0.58);
            }

            html.app-skin-dark body .hero-title {
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.52);
            }

            .hero-text {
                margin-top: 12px;
                max-width: 640px;
                color: #1d3249;
                font-size: 0.94rem;
                line-height: 1.72;
                text-shadow: 0 1px 1px rgba(255, 255, 255, 0.46);
                background: rgba(255, 255, 255, 0.48);
                border: 1px solid rgba(29, 50, 73, 0.14);
                border-radius: 12px;
                padding: 10px 13px;
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }

            html.app-skin-dark body .hero-text {
                color: #e7eff9;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.46);
                background: rgba(11, 21, 33, 0.44);
                border-color: rgba(192, 214, 240, 0.2);
            }

            .hero-actions {
                margin-top: 18px;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .hero-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #173654;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.84rem;
                padding: 8px 12px;
                border-radius: 999px;
                border: 1px solid rgba(29, 50, 73, 0.2);
                background: rgba(255, 255, 255, 0.64);
                box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
                transition: all 0.18s ease;
            }

            html.app-skin-dark body .hero-link {
                color: #e7eff9;
                border-color: rgba(192, 214, 240, 0.24);
                background: rgba(11, 21, 33, 0.5);
            }

            .hero-link:hover {
                color: var(--teal);
                border-color: rgba(12, 125, 139, 0.44);
                transform: translateY(-1px);
            }

            .hero-stats {
                margin-top: 14px;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 9px;
            }

            .hero-stat {
                border: 1px solid rgba(33, 54, 84, 0.16);
                background: rgba(255, 255, 255, 0.58);
                border-radius: 12px;
                padding: 10px;
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }

            html.app-skin-dark body .hero-stat {
                border-color: rgba(192, 214, 240, 0.2);
                background: rgba(11, 21, 33, 0.44);
            }

            .hero-stat strong {
                display: block;
                font-size: 0.9rem;
                letter-spacing: -0.01em;
            }

            .hero-stat span {
                display: block;
                margin-top: 2px;
                color: var(--muted);
                font-size: 0.76rem;
            }

            .hero-side {
                padding: 22px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 14px;
            }

            .hero-side h2 {
                margin: 0;
                font-size: 1.08rem;
                letter-spacing: -0.02em;
            }

            .hero-side p {
                margin: 6px 0 0;
                color: var(--muted);
                font-size: 0.86rem;
            }

            .snapshot-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .snapshot {
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 12px;
                background: linear-gradient(160deg, rgba(255, 255, 255, 0.88) 0%, rgba(245, 251, 255, 0.94) 100%);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            html.app-skin-dark body .snapshot {
                background: linear-gradient(160deg, rgba(26, 37, 53, 0.86) 0%, rgba(19, 30, 44, 0.95) 100%);
            }

            .snapshot:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
            }

            .snapshot strong {
                display: block;
                font-size: 1.12rem;
                line-height: 1.15;
                letter-spacing: -0.02em;
            }

            .snapshot span {
                display: block;
                margin-top: 5px;
                color: var(--muted);
                font-size: 0.75rem;
                line-height: 1.45;
            }

            .section {
                border: 1px solid var(--line);
                border-radius: 22px;
                background: var(--card);
                box-shadow: 0 14px 30px rgba(20, 31, 49, 0.07);
                padding: 22px;
                margin-top: 16px;
                animation: rise-in 0.52s ease both;
            }

            .section:nth-of-type(2) {
                animation-delay: 0.05s;
            }

            .section:nth-of-type(3) {
                animation-delay: 0.1s;
            }

            .section h3 {
                margin: 0;
                font-size: 1.25rem;
                letter-spacing: -0.02em;
            }

            .section p.lead {
                margin-top: 8px;
                color: var(--muted);
                font-size: 0.9rem;
            }

            .grid {
                margin-top: 16px;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .feature {
                border: 1px solid var(--line);
                border-radius: 16px;
                padding: 14px;
                background: linear-gradient(170deg, rgba(255, 255, 255, 0.96) 0%, rgba(247, 251, 255, 0.92) 100%);
                transition: transform 0.18s ease, box-shadow 0.18s ease;
                position: relative;
                overflow: hidden;
            }

            html.app-skin-dark body .feature {
                background: linear-gradient(170deg, rgba(24, 35, 50, 0.92) 0%, rgba(19, 29, 42, 0.94) 100%);
            }

            .feature::after {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
                height: 3px;
                background: linear-gradient(90deg, rgba(203, 87, 40, 0.76), rgba(12, 125, 139, 0.76));
                opacity: 0.5;
            }

            .feature:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 20px rgba(15, 23, 42, 0.1);
            }

            .feature-tag {
                display: inline-flex;
                border-radius: 999px;
                padding: 4px 10px;
                font-size: 0.67rem;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                font-weight: 800;
                background: rgba(12, 125, 139, 0.16);
                color: var(--teal);
                margin-bottom: 8px;
            }

            .feature h4 {
                margin: 0;
                font-size: 0.98rem;
                letter-spacing: -0.01em;
            }

            .feature p {
                margin: 6px 0 0;
                color: var(--muted);
                font-size: 0.84rem;
                line-height: 1.62;
            }

            .cta {
                margin-top: 18px;
                border-radius: 22px;
                border: 1px solid var(--line);
                background: linear-gradient(130deg, #fff0e6 0%, #f2fafc 100%);
                box-shadow: 0 16px 32px rgba(15, 23, 42, 0.1);
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                animation: rise-in 0.58s ease both;
                animation-delay: 0.14s;
            }

            html.app-skin-dark body .cta {
                border-color: rgba(186, 206, 232, 0.22);
                background: linear-gradient(130deg, #253243 0%, #1c2f3b 100%);
                box-shadow: 0 16px 30px rgba(0, 0, 0, 0.36);
            }

            .cta h4 {
                margin: 0;
                font-size: 1.06rem;
                letter-spacing: -0.02em;
            }

            .cta p {
                margin: 4px 0 0;
                color: var(--muted);
                font-size: 0.84rem;
            }

            .footer {
                margin-top: 24px;
                color: var(--muted);
                font-size: 0.8rem;
                text-align: center;
            }

            @keyframes rise-in {
                from {
                    opacity: 0;
                    transform: translateY(14px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 1080px) {
                .hero {
                    grid-template-columns: 1fr;
                }

                .grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 860px) {
                .site-header {
                    align-items: flex-start;
                }

                .brand {
                    width: 100%;
                }

                .site-nav {
                    width: 100%;
                    justify-content: flex-start;
                }
            }

            @media (max-width: 720px) {
                .page {
                    padding: 14px 12px 50px;
                }

                .site-header {
                    top: 10px;
                    border-radius: 14px;
                    padding: 10px;
                    gap: 10px;
                }

                .brand {
                    width: 100%;
                }

                .brand-mark {
                    width: 38px;
                    height: 38px;
                    border-radius: 12px;
                }

                .brand-copy strong {
                    font-size: 0.88rem;
                }

                .brand-copy span {
                    font-size: 0.64rem;
                }

                .site-nav {
                    width: 100%;
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 8px;
                }

                .nav-btn {
                    width: 100%;
                    text-align: center;
                    justify-content: center;
                    padding: 8px 12px;
                    min-height: 38px;
                }

                .site-nav .nav-btn.primary {
                    grid-column: 1 / -1;
                }

                .hero-main,
                .hero-side,
                .section,
                .cta {
                    border-radius: 18px;
                }

                .hero-stats,
                .grid,
                .snapshot-grid {
                    grid-template-columns: 1fr;
                }

                .cta {
                    align-items: flex-start;
                }

                .cta .nav-btn {
                    width: 100%;
                }
            }

            @media (max-width: 430px) {
                .site-nav {
                    grid-template-columns: 1fr;
                }

                .brand-copy span {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-bg" aria-hidden="true"></div>
        <div class="page-tint" aria-hidden="true"></div>
        <div class="noise"></div>

        <div class="page">
            @include('partials.public-header')

            <section class="hero">
                @php
                    $heroMainBackground = asset('assets/images/background.png');
                @endphp

                <article
                    class="hero-main"
                    style="--hero-main-bg: url('{{ $heroMainBackground }}'); --hero-main-overlay-light: linear-gradient(145deg, rgba(255, 255, 255, 0.62) 0%, rgba(255, 255, 255, 0.5) 58%, rgba(255, 255, 255, 0.68) 100%); --hero-main-overlay-dark: linear-gradient(145deg, rgba(13, 22, 34, 0.6) 0%, rgba(13, 22, 34, 0.48) 58%, rgba(13, 22, 34, 0.66) 100%);"
                >
                    <span class="hero-kicker">Thesis Portal</span>
                    <h1 class="hero-title">Manage your thesis workflow in one place.</h1>
                    <p class="hero-text">Submit proposals, review units, and prepare for defense with clear steps.</p>
                    <div class="hero-actions">
                        @if (Route::has('login'))
                            @auth
                                <a class="nav-btn primary" href="{{ url('/dashboard') }}">Open my workspace</a>
                                <a class="hero-link" href="{{ route('defense.schedule') }}">View defense schedule</a>
                            @else
                                <a class="nav-btn primary" href="{{ route('login') }}">Get started</a>
                                <a class="hero-link" href="{{ route('books.index') }}">Explore published books</a>
                            @endauth
                        @endif
                    </div>

                    <div class="hero-stats" aria-label="Platform highlights">
                        <article class="hero-stat">
                            <strong>Proposal Flow</strong>
                            <span>Submit, review, approve clearly.</span>
                        </article>
                        <article class="hero-stat">
                            <strong>Unit Tracking</strong>
                            <span>Keep version history organized.</span>
                        </article>
                        <article class="hero-stat">
                            <strong>Defense Ready</strong>
                            <span>Schedule and evaluate on time.</span>
                        </article>
                    </div>
                </article>

                <aside class="hero-side">
                    <div>
                        <h2>Today at a glance</h2>
                        <p>Track progress and decisions across proposal, feedback, versions, and defense milestones.</p>
                    </div>
                    <div class="snapshot-grid">
                        <div class="snapshot">
                            <strong>Proposals</strong>
                            <span>Structured review and approval states</span>
                        </div>
                        <div class="snapshot">
                            <strong>Units</strong>
                            <span>Unit history with final-thesis selection</span>
                        </div>
                        <div class="snapshot">
                            <strong>Defense</strong>
                            <span>Committee workflow with clear timeline</span>
                        </div>
                        <div class="snapshot">
                            <strong>Catalog</strong>
                            <span>Public books published from approved finals</span>
                        </div>
                    </div>
                </aside>
            </section>

            <section class="section">
                <h3>How The Platform Flows</h3>
                <p class="lead">A clear path from idea to defense, with accountability at every step.</p>
                <div class="grid">
                    <article class="feature">
                        <span class="feature-tag">Stage 1</span>
                        <h4>Submit and Assign</h4>
                        <p>Students submit proposals, then coordinators and supervisors route ownership quickly.</p>
                    </article>
                    <article class="feature">
                        <span class="feature-tag">Stage 2</span>
                        <h4>Review and Iterate</h4>
                        <p>Feedback, status changes, and version tracking stay organized in one thread.</p>
                    </article>
                    <article class="feature">
                        <span class="feature-tag">Stage 3</span>
                        <h4>Approve and Publish</h4>
                        <p>Final versions are selected, defense outcomes are recorded, and books can be published.</p>
                    </article>
                </div>
            </section>

            <section class="section">
                <h3>Designed For Every Role</h3>
                <p class="lead">Each user gets focused tools without losing cross-team visibility.</p>
                <div class="grid">
                    <article class="feature">
                        <span class="feature-tag">Students</span>
                        <h4>Clear Status, Zero Ambiguity</h4>
                        <p>Know what is pending, what changed, and what comes next without chasing updates.</p>
                    </article>
                    <article class="feature">
                        <span class="feature-tag">Supervisors</span>
                        <h4>Fast, Structured Decisions</h4>
                        <p>Approve proposals, manage versions, and communicate with context from one screen.</p>
                    </article>
                    <article class="feature">
                        <span class="feature-tag">Committee</span>
                        <h4>Reliable Evaluation Trail</h4>
                        <p>Defense decisions and evidence remain auditable and easy to retrieve.</p>
                    </article>
                </div>
            </section>

            <section class="cta">
                <div>
                    <h4>Ready to run your thesis process without friction?</h4>
                    <p>Launch the workspace and keep proposal, supervision, and defense aligned.</p>
                </div>
                @if (Route::has('login'))
                    @auth
                        <a class="nav-btn primary" href="{{ url('/dashboard') }}">Go to dashboard</a>
                    @else
                        <a class="nav-btn primary" href="{{ route('login') }}">Sign in now</a>
                    @endauth
                @endif
            </section>

            <p class="footer">{{ config('app.name', 'Thesis Management System') }} • Built for transparent academic workflows.</p>
        </div>

        <script>
            (() => {
                const root = document.documentElement;
                const button = document.getElementById('themeToggle');
                const buttonLabel = button ? button.querySelector('[data-theme-label]') : null;
                const storageKey = 'app-skin-dark';

                const applyTheme = (theme) => {
                    const dark = theme === 'dark';
                    root.classList.toggle('app-skin-dark', dark);
                    if (button) {
                        if (buttonLabel) {
                            buttonLabel.textContent = dark ? 'Light theme' : 'Dark theme';
                        } else {
                            button.textContent = dark ? 'Light theme' : 'Dark theme';
                        }
                        button.setAttribute('aria-pressed', dark ? 'true' : 'false');
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

                const initialTheme = savedTheme ? (savedTheme === 'app-skin-dark' ? 'dark' : 'light') : 'light';
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