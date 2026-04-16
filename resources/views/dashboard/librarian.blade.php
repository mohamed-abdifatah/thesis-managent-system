<x-app-layout>
    @php
        $catalogThesisQuery = \App\Models\Thesis::query()
            ->whereIn('status', ['defended', 'completed']);

        $catalogReadyCount = (clone $catalogThesisQuery)
            ->where('status', 'defended')
            ->where('is_library_approved', false)
            ->whereHas('defense', fn ($query) => $query->where('status', 'completed'))
            ->whereHas('approvedVersions')
            ->count();

        $pendingValidationCount = (clone $catalogThesisQuery)
            ->where('status', 'defended')
            ->where(function ($query) {
                $query->doesntHave('defense')
                    ->orWhereHas('defense', fn ($defenseQuery) => $defenseQuery->where('status', '!=', 'completed'))
                    ->orDoesntHave('approvedVersions');
            })
            ->count();

        $publishedCount = (clone $catalogThesisQuery)
            ->where('is_public', true)
            ->count();

        $totalCatalogCount = (clone $catalogThesisQuery)
            ->where('is_library_approved', true)
            ->count();

        $catalogQueue = \App\Models\Thesis::with(['student.user', 'supervisor.user', 'defense'])
            ->whereIn('status', ['defended', 'completed'])
            ->latest()
            ->take(8)
            ->get();

        $queueCoverage = max($catalogReadyCount + $pendingValidationCount, 1);
        $readinessRate = (int) round(($catalogReadyCount / $queueCoverage) * 100);
    @endphp

    <style>
        .libd-page {
            --libd-surface: #ffffff;
            --libd-border: #d9e3f2;
            --libd-muted: #64758d;
            --libd-ink: #0f172a;
            --libd-primary: #0f766e;
            --libd-primary-soft: #dcf6f3;
        }

        html.app-skin-dark .libd-page {
            --libd-surface: #151d28;
            --libd-border: rgba(196, 213, 238, 0.16);
            --libd-muted: #9fb2c9;
            --libd-ink: #e8eef8;
            --libd-primary: #63d6cb;
            --libd-primary-soft: rgba(99, 214, 203, 0.16);
        }

        .libd-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid #caece8;
            border-radius: 22px;
            background: linear-gradient(125deg, #edfbf9 0%, #e3f8f5 52%, #f2faf9 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .libd-hero {
            border-color: #35507a;
            background: linear-gradient(125deg, #1b2d34 0%, #17252e 52%, #1a2932 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .libd-hero::before {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            top: -120px;
            right: -90px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.16) 0%, transparent 74%);
            pointer-events: none;
        }

        .libd-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .libd-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f766e;
            font-weight: 800;
        }

        html.app-skin-dark .libd-kicker {
            color: #8de4dc;
        }

        .libd-title {
            margin: 0;
            color: var(--libd-ink);
            font-size: clamp(1.35rem, 2.7vw, 1.95rem);
            letter-spacing: -0.02em;
        }

        .libd-subtitle {
            margin: 8px 0 0;
            color: var(--libd-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .libd-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .libd-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--libd-border);
            background: var(--libd-surface);
            color: #1f4f52;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .libd-action {
            color: #d4e1f4;
        }

        .libd-action:hover {
            color: #0f766e;
            border-color: #bfe7e1;
            transform: translateY(-1px);
        }

        .libd-stat {
            border: 1px solid var(--libd-border);
            border-radius: 16px;
            background: var(--libd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .libd-stat-label {
            margin: 0;
            color: var(--libd-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .libd-stat-value {
            margin: 3px 0 0;
            color: var(--libd-ink);
            font-size: 1.45rem;
            line-height: 1;
        }

        .libd-stat-note {
            margin: 5px 0 0;
            color: var(--libd-muted);
            font-size: 0.73rem;
        }

        .libd-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--libd-primary);
            background: var(--libd-primary-soft);
            border: 1px solid #c8eae5;
        }

        html.app-skin-dark .libd-stat-icon {
            border-color: #35507a;
            color: #8de4dc;
        }

        .libd-panel {
            border: 1px solid var(--libd-border);
            border-radius: 18px;
            background: var(--libd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .libd-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--libd-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fbfefd 0%, #f6fbfa 100%);
        }

        html.app-skin-dark .libd-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .libd-panel-title {
            margin: 0;
            color: var(--libd-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .libd-panel-sub {
            color: var(--libd-muted);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .libd-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .libd-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .libd-table th {
            background: #f8fcfb;
            color: var(--libd-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.67rem;
            font-weight: 800;
            border-bottom: 1px solid var(--libd-border);
            padding: 11px 14px;
            white-space: nowrap;
        }

        html.app-skin-dark .libd-table th {
            background: #192433;
        }

        .libd-table td {
            border-bottom: 1px solid var(--libd-border);
            padding: 12px 14px;
            vertical-align: middle;
        }

        .libd-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .libd-table tbody tr:hover td {
            background: #f7fcfb;
        }

        html.app-skin-dark .libd-table tbody tr:hover td {
            background: #1a2737;
        }

        .libd-title-clip {
            max-width: 280px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #1f3f69;
            font-size: 0.83rem;
            font-weight: 700;
        }

        html.app-skin-dark .libd-title-clip {
            color: #d4e2f6;
        }

        .libd-step {
            border: 1px solid var(--libd-border);
            border-radius: 12px;
            padding: 10px;
            background: #f9fdfc;
        }

        html.app-skin-dark .libd-step {
            background: #1a2737;
        }

        .libd-step-kicker {
            margin: 0 0 4px;
            color: var(--libd-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.63rem;
            font-weight: 800;
        }

        .libd-step-title {
            margin: 0;
            color: var(--libd-ink);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .libd-step-note {
            margin: 5px 0 0;
            color: var(--libd-muted);
            font-size: 0.76rem;
            line-height: 1.45;
        }

        .libd-progress {
            border: 1px solid var(--libd-border);
            border-radius: 12px;
            padding: 10px;
            background: #f9fdfc;
            margin-bottom: 12px;
        }

        html.app-skin-dark .libd-progress {
            background: #1a2737;
        }

        .libd-progress-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 6px;
            color: var(--libd-muted);
            font-size: 0.78rem;
        }

        .libd-progress-head strong {
            color: var(--libd-ink);
        }

        .libd-progress-bar {
            height: 8px;
            border-radius: 999px;
            background: #e5f3f1;
            overflow: hidden;
        }

        html.app-skin-dark .libd-progress-bar {
            background: #263449;
        }

        .libd-progress-bar > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #0f766e 0%, #19a79c 100%);
            width: {{ $readinessRate }}%;
        }

        .libd-quick {
            display: grid;
            gap: 8px;
        }

        .libd-quick-item {
            border: 1px solid var(--libd-border);
            border-radius: 12px;
            min-height: 46px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #1f4f52;
            font-size: 0.83rem;
            font-weight: 700;
            transition: all 0.18s ease;
            background: #f8fcfb;
        }

        html.app-skin-dark .libd-quick-item {
            color: #d6e3f5;
            background: #1a2737;
        }

        .libd-quick-item:hover {
            border-color: #bfe7e1;
            transform: translateY(-1px);
            color: #0f766e;
        }

        @media (max-width: 767.98px) {
            .libd-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .libd-actions {
                width: 100%;
            }

            .libd-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="libd-page">
        <section class="libd-hero">
            <div class="libd-hero-content">
                <div>
                    <p class="libd-kicker">Library Workspace</p>
                    <h1 class="libd-title">Librarian Dashboard</h1>
                    <p class="libd-subtitle">
                        Validate defended theses, verify final versions, and keep your public books catalog accurate and publication-ready.
                    </p>
                </div>
                <div class="libd-actions">
                    <a href="{{ route('profile.edit') }}" class="libd-action">
                        <i class="feather-user"></i>
                        Profile
                    </a>
                    <a href="{{ route('library.catalog.index') }}" class="libd-action">
                        <i class="feather-book"></i>
                        Manage Catalog
                    </a>
                </div>
            </div>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="libd-stat">
                    <div>
                        <p class="libd-stat-label">Catalog Ready</p>
                        <h3 class="libd-stat-value">{{ $catalogReadyCount }}</h3>
                        <p class="libd-stat-note">Defended and eligible</p>
                    </div>
                    <span class="libd-stat-icon"><i class="feather-check-circle"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libd-stat">
                    <div>
                        <p class="libd-stat-label">Pending Validation</p>
                        <h3 class="libd-stat-value">{{ $pendingValidationCount }}</h3>
                        <p class="libd-stat-note">Missing requirements</p>
                    </div>
                    <span class="libd-stat-icon"><i class="feather-alert-triangle"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libd-stat">
                    <div>
                        <p class="libd-stat-label">Published Books</p>
                        <h3 class="libd-stat-value">{{ $publishedCount }}</h3>
                        <p class="libd-stat-note">Visible in public portal</p>
                    </div>
                    <span class="libd-stat-icon"><i class="feather-globe"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libd-stat">
                    <div>
                        <p class="libd-stat-label">Validated Theses</p>
                        <h3 class="libd-stat-value">{{ $totalCatalogCount }}</h3>
                        <p class="libd-stat-note">Completed catalog records</p>
                    </div>
                    <span class="libd-stat-icon"><i class="feather-archive"></i></span>
                </article>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="libd-panel">
                    <div class="libd-panel-head">
                        <h3 class="libd-panel-title">Catalog Processing Queue</h3>
                        <span class="libd-panel-sub">{{ $catalogQueue->count() }} recent entries</span>
                    </div>

                    <div class="libd-table-wrap">
                        <table class="libd-table">
                            <thead>
                                <tr>
                                    <th>Thesis</th>
                                    <th>Student</th>
                                    <th>Supervisor</th>
                                    <th>Defense</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($catalogQueue as $thesis)
                                    @php
                                        $isDefenseComplete = $thesis->defense && $thesis->defense->status === 'completed';
                                        $isValidated = (bool) $thesis->is_library_approved;
                                        $isPublished = (bool) $thesis->is_public;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="libd-title-clip" title="{{ $thesis->title }}">
                                                {{ \Illuminate\Support\Str::limit($thesis->title, 60) }}
                                            </span>
                                        </td>
                                        <td>{{ $thesis->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $thesis->supervisor->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="small text-muted">
                                                {{ optional(optional($thesis->defense)->scheduled_at)->format('M d, Y') ?? 'Unscheduled' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($isPublished)
                                                <span class="badge bg-soft-primary text-primary">Public</span>
                                            @elseif($isValidated)
                                                <span class="badge bg-soft-info text-info">Validated</span>
                                            @elseif($isDefenseComplete)
                                                <span class="badge bg-soft-success text-success">Ready to Validate</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Needs Review</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            No completed theses are ready for catalog processing yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="libd-panel mb-4">
                    <div class="libd-panel-head">
                        <h3 class="libd-panel-title">Workflow Guide</h3>
                    </div>

                    <div class="p-3">
                        <div class="libd-progress">
                            <div class="libd-progress-head">
                                <span>Queue Readiness</span>
                                <strong>{{ $readinessRate }}%</strong>
                            </div>
                            <div class="libd-progress-bar"><span></span></div>
                        </div>

                        <div class="d-grid gap-2">
                            <article class="libd-step">
                                <p class="libd-step-kicker">Step 1</p>
                                <p class="libd-step-title">Validate Defense Completion</p>
                                <p class="libd-step-note">Ensure defense session is completed before catalog validation.</p>
                            </article>

                            <article class="libd-step">
                                <p class="libd-step-kicker">Step 2</p>
                                <p class="libd-step-title">Confirm Final Digital Version</p>
                                <p class="libd-step-note">At least one approved thesis version is required for publication.</p>
                            </article>

                            <article class="libd-step">
                                <p class="libd-step-kicker">Step 3</p>
                                <p class="libd-step-title">Publish to Public Portal</p>
                                <p class="libd-step-note">Review title and identities, then publish to books portal.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="libd-panel">
                    <div class="libd-panel-head">
                        <h3 class="libd-panel-title">Quick Links</h3>
                    </div>

                    <div class="p-3">
                        <div class="libd-quick">
                            <a href="{{ route('library.catalog.index') }}" class="libd-quick-item">
                                Catalog Review <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('books.index') }}" class="libd-quick-item" target="_blank" rel="noopener noreferrer">
                                Public Books Portal <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('dashboard') }}" class="libd-quick-item">
                                Dashboard Home <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="libd-quick-item">
                                Profile Settings <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
