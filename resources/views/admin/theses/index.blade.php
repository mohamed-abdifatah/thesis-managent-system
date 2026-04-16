<x-app-layout>
    <style>
        .tt-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .tt-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tt-stat .icon {
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

        .tt-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .tt-stat .value {
            margin: 2px 0 0;
            font-size: 1.35rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .tt-filter-body {
            padding: 14px 16px 16px;
            border-top: 1px solid var(--ta-border);
        }

        .tt-filters {
            display: grid;
            grid-template-columns: minmax(220px, 1.2fr) minmax(170px, 0.7fr) minmax(180px, 0.8fr) minmax(130px, 0.5fr) auto;
            gap: 10px;
            align-items: end;
        }

        .tt-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #627892;
            font-weight: 700;
        }

        .tt-filters .btn {
            white-space: nowrap;
        }

        .tt-table-shell {
            padding: 0 12px 12px;
        }

        .tt-table {
            min-width: 1120px;
            border-collapse: separate !important;
            border-spacing: 0 8px;
        }

        .tt-table thead th {
            background: transparent;
            border: 0;
            font-size: 0.69rem;
            color: #5e728d;
            letter-spacing: 0.08em;
            padding: 0 12px 6px;
            text-transform: uppercase;
        }

        .tt-table tbody td {
            vertical-align: middle;
            padding: 12px;
            border-top: 1px solid #e6edf7;
            border-bottom: 1px solid #e6edf7;
            background: #ffffff;
            box-shadow: 0 7px 18px rgba(15, 23, 42, 0.04);
            transition: all 0.18s ease;
        }

        .tt-row td:first-child {
            border-left: 1px solid #e6edf7;
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
            padding-left: 14px;
        }

        .tt-row td:last-child {
            border-right: 1px solid #e6edf7;
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
            padding-right: 14px;
        }

        .tt-row:hover td {
            background: #fbfdff;
            border-color: #d9e5f5;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
        }

        .tt-topic {
            min-width: 255px;
        }

        .tt-title {
            margin: 0;
            font-weight: 800;
            color: #10233e;
            line-height: 1.3;
            max-width: 390px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.95rem;
        }

        .tt-meta-row {
            margin-top: 7px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tt-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            border: 1px solid #d9e6f7;
            background: #f7faff;
            padding: 3px 9px;
            font-size: 0.73rem;
            color: #536982;
            font-weight: 700;
        }

        .tt-participants {
            min-width: 245px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .tt-person-card {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .tt-avatar {
            width: 34px;
            height: 34px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eaf1ff;
            font-weight: 800;
            flex-shrink: 0;
        }

        .tt-person-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .tt-role-tag {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 2px 7px;
            font-size: 0.64rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 800;
            color: #4e647e;
            background: #f2f6fb;
            border: 1px solid #dbe6f3;
        }

        .tt-person-name {
            color: #10233e;
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .tt-sub {
            color: #66758d;
            font-size: 0.76rem;
            line-height: 1.3;
        }

        .tt-workflow {
            min-width: 200px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .tt-status {
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
        }

        .tt-status.ready,
        .tt-status.completed,
        .tt-status.defended {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .tt-status.pending,
        .tt-status.progress,
        .tt-status.proposal {
            color: #855800;
            background: #fff8e9;
            border-color: #f7dba6;
        }

        .tt-status.rejected {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .tt-status.approved {
            color: #1d4ed8;
            background: #eef4ff;
            border-color: #cfe0ff;
        }

        .tt-status-note {
            color: #657892;
            font-size: 0.77rem;
            line-height: 1.35;
        }

        .tt-records {
            min-width: 170px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .tt-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            border: 1px solid #d6e2f3;
            border-radius: 10px;
            background: #f8fbff;
            padding: 6px 9px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #324a68;
        }

        .tt-chip-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .tt-chip strong {
            color: #10233e;
            font-weight: 800;
        }

        .tt-updated {
            min-width: 145px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .tt-updated strong {
            color: #10233e;
            font-size: 0.79rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .tt-updated span {
            color: #657892;
            font-size: 0.74rem;
            line-height: 1.25;
        }

        .tt-actions {
            display: inline-flex;
            justify-content: flex-end;
            width: 100%;
        }

        .tt-action-btn {
            min-height: 36px;
            padding: 0.4rem 0.75rem;
            border-radius: 10px;
            border: 1px solid #cfe0ff;
            color: #1d4ed8;
            background: #eef4ff;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .tt-action-btn:hover {
            color: #1e40af;
            background: #e3edff;
            border-color: #b9d0ff;
            transform: translateY(-1px);
        }

        .tt-empty {
            text-align: center;
            padding: 38px 16px;
            color: #66758d;
        }

        .tt-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .tt-empty h4 {
            margin: 0;
            font-size: 1.03rem;
            color: #10233e;
        }

        .tt-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        html.app-skin-dark .tt-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .tt-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tt-stat .label,
        html.app-skin-dark .tt-sub,
        html.app-skin-dark .tt-status-note,
        html.app-skin-dark .tt-updated span,
        html.app-skin-dark .tt-empty,
        html.app-skin-dark .tt-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .tt-stat .value,
        html.app-skin-dark .tt-title,
        html.app-skin-dark .tt-person-name,
        html.app-skin-dark .tt-chip strong,
        html.app-skin-dark .tt-updated strong,
        html.app-skin-dark .tt-empty h4 {
            color: #e6edf7;
        }

        html.app-skin-dark .tt-table thead th {
            color: #94a8c2;
        }

        html.app-skin-dark .tt-table tbody td {
            background: #141d2a;
            border-color: rgba(255, 255, 255, 0.11);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        html.app-skin-dark .tt-row:hover td {
            background: #1a2534;
            border-color: rgba(159, 193, 255, 0.35);
        }

        html.app-skin-dark .tt-meta-pill {
            color: #b8c6d8;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.13);
        }

        html.app-skin-dark .tt-role-tag {
            color: #b8c6d8;
            background: #1f2b3b;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tt-avatar {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
        }

        html.app-skin-dark .tt-chip {
            color: #c8d3e3;
            background: #1a2433;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tt-action-btn {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tt-status.ready,
        html.app-skin-dark .tt-status.completed,
        html.app-skin-dark .tt-status.defended {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .tt-status.pending,
        html.app-skin-dark .tt-status.progress,
        html.app-skin-dark .tt-status.proposal {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .tt-status.rejected {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        html.app-skin-dark .tt-status.approved {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        @media (max-width: 1199px) {
            .tt-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tt-filters {
                grid-template-columns: 1fr 1fr;
            }

            .tt-table {
                min-width: 980px;
            }
        }

        @media (max-width: 767px) {
            .tt-stats {
                grid-template-columns: 1fr;
            }

            .tt-filters {
                grid-template-columns: 1fr;
            }

            .tt-table-shell {
                padding: 0 8px 10px;
            }

            .tt-table {
                min-width: 900px;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Thesis Management</h1>
            <p class="ta-page-subtitle">Monitor thesis lifecycle, assign supervisors, and track proposal/version progress.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.defenses.index') }}" class="ta-chip-link">
                <i class="feather-calendar"></i>
                Defense Sessions
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <section class="tt-stats" aria-label="Thesis summary cards">
        <article class="tt-stat">
            <span class="icon"><i class="feather-book-open"></i></span>
            <div>
                <p class="label">Total Theses</p>
                <p class="value">{{ number_format($totalTheses) }}</p>
            </div>
        </article>
        <article class="tt-stat">
            <span class="icon"><i class="feather-user-check"></i></span>
            <div>
                <p class="label">Assigned Supervisor</p>
                <p class="value">{{ number_format($assignedTheses) }}</p>
            </div>
        </article>
        <article class="tt-stat">
            <span class="icon"><i class="feather-flag"></i></span>
            <div>
                <p class="label">Ready/Defended</p>
                <p class="value">{{ number_format($readyDefenseTheses) }}</p>
            </div>
        </article>
        <article class="tt-stat">
            <span class="icon"><i class="feather-check-circle"></i></span>
            <div>
                <p class="label">Completed</p>
                <p class="value">{{ number_format($completedTheses) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel mb-3">
        <div class="ta-panel-head">
            <div>
                <h3>Filter Theses</h3>
                <span class="text-muted small">{{ number_format($filteredCount) }} matching records</span>
            </div>
        </div>
        <div class="tt-filter-body">
            <form method="GET" action="{{ route('admin.theses.index') }}" class="tt-filters">
                <div>
                    <label class="tt-label" for="q">Search</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Title, student, supervisor, status...">
                </div>

                <div>
                    <label class="tt-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach($statusOptions as $statusKey => $statusLabel)
                            <option value="{{ $statusKey }}" {{ $statusFilter === $statusKey ? 'selected' : '' }}>
                                {{ $statusLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="tt-label" for="supervisor_id">Supervisor</label>
                    <select id="supervisor_id" name="supervisor_id" class="form-select">
                        <option value="0">All supervisors</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ $supervisorFilter === $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="tt-label" for="per_page">Rows per page</label>
                    <select id="per_page" name="per_page" class="form-select">
                        <option value="10" {{ $perPage === 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="feather-search me-1"></i>
                        Apply
                    </button>
                    @if($search !== '' || $statusFilter !== '' || $supervisorFilter > 0 || $perPage !== 10)
                        <a href="{{ route('admin.theses.index') }}" class="btn btn-light btn-sm px-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>Theses Directory</h3>
                <span class="text-muted small">{{ $theses->total() }} records across {{ $theses->lastPage() }} pages</span>
            </div>
        </div>

        <div class="ta-table-shell">
            <table class="table table-hover mb-0 tt-table" id="thesisList">
                <thead>
                    <tr>
                        <th>Thesis Topic</th>
                        <th>Participants</th>
                        <th>Workflow</th>
                        <th>Records</th>
                        <th>Last Update</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($theses as $thesis)
                        @php
                            $statusText = str_replace('_', ' ', $thesis->status);
                            $statusClass = match($thesis->status) {
                                'completed' => 'completed',
                                'defended' => 'defended',
                                'ready_for_defense' => 'ready',
                                'proposal_pending' => 'proposal pending',
                                'in_progress' => 'progress',
                                'proposal_approved' => 'approved',
                                'rejected' => 'rejected',
                                default => 'pending',
                            };

                            $statusIcon = match($thesis->status) {
                                'completed' => 'feather-check-circle',
                                'defended' => 'feather-award',
                                'ready_for_defense' => 'feather-flag',
                                'proposal_pending' => 'feather-clock',
                                'in_progress' => 'feather-loader',
                                'proposal_approved' => 'feather-thumbs-up',
                                'rejected' => 'feather-alert-triangle',
                                default => 'feather-info',
                            };

                            $statusNote = match($thesis->status) {
                                'completed' => 'Final version approved and archive-ready.',
                                'defended' => 'Defense completed, awaiting final closure.',
                                'ready_for_defense' => 'Eligible for defense scheduling.',
                                'proposal_pending' => 'Waiting for proposal approval.',
                                'in_progress' => 'Student is actively revising thesis versions.',
                                'proposal_approved' => 'Proposal approved and thesis initiated.',
                                'rejected' => 'Requires resubmission or scope revision.',
                                default => 'Current stage is being tracked.',
                            };
                        @endphp
                        <tr class="tt-row">
                            <td>
                                <div class="tt-topic">
                                    <p class="tt-title" title="{{ $thesis->title }}">{{ $thesis->title }}</p>
                                    <div class="tt-meta-row">
                                        <span class="tt-meta-pill">
                                            <i class="feather-hash"></i>
                                            Thesis {{ $thesis->id }}
                                        </span>
                                        <span class="tt-meta-pill">
                                            <i class="feather-calendar"></i>
                                            {{ $thesis->created_at?->format('M d, Y') ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="tt-participants">
                                    <div class="tt-person-card">
                                        <span class="tt-avatar">{{ strtoupper(substr($thesis->student?->user?->name ?? 'U', 0, 1)) }}</span>
                                        <span class="tt-person-main">
                                            <span class="tt-role-tag">Student</span>
                                            <span class="tt-person-name">{{ $thesis->student?->user?->name ?? 'Unknown Student' }}</span>
                                            <span class="tt-sub">{{ $thesis->student?->student_id_number ?? 'No student id' }}</span>
                                        </span>
                                    </div>

                                    <div class="tt-person-card">
                                        <span class="tt-avatar">{{ strtoupper(substr($thesis->supervisor?->user?->name ?? 'S', 0, 1)) }}</span>
                                        <span class="tt-person-main">
                                            <span class="tt-role-tag">Supervisor</span>
                                            <span class="tt-person-name">{{ $thesis->supervisor?->user?->name ?? 'Unassigned' }}</span>
                                            <span class="tt-sub">{{ $thesis->supervisor?->specialization ?? 'Assign for domain review' }}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="tt-workflow">
                                    <span class="tt-status {{ $statusClass }}">
                                        <i class="{{ $statusIcon }}"></i>
                                        {{ $statusText }}
                                    </span>
                                    <span class="tt-status-note">{{ $statusNote }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="tt-records">
                                    <span class="tt-chip">
                                        <span class="tt-chip-label"><i class="feather-file-text"></i> Proposals</span>
                                        <strong>{{ $thesis->proposals_count }}</strong>
                                    </span>
                                    <span class="tt-chip">
                                        <span class="tt-chip-label"><i class="feather-layers"></i> Versions</span>
                                        <strong>{{ $thesis->versions_count }}</strong>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="tt-updated">
                                    <strong>{{ $thesis->updated_at?->format('M d, Y') ?? '-' }}</strong>
                                    <span>{{ $thesis->updated_at?->format('h:i A') ?? 'No time data' }}</span>
                                    <span>{{ $thesis->updated_at?->diffForHumans() ?? 'No updates yet' }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="tt-actions">
                                    <button
                                        type="button"
                                        class="tt-action-btn"
                                        data-thesis-id="{{ $thesis->id }}"
                                        data-current-supervisor="{{ $thesis->supervisor_id ?? '' }}"
                                        onclick="openAssignModal(this)"
                                        title="{{ $thesis->supervisor ? 'Reassign Supervisor' : 'Assign Supervisor' }}"
                                    >
                                        <i class="feather-user-plus"></i>
                                        {{ $thesis->supervisor ? 'Reassign supervisor' : 'Assign supervisor' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="tt-empty">
                                    <i class="feather-folder-minus"></i>
                                    <h4>No theses found</h4>
                                    <p>Try changing filters or create thesis records through proposal approvals.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2 px-1">
        <span class="text-muted small">
            Showing {{ $theses->firstItem() ?? 0 }} - {{ $theses->lastItem() ?? 0 }} of {{ $theses->total() }} records
        </span>
        {{ $theses->links() }}
    </div>

    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="assign_supervisor_id" class="form-label">Select Supervisor</label>
                            <select name="supervisor_id" id="assign_supervisor_id" class="form-select" required>
                                <option value="" selected disabled>Choose supervisor</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">
                                        {{ $supervisor->user->name }} ({{ $supervisor->specialization ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAssignModal(triggerElement) {
            const thesisId = triggerElement.getAttribute('data-thesis-id');
            const currentSupervisorId = triggerElement.getAttribute('data-current-supervisor');
            const form = document.getElementById('assignForm');
            const select = document.getElementById('assign_supervisor_id');

            form.action = `/admin/theses/${thesisId}/assign`;

            if (currentSupervisorId) {
                select.value = currentSupervisorId;
            } else {
                select.selectedIndex = 0;
            }

            const modalEl = document.getElementById('assignModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    </script>
        </div>
</x-app-layout>
