<header class="site-header" data-nav-header>
    <a href="{{ url('/') }}" class="brand">
        <span class="brand-mark">
            <img src="{{ asset('assets/images/rsu.jpg') }}" alt="RSU logo">
        </span>
        <span class="brand-copy">
            <strong>{{ config('app.name', 'Thesis Management System') }}</strong>
            <span>Academic Workspace</span>
        </span>
    </a>

    <button
        class="site-menu-toggle nav-btn ghost"
        id="siteMenuToggle"
        type="button"
        aria-expanded="false"
        aria-controls="siteNavMenu"
    >
        <span class="site-menu-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <path d="M3 6h18"></path>
                <path d="M3 12h18"></path>
                <path d="M3 18h18"></path>
            </svg>
        </span>
        <span>Menu</span>
    </button>

    <nav class="site-nav" id="siteNavMenu">
        <button class="nav-btn ghost" id="themeToggle" type="button" aria-pressed="false">
            <span class="nav-item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                    <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"></path>
                </svg>
            </span>
            <span data-theme-label>Dark theme</span>
        </button>
        <a class="nav-btn ghost" href="{{ route('books.index') }}">
            <span class="nav-item-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                    <path d="M4 5a2 2 0 0 1 2-2h14"></path>
                    <path d="M4 5v14a2 2 0 0 0 2 2h14"></path>
                    <path d="M8 7h10"></path>
                    <path d="M8 11h10"></path>
                </svg>
            </span>
            <span>Browse books</span>
        </a>
        @if (Route::has('login'))
            @auth
                <a class="nav-btn primary" href="{{ url('/dashboard') }}">
                    <span class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                            <rect x="3" y="3" width="8" height="8" rx="1"></rect>
                            <rect x="13" y="3" width="8" height="5" rx="1"></rect>
                            <rect x="13" y="10" width="8" height="11" rx="1"></rect>
                            <rect x="3" y="13" width="8" height="8" rx="1"></rect>
                        </svg>
                    </span>
                    <span>Go to dashboard</span>
                </a>
            @else
                <a class="nav-btn ghost" href="{{ route('login') }}">
                    <span class="nav-item-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M21 21V3"></path>
                        </svg>
                    </span>
                    <span>Log in</span>
                </a>
                @if (Route::has('register'))
                    <a class="nav-btn primary" href="{{ route('register') }}">
                        <span class="nav-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                <path d="M15 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8" cy="7" r="4"></circle>
                                <path d="M20 8v6"></path>
                                <path d="M17 11h6"></path>
                            </svg>
                        </span>
                        <span>Create account</span>
                    </a>
                @endif
            @endauth
        @endif
    </nav>
</header>

<style>
    .site-menu-toggle.nav-btn {
        display: none;
        align-items: center;
        gap: 8px;
    }

    .nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .site-menu-icon,
    .nav-item-icon {
        width: 14px;
        height: 14px;
        display: inline-flex;
        flex-shrink: 0;
    }

    .site-menu-icon svg,
    .nav-item-icon svg {
        width: 100%;
        height: 100%;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    @media (max-width: 860px) {
        .site-header[data-nav-header] {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 10px;
            overflow: visible;
        }

        .site-header[data-nav-header] .brand {
            width: auto;
            min-width: 0;
        }

        .site-header[data-nav-header] .site-menu-toggle.nav-btn {
            display: inline-flex;
            margin-left: auto;
        }

        .site-header[data-nav-header] .site-nav {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 35;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--header-glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 14px 26px rgba(15, 23, 42, 0.16);
            padding: 8px;
            margin: 0;
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 8px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-6px);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        }

        .site-header[data-nav-header].is-menu-open .site-nav {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .site-header[data-nav-header] .site-nav .nav-btn {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>

<script>
    (() => {
        const header = document.querySelector('[data-nav-header]');
        const toggle = document.getElementById('siteMenuToggle');
        const menu = document.getElementById('siteNavMenu');

        if (!header || !toggle || !menu) {
            return;
        }

        const closeMenu = () => {
            header.classList.remove('is-menu-open');
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', () => {
            const isOpen = header.classList.toggle('is-menu-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (event) => {
            if (!header.contains(event.target)) {
                closeMenu();
            }
        });

        menu.querySelectorAll('a, button').forEach((item) => {
            if (item.id === 'siteMenuToggle') {
                return;
            }

            item.addEventListener('click', () => {
                if (window.innerWidth <= 860) {
                    closeMenu();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 860) {
                closeMenu();
            }
        });
    })();
</script>
