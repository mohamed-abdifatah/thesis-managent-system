<x-app-layout>
    @php
        $supervisor = auth()->user()->supervisor;
        $groups = $supervisor
            ? $supervisor->groups()->with(['department', 'students.user'])->withCount('students')->orderBy('name')->take(8)->get()
            : collect();

        $groupIds = $groups->pluck('id');
        $groupTheses = collect();
        if ($supervisor && $groupIds->isNotEmpty()) {
            $groupTheses = \App\Models\Thesis::query()
                ->with(['proposals' => fn ($query) => $query->latest()])
                ->where('supervisor_id', $supervisor->id)
                ->whereIn('student_group_id', $groupIds)
                ->latest('id')
                ->get()
                ->groupBy('student_group_id')
                ->map(fn ($thesisCollection) => $thesisCollection->first());
        }

        $groupCount = $groups->count();
        $supervisedStudentCount = $supervisor ? $supervisor->students()->count() : 0;
        $activeGroupCount = $groupTheses->filter(function ($thesis) {
            return in_array($thesis->status, ['proposal_approved', 'in_progress', 'ready_for_defense'], true);
        })->count();

        $pendingReviewCount = $supervisor
            ? \App\Models\Proposal::whereHas('thesis', fn ($query) => $query->where('supervisor_id', $supervisor->id))
                ->whereIn('status', ['pending', 'revision_required'])
                ->count()
            : 0;

        $upcomingDefenseCount = $supervisor
            ? \App\Models\DefenseSession::whereHas('thesis', fn ($query) => $query->where('supervisor_id', $supervisor->id))
                ->where('scheduled_at', '>=', now())
                ->count()
            : 0;

        $groupsWaitingForProposal = max($groupCount - $groupTheses->count(), 0);
    @endphp

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Supervisor Workspace</span>
            <h1 class="ta-page-title">Supervision Dashboard</h1>
            <p class="ta-page-subtitle">Follow your student pipeline, review pending proposals, and keep defense timelines on track.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('supervisor.students.index') }}" class="ta-chip-link">
                <i class="feather-users"></i>
                My Students
            </a>
            <a href="{{ route('defense.schedule') }}" class="ta-chip-link">
                <i class="feather-calendar"></i>
                Defense Schedule
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Assigned Groups</p>
                        <h3 class="mb-0">{{ $groupCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                        <i class="feather-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Supervised Students</p>
                        <h3 class="mb-0">{{ $supervisedStudentCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                        <i class="feather-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Active Group Theses</p>
                        <h3 class="mb-0">{{ $activeGroupCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                        <i class="feather-activity"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Upcoming Defenses</p>
                        <h3 class="mb-0">{{ $upcomingDefenseCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                        <i class="feather-calendar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Group Thesis Queue</h3>
                    <a href="{{ route('supervisor.students.index') }}" class="ta-chip-link">Open Full List</a>
                </div>
                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Members</th>
                                <th>Shared Thesis</th>
                                <th>Latest Proposal</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                                @php
                                    $groupThesis = $groupTheses->get($group->id);
                                    $latestProposal = $groupThesis?->proposals?->first();
                                    $statusClass = $groupThesis
                                        ? match($groupThesis->status) {
                                            'completed' => 'bg-soft-success text-success',
                                            'rejected' => 'bg-soft-danger text-danger',
                                            default => 'bg-soft-warning text-warning',
                                        }
                                        : 'bg-soft-dark text-dark';

                                    $memberNames = $group->students->pluck('user.name')->filter()->values();
                                @endphp
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $group->name }}</span>
                                            <span class="d-block small text-muted">{{ $group->department?->code ?? 'No Department' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $group->students_count }}</span>
                                        <span class="d-block small text-muted text-truncate" style="max-width: 180px;" title="{{ $memberNames->implode(', ') }}">
                                            {{ $memberNames->take(2)->implode(', ') }}{{ $memberNames->count() > 2 ? ' +' . ($memberNames->count() - 2) . ' more' : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($groupThesis)
                                            <span class="d-inline-block text-truncate" style="max-width: 240px;">
                                                {{ \Illuminate\Support\Str::limit($groupThesis->title, 60) }}
                                            </span>
                                        @else
                                            <span class="small text-muted">No thesis submitted yet</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-muted">
                                            {{ $latestProposal ? ucfirst(str_replace('_', ' ', $latestProposal->status)) : 'No proposal yet' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClass }} text-uppercase">
                                            {{ $groupThesis ? str_replace('_', ' ', $groupThesis->status) : 'awaiting proposal' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($groupThesis)
                                            <a href="{{ route('supervisor.theses.show', $groupThesis) }}" class="btn btn-sm btn-primary">Manage</a>
                                        @else
                                            <button class="btn btn-sm btn-light" disabled>Awaiting Proposal</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No groups are assigned yet.</td>
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
                    <h3>Review Priorities</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Pending Proposal Reviews</span>
                        <strong>{{ $pendingReviewCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Groups Waiting For Proposal</span>
                        <strong>{{ $groupsWaitingForProposal }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Upcoming Defenses</span>
                        <strong>{{ $upcomingDefenseCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">Active Group Theses</span>
                        <strong>{{ $activeGroupCount }}</strong>
                    </div>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Quick Links</h3>
                </div>
                <div class="ta-panel-body d-grid gap-2">
                    <a href="{{ route('supervisor.students.index') }}" class="ta-chip-link justify-content-between">
                        Manage Students <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('defense.schedule') }}" class="ta-chip-link justify-content-between">
                        Defense Schedule <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="ta-chip-link justify-content-between">
                        Profile Settings <i class="feather-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
