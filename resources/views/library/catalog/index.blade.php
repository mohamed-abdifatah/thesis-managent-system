<x-app-layout>
    <style>
        .libc-page {
            --libc-surface: #ffffff;
            --libc-border: #d9e3f2;
            --libc-muted: #64758d;
            --libc-ink: #0f172a;
            --libc-primary: #0f766e;
            --libc-primary-soft: #dcf6f3;
        }

        html.app-skin-dark .libc-page {
            --libc-surface: #151d28;
            --libc-border: rgba(196, 213, 238, 0.16);
            --libc-muted: #9fb2c9;
            --libc-ink: #e8eef8;
            --libc-primary: #63d6cb;
            --libc-primary-soft: rgba(99, 214, 203, 0.16);
        }

        .libc-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid #caece8;
            border-radius: 22px;
            background: linear-gradient(125deg, #edfbf9 0%, #e3f8f5 52%, #f2faf9 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .libc-hero {
            border-color: #35507a;
            background: linear-gradient(125deg, #1b2d34 0%, #17252e 52%, #1a2932 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .libc-hero::before {
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

        .libc-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .libc-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f766e;
            font-weight: 800;
        }

        html.app-skin-dark .libc-kicker {
            color: #8de4dc;
        }

        .libc-title {
            margin: 0;
            color: var(--libc-ink);
            font-size: clamp(1.35rem, 2.7vw, 1.95rem);
            letter-spacing: -0.02em;
        }

        .libc-subtitle {
            margin: 8px 0 0;
            color: var(--libc-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .libc-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .libc-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--libc-border);
            background: var(--libc-surface);
            color: #1f4f52;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .libc-action {
            color: #d4e1f4;
        }

        .libc-action:hover {
            color: #0f766e;
            border-color: #bfe7e1;
            transform: translateY(-1px);
        }

        .libc-stat {
            border: 1px solid var(--libc-border);
            border-radius: 16px;
            background: var(--libc-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .libc-stat-label {
            margin: 0;
            color: var(--libc-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .libc-stat-value {
            margin: 3px 0 0;
            color: var(--libc-ink);
            font-size: 1.45rem;
            line-height: 1;
        }

        .libc-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--libc-primary);
            background: var(--libc-primary-soft);
            border: 1px solid #c8eae5;
        }

        html.app-skin-dark .libc-stat-icon {
            border-color: #35507a;
            color: #8de4dc;
        }

        .libc-panel {
            border: 1px solid var(--libc-border);
            border-radius: 18px;
            background: var(--libc-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .libc-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--libc-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fbfefd 0%, #f6fbfa 100%);
        }

        html.app-skin-dark .libc-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .libc-panel-title {
            margin: 0;
            color: var(--libc-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .libc-filter-note {
            color: var(--libc-muted);
            font-size: 0.78rem;
            line-height: 1.55;
        }

        .libc-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .libc-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .libc-table th {
            background: #f8fcfb;
            color: var(--libc-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.67rem;
            font-weight: 800;
            border-bottom: 1px solid var(--libc-border);
            padding: 11px 14px;
            white-space: nowrap;
        }

        html.app-skin-dark .libc-table th {
            background: #192433;
        }

        .libc-table td {
            border-bottom: 1px solid var(--libc-border);
            padding: 12px 14px;
            vertical-align: top;
        }

        .libc-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .libc-table tbody tr:hover td {
            background: #f7fcfb;
        }

        html.app-skin-dark .libc-table tbody tr:hover td {
            background: #1a2737;
        }

        .libc-title-clip {
            max-width: 300px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #1f3f69;
            font-size: 0.84rem;
            font-weight: 700;
        }

        html.app-skin-dark .libc-title-clip {
            color: #d4e2f6;
        }

        .libc-owner {
            margin: 0;
            color: var(--libc-ink);
            font-size: 0.83rem;
            font-weight: 700;
        }

        .libc-sub {
            margin: 2px 0 0;
            color: var(--libc-muted);
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .libc-action-stack {
            min-width: 240px;
            display: grid;
            gap: 8px;
        }

        .libc-action-form {
            border: 1px solid var(--libc-border);
            border-radius: 10px;
            padding: 8px;
            background: #f9fdfc;
            display: grid;
            gap: 6px;
        }

        html.app-skin-dark .libc-action-form {
            background: #1a2737;
        }

        .libc-action-note {
            border: 1px dashed var(--libc-border);
            border-radius: 10px;
            padding: 8px 10px;
            background: #f9fdfc;
            color: var(--libc-muted);
            font-size: 0.74rem;
            line-height: 1.45;
            text-align: left;
        }

        html.app-skin-dark .libc-action-note {
            background: #1a2737;
        }

        .libc-events {
            display: grid;
            gap: 8px;
        }

        .libc-event {
            border: 1px solid var(--libc-border);
            border-radius: 12px;
            padding: 10px;
            background: #f9fdfc;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
        }

        html.app-skin-dark .libc-event {
            background: #1a2737;
        }

        .libc-event-title {
            margin: 0;
            color: var(--libc-ink);
            font-size: 0.82rem;
            font-weight: 700;
            max-width: 230px;
        }

        .libc-event-sub {
            margin: 3px 0 0;
            color: var(--libc-muted);
            font-size: 0.73rem;
            line-height: 1.4;
        }

        .libc-pagination {
            border-top: 1px solid var(--libc-border);
            padding: 12px 16px;
        }

        @media (max-width: 991.98px) {
            .libc-action-stack {
                min-width: 200px;
            }
        }

        @media (max-width: 767.98px) {
            .libc-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .libc-actions {
                width: 100%;
            }

            .libc-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="libc-page">
        <section class="libc-hero">
            <div class="libc-hero-content">
                <div>
                    <p class="libc-kicker">Library Operations</p>
                    <h1 class="libc-title">Catalog Review</h1>
                    <p class="libc-subtitle">
                        Validate defended theses and publish stable records using the supervisor-selected Final Thesis unit.
                    </p>
                </div>
                <div class="libc-actions">
                    <a href="{{ route('books.index') }}" class="libc-action" target="_blank" rel="noopener noreferrer">
                        <i class="feather-globe"></i>
                        Public Portal
                    </a>
                    <a href="{{ route('dashboard') }}" class="libc-action">
                        <i class="feather-grid"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </section>

        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="libc-stat">
                    <div>
                        <p class="libc-stat-label">Ready for Validation</p>
                        <h3 class="libc-stat-value">{{ $readyForValidationCount }}</h3>
                    </div>
                    <span class="libc-stat-icon"><i class="feather-check-circle"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libc-stat">
                    <div>
                        <p class="libc-stat-label">Validated</p>
                        <h3 class="libc-stat-value">{{ $validatedCount }}</h3>
                    </div>
                    <span class="libc-stat-icon"><i class="feather-shield"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libc-stat">
                    <div>
                        <p class="libc-stat-label">Published</p>
                        <h3 class="libc-stat-value">{{ $publishedCount }}</h3>
                    </div>
                    <span class="libc-stat-icon"><i class="feather-globe"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="libc-stat">
                    <div>
                        <p class="libc-stat-label">Needs Follow-up</p>
                        <h3 class="libc-stat-value">{{ $pendingIssuesCount }}</h3>
                    </div>
                    <span class="libc-stat-icon"><i class="feather-alert-triangle"></i></span>
                </article>
            </div>
        </div>

        <section class="libc-panel mb-4">
            <div class="libc-panel-head">
                <h3 class="libc-panel-title">Search and Workflow Filters</h3>
            </div>
            <div class="p-3">
                <form method="GET" action="{{ route('library.catalog.index') }}" class="row g-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label small text-muted" for="q">Search</label>
                        <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Title, student, or group" />
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label small text-muted" for="workflow">Workflow</label>
                        <select id="workflow" name="workflow" class="form-select">
                            <option value="all" @selected($workflow === 'all')>All</option>
                            <option value="ready" @selected($workflow === 'ready')>Ready for validation</option>
                            <option value="validated" @selected($workflow === 'validated')>Validated (not public)</option>
                            <option value="published" @selected($workflow === 'published')>Published</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                        <a href="{{ route('library.catalog.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </form>

                <p class="libc-filter-note mt-3 mb-0">
                    Working uploads remain private. Only a selected approved final thesis unit is published to the public portal.
                </p>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="libc-panel">
                    <div class="libc-panel-head">
                        <h3 class="libc-panel-title">Catalog Queue</h3>
                        <span class="small text-muted">{{ $theses->total() }} records</span>
                    </div>

                    <div class="libc-table-wrap">
                        <table class="libc-table">
                            <thead>
                                <tr>
                                    <th>Thesis</th>
                                    <th>Owner</th>
                                    <th>Defense</th>
                                    <th>Approved Versions</th>
                                    <th>Workflow</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($theses as $thesis)
                                    @php
                                        $isDefenseComplete = $thesis->defense && $thesis->defense->status === 'completed';
                                        $hasApprovedVersion = $thesis->approved_versions_count > 0;
                                        $canValidate = !$thesis->is_library_approved && $thesis->status === 'defended' && $isDefenseComplete && $hasApprovedVersion;
                                        $hasLockedFinalVersion = (bool) $thesis->finalThesisVersion;
                                        $canPublish = $thesis->is_library_approved && $thesis->status === 'completed' && !$thesis->is_public && $hasLockedFinalVersion;
                                        $awaitingSupervisorFinal = $thesis->is_library_approved && $thesis->status === 'completed' && !$thesis->is_public && !$hasLockedFinalVersion;
                                        $owner = $thesis->group
                                            ? $thesis->group->name
                                            : ($thesis->student->user->name ?? 'Unknown');
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="libc-title-clip" title="{{ $thesis->title }}">
                                                {{ \Illuminate\Support\Str::limit($thesis->title, 72) }}
                                            </span>
                                            <div class="libc-sub">{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</div>
                                        </td>
                                        <td>
                                            <p class="libc-owner">{{ $owner }}</p>
                                            <p class="libc-sub">Supervisor: {{ $thesis->supervisor->user->name ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            @if($isDefenseComplete)
                                                <span class="badge bg-soft-success text-success">Completed</span>
                                            @elseif($thesis->defense)
                                                <span class="badge bg-soft-warning text-warning">{{ ucfirst($thesis->defense->status) }}</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Not Scheduled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-primary text-primary">{{ $thesis->approved_versions_count }}</span>
                                            <div class="libc-sub">
                                                @if($thesis->finalThesisVersion)
                                                    Final thesis: {{ $thesis->finalThesisVersion->unit_label }} (Supervisor selected)
                                                @else
                                                    Waiting: Supervisor must set Final Thesis Selected
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($thesis->is_public)
                                                <span class="badge bg-soft-primary text-primary">Public</span>
                                            @elseif($thesis->is_library_approved)
                                                <span class="badge bg-soft-info text-info">Validated</span>
                                            @elseif($canValidate)
                                                <span class="badge bg-soft-success text-success">Ready to Validate</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Blocked</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="libc-action-stack">
                                                @if($canValidate)
                                                    <form method="POST" action="{{ route('library.catalog.validate', $thesis) }}" class="libc-action-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <textarea name="catalog_notes" rows="2" class="form-control form-control-sm" placeholder="Validation notes (optional)"></textarea>
                                                        <button type="submit" class="btn btn-sm btn-success">Validate</button>
                                                    </form>
                                                @endif

                                                @if($canPublish)
                                                    <form method="POST" action="{{ route('library.catalog.publish', $thesis) }}" class="libc-action-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="libc-sub">Using supervisor-selected final thesis {{ $thesis->finalThesisVersion->unit_label }}.</div>
                                                        <textarea name="publish_notes" rows="2" class="form-control form-control-sm" placeholder="Publication notes (optional)"></textarea>
                                                        <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                                    </form>
                                                @elseif($awaitingSupervisorFinal)
                                                    <div class="libc-action-note">
                                                        Publish is blocked until the supervisor assigns <strong>Final Thesis Selected</strong>.
                                                    </div>
                                                @endif

                                                @if($thesis->is_public)
                                                    <a href="{{ route('books.show', $thesis) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener noreferrer">View Public Book</a>
                                                    <form method="POST" action="{{ route('library.catalog.unpublish', $thesis) }}" class="libc-action-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <textarea name="unpublish_reason" rows="2" class="form-control form-control-sm" placeholder="Reason for unpublish (required)" required></textarea>
                                                        <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Unpublish this thesis from the public catalog?');">Unpublish</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No defended or completed theses found for catalog operations.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="libc-pagination">
                        {{ $theses->links() }}
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="libc-panel">
                    <div class="libc-panel-head">
                        <h3 class="libc-panel-title">Recent Catalog Activity</h3>
                    </div>

                    <div class="p-3">
                        <div class="libc-events">
                            @forelse($recentEvents as $event)
                                <article class="libc-event">
                                    <div>
                                        <p class="libc-event-title">{{ \Illuminate\Support\Str::limit($event->thesis->title ?? 'Thesis', 48) }}</p>
                                        <p class="libc-event-sub">
                                            {{ ucfirst($event->action) }} by {{ $event->user->name ?? 'System' }}
                                        </p>
                                        @if($event->notes)
                                            <p class="libc-event-sub">{{ \Illuminate\Support\Str::limit($event->notes, 88) }}</p>
                                        @endif
                                    </div>
                                    <span class="small text-muted">{{ $event->created_at->diffForHumans() }}</span>
                                </article>
                            @empty
                                <p class="text-muted mb-0">No activity recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
