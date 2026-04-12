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
    @endphp

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Examiner Workspace</span>
            <h1 class="ta-page-title">Evaluation Dashboard</h1>
            <p class="ta-page-subtitle">Review assigned defenses, submit scores, and monitor your evaluation workload.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('examiner.defenses.index') }}" class="ta-chip-link">
                <i class="feather-check-square"></i>
                My Evaluations
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Assigned Defenses</p>
                        <h3 class="mb-0">{{ $totalAssigned }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                        <i class="feather-clipboard"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Pending Evaluations</p>
                        <h3 class="mb-0">{{ $pendingCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                        <i class="feather-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Completed Reviews</p>
                        <h3 class="mb-0">{{ $completedCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                        <i class="feather-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Average Score</p>
                        <h3 class="mb-0">{{ $avgScore !== null ? $avgScore : '--' }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                        <i class="feather-bar-chart-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Assigned Defense Sessions</h3>
                    <a href="{{ route('examiner.defenses.index') }}" class="ta-chip-link">Open Full List</a>
                </div>
                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle">
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
                                        <span class="fw-semibold d-inline-block text-truncate" style="max-width: 260px;">
                                            {{ \Illuminate\Support\Str::limit($session->thesis->title ?? 'N/A', 60) }}
                                        </span>
                                    </td>
                                    <td>{{ $session->thesis->student->user->name ?? 'N/A' }}</td>
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
                                        <a href="{{ route('examiner.defenses.index') }}" class="btn btn-sm btn-light-brand">
                                            <i class="feather-arrow-right"></i>
                                        </a>
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
            </div>
        </div>

        <div class="col-xl-4">
            <div class="ta-panel mb-4">
                <div class="ta-panel-head">
                    <h3>Next Session</h3>
                </div>
                <div class="ta-panel-body">
                    @if($nextSession)
                        <p class="mb-1 text-muted small">Thesis</p>
                        <p class="fw-semibold mb-3">{{ \Illuminate\Support\Str::limit($nextSession->thesis->title ?? 'N/A', 70) }}</p>
                        <p class="mb-1 text-muted small">Date</p>
                        <p class="fw-semibold mb-3">{{ optional($nextSession->scheduled_at)->format('M d, Y - h:i A') }}</p>
                        <p class="mb-0 text-muted small">Upcoming sessions: {{ $upcomingCount }}</p>
                    @else
                        <p class="text-muted mb-0">No upcoming sessions scheduled.</p>
                    @endif
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Quick Links</h3>
                </div>
                <div class="ta-panel-body d-grid gap-2">
                    <a href="{{ route('examiner.defenses.index') }}" class="ta-chip-link justify-content-between">
                        Evaluation Workspace <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="ta-chip-link justify-content-between">
                        Profile Settings <i class="feather-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
