<x-app-layout>
    <style>
        .ds-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .ds-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ds-stat .icon {
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

        .ds-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .ds-stat .value {
            margin: 2px 0 0;
            font-size: 1.35rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .ds-filter-body {
            padding: 14px 16px 16px;
            border-top: 1px solid var(--ta-border);
        }

        .ds-filters {
            display: grid;
            grid-template-columns: minmax(230px, 1.4fr) minmax(180px, 0.8fr) minmax(130px, 0.55fr) auto;
            gap: 10px;
            align-items: end;
        }

        .ds-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #627892;
            font-weight: 700;
        }

        .ds-filters .btn {
            white-space: nowrap;
        }

        .ds-table-shell {
            padding: 0 12px 12px;
        }

        .ds-table {
            min-width: 1080px;
            border-collapse: separate !important;
            border-spacing: 0 8px;
        }

        .ds-table thead th {
            background: transparent;
            border: 0;
            font-size: 0.69rem;
            color: #5e728d;
            letter-spacing: 0.08em;
            padding: 0 12px 6px;
            text-transform: uppercase;
        }

        .ds-table tbody td {
            vertical-align: middle;
            padding: 12px;
            border-top: 1px solid #e6edf7;
            border-bottom: 1px solid #e6edf7;
            background: #ffffff;
            box-shadow: 0 7px 18px rgba(15, 23, 42, 0.04);
            transition: all 0.18s ease;
        }

        .ds-row td:first-child {
            border-left: 1px solid #e6edf7;
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
            padding-left: 14px;
        }

        .ds-row td:last-child {
            border-right: 1px solid #e6edf7;
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
            padding-right: 14px;
        }

        .ds-row:hover td {
            background: #fbfdff;
            border-color: #d9e5f5;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
        }

        .ds-title {
            margin: 0;
            font-size: 0.93rem;
            font-weight: 800;
            line-height: 1.28;
            color: #10233e;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ds-sub {
            margin-top: 4px;
            color: #66758d;
            font-size: 0.78rem;
            line-height: 1.3;
        }

        .ds-student {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .ds-avatar {
            width: 34px;
            height: 34px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eaf1ff;
            font-size: 0.78rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .ds-student-name {
            display: block;
            color: #10233e;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.22;
            max-width: 210px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ds-student-note {
            display: block;
            color: #66758d;
            font-size: 0.74rem;
            line-height: 1.2;
        }

        .ds-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d8e6f8;
            background: #f7faff;
            padding: 4px 9px;
            font-size: 0.73rem;
            font-weight: 700;
            color: #415a76;
        }

        .ds-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
            border: 1px solid transparent;
        }

        .ds-status.scheduled {
            color: #855800;
            background: #fff8e9;
            border-color: #f7dba6;
        }

        .ds-status.completed {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .ds-status.cancelled {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .ds-committee {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 160px;
        }

        .ds-committee-list {
            color: #66758d;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .ds-action {
            min-height: 34px;
            padding: 0.35rem 0.7rem;
            border-radius: 10px;
            border: 1px solid #cfe0ff;
            color: #1d4ed8;
            background: #eef4ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .ds-action:hover {
            color: #1e40af;
            background: #e3edff;
            border-color: #b9d0ff;
            transform: translateY(-1px);
        }

        .ds-empty {
            text-align: center;
            padding: 38px 16px;
            color: #66758d;
        }

        .ds-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .ds-empty h4 {
            margin: 0;
            font-size: 1.03rem;
            color: #10233e;
        }

        .ds-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        html.app-skin-dark .ds-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .ds-stat .icon,
        html.app-skin-dark .ds-avatar {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .ds-stat .label,
        html.app-skin-dark .ds-sub,
        html.app-skin-dark .ds-student-note,
        html.app-skin-dark .ds-committee-list,
        html.app-skin-dark .ds-empty,
        html.app-skin-dark .ds-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .ds-stat .value,
        html.app-skin-dark .ds-title,
        html.app-skin-dark .ds-student-name,
        html.app-skin-dark .ds-empty h4 {
            color: #e6edf7;
        }

        html.app-skin-dark .ds-table thead th {
            color: #94a8c2;
        }

        html.app-skin-dark .ds-table tbody td {
            background: #141d2a;
            border-color: rgba(255, 255, 255, 0.11);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        html.app-skin-dark .ds-row:hover td {
            background: #1a2534;
            border-color: rgba(159, 193, 255, 0.35);
        }

        html.app-skin-dark .ds-chip {
            color: #c8d3e3;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .ds-action {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .ds-status.scheduled {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .ds-status.completed {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .ds-status.cancelled {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        @media (max-width: 1199px) {
            .ds-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .ds-filters {
                grid-template-columns: 1fr 1fr;
            }

            .ds-table {
                min-width: 980px;
            }
        }

        @media (max-width: 767px) {
            .ds-stats {
                grid-template-columns: 1fr;
            }

            .ds-filters {
                grid-template-columns: 1fr;
            }

            .ds-table-shell {
                padding: 0 8px 10px;
            }

            .ds-table {
                min-width: 900px;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Defense Sessions</h1>
            <p class="ta-page-subtitle">Schedule, monitor, and update thesis defense sessions with clear status and committee visibility.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.defenses.create') }}" class="ta-chip-link ta-primary">
                <i class="feather-plus"></i>
                New Session
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

    <section class="ds-stats" aria-label="Defense summary cards">
        <article class="ds-stat">
            <span class="icon"><i class="feather-calendar"></i></span>
            <div>
                <p class="label">Total Sessions</p>
                <p class="value">{{ number_format($totalSessions) }}</p>
            </div>
        </article>
        <article class="ds-stat">
            <span class="icon"><i class="feather-clock"></i></span>
            <div>
                <p class="label">Scheduled</p>
                <p class="value">{{ number_format($scheduledSessions) }}</p>
            </div>
        </article>
        <article class="ds-stat">
            <span class="icon"><i class="feather-flag"></i></span>
            <div>
                <p class="label">Upcoming</p>
                <p class="value">{{ number_format($upcomingSessions) }}</p>
            </div>
        </article>
        <article class="ds-stat">
            <span class="icon"><i class="feather-check-circle"></i></span>
            <div>
                <p class="label">Completed</p>
                <p class="value">{{ number_format($completedSessions) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel mb-3">
        <div class="ta-panel-head">
            <div>
                <h3>Filter Sessions</h3>
                <span class="text-muted small">{{ number_format($filteredCount) }} matching records</span>
            </div>
        </div>
        <div class="ds-filter-body">
            <form method="GET" action="{{ route('admin.defenses.index') }}" class="ds-filters">
                <div>
                    <label class="ds-label" for="q">Search</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Thesis, student, location, status...">
                </div>

                <div>
                    <label class="ds-label" for="status">Status</label>
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
                    <label class="ds-label" for="per_page">Rows per page</label>
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
                    @if($search !== '' || $statusFilter !== '' || $perPage !== 10)
                        <a href="{{ route('admin.defenses.index') }}" class="btn btn-light btn-sm px-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>Sessions Directory</h3>
                <span class="text-muted small">{{ $sessions->total() }} records across {{ $sessions->lastPage() }} pages</span>
            </div>
        </div>

        <div class="ta-table-shell ds-table-shell">
            <table class="table table-hover mb-0 ds-table" id="defenseList">
                <thead>
                    <tr>
                        <th>Thesis & Student</th>
                        <th>Schedule</th>
                        <th>Location</th>
                        <th>Committee</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        @php
                            $statusClass = match($session->status) {
                                'completed' => 'completed',
                                'cancelled' => 'cancelled',
                                default => 'scheduled',
                            };

                            $statusIcon = match($session->status) {
                                'completed' => 'feather-check-circle',
                                'cancelled' => 'feather-x-circle',
                                default => 'feather-clock',
                            };

                            $committeePreview = $session->committeeMembers
                                ->take(2)
                                ->map(function ($member) {
                                    return $member->user?->name ?? 'Unknown';
                                })
                                ->implode(', ');

                            $extraCommittee = max($session->committeeMembers->count() - 2, 0);
                            $isUpcoming = $session->status === 'scheduled' && $session->scheduled_at && $session->scheduled_at->isFuture();
                        @endphp
                        <tr class="ds-row">
                            <td>
                                <p class="ds-title" title="{{ $session->thesis?->title ?? 'N/A' }}">{{ $session->thesis?->title ?? 'N/A' }}</p>
                                <div class="ds-student mt-2">
                                    <span class="ds-avatar">{{ strtoupper(substr($session->thesis?->student?->user?->name ?? 'S', 0, 1)) }}</span>
                                    <span>
                                        <span class="ds-student-name">{{ $session->thesis?->student?->user?->name ?? 'N/A' }}</span>
                                        <span class="ds-student-note">Thesis ID {{ $session->thesis_id }}</span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="ds-chip">
                                    <i class="feather-calendar"></i>
                                    {{ $session->scheduled_at?->format('M d, Y') ?? '-' }}
                                </span>
                                <p class="ds-sub">{{ $session->scheduled_at?->format('h:i A') ?? '-' }} @if($session->scheduled_at) ({{ $session->scheduled_at->diffForHumans() }}) @endif</p>
                            </td>
                            <td>
                                <span class="ds-chip">
                                    <i class="feather-map-pin"></i>
                                    {{ $session->location ?? 'TBD' }}
                                </span>
                            </td>
                            <td>
                                <div class="ds-committee">
                                    <span class="ds-chip">
                                        <i class="feather-users"></i>
                                        {{ $session->committeeMembers->count() }} members
                                    </span>
                                    <span class="ds-committee-list">
                                        {{ $committeePreview !== '' ? $committeePreview : 'No committee assigned' }}
                                        @if($extraCommittee > 0)
                                            +{{ $extraCommittee }} more
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="ds-status {{ $statusClass }}">
                                    <i class="{{ $statusIcon }}"></i>
                                    {{ $session->status }}
                                </span>
                                @if($isUpcoming)
                                    <p class="ds-sub">Upcoming defense window</p>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.defenses.edit', $session) }}" class="ds-action">
                                    <i class="feather-edit-3"></i>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ds-empty">
                                    <i class="feather-calendar"></i>
                                    <h4>No defense sessions found</h4>
                                    <p>Start by creating a new session and assigning committee members.</p>
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
            Showing {{ $sessions->firstItem() ?? 0 }} - {{ $sessions->lastItem() ?? 0 }} of {{ $sessions->total() }} records
        </span>
        {{ $sessions->links() }}
    </div>
        </div>
</x-app-layout>
