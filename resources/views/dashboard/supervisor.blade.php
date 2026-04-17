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
        $reviewLoadPct = $groupCount > 0 ? min(100, (int) round(($pendingReviewCount / $groupCount) * 100)) : 0;
        $thesisCoveragePct = $groupCount > 0 ? min(100, (int) round(($groupTheses->count() / $groupCount) * 100)) : 0;
        $defenseReadinessPct = $groupCount > 0 ? min(100, (int) round(($upcomingDefenseCount / $groupCount) * 100)) : 0;
    @endphp

    <style>
        .svd-page {
            --svd-surface: #ffffff;
            --svd-border: #d9e3f2;
            --svd-muted: #63758f;
            --svd-ink: #0f172a;
            --svd-blue: #2563eb;
            --svd-blue-soft: #eaf1ff;
            --svd-success: #16a34a;
            --svd-warning: #d97706;
            --svd-danger: #dc2626;
        }

        html.app-skin-dark .svd-page {
            --svd-surface: #151d28;
            --svd-border: rgba(196, 213, 238, 0.16);
            --svd-muted: #9fb2c9;
            --svd-ink: #e8eef8;
            --svd-blue: #8fb2ff;
            --svd-blue-soft: rgba(143, 178, 255, 0.16);
            --svd-success: #6ad89f;
            --svd-warning: #ffc46a;
            --svd-danger: #ff9f9f;
        }

        .svd-hero {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            border: 1px solid #d7e5ff;
            background: linear-gradient(130deg, #f5f9ff 0%, #edf4ff 58%, #f2f8ff 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            margin-bottom: 16px;
            padding: 20px;
        }

        html.app-skin-dark .svd-hero {
            border-color: #35507a;
            background: linear-gradient(130deg, #1b2739 0%, #162131 58%, #182737 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .svd-hero::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            top: -130px;
            right: -90px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, transparent 74%);
            pointer-events: none;
        }

        .svd-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .svd-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #1d4ed8;
            font-weight: 800;
        }

        html.app-skin-dark .svd-kicker {
            color: #9fbeff;
        }

        .svd-title {
            margin: 0;
            color: var(--svd-ink);
            font-size: clamp(1.35rem, 2.7vw, 1.95rem);
            letter-spacing: -0.02em;
        }

        .svd-subtitle {
            margin: 8px 0 0;
            color: var(--svd-muted);
            font-size: 0.9rem;
            max-width: 780px;
            line-height: 1.6;
        }

        .svd-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .svd-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--svd-border);
            background: var(--svd-surface);
            color: #173762;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .svd-action {
            color: #d4e1f4;
        }

        .svd-action:hover {
            color: #1d4ed8;
            border-color: #bdd2f2;
            transform: translateY(-1px);
        }

        .svd-stat {
            border: 1px solid var(--svd-border);
            border-radius: 16px;
            background: var(--svd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .svd-stat-label {
            margin: 0;
            color: var(--svd-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .svd-stat-value {
            margin: 3px 0 0;
            color: var(--svd-ink);
            font-size: 1.5rem;
            line-height: 1;
        }

        .svd-stat-note {
            margin: 6px 0 0;
            color: var(--svd-muted);
            font-size: 0.73rem;
        }

        .svd-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: var(--svd-blue-soft);
            border: 1px solid #cfddf8;
        }

        html.app-skin-dark .svd-stat-icon {
            border-color: #35507a;
            color: #acc6ff;
        }

        .svd-panel {
            border: 1px solid var(--svd-border);
            border-radius: 18px;
            background: var(--svd-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .svd-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--svd-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
        }

        html.app-skin-dark .svd-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .svd-panel-title {
            margin: 0;
            color: var(--svd-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .svd-link {
            color: #1d4ed8;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
        }

        .svd-link:hover {
            text-decoration: underline;
        }

        .svd-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .svd-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .svd-table th {
            background: #f8fbff;
            color: var(--svd-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.67rem;
            font-weight: 800;
            border-bottom: 1px solid var(--svd-border);
            padding: 11px 14px;
            white-space: nowrap;
        }

        html.app-skin-dark .svd-table th {
            background: #192433;
        }

        .svd-table td {
            border-bottom: 1px solid var(--svd-border);
            padding: 12px 14px;
            vertical-align: middle;
        }

        .svd-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .svd-table tbody tr:hover td {
            background: #f8fbff;
        }

        html.app-skin-dark .svd-table tbody tr:hover td {
            background: #1a2737;
        }

        .svd-group-name {
            margin: 0;
            color: var(--svd-ink);
            font-size: 0.87rem;
            font-weight: 700;
        }

        .svd-subtext {
            margin: 2px 0 0;
            color: var(--svd-muted);
            font-size: 0.76rem;
        }

        .svd-title-clip {
            max-width: 240px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #1f3f69;
            font-size: 0.83rem;
            font-weight: 600;
        }

        html.app-skin-dark .svd-title-clip {
            color: #d4e2f6;
        }

        .svd-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.66rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 800;
            border: 1px solid transparent;
        }

        .svd-status.is-success {
            color: #126d39;
            background: #ddf6e8;
            border-color: #b9e8cf;
        }

        .svd-status.is-warning {
            color: #92400e;
            background: #fff2dd;
            border-color: #ffdca7;
        }

        .svd-status.is-danger {
            color: #9f1d1d;
            background: #ffe8e6;
            border-color: #ffc2bd;
        }

        .svd-status.is-muted {
            color: #475569;
            background: #edf2f9;
            border-color: #d9e2ef;
        }

        html.app-skin-dark .svd-status.is-success {
            color: #96ebbb;
            background: rgba(26, 103, 63, 0.35);
            border-color: rgba(101, 214, 155, 0.45);
        }

        html.app-skin-dark .svd-status.is-warning {
            color: #ffdca1;
            background: rgba(132, 82, 20, 0.35);
            border-color: rgba(255, 196, 106, 0.45);
        }

        html.app-skin-dark .svd-status.is-danger {
            color: #ffc5c5;
            background: rgba(129, 44, 44, 0.35);
            border-color: rgba(255, 159, 159, 0.45);
        }

        html.app-skin-dark .svd-status.is-muted {
            color: #c8d8ee;
            background: rgba(77, 101, 133, 0.25);
            border-color: rgba(145, 174, 213, 0.35);
        }

        .svd-meter-row + .svd-meter-row {
            margin-top: 11px;
        }

        .svd-meter-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
            font-size: 0.78rem;
            color: var(--svd-muted);
        }

        .svd-meter-head strong {
            color: var(--svd-ink);
            font-size: 0.8rem;
        }

        .svd-meter {
            height: 8px;
            border-radius: 999px;
            background: #edf3fd;
            overflow: hidden;
        }

        html.app-skin-dark .svd-meter {
            background: #263449;
        }

        .svd-meter > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #2563eb 0%, #5b8cff 100%);
        }

        .svd-quick {
            display: grid;
            gap: 8px;
        }

        .svd-quick-item {
            border: 1px solid var(--svd-border);
            border-radius: 12px;
            min-height: 46px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #214068;
            font-size: 0.83rem;
            font-weight: 700;
            transition: all 0.18s ease;
            background: #f8fbff;
        }

        html.app-skin-dark .svd-quick-item {
            color: #d6e3f5;
            background: #1a2737;
        }

        .svd-quick-item:hover {
            border-color: #bfd2ef;
            transform: translateY(-1px);
            color: #1d4ed8;
        }

        @media (max-width: 767.98px) {
            .svd-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .svd-actions {
                width: 100%;
            }

            .svd-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    @include('partials.supervisor-account-refresh')

    <div class="sup-refresh svd-page">
        <div class="ta-page-head">
            <div>
                <span class="ta-page-kicker">Supervisor Workspace</span>
                <h1 class="ta-page-title">Supervision Command Center</h1>
                <p class="ta-page-subtitle">Track every assigned group, keep proposal reviews flowing, and proactively monitor readiness for defense.</p>
            </div>
            <div class="ta-page-actions">
                <a href="{{ route('supervisor.students.index') }}" class="ta-chip-link">
                    <i class="feather-users"></i>
                    Open My Students
                </a>
                <a href="{{ route('defense.schedule') }}" class="ta-chip-link">
                    <i class="feather-calendar"></i>
                    Defense Calendar
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="svd-stat">
                    <div>
                        <p class="svd-stat-label">Assigned Groups</p>
                        <h3 class="svd-stat-value">{{ $groupCount }}</h3>
                        <p class="svd-stat-note">{{ $groupsWaitingForProposal }} waiting to submit proposal</p>
                    </div>
                    <span class="svd-stat-icon"><i class="feather-users"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="svd-stat">
                    <div>
                        <p class="svd-stat-label">Supervised Students</p>
                        <h3 class="svd-stat-value">{{ $supervisedStudentCount }}</h3>
                        <p class="svd-stat-note">Across active and new groups</p>
                    </div>
                    <span class="svd-stat-icon"><i class="feather-user-check"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="svd-stat">
                    <div>
                        <p class="svd-stat-label">Active Group Theses</p>
                        <h3 class="svd-stat-value">{{ $activeGroupCount }}</h3>
                        <p class="svd-stat-note">In proposal-approved or progress stages</p>
                    </div>
                    <span class="svd-stat-icon"><i class="feather-activity"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="svd-stat">
                    <div>
                        <p class="svd-stat-label">Upcoming Defenses</p>
                        <h3 class="svd-stat-value">{{ $upcomingDefenseCount }}</h3>
                        <p class="svd-stat-note">Scheduled from now onward</p>
                    </div>
                    <span class="svd-stat-icon"><i class="feather-calendar"></i></span>
                </article>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="svd-panel ta-panel">
                    <div class="svd-panel-head ta-panel-head">
                        <h3 class="svd-panel-title">Group Thesis Queue</h3>
                        <a href="{{ route('supervisor.students.index') }}" class="svd-link">View full supervision list</a>
                    </div>
                    <div class="svd-table-wrap">
                        <table class="svd-table">
                            <thead>
                                <tr>
                                    <th>Group</th>
                                    <th>Members</th>
                                    <th>Shared Thesis</th>
                                    <th>Proposal</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    @php
                                        $groupThesis = $groupTheses->get($group->id);
                                        $latestProposal = $groupThesis?->proposals?->first();
                                        $statusTone = $groupThesis
                                            ? match($groupThesis->status) {
                                                'completed' => 'is-success',
                                                'rejected' => 'is-danger',
                                                default => 'is-warning',
                                            }
                                            : 'is-muted';
                                        $memberNames = $group->students->pluck('user.name')->filter()->values();
                                    @endphp
                                    <tr>
                                        <td>
                                            <p class="svd-group-name">{{ $group->name }}</p>
                                            <p class="svd-subtext">{{ $group->department?->code ?? 'No Department' }}</p>
                                        </td>
                                        <td>
                                            <p class="svd-group-name mb-0">{{ $group->students_count }}</p>
                                            <p class="svd-subtext" title="{{ $memberNames->implode(', ') }}">
                                                {{ $memberNames->take(2)->implode(', ') }}{{ $memberNames->count() > 2 ? ' +' . ($memberNames->count() - 2) : '' }}
                                            </p>
                                        </td>
                                        <td>
                                            @if($groupThesis)
                                                <span class="svd-title-clip" title="{{ $groupThesis->title }}">
                                                    {{ \Illuminate\Support\Str::limit($groupThesis->title, 62) }}
                                                </span>
                                            @else
                                                <span class="svd-subtext">No thesis submitted yet</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="svd-subtext">
                                                {{ $latestProposal ? ucfirst(str_replace('_', ' ', $latestProposal->status)) : 'No proposal yet' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="svd-status {{ $statusTone }}">
                                                {{ $groupThesis ? str_replace('_', ' ', $groupThesis->status) : 'awaiting proposal' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($groupThesis)
                                                <a href="{{ route('supervisor.theses.show', $groupThesis) }}" class="btn btn-sm btn-primary">Manage</a>
                                            @else
                                                <button class="btn btn-sm btn-light" disabled>Waiting</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5" style="color: var(--svd-muted);">No groups are assigned yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="svd-panel ta-panel mb-4">
                    <div class="svd-panel-head ta-panel-head">
                        <h3 class="svd-panel-title">Review Load Snapshot</h3>
                    </div>
                    <div class="p-3">
                        <div class="svd-meter-row">
                            <div class="svd-meter-head">
                                <span>Pending Proposal Reviews</span>
                                <strong>{{ $pendingReviewCount }}</strong>
                            </div>
                            <div class="svd-meter"><span style="width: {{ $reviewLoadPct }}%"></span></div>
                        </div>

                        <div class="svd-meter-row">
                            <div class="svd-meter-head">
                                <span>Groups With Thesis</span>
                                <strong>{{ $groupTheses->count() }} / {{ $groupCount }}</strong>
                            </div>
                            <div class="svd-meter"><span style="width: {{ $thesisCoveragePct }}%"></span></div>
                        </div>

                        <div class="svd-meter-row">
                            <div class="svd-meter-head">
                                <span>Defense Pipeline Readiness</span>
                                <strong>{{ $upcomingDefenseCount }}</strong>
                            </div>
                            <div class="svd-meter"><span style="width: {{ $defenseReadinessPct }}%"></span></div>
                        </div>
                    </div>
                </section>

                <section class="svd-panel ta-panel">
                    <div class="svd-panel-head ta-panel-head">
                        <h3 class="svd-panel-title">Quick Actions</h3>
                    </div>
                    <div class="p-3">
                        <div class="svd-quick">
                            <a href="{{ route('supervisor.students.index') }}" class="svd-quick-item">
                                Manage Students
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('defense.schedule') }}" class="svd-quick-item">
                                Open Defense Schedule
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="svd-quick-item">
                                Update Profile
                                <i class="feather-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
