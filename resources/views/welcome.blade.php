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
                filter: blur(8px) brightness(0.9);
                transform: scale(1.04);
                pointer-events: none;
                z-index: 0;
            }

            .page-tint {
                position: fixed;
                inset: 0;
                background:
                    radial-gradient(900px 460px at 8% -2%, var(--hero-glow-a), transparent 60%),
                    radial-gradient(760px 420px at 92% 0%, var(--hero-glow-b), transparent 58%),
                    linear-gradient(150deg, rgba(254, 250, 243, 0.5) 0%, rgba(246, 240, 231, 0.58) 100%);
                pointer-events: none;
                z-index: 1;
            }

            html.app-skin-dark .page-tint {
                background:
                    radial-gradient(900px 460px at 8% -2%, var(--hero-glow-a), transparent 60%),
                    radial-gradient(760px 420px at 92% 0%, var(--hero-glow-b), transparent 58%),
                    linear-gradient(150deg, rgba(12, 19, 28, 0.6) 0%, rgba(13, 20, 29, 0.68) 100%);
            }

            .noise {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.95' numOctaves='2' stitchTiles='stitch'/></filter><rect width='140' height='140' filter='url(%23n)' opacity='0.07'/></svg>");
                pointer-events: none;
                mix-blend-mode: multiply;
                z-index: 2;
            }

            html.app-skin-dark body .noise {
                mix-blend-mode: screen;
                opacity: 0.34;
            }

            .page {
                position: relative;
                z-index: 3;
                width: min(1220px, 100%);
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
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
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
                gap: 18px;
                margin-bottom: 18px;
            }

            .hero-main,
            .hero-side {
                border: 1px solid var(--line);
                border-radius: 26px;
                background: var(--card);
                box-shadow: 0 20px 42px rgba(20, 31, 49, 0.1);
            }

            .hero-main {
                position: relative;
                overflow: hidden;
                padding: clamp(20px, 3.2vw, 34px);
            }

            .hero-main::before {
                content: "";
                position: absolute;
                width: 280px;
                height: 280px;
                border-radius: 999px;
                right: -130px;
                top: -130px;
                background: radial-gradient(circle, rgba(15, 138, 150, 0.2) 0%, transparent 72%);
                pointer-events: none;
            }

            .hero-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--teal);
                font-size: 0.74rem;
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
                line-height: 1.08;
                font-size: clamp(2rem, 4.5vw, 3.5rem);
                max-width: 720px;
            }

            .hero-text {
                margin-top: 12px;
                max-width: 660px;
                color: var(--muted);
                font-size: 0.96rem;
                line-height: 1.78;
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
                color: var(--ink);
                text-decoration: none;
                font-weight: 700;
                font-size: 0.86rem;
            }

            .hero-link:hover {
                color: var(--teal);
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
                font-size: 1.1rem;
                letter-spacing: -0.02em;
            }

            .hero-side p {
                margin: 6px 0 0;
                color: var(--muted);
                font-size: 0.88rem;
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
                background: linear-gradient(160deg, rgba(255, 255, 255, 0.82) 0%, rgba(247, 251, 255, 0.92) 100%);
            }

            html.app-skin-dark body .snapshot {
                background: linear-gradient(160deg, rgba(26, 37, 53, 0.86) 0%, rgba(19, 30, 44, 0.95) 100%);
            }

            .snapshot strong {
                display: block;
                font-size: 1.24rem;
                line-height: 1.15;
                letter-spacing: -0.03em;
            }

            .snapshot span {
                display: block;
                margin-top: 5px;
                color: var(--muted);
                font-size: 0.76rem;
            }

            .section {
                border: 1px solid var(--line);
                border-radius: 24px;
                background: var(--card);
                box-shadow: 0 16px 34px rgba(20, 31, 49, 0.07);
                padding: 22px;
                margin-top: 16px;
            }

            .section h3 {
                margin: 0;
                font-size: 1.3rem;
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
                background: linear-gradient(170deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 255, 0.9) 100%);
                transition: transform 0.18s ease, box-shadow 0.18s ease;
            }

            html.app-skin-dark body .feature {
                background: linear-gradient(170deg, rgba(24, 35, 50, 0.92) 0%, rgba(19, 29, 42, 0.94) 100%);
            }

            .feature:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 20px rgba(15, 23, 42, 0.1);
            }

            .feature-tag {
                display: inline-flex;
                border-radius: 999px;
                padding: 4px 10px;
                font-size: 0.68rem;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                font-weight: 800;
                background: rgba(15, 138, 150, 0.16);
                color: var(--teal);
                margin-bottom: 8px;
            }

            .feature h4 {
                margin: 0;
                font-size: 1rem;
                letter-spacing: -0.01em;
            }

            .feature p {
                margin: 6px 0 0;
                color: var(--muted);
                font-size: 0.85rem;
                line-height: 1.62;
            }

            .cta {
                margin-top: 16px;
                border-radius: 24px;
                border: 1px solid var(--line);
                background: linear-gradient(135deg, rgba(217, 79, 32, 0.12) 0%, rgba(15, 138, 150, 0.12) 100%);
                padding: 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
            }

            .cta h4 {
                margin: 0;
                font-size: 1.08rem;
                letter-spacing: -0.02em;
            }

            .cta p {
                margin: 4px 0 0;
                color: var(--muted);
                font-size: 0.85rem;
            }

            .footer {
                margin-top: 24px;
                color: var(--muted);
                font-size: 0.8rem;
                text-align: center;
            }

            @media (max-width: 1024px) {
                .hero {
                    grid-template-columns: 1fr;
                }

                .grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 720px) {
                .page {
                    padding: 14px 12px 50px;
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

                .hero-main,
                .hero-side,
                .section,
                .cta {
                    border-radius: 18px;
                }

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
        </style>
    </head>
    <body>
        <div class="page-bg" aria-hidden="true"></div>
        <div class="page-tint" aria-hidden="true"></div>
        <div class="noise"></div>

        <div class="page">
            @include('partials.public-header')

            <section class="hero">
                <article class="hero-main">
                    <span class="hero-kicker">Research Operations, Simplified</span>
                    <h1 class="hero-title">Build, review, and defend every thesis with one connected workflow.</h1>
                    <p class="hero-text">From first proposal to final defense and public catalog release, this platform keeps students, supervisors, librarians, and examiners moving in sync. No missing files, no guesswork, no scattered communication.</p>
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
                            <strong>Versions</strong>
                            <span>Version history with final-thesis selection</span>
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