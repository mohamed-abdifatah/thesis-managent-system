<x-app-layout>
    @php
        $user = auth()->user();
        $roleName = $user?->role?->name;
        $roleLabel = $roleName ? ucfirst($roleName) : 'No role assigned';

        $publicBooksCount = \App\Models\Thesis::where('is_public', true)->count();
        $defenseUpcoming = \App\Models\DefenseSession::where('scheduled_at', '>=', now())->count();
        $totalUsers = \App\Models\User::count();
    @endphp

    <style>
        .ta-fallback {
            --ta-line: rgba(27, 34, 48, 0.14);
            --ta-muted: #63758c;
            --ta-accent: #d94f20;
            --ta-accent-strong: #b63b14;
            --ta-teal: #0f8a96;
        }

        .ta-fallback .ta-alert {
            border: 1px solid var(--ta-line);
            border-radius: 16px;
            padding: 13px 14px;
            background: linear-gradient(145deg, rgba(255, 247, 235, 0.95) 0%, rgba(239, 250, 252, 0.95) 100%);
            margin-bottom: 14px;
            color: #45566f;
            font-size: 0.86rem;
            line-height: 1.62;
        }

        .ta-fallback .ta-alert strong {
            color: #1d2a3e;
        }

        .ta-fallback .ta-chip-link.ta-primary {
            color: #ffffff;
            border-color: transparent;
            background: linear-gradient(145deg, var(--ta-accent), var(--ta-accent-strong));
            box-shadow: 0 14px 26px rgba(217, 79, 32, 0.28);
        }

        .ta-fallback .ta-chip-link.ta-primary:hover {
            color: #ffffff;
            box-shadow: 0 18px 30px rgba(217, 79, 32, 0.33);
        }

        .ta-fallback .ta-panel-head h3 {
            letter-spacing: -0.02em;
        }

        .ta-fallback .ta-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .ta-fallback .ta-stat {
            border: 1px solid var(--ta-line);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            padding: 14px;
        }

        .ta-fallback .ta-stat p {
            margin: 0;
            font-size: 0.79rem;
            color: var(--ta-muted);
            font-weight: 700;
        }

        .ta-fallback .ta-stat strong {
            display: block;
            margin-top: 6px;
            font-size: 1.45rem;
            line-height: 1.06;
            letter-spacing: -0.03em;
        }

        .ta-fallback .ta-list {
            display: grid;
            gap: 8px;
        }

        .ta-fallback .ta-list a {
            text-decoration: none;
            border: 1px solid var(--ta-line);
            border-radius: 12px;
            padding: 10px 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #1f314c;
            font-size: 0.84rem;
            font-weight: 700;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .ta-fallback .ta-list a:hover {
            border-color: #b9cbe7;
            color: #1f58d8;
            transform: translateY(-1px);
        }

        html.app-skin-dark .ta-fallback .ta-alert,
        html.app-skin-dark .ta-fallback .ta-stat,
        html.app-skin-dark .ta-fallback .ta-list a {
            background: #151e2b;
            border-color: rgba(224, 235, 250, 0.14);
            color: #d7e6fb;
        }

        html.app-skin-dark .ta-fallback .ta-alert strong {
            color: #e8effd;
        }

        html.app-skin-dark .ta-fallback .ta-stat p {
            color: #9eb0c8;
        }

        @media (max-width: 991px) {
            .ta-fallback .ta-mini-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="ta-fallback">
        <div class="ta-page-head">
            <div>
                <span class="ta-page-kicker">Workspace Overview</span>
                <h1 class="ta-page-title">Dashboard Home</h1>
                <p class="ta-page-subtitle">Use this dashboard to reach your main tools quickly. Your detected role is <strong>{{ $roleLabel }}</strong>.</p>
            </div>
            <div class="ta-page-actions">
                <a href="{{ route('profile.edit') }}" class="ta-chip-link ta-primary">
                    <i class="feather-user"></i>
                    Profile Settings
                </a>
                <a href="{{ route('books.index') }}" class="ta-chip-link">
                    <i class="feather-book-open"></i>
                    Public Books
                </a>
            </div>
        </div>

        @if(!empty($error))
            <div class="ta-alert" role="alert">
                <strong>Dashboard Notice:</strong> {{ $error }}. You can still access common tools from the quick links below.
            </div>
        @elseif(!$roleName)
            <div class="ta-alert" role="status">
                <strong>Dashboard Notice:</strong> Your account does not have a role yet. Please contact an administrator to unlock role-specific features.
            </div>
        @endif

        <div class="ta-mini-grid">
            <article class="ta-stat">
                <p>Total Users</p>
                <strong>{{ number_format($totalUsers) }}</strong>
            </article>
            <article class="ta-stat">
                <p>Upcoming Defenses</p>
                <strong>{{ number_format($defenseUpcoming) }}</strong>
            </article>
            <article class="ta-stat">
                <p>Public Catalog Books</p>
                <strong>{{ number_format($publicBooksCount) }}</strong>
            </article>
        </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <section class="ta-panel">
                    <header class="ta-panel-head">
                        <h3>Quick Access</h3>
                    </header>
                    <div class="ta-panel-body">
                        <div class="ta-list">
                            <a href="{{ route('profile.edit') }}">
                                <span>Edit my profile</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('books.index') }}">
                                <span>Browse public thesis books</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('defense.schedule') }}">
                                <span>Open defense schedule</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-6">
                <section class="ta-panel">
                    <header class="ta-panel-head">
                        <h3>Role Guidance</h3>
                    </header>
                    <div class="ta-panel-body">
                        <p class="text-muted small mb-2">If you expected a role-specific dashboard, verify your account role with an administrator. Once assigned, your dashboard will automatically switch to the corresponding workspace.</p>
                        <p class="text-muted small mb-0">Current role value: <strong>{{ $roleName ?? 'none' }}</strong></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
