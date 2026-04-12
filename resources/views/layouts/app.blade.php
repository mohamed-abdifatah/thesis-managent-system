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
                --ta-bg: #f3f7fd;
                --ta-surface: #ffffff;
                --ta-ink: #101828;
                --ta-muted: #66758d;
                --ta-border: #dbe4f1;
                --ta-primary: #375dfb;
                --ta-primary-deep: #1d4ed8;
                --ta-soft: #edf3ff;
                --ta-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            }

            body {
                font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background:
                    radial-gradient(circle at 88% 4%, rgba(59, 130, 246, 0.11), transparent 35%),
                    radial-gradient(circle at 10% 96%, rgba(79, 70, 229, 0.1), transparent 35%),
                    var(--ta-bg);
                color: var(--ta-ink);
            }

            .nxl-header,
            .nxl-navigation,
            .nxl-content,
            .main-content,
            .card,
            .dropdown-menu,
            .modal-content,
            .page-header,
            .nxl-h-dropdown {
                filter: none !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }

            .nxl-navigation {
                background: var(--ta-surface);
                border-right: 1px solid var(--ta-border);
                box-shadow: none;
            }

            .nxl-navigation .m-header {
                border-bottom: 1px solid var(--ta-border);
                min-height: 84px;
                display: flex;
                align-items: center;
                padding: 0 16px;
            }

            .ta-brand {
                width: 100%;
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
            }

            .ta-brand-icon {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                background: linear-gradient(135deg, var(--ta-primary) 0%, var(--ta-primary-deep) 100%);
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .ta-brand-text {
                display: flex;
                flex-direction: column;
                min-width: 0;
            }

            .ta-brand-text .app-brand-title {
                margin: 0;
                color: #0f172a;
                font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
                letter-spacing: -0.02em;
                font-size: 1rem;
                font-weight: 700;
                line-height: 1.1;
            }

            .ta-brand-text small {
                color: #66758d;
                font-size: 0.72rem;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                margin-top: 3px;
            }

            .nxl-navbar .nxl-caption label {
                font-size: 0.68rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                color: #8393a7;
                text-transform: uppercase;
            }

            .nxl-navbar .nxl-item .nxl-link {
                border-radius: 12px;
                margin-bottom: 4px;
                color: #344054;
                font-weight: 600;
                transition: all 0.2s ease;
            }

            .nxl-navbar .nxl-item .nxl-link:hover {
                background: #f3f7ff;
                color: #162f77;
            }

            .nxl-navbar .nxl-item .nxl-link.is-active {
                background: var(--ta-soft);
                color: #1d4ed8;
                box-shadow: inset 0 0 0 1px rgba(29, 78, 216, 0.14);
            }

            .nxl-navbar .nxl-item .nxl-link.is-active .nxl-micon i,
            .nxl-navbar .nxl-item .nxl-link.is-active .nxl-arrow i {
                color: #1d4ed8;
            }

            .nxl-submenu .nxl-link {
                padding-left: 18px;
                font-size: 0.84rem;
                color: #4f627b;
            }

            .nxl-header {
                background: rgba(255, 255, 255, 0.94);
                border-bottom: 1px solid var(--ta-border);
                min-height: 72px;
                overflow: visible;
            }

            .header-wrapper {
                min-height: 72px;
                padding: 0 18px;
                position: relative;
                z-index: 20;
            }

            .header-left {
                min-width: 0;
                flex: 1 1 auto;
            }

            .header-right {
                position: relative;
                z-index: 30;
                flex-shrink: 0;
            }

            .header-right .dropdown-menu {
                z-index: 1080;
            }

            .header-right .dropdown.nxl-h-item {
                position: relative;
            }

            .header-right .dropdown-menu.nxl-user-dropdown {
                width: 300px;
                max-width: min(300px, calc(100vw - 20px));
                right: 0;
                left: auto;
                top: calc(100% + 8px);
            }

            .header-right .dropdown-menu.nxl-user-dropdown.show {
                top: calc(100% + 8px) !important;
                right: 0 !important;
                left: auto !important;
                transform: none !important;
                margin: 0 !important;
            }

            .ta-search-wrap {
                width: min(520px, 50vw);
                max-width: 520px;
                flex: 1 1 520px;
                position: relative;
            }

            .ta-search-wrap form {
                position: relative;
                width: 100%;
            }

            .ta-search-icon {
                position: absolute;
                top: 50%;
                left: 14px;
                transform: translateY(-50%);
                color: #6b7d93;
                font-size: 0.95rem;
                pointer-events: none;
            }

            .ta-search-input {
                width: 100%;
                height: 42px;
                border-radius: 12px;
                border: 1px solid var(--ta-border);
                background: #f8fbff;
                padding: 0 76px 0 38px;
                color: #14263f;
                font-size: 0.84rem;
                font-weight: 600;
                outline: 0;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .ta-search-input:focus {
                border-color: #4e7cff;
                box-shadow: 0 0 0 4px rgba(78, 124, 255, 0.12);
            }

            .ta-search-shortcut {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                border: 1px solid #cfdbed;
                border-radius: 8px;
                padding: 2px 7px;
                font-size: 0.64rem;
                color: #5f7390;
                font-weight: 700;
                letter-spacing: 0.03em;
                background: #fff;
            }

            .ta-head-btn {
                width: 40px;
                height: 40px;
                border-radius: 999px;
                border: 1px solid var(--ta-border);
                color: #4d6078;
                background: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .ta-head-btn:focus-visible,
            .ta-avatar-trigger:focus-visible {
                outline: 3px solid rgba(55, 93, 251, 0.25);
                outline-offset: 2px;
            }

            .ta-avatar-trigger {
                border: 0;
                background: transparent;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            .ta-head-btn:hover {
                background: #f4f8ff;
                color: #1d4ed8;
                border-color: #b8cae7;
            }

            .ta-notification-dot {
                position: absolute;
                top: 7px;
                right: 8px;
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #f04438;
                border: 2px solid #fff;
            }

            .ta-notification-menu {
                width: 290px;
                border-radius: 16px;
                border: 1px solid var(--ta-border);
                box-shadow: var(--ta-shadow);
                padding: 8px;
            }

            .ta-notification-menu .dropdown-header {
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: #72839a;
                padding: 8px 10px;
            }

            .ta-notification-menu .dropdown-item {
                border-radius: 10px;
                font-size: 0.84rem;
                color: #354960;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .ta-notification-menu .dropdown-item:hover {
                background: #f3f7ff;
                color: #1d4ed8;
            }

            .nxl-container,
            .nxl-content,
            .main-content {
                background: transparent;
            }

            .main-content {
                padding-top: 22px;
            }

            .page-header {
                margin-bottom: 18px;
            }

            .card,
            .dropdown-menu,
            .modal-content,
            .nxl-card,
            .bg-white {
                border-color: var(--ta-border) !important;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            }

            .ta-page-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                margin-bottom: 18px;
                flex-wrap: wrap;
            }

            .ta-page-kicker {
                display: inline-block;
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                font-weight: 700;
                color: #627892;
                margin-bottom: 4px;
            }

            .ta-page-title {
                margin: 0;
                font-size: clamp(1.35rem, 2vw, 1.75rem);
                letter-spacing: -0.02em;
                font-weight: 800;
                color: #0f172a;
            }

            .ta-page-subtitle {
                margin: 8px 0 0;
                color: #607086;
                font-size: 0.86rem;
                line-height: 1.6;
                max-width: 720px;
            }

            .ta-page-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            .ta-chip-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 12px;
                border-radius: 12px;
                text-decoration: none;
                font-size: 0.8rem;
                font-weight: 700;
                color: #1f3654;
                background: #fff;
                border: 1px solid var(--ta-border);
                transition: all 0.2s ease;
            }

            .ta-chip-link:hover {
                color: #1d4ed8;
                border-color: #bfd1ec;
                transform: translateY(-1px);
            }

            .ta-panel {
                border: 1px solid var(--ta-border);
                border-radius: 18px;
                background: #fff;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
                overflow: hidden;
            }

            .ta-panel-head {
                padding: 14px 16px;
                border-bottom: 1px solid var(--ta-border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            .ta-panel-head h3,
            .ta-panel-head h4 {
                margin: 0;
                font-size: 0.98rem;
                font-weight: 800;
            }

            .ta-panel-body {
                padding: 16px;
            }

            .ta-table-shell {
                width: 100%;
                overflow-x: auto;
            }

            .ta-table-shell .table,
            .ta-table-shell table {
                width: 100%;
                margin: 0;
                border-collapse: collapse;
            }

            .ta-table-shell thead th {
                background: #f7faff;
                color: #607086;
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                border-bottom: 1px solid var(--ta-border);
                padding: 12px 14px;
            }

            .ta-table-shell tbody td {
                border-bottom: 1px solid var(--ta-border);
                padding: 12px 14px;
                vertical-align: middle;
            }

            .ta-table-shell tbody tr:last-child td {
                border-bottom: 0;
            }

            .footer {
                border-top: 1px solid var(--ta-border);
                margin-top: 18px;
                padding-top: 16px;
                padding-bottom: 18px;
            }

            .modal-backdrop {
                display: none !important;
                background-color: transparent !important;
                opacity: 0 !important;
            }

            body.modal-open {
                overflow: auto !important;
                padding-right: 0 !important;
            }

            body.modal-open .nxl-container,
            body.modal-open .nxl-header,
            body.modal-open .nxl-navigation,
            body.modal-open .page-header {
                filter: none !important;
                transition: none !important;
            }

            html.app-skin-dark body {
                background:
                    radial-gradient(circle at 84% 10%, rgba(55, 93, 251, 0.2), transparent 35%),
                    #0f141b;
                color: #e6edf3;
            }

            html.app-skin-dark .nxl-header,
            html.app-skin-dark .nxl-navigation {
                background: #101722;
                border-color: rgba(255, 255, 255, 0.08);
            }

            html.app-skin-dark .ta-brand-text .app-brand-title {
                color: #e6edf3;
            }

            html.app-skin-dark .ta-brand-text small,
            html.app-skin-dark .nxl-navbar .nxl-caption label {
                color: #98a4b5;
            }

            html.app-skin-dark .nxl-navbar .nxl-item .nxl-link {
                color: #b7c3d1;
            }

            html.app-skin-dark .nxl-navbar .nxl-item .nxl-link:hover {
                background: #1a2433;
                color: #e1ebff;
            }

            html.app-skin-dark .nxl-navbar .nxl-item .nxl-link.is-active {
                background: rgba(55, 93, 251, 0.18);
                color: #c9d7ff;
                box-shadow: inset 0 0 0 1px rgba(82, 118, 255, 0.35);
            }

            html.app-skin-dark .ta-search-input {
                background: #151f2d;
                border-color: rgba(255, 255, 255, 0.12);
                color: #dce8ff;
            }

            html.app-skin-dark .ta-search-input::placeholder,
            html.app-skin-dark .ta-search-icon {
                color: #9fb0c5;
            }

            html.app-skin-dark .ta-search-shortcut,
            html.app-skin-dark .ta-head-btn {
                background: #151f2d;
                border-color: rgba(255, 255, 255, 0.14);
                color: #c2d0e2;
            }

            html.app-skin-dark .ta-head-btn:hover {
                background: #1c2940;
                color: #d9e4ff;
            }

            html.app-skin-dark .ta-notification-dot {
                border-color: #151f2d;
            }

            html.app-skin-dark .card,
            html.app-skin-dark .nxl-card,
            html.app-skin-dark .modal-content,
            html.app-skin-dark .dropdown-menu,
            html.app-skin-dark .table,
            html.app-skin-dark .bg-white {
                background-color: #151a21 !important;
                color: #e6edf3;
                border-color: rgba(255, 255, 255, 0.08) !important;
            }

            html.app-skin-dark .text-dark {
                color: #e6edf3 !important;
            }

            html.app-skin-dark .text-muted {
                color: #9aa4b2 !important;
            }

            html.app-skin-dark .ta-notification-menu .dropdown-item {
                color: #c7d2df;
            }

            html.app-skin-dark .ta-notification-menu .dropdown-item:hover {
                background: #202c3f;
                color: #dbe7ff;
            }

            html.app-skin-dark .ta-notification-menu .dropdown-header {
                color: #9fb0c5;
            }

            html.app-skin-dark .ta-page-title {
                color: #e6edf3;
            }

            html.app-skin-dark .ta-page-kicker,
            html.app-skin-dark .ta-page-subtitle {
                color: #a7b3c3;
            }

            html.app-skin-dark .ta-chip-link,
            html.app-skin-dark .ta-panel,
            html.app-skin-dark .ta-table-shell thead th {
                background: #151a21;
                color: #d8e4f5;
                border-color: rgba(255, 255, 255, 0.08);
            }

            html.app-skin-dark .ta-table-shell tbody td {
                border-color: rgba(255, 255, 255, 0.08);
            }

            html.app-skin-dark .bg-light {
                background-color: #151a21 !important;
            }

            html.app-skin-dark .form-control,
            html.app-skin-dark .form-select,
            html.app-skin-dark .input-group-text {
                background-color: #1b212b;
                color: #e6edf3;
                border-color: rgba(255, 255, 255, 0.12);
            }

            html.app-skin-dark .table > :not(caption) > * > * {
                background-color: transparent;
                color: inherit;
                border-color: rgba(255, 255, 255, 0.08);
            }

            @media (max-width: 991px) {
                .header-wrapper {
                    padding: 0 14px;
                }

                .main-content {
                    padding-top: 14px;
                }

                .nxl-header .header-wrapper .header-right .nxl-h-item .nxl-h-dropdown.nxl-user-dropdown {
                    left: auto !important;
                    right: 0 !important;
                    width: min(300px, calc(100vw - 20px)) !important;
                }
            }
        </style>
        
        <!-- Scripts -->
        <!-- We are using the template scripts instead of Vite for now as requested -->
    </head>
    <body class="bg-gray-100">
        <!-- Navigation Menu -->
        @include('layouts.sidebar')

        <!-- Header -->
        @include('layouts.topbar')

        <!-- Main Container -->
        <main class="nxl-container">
            <div class="nxl-content">
                <!-- Page Content -->
                <div class="main-content">
                    {{ $slot }}
                </div>
            </div>
            
             <!-- Footer -->
            <footer class="footer">
                <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
                    <span>Copyright © {{ date('Y') }}</span>
                </p>
                <div class="d-flex align-items-center gap-4">
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Help</a>
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Terms</a>
                    <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Privacy</a>
                </div>
            </footer>
        </main>

        <!-- Vendors JS -->
        <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>

        <!-- Bootstrap JS (modal, dropdown, etc.) -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        
        <!-- Common Init JS -->
        <script src="{{ asset('assets/js/common-init.min.js') }}"></script>

        <script>
            (() => {
                const storageKey = 'app-skin-dark';
                const html = document.documentElement;
                const darkButton = document.querySelector('.dark-button');
                const lightButton = document.querySelector('.light-button');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const stored = localStorage.getItem(storageKey);
                const initialDark = stored ? stored === 'app-skin-dark' : prefersDark;

                const applyTheme = (isDark) => {
                    html.classList.toggle('app-skin-dark', isDark);
                    if (darkButton && lightButton) {
                        darkButton.style.display = isDark ? 'none' : 'inline-flex';
                        lightButton.style.display = isDark ? 'inline-flex' : 'none';
                    }
                    localStorage.setItem(storageKey, isDark ? 'app-skin-dark' : 'app-skin-light');
                };

                applyTheme(initialDark);

                if (darkButton) {
                    darkButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        applyTheme(true);
                    });
                }

                if (lightButton) {
                    lightButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        applyTheme(false);
                    });
                }
            })();
        </script>
        
        <!-- Custom Scripts if any -->
        @stack('modals')
        @stack('scripts')
    </body>
</html>
