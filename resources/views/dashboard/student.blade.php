<x-app-layout>
    @php
        $student = auth()->user()->student;
        $thesis = $student ? $student->accessibleThesis() : null;
        $proposals = $thesis ? $thesis->proposals()->latest()->take(6)->get() : collect();
        $latestProposal = $proposals->first();
        $versions = $thesis ? $thesis->versions()->latest('version_number')->take(8)->get() : collect();
        $latestVersion = $versions->first();
        $versionCount = $versions->count();
        $approvedVersionCount = $versions->where('status', 'approved')->count();
        $feedbackCount = $thesis ? $thesis->feedbacks()->count() : 0;
        $defense = $thesis ? $thesis->defense : null;
        $isDefenseUpcoming = $defense && $defense->scheduled_at && $defense->scheduled_at->isFuture();

        $proposalApproved = $latestProposal && $latestProposal->status === 'approved';
        $hasVersionWork = $versionCount > 0;
        $defenseReady = $defense && in_array($defense->status, ['scheduled', 'completed'], true);
    @endphp

    <style>
        .sd-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .sd-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sd-stat .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eaf1ff;
            border: 1px solid #d2e1ff;
        }

        .sd-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .sd-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .sd-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #d7e5f6;
            border-radius: 999px;
            background: #f8fbff;
            padding: 4px 10px;
            font-size: 0.74rem;
            font-weight: 700;
            color: #3f5874;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .sd-status-pill.success {
            border-color: #bfead1;
            background: #edfdf3;
            color: #0f7b46;
        }

        .sd-status-pill.warning {
            border-color: #f7dba6;
            background: #fff8e9;
            color: #855800;
        }

        .sd-status-pill.danger {
            border-color: #f7d0cb;
            background: #fff2f0;
            color: #b42318;
        }

        .sd-grid {
            display: grid;
            grid-template-columns: 1.4fr 0.8fr;
            gap: 16px;
        }

        .sd-step-list {
            display: grid;
            gap: 10px;
        }

        .sd-step {
            border: 1px solid #dce7f5;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #fbfdff;
        }

        .sd-step .dot {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d0ddf0;
            color: #6c7f98;
            background: #f3f7fd;
            flex-shrink: 0;
        }

        .sd-step h5 {
            margin: 0;
            font-size: 0.84rem;
            color: #10233e;
            font-weight: 700;
            line-height: 1.2;
        }

        .sd-step p {
            margin: 3px 0 0;
            font-size: 0.76rem;
            color: #66758d;
            line-height: 1.3;
        }

        .sd-step.done {
            border-color: #bfead1;
            background: #f4fcf7;
        }

        .sd-step.done .dot {
            border-color: #98d8b7;
            color: #0f7b46;
            background: #e8f7ef;
        }

        .sd-step.current {
            border-color: #cfddff;
            background: #f3f7ff;
        }

        .sd-step.current .dot {
            border-color: #b9d0ff;
            color: #1d4ed8;
            background: #e8f0ff;
        }

        .sd-table tbody td {
            vertical-align: middle;
        }

        .sd-proposal-title {
            display: block;
            color: #10233e;
            font-weight: 700;
            line-height: 1.25;
            max-width: 360px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sd-proposal-meta {
            display: block;
            margin-top: 3px;
            font-size: 0.76rem;
            color: #66758d;
        }

        .sd-kpi-list {
            display: grid;
            gap: 8px;
        }

        .sd-kpi {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #dce7f5;
            border-radius: 10px;
            padding: 8px 10px;
            background: #fbfdff;
            font-size: 0.79rem;
            color: #4f6380;
        }

        .sd-kpi strong {
            font-size: 0.85rem;
            color: #10233e;
        }

        html.app-skin-dark .sd-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .sd-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .sd-stat .label,
        html.app-skin-dark .sd-step p,
        html.app-skin-dark .sd-proposal-meta,
        html.app-skin-dark .sd-kpi {
            color: #a3b1c4;
        }

        html.app-skin-dark .sd-stat .value,
        html.app-skin-dark .sd-step h5,
        html.app-skin-dark .sd-proposal-title,
        html.app-skin-dark .sd-kpi strong {
            color: #e6edf7;
        }

        html.app-skin-dark .sd-status-pill {
            color: #c8d3e3;
            background: #1a2534;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .sd-status-pill.success {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .sd-status-pill.warning {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .sd-status-pill.danger {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        html.app-skin-dark .sd-step,
        html.app-skin-dark .sd-kpi {
            border-color: rgba(255, 255, 255, 0.14);
            background: #172232;
        }

        html.app-skin-dark .sd-step .dot {
            border-color: rgba(255, 255, 255, 0.17);
            color: #a3b1c4;
            background: #243349;
        }

        html.app-skin-dark .sd-step.done {
            border-color: rgba(90, 175, 133, 0.42);
            background: rgba(35, 115, 73, 0.24);
        }

        html.app-skin-dark .sd-step.current {
            border-color: rgba(110, 154, 242, 0.45);
            background: rgba(48, 88, 168, 0.26);
        }

        @media (max-width: 1199px) {
            .sd-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sd-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .sd-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.student-account-refresh')

    <div class="stu-refresh">
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Thesis Progress Dashboard</h1>
            <p class="ta-page-subtitle">Track proposal decisions, revision health, and defense readiness from one focused workspace.</p>
        </div>
        <div class="ta-page-actions">
            @if(!$thesis)
                <a href="{{ route('proposals.create') }}" class="ta-chip-link ta-primary">
                    <i class="feather-plus"></i>
                    Submit Proposal
                </a>
            @else
                <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link ta-primary">
                    <i class="feather-upload-cloud"></i>
                    Thesis Versions
                </a>
                <a href="{{ route('defense.schedule') }}" class="ta-chip-link">
                    <i class="feather-calendar"></i>
                    Defense Schedule
                </a>
            @endif
        </div>
    </div>

    <section class="sd-stats" aria-label="Student dashboard summary cards">
        <article class="sd-stat">
            <span class="icon"><i class="feather-book-open"></i></span>
            <div>
                <p class="label">Thesis Status</p>
                <p class="value">{{ $thesis ? ucfirst(str_replace('_', ' ', $thesis->status)) : 'Not Started' }}</p>
            </div>
        </article>
        <article class="sd-stat">
            <span class="icon"><i class="feather-user-check"></i></span>
            <div>
                <p class="label">Supervisor</p>
                <p class="value">{{ $thesis && $thesis->supervisor ? $thesis->supervisor->user->name : 'Unassigned' }}</p>
            </div>
        </article>
        <article class="sd-stat">
            <span class="icon"><i class="feather-layers"></i></span>
            <div>
                <p class="label">Versions Uploaded</p>
                <p class="value">{{ number_format($versionCount) }}</p>
            </div>
        </article>
        <article class="sd-stat">
            <span class="icon"><i class="feather-message-square"></i></span>
            <div>
                <p class="label">Feedback Entries</p>
                <p class="value">{{ number_format($feedbackCount) }}</p>
            </div>
        </article>
    </section>

    <div class="sd-grid">
        <div class="d-grid gap-3">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <div>
                        <h3>Progress Path</h3>
                        <span class="text-muted small">Current milestones and completion state</span>
                    </div>
                </div>
                <div class="ta-panel-body">
                    <div class="sd-step-list">
                        <div class="sd-step {{ $latestProposal ? 'done' : 'current' }}">
                            <span class="dot"><i class="feather-file-text"></i></span>
                            <div>
                                <h5>Proposal Submitted</h5>
                                <p>{{ $latestProposal ? 'Submitted on '.$latestProposal->created_at->format('M d, Y') : 'Submit your first proposal to begin the thesis workflow.' }}</p>
                            </div>
                        </div>

                        <div class="sd-step {{ $proposalApproved ? 'done' : ($latestProposal ? 'current' : '') }}">
                            <span class="dot"><i class="feather-check-circle"></i></span>
                            <div>
                                <h5>Proposal Decision</h5>
                                <p>{{ $proposalApproved ? 'Your proposal is approved and thesis execution is active.' : 'Await supervisor decision or required revision feedback.' }}</p>
                            </div>
                        </div>

                        <div class="sd-step {{ $hasVersionWork ? 'done' : ($proposalApproved ? 'current' : '') }}">
                            <span class="dot"><i class="feather-upload-cloud"></i></span>
                            <div>
                                <h5>Version Reviews</h5>
                                <p>{{ $hasVersionWork ? $approvedVersionCount.' approved version(s) so far.' : 'Upload and iterate on thesis unit versions.' }}</p>
                            </div>
                        </div>

                        <div class="sd-step {{ $defenseReady ? 'done' : ($hasVersionWork ? 'current' : '') }}">
                            <span class="dot"><i class="feather-calendar"></i></span>
                            <div>
                                <h5>Defense Session</h5>
                                <p>
                                    @if($defense && $defense->scheduled_at)
                                        Scheduled on {{ $defense->scheduled_at->format('M d, Y h:i A') }}.
                                    @else
                                        Becomes available when supervisor and reviewers mark readiness.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <div>
                        <h3>Recent Proposal Activity</h3>
                        <span class="text-muted small">{{ $proposals->count() }} recent item(s)</span>
                    </div>
                    <a href="{{ route('proposals.index') }}" class="ta-chip-link">View All</a>
                </div>
                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle sd-table">
                        <thead>
                            <tr>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $proposal)
                                @php
                                    $statusClass = match($proposal->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'revision_required' => 'warning',
                                        default => '',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <span class="sd-proposal-title">{{ \Illuminate\Support\Str::limit($proposal->title, 72) }}</span>
                                        <span class="sd-proposal-meta">ID #{{ $proposal->id }}</span>
                                    </td>
                                    <td>
                                        <span class="sd-status-pill {{ $statusClass }}">{{ str_replace('_', ' ', $proposal->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $proposal->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-light-brand">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No proposal history yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-grid gap-3">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Current Snapshot</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="sd-kpi-list">
                        <div class="sd-kpi">
                            <span>Latest Proposal Status</span>
                            <strong>{{ $latestProposal ? ucfirst(str_replace('_', ' ', $latestProposal->status)) : 'None' }}</strong>
                        </div>
                        <div class="sd-kpi">
                            <span>Latest Version</span>
                            <strong>{{ $latestVersion ? '#'.$latestVersion->version_number : 'Not Uploaded' }}</strong>
                        </div>
                        <div class="sd-kpi">
                            <span>Approved Versions</span>
                            <strong>{{ $approvedVersionCount }}</strong>
                        </div>
                        <div class="sd-kpi">
                            <span>Defense Status</span>
                            <strong>
                                @if($defense)
                                    {{ $isDefenseUpcoming ? 'Upcoming' : ucfirst($defense->status) }}
                                @else
                                    Unscheduled
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Quick Links</h3>
                </div>
                <div class="ta-panel-body d-grid gap-2">
                    <a href="{{ route('proposals.index') }}" class="ta-chip-link justify-content-between">
                        My Proposals <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link justify-content-between">
                        Thesis Versions <i class="feather-arrow-right"></i>
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
    </div>
</x-app-layout>
