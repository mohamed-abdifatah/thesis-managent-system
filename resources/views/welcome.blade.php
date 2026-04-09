<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Thesis Management System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700&display=swap" rel="stylesheet">

        <style>
            :root {
                --ink: #151515;
                --muted: #5f6672;
                --paper: #f6f2ed;
                --cream: #fbf7f2;
                --accent: #e4572e;
                --accent-dark: #c7421d;
                --sea: #2f8f9d;
                --dusk: #2b2d42;
                --card: #ffffff;
                --shadow: 0 18px 45px rgba(18, 20, 23, 0.12);
                --radius-xl: 28px;
                --bg-1: #fff3e6;
                --bg-2: #d7f3f6;
                --bg-3: #f5efe8;
            }

            body.theme-dark {
                --ink: #f1f1f1;
                --muted: #a4adbb;
                --paper: #101419;
                --cream: #1a2027;
                --accent: #ff8a5b;
                --accent-dark: #ff6a32;
                --sea: #6ad0dd;
                --dusk: #e2e7f3;
                --card: #151a21;
                --shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
                --bg-1: #1d232d;
                --bg-2: #122033;
                --bg-3: #0c1117;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: "Space Grotesk", "Helvetica Neue", Helvetica, Arial, sans-serif;
                color: var(--ink);
                background: radial-gradient(circle at 20% 10%, var(--bg-1) 0%, transparent 55%),
                    radial-gradient(circle at 80% 20%, var(--bg-2) 0%, transparent 40%),
                    linear-gradient(120deg, #fdf9f4 0%, var(--bg-3) 100%);
                min-height: 100vh;
                transition: background 0.3s ease, color 0.3s ease;
            }

            body.theme-dark {
                background: radial-gradient(circle at 18% 8%, #243043 0%, transparent 55%),
                    radial-gradient(circle at 82% 15%, #1a2f3a 0%, transparent 40%),
                    linear-gradient(120deg, #0f141b 0%, #0a0f14 100%);
            }

            .noise {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/></filter><rect width='160' height='160' filter='url(%23n)' opacity='0.08'/></svg>");
                pointer-events: none;
                mix-blend-mode: multiply;
            }

            body.theme-dark .noise {
                mix-blend-mode: screen;
                opacity: 0.4;
            }

            .page {
                max-width: 1200px;
                margin: 0 auto;
                padding: 28px 20px 80px;
            }

            header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
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

            nav {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
            }

            .btn {
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
            }

            .btn-ghost {
                border-color: rgba(21, 21, 21, 0.2);
                color: var(--ink);
                background: rgba(255, 255, 255, 0.7);
            }

            body.theme-dark .btn-ghost {
                background: rgba(18, 22, 28, 0.7);
                border-color: rgba(255, 255, 255, 0.18);
            }

            .btn-ghost:hover {
                border-color: rgba(21, 21, 21, 0.45);
                transform: translateY(-1px);
            }

            body.theme-dark .btn-ghost:hover {
                border-color: rgba(255, 255, 255, 0.45);
            }

            .btn-primary {
                background: var(--accent);
                color: #fff;
                box-shadow: 0 14px 30px rgba(228, 87, 46, 0.35);
            }

            .btn-primary:hover {
                background: var(--accent-dark);
                transform: translateY(-1px);
            }

            .hero {
                margin-top: 48px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 28px;
                align-items: center;
            }

            .hero h1 {
                font-family: "Fraunces", serif;
                font-size: clamp(32px, 5vw, 56px);
                line-height: 1.05;
                margin-bottom: 18px;
            }

            .hero p {
                color: var(--muted);
                font-size: 18px;
                line-height: 1.6;
                margin-bottom: 28px;
            }

            .hero-actions {
                display: flex;
                gap: 14px;
                flex-wrap: wrap;
            }

            .panel {
                background: var(--card);
                border-radius: var(--radius-xl);
                padding: 28px;
                box-shadow: var(--shadow);
                position: relative;
                overflow: hidden;
                animation: float 7s ease-in-out infinite;
            }

            .panel:before {
                content: "";
                position: absolute;
                inset: -60% 20% auto auto;
                width: 220px;
                height: 220px;
                background: radial-gradient(circle, rgba(47, 143, 157, 0.18), transparent 70%);
            }

            .muted {
                color: var(--muted);
            }

            .stat-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
                margin-top: 18px;
            }

            .stat {
                background: var(--cream);
                border-radius: 18px;
                padding: 16px;
                border: 1px solid rgba(21, 21, 21, 0.08);
            }

            body.theme-dark .stat {
                border-color: rgba(255, 255, 255, 0.1);
            }

            .stat h3 {
                font-size: 20px;
                margin-bottom: 6px;
            }

            .stat span {
                color: var(--muted);
                font-size: 14px;
            }

            .section {
                margin-top: 56px;
            }

            .section-title {
                font-size: 24px;
                margin-bottom: 18px;
            }

            .cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 18px;
            }

            .card {
                background: rgba(255, 255, 255, 0.85);
                border-radius: 22px;
                padding: 20px;
                border: 1px solid rgba(21, 21, 21, 0.08);
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            body.theme-dark .card {
                background: rgba(14, 18, 24, 0.9);
                border-color: rgba(255, 255, 255, 0.08);
            }

            .card:hover {
                transform: translateY(-3px);
                box-shadow: 0 16px 30px rgba(30, 35, 40, 0.12);
            }

            .tag {
                display: inline-flex;
                padding: 4px 10px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 600;
                background: rgba(47, 143, 157, 0.15);
                color: var(--sea);
                margin-bottom: 10px;
            }

            body.theme-dark .tag {
                background: rgba(106, 208, 221, 0.16);
            }

            .footer {
                margin-top: 64px;
                text-align: center;
                color: var(--muted);
                font-size: 14px;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }

            @media (max-width: 720px) {
                .panel {
                    animation: none;
                }
                header {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    </head>
    <body>
        <div class="noise"></div>
        <div class="page">
            <header>
                <div class="logo">
                    <div class="logo-mark">T</div>
                    <span>{{ config('app.name', 'Thesis Management System') }}</span>
                </div>
                <nav>
                    <button class="btn btn-ghost" id="themeToggle" type="button" aria-pressed="false">
                        Dark theme
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a class="btn btn-primary" href="{{ url('/dashboard') }}">Go to dashboard</a>
                        @else
                            <a class="btn btn-ghost" href="{{ route('login') }}">Log in</a>
                            @if (Route::has('register'))
                                <a class="btn btn-primary" href="{{ route('register') }}">Create account</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <section class="hero">
                <div>
                    <h1>Run every thesis journey with clarity, not chaos.</h1>
                    <p>From proposal to defense, this system keeps students, supervisors, and examiners on the same track. Organize submissions, schedule defenses, and keep every version secure.</p>
                    <div class="hero-actions">
                        @if (Route::has('login'))
                            @auth
                                <a class="btn btn-primary" href="{{ url('/dashboard') }}">Open my workspace</a>
                                <a class="btn btn-ghost" href="{{ route('defense.schedule') }}">View defense schedule</a>
                            @else
                                <a class="btn btn-primary" href="{{ route('login') }}">Start now</a>
                                @if (Route::has('register'))
                                    <a class="btn btn-ghost" href="{{ route('register') }}">Request access</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
                <div class="panel">
                    <h2>Today at a glance</h2>
                    <p class="muted">Your program timeline is now in one place.</p>
                    <div class="stat-grid">
                        <div class="stat">
                            <h3>Proposals</h3>
                            <span>Track reviews and approvals</span>
                        </div>
                        <div class="stat">
                            <h3>Versions</h3>
                            <span>Every revision stored</span>
                        </div>
                        <div class="stat">
                            <h3>Defense</h3>
                            <span>Unified schedule</span>
                        </div>
                        <div class="stat">
                            <h3>Groups</h3>
                            <span>Manage cohorts easily</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2 class="section-title">How it works</h2>
                <div class="cards">
                    <div class="card">
                        <span class="tag">Step 1</span>
                        <h3>Submit & assign</h3>
                        <p>Students submit proposals, coordinators assign supervisors, and the workflow kicks in with notifications.</p>
                    </div>
                    <div class="card">
                        <span class="tag">Step 2</span>
                        <h3>Collaborate securely</h3>
                        <p>Upload versions, capture feedback, and keep a verified audit trail for every milestone.</p>
                    </div>
                    <div class="card">
                        <span class="tag">Step 3</span>
                        <h3>Schedule defense</h3>
                        <p>Set committees, assign examiners, and publish a clear defense timetable for all roles.</p>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2 class="section-title">Built for every role</h2>
                <div class="cards">
                    <div class="card">
                        <span class="tag">Students</span>
                        <p>Submit proposals, track versions, and follow defense status without guessing.</p>
                    </div>
                    <div class="card">
                        <span class="tag">Supervisors</span>
                        <p>See your students instantly, review work, and keep feedback structured.</p>
                    </div>
                    <div class="card">
                        <span class="tag">Examiners</span>
                        <p>Evaluate defenses with a clean rubric and publish results on time.</p>
                    </div>
                    <div class="card">
                        <span class="tag">Admins</span>
                        <p>Manage users, groups, and defense logistics from a single dashboard.</p>
                    </div>
                </div>
            </section>

            <div class="footer">
                Thesis Management System. Designed for fast, transparent academic workflows.
            </div>
        </div>

        <script>
            const body = document.body;
            const button = document.getElementById('themeToggle');
            const storageKey = 'app-skin-dark';

            const applyTheme = (theme) => {
                if (theme === 'dark') {
                    body.classList.add('theme-dark');
                    button.textContent = 'Light theme';
                    button.setAttribute('aria-pressed', 'true');
                    localStorage.setItem(storageKey, 'app-skin-dark');
                } else {
                    body.classList.remove('theme-dark');
                    button.textContent = 'Dark theme';
                    button.setAttribute('aria-pressed', 'false');
                    localStorage.setItem(storageKey, 'app-skin-light');
                }
            };

            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem(storageKey);
            const initialTheme = savedTheme ? (savedTheme === 'app-skin-dark' ? 'dark' : 'light') : (prefersDark ? 'dark' : 'light');
            applyTheme(initialTheme);

            button.addEventListener('click', () => {
                const isDark = body.classList.contains('theme-dark');
                const nextTheme = isDark ? 'light' : 'dark';
                applyTheme(nextTheme);
            });
        </script>
    </body>
</html>
