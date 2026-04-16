<x-app-layout>
    @php
        $usersCount = \App\Models\User::count();
        $thesesTotal = \App\Models\Thesis::count();
        $thesesActive = \App\Models\Thesis::where('status', 'in_progress')->count();
        $proposalsCount = \App\Models\Proposal::count();
        $thesesCompleted = \App\Models\Thesis::where('status', 'completed')->count();
        $thesesPending = \App\Models\Thesis::whereIn('status', ['proposal_pending', 'pending'])->count();
        $thesesRejected = \App\Models\Thesis::where('status', 'rejected')->count();
        $upcomingDefenses = \App\Models\DefenseSession::where('scheduled_at', '>=', now())->count();

        $completionRate = $thesesTotal > 0 ? round(($thesesCompleted / $thesesTotal) * 100) : 0;
        $activeRate = $thesesTotal > 0 ? round(($thesesActive / $thesesTotal) * 100) : 0;
        $pendingRate = $thesesTotal > 0 ? round(($thesesPending / $thesesTotal) * 100) : 0;
        $throughputRate = $thesesTotal > 0 ? round((($thesesCompleted + $thesesActive) / $thesesTotal) * 100) : 0;

        $recentTheses = \App\Models\Thesis::with(['student.user', 'supervisor.user'])->latest()->take(7)->get();

        $nextDefense = \App\Models\DefenseSession::query()
            ->with(['thesis.student.user'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->first();
    @endphp

    <style>
        .ta-admin {
            --ta-bg-panel: #ffffff;
            --ta-line: rgba(27, 34, 48, 0.14);
            --ta-ink: #1b2230;
            --ta-muted: #63758c;
            --ta-accent: #d94f20;
            --ta-accent-strong: #b63b14;
            --ta-teal: #0f8a96;
            --ta-soft: #f7efe4;
            --ta-shadow: 0 14px 30px rgba(20, 31, 49, 0.08);
            color: var(--ta-ink);
        }

        .ta-admin .ta-page-head {
            margin-bottom: 14px;
        }

        .ta-admin .ta-page-title {
            font-size: clamp(1.58rem, 2.3vw, 2.1rem);
            letter-spacing: -0.03em;
        }

        .ta-admin .ta-page-kicker {
            color: var(--ta-teal);
        }

        .ta-admin .ta-page-subtitle {
            max-width: 760px;
            font-size: 0.9rem;
            line-height: 1.72;
        }

        .ta-admin .ta-chip-link {
            border-radius: 999px;
            min-height: 40px;
            padding: 9px 15px;
            font-weight: 800;
        }

        .ta-admin .ta-chip-link.ta-primary {
            color: #ffffff;
            border-color: transparent;
            background: linear-gradient(145deg, var(--ta-accent), var(--ta-accent-strong));
            box-shadow: 0 14px 26px rgba(217, 79, 32, 0.28);
        }

        .ta-admin .ta-chip-link.ta-primary:hover {
            color: #ffffff;
            box-shadow: 0 18px 30px rgba(217, 79, 32, 0.33);
        }

        .ta-admin .ta-hero {
            border: 1px solid var(--ta-line);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(255, 248, 236, 0.95) 0%, rgba(244, 252, 255, 0.95) 100%);
            box-shadow: var(--ta-shadow);
            padding: clamp(18px, 3vw, 28px);
            display: grid;
            grid-template-columns: minmax(0, 1.12fr) minmax(0, 0.88fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .ta-admin .ta-hero-kicker {
            margin: 0;
            color: var(--ta-teal);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
            font-size: 0.72rem;
        }

        .ta-admin .ta-hero-title {
            margin: 8px 0 0;
            font-size: clamp(1.45rem, 2.2vw, 2rem);
            letter-spacing: -0.03em;
            line-height: 1.15;
        }

        .ta-admin .ta-hero-copy {
            margin: 10px 0 0;
            color: var(--ta-muted);
            max-width: 640px;
            font-size: 0.9rem;
            line-height: 1.7;
        }

        .ta-admin .ta-hero-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            align-self: center;
        }

        .ta-admin .ta-snapshot {
            border: 1px solid var(--ta-line);
            border-radius: 14px;
            background: var(--ta-bg-panel);
            padding: 12px;
        }

        .ta-admin .ta-snapshot strong {
            display: block;
            font-size: 1.28rem;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .ta-admin .ta-snapshot span {
            display: block;
            margin-top: 4px;
            color: var(--ta-muted);
            font-size: 0.75rem;
        }

        .ta-admin .ta-metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .ta-admin .ta-metric {
            border: 1px solid var(--ta-line);
            border-radius: 18px;
            background: var(--ta-bg-panel);
            box-shadow: var(--ta-shadow);
            padding: 14px;
            display: grid;
            gap: 10px;
        }

        .ta-admin .ta-metric-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .ta-admin .ta-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 138, 150, 0.14);
            color: #0a6c75;
            font-size: 1rem;
        }

        .ta-admin .ta-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 0.71rem;
            font-weight: 800;
            color: #155b94;
            background: #e8f2ff;
        }

        .ta-admin .ta-label {
            margin: 0;
            font-size: 0.78rem;
            color: var(--ta-muted);
            font-weight: 700;
        }

        .ta-admin .ta-value {
            margin: 0;
            font-size: 1.6rem;
            line-height: 1;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .ta-admin .ta-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ta-admin .ta-mini-link {
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 800;
            color: #1f58d8;
        }

        .ta-admin .ta-mini-link:hover {
            text-decoration: underline;
        }

        .ta-admin .ta-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
            gap: 12px;
        }

        .ta-admin .ta-stack {
            display: grid;
            gap: 12px;
        }

        .ta-admin .ta-panel {
            border: 1px solid var(--ta-line);
            border-radius: 18px;
            background: var(--ta-bg-panel);
            box-shadow: var(--ta-shadow);
            overflow: hidden;
        }

        .ta-admin .ta-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--ta-line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ta-admin .ta-panel-head h3 {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .ta-admin .ta-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .ta-admin .ta-table th,
        .ta-admin .ta-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--ta-line);
            font-size: 0.84rem;
            vertical-align: middle;
        }

        .ta-admin .ta-table th {
            font-size: 0.71rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ta-muted);
            font-weight: 700;
            background: rgba(252, 248, 241, 0.85);
        }

        .ta-admin .ta-table tr:last-child td {
            border-bottom: 0;
        }

        .ta-admin .ta-ellipsis {
            display: inline-block;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 700;
        }

        .ta-admin .ta-user {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .ta-admin .ta-avatar {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #efe6da;
            color: #8f3f20;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .ta-admin .ta-status {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .ta-admin .ta-status.pending {
            color: #9a6500;
            background: #fff3da;
        }

        .ta-admin .ta-status.progress {
            color: #1650c9;
            background: #e8efff;
        }

        .ta-admin .ta-status.completed {
            color: #027a48;
            background: #e8f8ef;
        }

        .ta-admin .ta-status.rejected {
            color: #b42318;
            background: #fee4e2;
        }

        .ta-admin .ta-panel-body {
            padding: 14px 16px;
        }

        .ta-admin .ta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            color: #44556d;
            font-size: 0.83rem;
        }

        .ta-admin .ta-row:last-child {
            margin-bottom: 0;
        }

        .ta-admin .ta-row strong {
            color: #1e2f47;
            font-size: 0.85rem;
        }

        .ta-admin .ta-progress {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            overflow: hidden;
            background: #ebf1fb;
            margin-bottom: 12px;
        }

        .ta-admin .ta-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(145deg, var(--ta-accent), var(--ta-accent-strong));
        }

        .ta-admin .ta-action-list {
            display: grid;
            gap: 8px;
        }

        .ta-admin .ta-action {
            text-decoration: none;
            border: 1px solid var(--ta-line);
            border-radius: 12px;
            padding: 10px 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #1f314c;
            background: #ffffff;
            font-size: 0.82rem;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .ta-admin .ta-action:hover {
            border-color: #b9cbe7;
            color: #1f58d8;
            transform: translateY(-1px);
        }

        .ta-admin .ta-callout {
            margin-top: 10px;
            border-radius: 12px;
            border: 1px solid var(--ta-line);
            background: linear-gradient(145deg, rgba(249, 242, 230, 0.95) 0%, rgba(236, 247, 250, 0.95) 100%);
            padding: 12px;
            color: #4c5d73;
            font-size: 0.82rem;
            line-height: 1.62;
        }

        html.app-skin-dark .ta-admin {
            --ta-bg-panel: #151e2b;
            --ta-line: rgba(224, 235, 250, 0.14);
            --ta-ink: #e8eef8;
            --ta-muted: #9eb0c8;
            --ta-soft: rgba(255, 141, 95, 0.1);
            --ta-shadow: 0 16px 32px rgba(0, 0, 0, 0.32);
        }

        html.app-skin-dark .ta-admin .ta-hero {
            background: linear-gradient(145deg, rgba(35, 48, 68, 0.95) 0%, rgba(26, 39, 59, 0.95) 100%);
        }

        html.app-skin-dark .ta-admin .ta-snapshot,
        html.app-skin-dark .ta-admin .ta-action {
            background: rgba(19, 30, 45, 0.88);
        }

        html.app-skin-dark .ta-admin .ta-icon {
            background: rgba(114, 217, 227, 0.14);
            color: #9ae6ef;
        }

        html.app-skin-dark .ta-admin .ta-pill {
            background: rgba(114, 170, 255, 0.2);
            color: #bfd8ff;
        }

        html.app-skin-dark .ta-admin .ta-table th {
            background: rgba(22, 34, 51, 0.9);
        }

        html.app-skin-dark .ta-admin .ta-avatar {
            background: rgba(255, 141, 95, 0.2);
            color: #ffd4c4;
        }

        html.app-skin-dark .ta-admin .ta-row strong {
            color: #dce9fb;
        }

        html.app-skin-dark .ta-admin .ta-progress {
            background: rgba(255, 255, 255, 0.12);
        }

        html.app-skin-dark .ta-admin .ta-callout {
            background: linear-gradient(145deg, rgba(36, 50, 74, 0.9) 0%, rgba(22, 35, 53, 0.92) 100%);
            color: #bcd0ea;
        }

        @media (max-width: 1199px) {
            .ta-admin .ta-hero,
            .ta-admin .ta-grid {
                grid-template-columns: 1fr;
            }

            .ta-admin .ta-metric-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .ta-admin .ta-metric-grid,
            .ta-admin .ta-hero-grid {
                grid-template-columns: 1fr;
            }

            .ta-admin .ta-panel-head,
            .ta-admin .ta-panel-body,
            .ta-admin .ta-table th,
            .ta-admin .ta-table td {
                padding-left: 12px;
                padding-right: 12px;
            }
        }
    </style>

    <div class="ta-admin">
        <div class="ta-page-head">
            <div>
                <span class="ta-page-kicker">Admin Workspace</span>
                <h1 class="ta-page-title">Operations Dashboard</h1>
                <p class="ta-page-subtitle">Monitor the thesis lifecycle from proposal intake to defense scheduling and publication readiness with one user-friendly command center.</p>
            </div>
            <div class="ta-page-actions">
                <a href="{{ route('admin.users.index') }}" class="ta-chip-link ta-primary">
                    <i class="feather-users"></i>
                    Manage Users
                </a>
                <a href="{{ route('admin.theses.index') }}" class="ta-chip-link">
                    <i class="feather-book-open"></i>
                    Thesis Queue
                </a>
                <a href="{{ route('admin.defenses.index') }}" class="ta-chip-link">
                    <i class="feather-calendar"></i>
                    Defenses
                </a>
            </div>
        </div>

        <section class="ta-hero" aria-label="Dashboard summary">
            <div>
                <p class="ta-hero-kicker">Control Center</p>
                <h2 class="ta-hero-title">Keep proposal, supervision, and defense workflows in sync.</h2>
                <p class="ta-hero-copy">You currently manage {{ number_format($usersCount) }} users, {{ number_format($thesesTotal) }} theses, and {{ number_format($proposalsCount) }} proposals. Use the quick panels to resolve bottlenecks early and keep academic timelines predictable.</p>
            </div>
            <div class="ta-hero-grid">
                <article class="ta-snapshot">
                    <strong>{{ number_format($thesesPending) }}</strong>
                    <span>Theses awaiting review</span>
                </article>
                <article class="ta-snapshot">
                    <strong>{{ number_format($thesesRejected) }}</strong>
                    <span>Rejected theses to resolve</span>
                </article>
                <article class="ta-snapshot">
                    <strong>{{ number_format($upcomingDefenses) }}</strong>
                    <span>Upcoming defense sessions</span>
                </article>
                <article class="ta-snapshot">
                    <strong>{{ $throughputRate }}%</strong>
                    <span>Overall workflow throughput</span>
                </article>
            </div>
        </section>

        <div class="ta-metric-grid">
            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-icon"><i class="feather-users"></i></span>
                    <span class="ta-pill">Live</span>
                </div>
                <p class="ta-label">Total Users</p>
                <p class="ta-value">{{ number_format($usersCount) }}</p>
                <div class="ta-foot">
                    <a href="{{ route('admin.users.index') }}" class="ta-mini-link">Open list</a>
                    <span class="ta-pill">Accounts</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-icon"><i class="feather-activity"></i></span>
                    <span class="ta-pill">{{ $activeRate }}%</span>
                </div>
                <p class="ta-label">Active Theses</p>
                <p class="ta-value">{{ number_format($thesesActive) }}</p>
                <div class="ta-foot">
                    <a href="{{ route('admin.theses.index') }}" class="ta-mini-link">Review queue</a>
                    <span class="ta-pill">In progress</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-icon"><i class="feather-file-text"></i></span>
                    <span class="ta-pill">Tracked</span>
                </div>
                <p class="ta-label">Total Proposals</p>
                <p class="ta-value">{{ number_format($proposalsCount) }}</p>
                <div class="ta-foot">
                    <a href="{{ route('admin.theses.index') }}" class="ta-mini-link">View thesis map</a>
                    <span class="ta-pill">Pipeline</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-icon"><i class="feather-check-circle"></i></span>
                    <span class="ta-pill">{{ $completionRate }}%</span>
                </div>
                <p class="ta-label">Completed Theses</p>
                <p class="ta-value">{{ number_format($thesesCompleted) }}</p>
                <div class="ta-foot">
                    <a href="{{ route('admin.defenses.index') }}" class="ta-mini-link">Defense records</a>
                    <span class="ta-pill">Archive ready</span>
                </div>
            </article>
        </div>

        <div class="ta-grid">
            <section class="ta-panel">
                <header class="ta-panel-head">
                    <h3>Recent Thesis Activity</h3>
                    <a class="ta-mini-link" href="{{ route('admin.theses.index') }}">View all</a>
                </header>

                <div class="table-responsive">
                    <table class="ta-table">
                        <thead>
                            <tr>
                                <th>Thesis</th>
                                <th>Student</th>
                                <th>Supervisor</th>
                                <th>Status</th>
                                <th class="text-end">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTheses as $thesis)
                                @php
                                    $statusClass = match($thesis->status) {
                                        'completed' => 'completed',
                                        'rejected' => 'rejected',
                                        'proposal_pending', 'pending' => 'pending',
                                        default => 'progress',
                                    };
                                    $studentName = $thesis->student?->user?->name ?? 'Unknown student';
                                    $supervisorName = $thesis->supervisor?->user?->name ?? 'Unassigned';
                                @endphp
                                <tr>
                                    <td>
                                        <span class="ta-ellipsis" title="{{ $thesis->title }}">{{ $thesis->title }}</span>
                                    </td>
                                    <td>
                                        <span class="ta-user">
                                            <span class="ta-avatar">{{ strtoupper(substr($studentName, 0, 1)) }}</span>
                                            <span>{{ $studentName }}</span>
                                        </span>
                                    </td>
                                    <td>{{ $supervisorName }}</td>
                                    <td>
                                        <span class="ta-status {{ $statusClass }}">{{ str_replace('_', ' ', $thesis->status) }}</span>
                                    </td>
                                    <td class="text-end text-muted">{{ $thesis->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No thesis records found yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="ta-stack">
                <section class="ta-panel">
                    <header class="ta-panel-head">
                        <h3>Pipeline Health</h3>
                    </header>
                    <div class="ta-panel-body">
                        <div class="ta-row">
                            <span>Completion Ratio</span>
                            <strong>{{ $completionRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $completionRate }}%;"></span></div>

                        <div class="ta-row">
                            <span>Active Workload</span>
                            <strong>{{ $activeRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $activeRate }}%;"></span></div>

                        <div class="ta-row">
                            <span>Pending Review</span>
                            <strong>{{ $pendingRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $pendingRate }}%;"></span></div>

                        <div class="ta-row">
                            <span>Upcoming Defenses</span>
                            <strong>{{ number_format($upcomingDefenses) }}</strong>
                        </div>
                    </div>
                </section>

                <section class="ta-panel">
                    <header class="ta-panel-head">
                        <h3>Quick Actions</h3>
                    </header>
                    <div class="ta-panel-body">
                        <div class="ta-action-list">
                            <a href="{{ route('admin.users.create') }}" class="ta-action">
                                <span><i class="feather-user-plus me-1"></i> Add user account</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('admin.groups.create') }}" class="ta-action">
                                <span><i class="feather-users me-1"></i> Create student group</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('admin.theses.index') }}" class="ta-action">
                                <span><i class="feather-book-open me-1"></i> Review thesis queue</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                            <a href="{{ route('admin.defenses.create') }}" class="ta-action">
                                <span><i class="feather-clock me-1"></i> Schedule defense session</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                        </div>

                        @if($nextDefense)
                            <div class="ta-callout">
                                Next defense: <strong>{{ $nextDefense->thesis?->student?->user?->name ?? 'Unknown student' }}</strong>
                                on {{ optional($nextDefense->scheduled_at)->format('M d, Y - h:i A') ?? 'TBD' }}.
                            </div>
                        @else
                            <div class="ta-callout">
                                No upcoming defenses are scheduled. Use the quick actions panel to add the next defense session.
                            </div>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
