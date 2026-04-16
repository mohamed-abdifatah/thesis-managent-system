<x-app-layout>
    @php
        $userId = auth()->id();

        $assignedQuery = \App\Models\DefenseSession::query()
            ->with(['thesis.student.user'])
            ->whereHas('committeeMembers', fn ($query) => $query->where('user_id', $userId));

        $totalAssigned = (clone $assignedQuery)->count();
        $upcomingCount = (clone $assignedQuery)->where('scheduled_at', '>=', now())->count();
        $recentSessions = (clone $assignedQuery)->orderByDesc('scheduled_at')->take(8)->get();
        $nextSession = (clone $assignedQuery)->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->first();

        $evaluations = \App\Models\Evaluation::where('user_id', $userId)->get()->keyBy('defense_session_id');
        $completedCount = $evaluations->count();
        $pendingCount = max($totalAssigned - $completedCount, 0);
        $avgScore = $completedCount > 0 ? round($evaluations->avg('score'), 1) : null;
        $completionRate = $totalAssigned > 0 ? (int) round(($completedCount / $totalAssigned) * 100) : 0;
    @endphp

    <style>
        .exd-page {
            --exd-surface: #ffffff;
            --exd-border: #d9e3f2;
            --exd-muted: #64758d;
            --exd-ink: #0f172a;
            --exd-primary: #b45309;
            --exd-primary-soft: #fff3e3;
        }

        html.app-skin-dark .exd-page {
            --exd-surface: #151d28;
            --exd-border: rgba(196, 213, 238, 0.16);
            --exd-muted: #9fb2c9;
            --exd-ink: #e8eef8;
            --exd-primary: #f8be74;
            --exd-primary-soft: rgba(248, 190, 116, 0.18);
        }

        .exd-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid #f2d7b3;
            border-radius: 22px;
            background: linear-gradient(120deg, #fff8ef 0%, #fef3e2 56%, #fff9ef 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .exd-hero {
            border-color: #4b5f7d;
            background: linear-gradient(120deg, #2d2b25 0%, #2a2721 56%, #25221c 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .exd-hero::before {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            top: -120px;
            right: -90px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(180, 83, 9, 0.18) 0%, transparent 74%);
            pointer-events: none;
        }

        .exd-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .exd-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #b45309;
            font-weight: 800;
        }

        html.app-skin-dark .exd-kicker {
            color: #ffd49f;
        }

        .exd-title {
            margin: 0;
            color: var(--exd-ink);
            font-size: clamp(1.35rem, 2.7vw, 1.95rem);
            letter-spacing: -0.02em;
        }

        .exd-subtitle {
            margin: 8px 0 0;
            color: var(--exd-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .exd-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .exd-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--exd-border);
            background: var(--exd-surface);
            color: #5a3811;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .exd-action {
            color: #d4e1f4;
        }

        .exd-action:hover {
            color: #b45309;
            border-color: #f0ca9a;
            transform: translateY(-1px);
        }

        .exd-stat {
            border: 1px solid var(--exd-border);
            border-radius: 16px;
            background: var(--exd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .exd-stat-label {
            margin: 0;
            color: var(--exd-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .exd-stat-value {
            margin: 3px 0 0;
            color: var(--exd-ink);
            font-size: 1.45rem;
            line-height: 1;
        }

        .exd-stat-note {
            margin: 5px 0 0;
            color: var(--exd-muted);
            font-size: 0.73rem;
        }

        .exd-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--exd-primary);
            background: var(--exd-primary-soft);
            border: 1px solid #f1ddc2;
        }

        html.app-skin-dark .exd-stat-icon {
            border-color: #4b5f7d;
            color: #ffd49f;
        }

        .exd-panel {
            border: 1px solid var(--exd-border);
            border-radius: 18px;
            background: var(--exd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .exd-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--exd-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fffcf8 0%, #fff8ef 100%);
        }

        html.app-skin-dark .exd-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .exd-panel-title {
            margin: 0;
            color: var(--exd-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .exd-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .exd-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .exd-table th {
            background: #fffaf2;
            color: var(--exd-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.67rem;
            font-weight: 800;
            border-bottom: 1px solid var(--exd-border);
            padding: 11px 14px;
            white-space: nowrap;
        }

        html.app-skin-dark .exd-table th {
            background: #192433;
        }

        .exd-table td {
            border-bottom: 1px solid var(--exd-border);
            padding: 12px 14px;
            vertical-align: middle;
        }

        .exd-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .exd-table tbody tr:hover td {
            background: #fffcf8;
        }

        html.app-skin-dark .exd-table tbody tr:hover td {
            background: #1a2737;
        }

        .exd-title-clip {
            max-width: 280px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #6a3f0b;
            font-size: 0.84rem;
            font-weight: 700;
        }

        html.app-skin-dark .exd-title-clip {
            color: #f2e1cc;
        }

        .exd-sub {
            margin: 3px 0 0;
            color: var(--exd-muted);
            font-size: 0.74rem;
        }

        .exd-focus-item {
            border: 1px solid var(--exd-border);
            border-radius: 12px;
            padding: 10px;
            background: #fffcf8;
        }

        html.app-skin-dark .exd-focus-item {
            background: #1a2737;
        }

        .exd-progress {
            height: 8px;
            border-radius: 999px;
            background: #ffe7c7;
            overflow: hidden;
        }

        .exd-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #f59e0b 0%, #b45309 100%);
        }

        html.app-skin-dark .exd-progress {
            background: #334964;
        }

        @media (max-width: 767.98px) {
            .exd-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .exd-actions {
                width: 100%;
            }

            .exd-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="exd-page">
        <section class="exd-hero">
            <div class="exd-hero-content">
                <div>
                    <p class="exd-kicker">Examiner Workspace</p>
                    <h1 class="exd-title">Evaluation Dashboard</h1>
                    <p class="exd-subtitle">Track your assigned defenses, focus on pending evaluations, and complete scoring with a clear workflow.</p>
                </div>
                <div class="exd-actions">
                    <a href="{{ route('examiner.defenses.index') }}" class="exd-action">
                        <i class="feather-check-square"></i>
                        Evaluation Workspace
                    </a>
                    <a href="{{ route('profile.edit') }}" class="exd-action">
                        <i class="feather-user"></i>
                        Profile
                    </a>
                </div>
            </div>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="exd-stat">
                    <div>
                        <p class="exd-stat-label">Assigned Defenses</p>
                        <h3 class="exd-stat-value">{{ $totalAssigned }}</h3>
                        <p class="exd-stat-note">Upcoming: {{ $upcomingCount }}</p>
                    </div>
                    <span class="exd-stat-icon"><i class="feather-clipboard"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="exd-stat">
                    <div>
                        <p class="exd-stat-label">Pending Evaluations</p>
                        <h3 class="exd-stat-value">{{ $pendingCount }}</h3>
                        <p class="exd-stat-note">Requires action</p>
                    </div>
                    <span class="exd-stat-icon"><i class="feather-clock"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="exd-stat">
                    <div>
                        <p class="exd-stat-label">Completed Reviews</p>
                        <h3 class="exd-stat-value">{{ $completedCount }}</h3>
                        <p class="exd-stat-note">Progress: {{ $completionRate }}%</p>
                    </div>
                    <span class="exd-stat-icon"><i class="feather-check-circle"></i></span>
                </article>
            </div>

            <div class="col-sm-6 col-xl-3">
                <article class="exd-stat">
                    <div>
                        <p class="exd-stat-label">Average Score</p>
                        <h3 class="exd-stat-value">{{ $avgScore !== null ? $avgScore : '--' }}</h3>
                        <p class="exd-stat-note">From submitted evaluations</p>
                    </div>
                    <span class="exd-stat-icon"><i class="feather-bar-chart-2"></i></span>
                </article>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="exd-panel">
                    <div class="exd-panel-head">
                        <h3 class="exd-panel-title">Assigned Defense Sessions</h3>
                        <a href="{{ route('examiner.defenses.index') }}" class="btn btn-sm btn-light">Open Full List</a>
                    </div>

                    <div class="exd-table-wrap">
                        <table class="exd-table">
                            <thead>
                                <tr>
                                    <th>Thesis</th>
                                    <th>Student</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSessions as $session)
                                    @php
                                        $evaluation = $evaluations->get($session->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="exd-title-clip" title="{{ $session->thesis->title ?? 'N/A' }}">
                                                {{ \Illuminate\Support\Str::limit($session->thesis->title ?? 'N/A', 62) }}
                                            </span>
                                            <p class="exd-sub">{{ optional($session->scheduled_at)->format('M d, Y') ?? 'Date pending' }}</p>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $session->thesis->student->user->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ optional($session->scheduled_at)->format('M d, Y - h:i A') ?? 'TBD' }}</span>
                                        </td>
                                        <td>
                                            @if($evaluation)
                                                <span class="badge bg-soft-success text-success">Submitted ({{ $evaluation->score }})</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('examiner.theses.show', $session->thesis) }}" class="btn btn-sm btn-outline-secondary">Thesis</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No assigned defense sessions yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="exd-panel mb-4">
                    <div class="exd-panel-head">
                        <h3 class="exd-panel-title">Next Session Focus</h3>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        @if($nextSession)
                            <article class="exd-focus-item">
                                <p class="text-muted small mb-1">Thesis</p>
                                <p class="fw-semibold mb-2">{{ \Illuminate\Support\Str::limit($nextSession->thesis->title ?? 'N/A', 70) }}</p>
                                <p class="text-muted small mb-1">Schedule</p>
                                <p class="fw-semibold mb-0">{{ optional($nextSession->scheduled_at)->format('M d, Y - h:i A') }}</p>
                            </article>
                        @else
                            <p class="text-muted mb-0">No upcoming sessions scheduled.</p>
                        @endif

                        <article class="exd-focus-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Evaluation Completion</span>
                                <span class="fw-semibold small">{{ $completionRate }}%</span>
                            </div>
                            <div class="exd-progress mb-2"><span style="width: {{ $completionRate }}%;"></span></div>
                            <p class="small text-muted mb-0">{{ $completedCount }} completed out of {{ $totalAssigned }} assigned defenses.</p>
                        </article>
                    </div>
                </section>

                <section class="exd-panel">
                    <div class="exd-panel-head">
                        <h3 class="exd-panel-title">Quick Links</h3>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        <a href="{{ route('examiner.defenses.index') }}" class="btn btn-light text-start">Go to Evaluation Workspace</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-light text-start">Update Profile Settings</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
