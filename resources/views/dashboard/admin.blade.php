<x-app-layout>
    @php
        $usersCount = \App\Models\User::count();
        $thesesTotal = \App\Models\Thesis::count();
        $thesesActive = \App\Models\Thesis::where('status', 'in_progress')->count();
        $proposalsCount = \App\Models\Proposal::count();
        $thesesCompleted = \App\Models\Thesis::where('status', 'completed')->count();
        $thesesPending = \App\Models\Thesis::whereIn('status', ['proposal_pending', 'pending'])->count();
        $thesesRejected = \App\Models\Thesis::where('status', 'rejected')->count();
        $completionRate = $thesesTotal > 0 ? round(($thesesCompleted / $thesesTotal) * 100) : 0;
        $activeRate = $thesesTotal > 0 ? round(($thesesActive / $thesesTotal) * 100) : 0;
        $pendingRate = $thesesTotal > 0 ? round(($thesesPending / $thesesTotal) * 100) : 0;

        $recentTheses = \App\Models\Thesis::with('student.user')->latest()->take(6)->get();
    @endphp

    <style>
        .ta-admin {
            --ta-panel: #ffffff;
            --ta-border: #dbe4f1;
            --ta-ink: #101828;
            --ta-muted: #66758d;
            --ta-accent: #375dfb;
            --ta-surface: #f4f7fc;
            color: var(--ta-ink);
        }

        .ta-admin .ta-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .ta-admin .ta-overline {
            display: inline-block;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #56708f;
        }

        .ta-admin h1 {
            margin: 0;
            font-size: clamp(1.65rem, 2.2vw, 2.15rem);
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .ta-admin .ta-subhead {
            margin: 10px 0 0;
            color: var(--ta-muted);
            max-width: 660px;
            line-height: 1.6;
            font-size: 0.92rem;
        }

        .ta-admin .ta-header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ta-admin .ta-btn {
            border: 1px solid var(--ta-border);
            background: #fff;
            color: #23344f;
            border-radius: 12px;
            padding: 9px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .ta-admin .ta-btn:hover {
            border-color: #b9cbe7;
            color: #1e2e47;
            transform: translateY(-1px);
        }

        .ta-admin .ta-btn-primary {
            background: linear-gradient(135deg, #375dfb 0%, #1d4ed8 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 14px 24px rgba(29, 78, 216, 0.24);
        }

        .ta-admin .ta-btn-primary:hover {
            color: #fff;
            box-shadow: 0 18px 28px rgba(29, 78, 216, 0.28);
        }

        .ta-admin .ta-metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .ta-admin .ta-metric {
            background: var(--ta-panel);
            border: 1px solid var(--ta-border);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
        }

        .ta-admin .ta-metric-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .ta-admin .ta-metric-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--ta-surface);
            color: #1f3d91;
            font-size: 1.1rem;
        }

        .ta-admin .ta-metric-label {
            margin: 0;
            color: var(--ta-muted);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .ta-admin .ta-metric-value {
            margin: 6px 0 0;
            font-size: 1.8rem;
            letter-spacing: -0.03em;
            font-weight: 800;
            line-height: 1;
        }

        .ta-admin .ta-metric-foot {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ta-admin .ta-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.73rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 999px;
        }

        .ta-admin .ta-tag.ta-up {
            color: #027a48;
            background: #e7f8ef;
        }

        .ta-admin .ta-tag.ta-neutral {
            color: #344054;
            background: #eef3f9;
        }

        .ta-admin .ta-link {
            font-size: 0.78rem;
            text-decoration: none;
            color: #335cff;
            font-weight: 700;
        }

        .ta-admin .ta-link:hover {
            text-decoration: underline;
        }

        .ta-admin .ta-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
            gap: 14px;
        }

        .ta-admin .ta-stack {
            display: grid;
            gap: 14px;
        }

        .ta-admin .ta-panel {
            background: var(--ta-panel);
            border: 1px solid var(--ta-border);
            border-radius: 18px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .ta-admin .ta-panel-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--ta-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ta-admin .ta-panel-head h2,
        .ta-admin .ta-panel-head h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
        }

        .ta-admin .ta-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .ta-admin .ta-table th,
        .ta-admin .ta-table td {
            padding: 13px 18px;
            border-bottom: 1px solid var(--ta-border);
            font-size: 0.84rem;
            vertical-align: middle;
        }

        .ta-admin .ta-table th {
            color: #5d6e86;
            font-size: 0.73rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            background: #f8fbff;
        }

        .ta-admin .ta-table tr:last-child td {
            border-bottom: 0;
        }

        .ta-admin .ta-table-title {
            font-weight: 700;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ta-admin .ta-user-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .ta-admin .ta-avatar {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #e5ecff;
            color: #2641a6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .ta-admin .ta-status {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .ta-admin .ta-status.pending {
            color: #9a6500;
            background: #fff3da;
        }

        .ta-admin .ta-status.progress {
            color: #1649ce;
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
            padding: 18px;
        }

        .ta-admin .ta-kpi-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            color: #44556d;
        }

        .ta-admin .ta-kpi-row:last-child {
            margin-bottom: 0;
        }

        .ta-admin .ta-kpi-row strong {
            color: #152740;
            font-size: 0.87rem;
        }

        .ta-admin .ta-progress {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            overflow: hidden;
            background: #ebf1fb;
            margin-bottom: 14px;
        }

        .ta-admin .ta-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(135deg, #375dfb 0%, #1d4ed8 100%);
        }

        .ta-admin .ta-action-list {
            display: grid;
            gap: 10px;
        }

        .ta-admin .ta-action {
            text-decoration: none;
            border: 1px solid var(--ta-border);
            border-radius: 12px;
            padding: 11px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #1f314c;
            font-size: 0.84rem;
            font-weight: 700;
            transition: all 0.2s ease;
            background: #fff;
        }

        .ta-admin .ta-action:hover {
            border-color: #b9cbe7;
            transform: translateY(-1px);
            color: #1d4ed8;
        }

        .ta-admin .ta-callout {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #d3def2;
            background: #f6f9ff;
            color: #44556d;
            font-size: 0.82rem;
            line-height: 1.55;
        }

        html.app-skin-dark .ta-admin {
            --ta-panel: #151a21;
            --ta-border: rgba(255, 255, 255, 0.09);
            --ta-ink: #ebf2ff;
            --ta-muted: #a6b0bf;
            --ta-surface: #1b212c;
        }

        html.app-skin-dark .ta-admin .ta-btn,
        html.app-skin-dark .ta-admin .ta-action {
            background: #171d27;
            color: #dbe7ff;
        }

        html.app-skin-dark .ta-admin .ta-btn-primary {
            color: #fff;
        }

        html.app-skin-dark .ta-admin .ta-callout,
        html.app-skin-dark .ta-admin .ta-table th {
            background: #1b2431;
            color: #b9c4d5;
        }

        html.app-skin-dark .ta-admin .ta-avatar {
            background: #273759;
            color: #e6edff;
        }

        @media (max-width: 1199px) {
            .ta-admin .ta-metric-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .ta-admin .ta-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .ta-admin .ta-metric-grid {
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
        <div class="ta-header">
            <div>
                <span class="ta-overline">Control Center</span>
                <h1>Admin Dashboard</h1>
                <p class="ta-subhead">Monitor the thesis pipeline from proposal intake to final defense with one unified operations view.</p>
            </div>
            <div class="ta-header-actions">
                <a href="{{ route('admin.users.index') }}" class="ta-btn ta-btn-primary">
                    <i class="feather-users"></i>
                    Manage Users
                </a>
                <a href="{{ route('admin.defenses.index') }}" class="ta-btn">
                    <i class="feather-calendar"></i>
                    Defense Sessions
                </a>
            </div>
        </div>

        <div class="ta-metric-grid">
            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-metric-icon"><i class="feather-users"></i></span>
                    <span class="ta-tag ta-up"><i class="feather-trending-up"></i> Growth</span>
                </div>
                <p class="ta-metric-label">Total Users</p>
                <p class="ta-metric-value">{{ number_format($usersCount) }}</p>
                <div class="ta-metric-foot">
                    <a class="ta-link" href="{{ route('admin.users.index') }}">Open list</a>
                    <span class="ta-tag ta-neutral">Live count</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-metric-icon"><i class="feather-book-open"></i></span>
                    <span class="ta-tag ta-neutral">{{ $activeRate }}%</span>
                </div>
                <p class="ta-metric-label">Active Theses</p>
                <p class="ta-metric-value">{{ number_format($thesesActive) }}</p>
                <div class="ta-metric-foot">
                    <a class="ta-link" href="{{ route('admin.theses.index') }}">Manage theses</a>
                    <span class="ta-tag ta-neutral">In progress</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-metric-icon"><i class="feather-file-text"></i></span>
                    <span class="ta-tag ta-neutral">Submission</span>
                </div>
                <p class="ta-metric-label">Total Proposals</p>
                <p class="ta-metric-value">{{ number_format($proposalsCount) }}</p>
                <div class="ta-metric-foot">
                    <span class="ta-link">Academic intake</span>
                    <span class="ta-tag ta-neutral">Tracked</span>
                </div>
            </article>

            <article class="ta-metric">
                <div class="ta-metric-head">
                    <span class="ta-metric-icon"><i class="feather-check-circle"></i></span>
                    <span class="ta-tag ta-up">{{ $completionRate }}%</span>
                </div>
                <p class="ta-metric-label">Completed Theses</p>
                <p class="ta-metric-value">{{ number_format($thesesCompleted) }}</p>
                <div class="ta-metric-foot">
                    <span class="ta-link">Completion ratio</span>
                    <span class="ta-tag ta-neutral">Archive ready</span>
                </div>
            </article>
        </div>

        <div class="ta-grid">
            <section class="ta-panel">
                <header class="ta-panel-head">
                    <h2>Recent Thesis Activity</h2>
                    <a class="ta-link" href="{{ route('admin.theses.index') }}">View all</a>
                </header>

                <div class="table-responsive">
                    <table class="ta-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Student</th>
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
                                        default => 'progress'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <span class="ta-table-title">{{ $thesis->title }}</span>
                                    </td>
                                    <td>
                                        <span class="ta-user-chip">
                                            <span class="ta-avatar">{{ strtoupper(substr($thesis->student->user->name ?? 'U', 0, 1)) }}</span>
                                            <span>{{ $thesis->student->user->name ?? 'Unknown student' }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="ta-status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</span>
                                    </td>
                                    <td class="text-end text-muted">{{ $thesis->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No thesis records found yet.</td>
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
                        <div class="ta-kpi-row">
                            <span>Completion Ratio</span>
                            <strong>{{ $completionRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $completionRate }}%;"></span></div>

                        <div class="ta-kpi-row">
                            <span>Active Workload</span>
                            <strong>{{ $activeRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $activeRate }}%;"></span></div>

                        <div class="ta-kpi-row">
                            <span>Pending Review</span>
                            <strong>{{ $pendingRate }}%</strong>
                        </div>
                        <div class="ta-progress"><span style="width: {{ $pendingRate }}%;"></span></div>

                        <div class="ta-kpi-row">
                            <span>Rejected Theses</span>
                            <strong>{{ number_format($thesesRejected) }}</strong>
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
                                <span><i class="feather-user-plus me-1"></i> Add new user</span>
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
                                <span><i class="feather-clock me-1"></i> Schedule defense</span>
                                <i class="feather-arrow-right"></i>
                            </a>
                        </div>

                        <div class="ta-callout">
                            Use this panel to keep the academic workflow balanced: assign supervisors early, watch pending reviews, and resolve rejected submissions quickly.
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
