<x-app-layout>
    @php
        $totalSessions = $sessions->count();
        $submittedCount = $evaluations->count();
        $pendingCount = max($totalSessions - $submittedCount, 0);
        $completionRate = $totalSessions > 0 ? (int) round(($submittedCount / $totalSessions) * 100) : 0;
        $avgScore = $submittedCount > 0 ? round($evaluations->avg('score'), 1) : null;
        $nextSession = $sessions
            ->filter(fn ($session) => optional($session->scheduled_at)->greaterThanOrEqualTo(now()))
            ->sortBy('scheduled_at')
            ->first();
    @endphp

    <style>
        .exq-page {
            --exq-surface: #ffffff;
            --exq-border: #d9e3f2;
            --exq-muted: #64758d;
            --exq-ink: #0f172a;
            --exq-primary: #9a3412;
            --exq-primary-soft: #ffefe8;
        }

        html.app-skin-dark .exq-page {
            --exq-surface: #151d28;
            --exq-border: rgba(196, 213, 238, 0.16);
            --exq-muted: #9fb2c9;
            --exq-ink: #e8eef8;
            --exq-primary: #f7ad94;
            --exq-primary-soft: rgba(247, 173, 148, 0.18);
        }

        .exq-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid #f4cebf;
            border-radius: 22px;
            background: linear-gradient(120deg, #fff6f2 0%, #ffefe8 56%, #fff7f2 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .exq-hero {
            border-color: #4b5f7d;
            background: linear-gradient(120deg, #2c2423 0%, #271f1d 56%, #251d1b 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .exq-hero::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            top: -130px;
            right: -90px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(154, 52, 18, 0.17) 0%, transparent 74%);
            pointer-events: none;
        }

        .exq-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .exq-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9a3412;
            font-weight: 800;
        }

        html.app-skin-dark .exq-kicker {
            color: #ffc9b6;
        }

        .exq-title {
            margin: 0;
            color: var(--exq-ink);
            font-size: clamp(1.3rem, 2.5vw, 1.85rem);
            letter-spacing: -0.02em;
        }

        .exq-subtitle {
            margin: 8px 0 0;
            color: var(--exq-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .exq-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .exq-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--exq-border);
            background: var(--exq-surface);
            color: #66311b;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .exq-action {
            color: #d4e1f4;
        }

        .exq-action:hover {
            color: #9a3412;
            border-color: #eeb7a4;
            transform: translateY(-1px);
        }

        .exq-stat {
            border: 1px solid var(--exq-border);
            border-radius: 16px;
            background: var(--exq-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .exq-stat-label {
            margin: 0;
            color: var(--exq-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .exq-stat-value {
            margin: 3px 0 0;
            color: var(--exq-ink);
            font-size: 1.45rem;
            line-height: 1;
        }

        .exq-stat-note {
            margin: 5px 0 0;
            color: var(--exq-muted);
            font-size: 0.73rem;
        }

        .exq-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--exq-primary);
            background: var(--exq-primary-soft);
            border: 1px solid #efd7cd;
        }

        html.app-skin-dark .exq-stat-icon {
            border-color: #4b5f7d;
            color: #ffc9b6;
        }

        .exq-panel {
            border: 1px solid var(--exq-border);
            border-radius: 18px;
            background: var(--exq-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .exq-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--exq-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fffdfb 0%, #fff8f4 100%);
        }

        html.app-skin-dark .exq-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .exq-panel-title {
            margin: 0;
            color: var(--exq-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .exq-session {
            border: 1px solid var(--exq-border);
            border-radius: 14px;
            padding: 12px;
            background: #fffcf9;
        }

        html.app-skin-dark .exq-session {
            background: #1a2737;
        }

        .exq-session-title {
            margin: 0;
            color: var(--exq-ink);
            font-size: 0.94rem;
            font-weight: 800;
            max-width: 760px;
        }

        .exq-sub {
            margin: 3px 0 0;
            color: var(--exq-muted);
            font-size: 0.76rem;
        }

        .exq-eval {
            margin-top: 10px;
            border: 1px solid var(--exq-border);
            border-radius: 10px;
            padding: 10px;
            background: #ffffff;
        }

        html.app-skin-dark .exq-eval {
            background: #162131;
        }

        .exq-empty {
            text-align: center;
            color: var(--exq-muted);
            padding: 32px 12px;
        }

        @media (max-width: 767.98px) {
            .exq-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .exq-actions {
                width: 100%;
            }

            .exq-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="exq-page">
        <section class="exq-hero">
            <div class="exq-hero-content">
                <div>
                    <p class="exq-kicker">Examiner Reviews</p>
                    <h1 class="exq-title">My Defense Evaluations</h1>
                    <p class="exq-subtitle">Submit or update scores per assigned defense session and keep your evaluation queue up to date.</p>
                </div>
                <div class="exq-actions">
                    <a href="{{ route('dashboard') }}" class="exq-action">
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

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <div class="fw-semibold mb-1">Please fix the following:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="exq-stat">
                    <div>
                        <p class="exq-stat-label">Assigned Sessions</p>
                        <h3 class="exq-stat-value">{{ $totalSessions }}</h3>
                        <p class="exq-stat-note">Current load</p>
                    </div>
                    <span class="exq-stat-icon"><i class="feather-clipboard"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exq-stat">
                    <div>
                        <p class="exq-stat-label">Pending</p>
                        <h3 class="exq-stat-value">{{ $pendingCount }}</h3>
                        <p class="exq-stat-note">Awaiting submission</p>
                    </div>
                    <span class="exq-stat-icon"><i class="feather-clock"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exq-stat">
                    <div>
                        <p class="exq-stat-label">Submitted</p>
                        <h3 class="exq-stat-value">{{ $submittedCount }}</h3>
                        <p class="exq-stat-note">Completion: {{ $completionRate }}%</p>
                    </div>
                    <span class="exq-stat-icon"><i class="feather-check-circle"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exq-stat">
                    <div>
                        <p class="exq-stat-label">Average Score</p>
                        <h3 class="exq-stat-value">{{ $avgScore !== null ? $avgScore : '--' }}</h3>
                        <p class="exq-stat-note">Submitted evaluations</p>
                    </div>
                    <span class="exq-stat-icon"><i class="feather-bar-chart-2"></i></span>
                </article>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="exq-panel">
                    <div class="exq-panel-head">
                        <h3 class="exq-panel-title">Assigned Defense Sessions</h3>
                        <span class="small text-muted">{{ $sessions->count() }} records</span>
                    </div>

                    <div class="p-3 d-grid gap-3">
                        @forelse($sessions as $session)
                            @php
                                $evaluation = $evaluations->get($session->id);
                            @endphp

                            <article class="exq-session">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                    <div>
                                        <h4 class="exq-session-title">{{ $session->thesis->title ?? 'Thesis' }}</h4>
                                        <p class="exq-sub">Student: {{ $session->thesis->student->user->name ?? 'N/A' }}</p>
                                        <p class="exq-sub mb-0">Scheduled: {{ optional($session->scheduled_at)->format('M d, Y h:i A') ?? 'TBD' }} | Location: {{ $session->location ?? 'TBD' }}</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-soft-primary text-primary text-uppercase">{{ $session->status }}</span>
                                        <a href="{{ route('examiner.theses.show', $session->thesis) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="feather-file-text me-1"></i> Thesis
                                        </a>
                                    </div>
                                </div>

                                <div class="exq-eval">
                                    <form method="POST" action="{{ route('examiner.defenses.evaluate', $session) }}" class="row g-3 align-items-end">
                                        @csrf
                                        <div class="col-md-3">
                                            <label class="form-label">Score (0-100)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="score" class="form-control" value="{{ old('score', $evaluation?->score) }}" required>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks...">{{ old('remarks', $evaluation?->remarks) }}</textarea>
                                        </div>
                                        <div class="col-md-2 d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-send me-1"></i>{{ $evaluation ? 'Update' : 'Submit' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="exq-empty">
                                <i class="feather-inbox fs-2 opacity-50 mb-2"></i>
                                <p class="mb-0">No defense sessions assigned yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="exq-panel">
                    <div class="exq-panel-head">
                        <h3 class="exq-panel-title">Next Session</h3>
                    </div>
                    <div class="p-3">
                        @if($nextSession)
                            <p class="text-muted small mb-1">Thesis</p>
                            <p class="fw-semibold mb-2">{{ \Illuminate\Support\Str::limit($nextSession->thesis->title ?? 'Thesis', 78) }}</p>
                            <p class="text-muted small mb-1">Schedule</p>
                            <p class="fw-semibold mb-2">{{ optional($nextSession->scheduled_at)->format('M d, Y h:i A') ?? 'TBD' }}</p>
                            <p class="text-muted small mb-0">Location: {{ $nextSession->location ?? 'TBD' }}</p>
                        @else
                            <p class="text-muted mb-0">No upcoming sessions found in your assigned defenses.</p>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
