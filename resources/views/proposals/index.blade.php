<x-app-layout>
    @php
        $user = auth()->user();
        $isStudent = $user && $user->hasRole('student');
        $hasActiveThesis = (bool) $user?->student?->accessibleThesis();

        $pendingCount = $proposals->where('status', 'pending')->count();
        $approvedCount = $proposals->where('status', 'approved')->count();
        $revisionCount = $proposals->where('status', 'revision_required')->count();
        $rejectedCount = $proposals->where('status', 'rejected')->count();
    @endphp

    <style>
        .pi-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .pi-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pi-stat .icon {
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

        .pi-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .pi-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .pi-filter-body {
            padding: 14px 16px 16px;
            border-top: 1px solid var(--ta-border);
        }

        .pi-filters {
            display: grid;
            grid-template-columns: minmax(240px, 1.6fr) minmax(180px, 0.8fr) auto;
            gap: 10px;
            align-items: end;
        }

        .pi-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #627892;
            font-weight: 700;
        }

        .pi-table tbody td {
            vertical-align: middle;
        }

        .pi-title {
            margin: 0;
            color: #10233e;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.3;
            max-width: 380px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pi-sub {
            margin-top: 3px;
            font-size: 0.76rem;
            color: #66758d;
        }

        .pi-person {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pi-avatar {
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

        .pi-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 9px;
            border: 1px solid transparent;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .pi-status.pending {
            color: #855800;
            background: #fff8e9;
            border-color: #f7dba6;
        }

        .pi-status.approved {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .pi-status.revision_required {
            color: #9a6400;
            background: #fff4dd;
            border-color: #f6d49a;
        }

        .pi-status.rejected {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .pi-action {
            min-height: 34px;
            padding: 0.35rem 0.7rem;
            border-radius: 10px;
            border: 1px solid #cfe0ff;
            color: #1d4ed8;
            background: #eef4ff;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            transition: all 0.18s ease;
            white-space: nowrap;
            text-decoration: none;
        }

        .pi-action:hover {
            color: #1e40af;
            background: #e3edff;
            border-color: #b9d0ff;
            transform: translateY(-1px);
        }

        .pi-empty {
            text-align: center;
            padding: 40px 16px;
            color: #66758d;
        }

        .pi-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .pi-empty h4 {
            margin: 0;
            color: #10233e;
            font-size: 1.04rem;
        }

        .pi-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        html.app-skin-dark .pi-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .pi-stat .icon,
        html.app-skin-dark .pi-avatar {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .pi-stat .label,
        html.app-skin-dark .pi-sub,
        html.app-skin-dark .pi-empty,
        html.app-skin-dark .pi-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .pi-stat .value,
        html.app-skin-dark .pi-title,
        html.app-skin-dark .pi-empty h4 {
            color: #e6edf7;
        }

        html.app-skin-dark .pi-action {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .pi-status.pending {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .pi-status.approved {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .pi-status.revision_required {
            color: #ffdeaa;
            background: rgba(138, 101, 39, 0.33);
            border-color: rgba(194, 154, 92, 0.47);
        }

        html.app-skin-dark .pi-status.rejected {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        @media (max-width: 1199px) {
            .pi-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .pi-filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767px) {
            .pi-stats {
                grid-template-columns: 1fr;
            }

            .pi-filters {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.student-account-refresh')

    <div class="{{ $isStudent ? 'stu-refresh' : '' }}">
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Proposal Management</h1>
            <p class="ta-page-subtitle">Review your submissions, track decision status, and open details for each proposal.</p>
        </div>
        <div class="ta-page-actions">
            @if($isStudent && !$hasActiveThesis)
                <a href="{{ route('proposals.create') }}" class="ta-chip-link ta-primary">
                    <i class="feather-plus"></i>
                    Create Proposal
                </a>
            @endif
            @if($isStudent && $hasActiveThesis)
                <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link">
                    <i class="feather-upload-cloud"></i>
                    Thesis Versions
                </a>
            @endif
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

    <section class="pi-stats" aria-label="Proposal summary cards">
        <article class="pi-stat">
            <span class="icon"><i class="feather-file-text"></i></span>
            <div>
                <p class="label">Total Proposals</p>
                <p class="value">{{ number_format($proposals->count()) }}</p>
            </div>
        </article>
        <article class="pi-stat">
            <span class="icon"><i class="feather-clock"></i></span>
            <div>
                <p class="label">Pending</p>
                <p class="value">{{ number_format($pendingCount) }}</p>
            </div>
        </article>
        <article class="pi-stat">
            <span class="icon"><i class="feather-check-circle"></i></span>
            <div>
                <p class="label">Approved</p>
                <p class="value">{{ number_format($approvedCount) }}</p>
            </div>
        </article>
        <article class="pi-stat">
            <span class="icon"><i class="feather-alert-triangle"></i></span>
            <div>
                <p class="label">Revision / Rejected</p>
                <p class="value">{{ number_format($revisionCount + $rejectedCount) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel mb-3">
        <div class="ta-panel-head">
            <div>
                <h3>Filter Proposals</h3>
                <span class="text-muted small"><span id="proposalMatchCount">{{ number_format($proposals->count()) }}</span> matching records</span>
            </div>
        </div>
        <div class="pi-filter-body">
            <div class="pi-filters">
                <div>
                    <label class="pi-label" for="proposalSearch">Search</label>
                    <input id="proposalSearch" type="text" class="form-control" placeholder="Title, student, email, status...">
                </div>
                <div>
                    <label class="pi-label" for="proposalStatusFilter">Status</label>
                    <select id="proposalStatusFilter" class="form-select">
                        <option value="">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="revision_required">Revision Required</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="d-flex align-items-end gap-2">
                    <button type="button" class="btn btn-light btn-sm px-3" id="proposalResetFilter">
                        <i class="feather-rotate-ccw me-1"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>Proposal Directory</h3>
                <span class="text-muted small">Browse and open proposal details</span>
            </div>
        </div>

        <div class="ta-table-shell">
            <table class="table table-hover mb-0 pi-table" id="proposalList">
                <thead>
                    <tr>
                        <th>Proposal</th>
                        <th>Student</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $proposal)
                        @php
                            $studentName = optional(optional(optional($proposal->thesis)->student)->user)->name ?? 'Unknown Student';
                            $studentEmail = optional(optional(optional($proposal->thesis)->student)->user)->email ?? 'No email';
                            $statusKey = (string) $proposal->status;
                            $statusIcon = match($statusKey) {
                                'approved' => 'feather-check-circle',
                                'rejected' => 'feather-x-circle',
                                'revision_required' => 'feather-edit-3',
                                default => 'feather-clock',
                            };
                        @endphp
                        <tr
                            data-proposal-row
                            data-status="{{ $statusKey }}"
                            data-search="{{ strtolower($proposal->title.' '.$studentName.' '.$studentEmail.' '.$statusKey.' '.$proposal->id) }}"
                        >
                            <td>
                                <p class="pi-title" title="{{ $proposal->title }}">{{ $proposal->title }}</p>
                                <span class="pi-sub">Proposal #{{ $proposal->id }}</span>
                            </td>
                            <td>
                                <div class="pi-person">
                                    <span class="pi-avatar">{{ strtoupper(substr($studentName, 0, 1)) }}</span>
                                    <span>
                                        <strong>{{ $studentName }}</strong>
                                        <span class="pi-sub d-block">{{ $studentEmail }}</span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="small text-muted d-block">{{ $proposal->created_at->format('M d, Y') }}</span>
                                <span class="pi-sub">{{ $proposal->created_at->format('h:i A') }}</span>
                            </td>
                            <td>
                                <span class="pi-status {{ $statusKey }}">
                                    <i class="{{ $statusIcon }}"></i>
                                    {{ str_replace('_', ' ', $statusKey) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('proposals.show', $proposal) }}" class="pi-action">
                                    <i class="feather-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="pi-empty">
                                    <i class="feather-file-minus"></i>
                                    <h4>No proposals found</h4>
                                    <p>Create your first proposal to start thesis approval workflow.</p>
                                    @if($isStudent && !$hasActiveThesis)
                                        <a href="{{ route('proposals.create') }}" class="btn btn-primary mt-3">Create Proposal</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="proposalNoMatchRow" style="display:none;">
                        <td colspan="5">
                            <div class="pi-empty">
                                <i class="feather-search"></i>
                                <h4>No matches for current filter</h4>
                                <p>Change search or reset status filter.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('proposalSearch');
            const statusFilter = document.getElementById('proposalStatusFilter');
            const resetButton = document.getElementById('proposalResetFilter');
            const matchCount = document.getElementById('proposalMatchCount');
            const rows = [...document.querySelectorAll('[data-proposal-row]')];
            const noMatchRow = document.getElementById('proposalNoMatchRow');

            const applyFilters = () => {
                const term = (searchInput?.value || '').trim().toLowerCase();
                const status = (statusFilter?.value || '').trim();
                let visibleCount = 0;

                rows.forEach((row) => {
                    const rowStatus = row.getAttribute('data-status') || '';
                    const searchText = row.getAttribute('data-search') || '';

                    const statusMatch = status === '' || rowStatus === status;
                    const termMatch = term === '' || searchText.includes(term);
                    const visible = statusMatch && termMatch;

                    row.style.display = visible ? '' : 'none';
                    if (visible) {
                        visibleCount += 1;
                    }
                });

                if (matchCount) {
                    matchCount.textContent = visibleCount.toString();
                }

                if (noMatchRow) {
                    noMatchRow.style.display = rows.length > 0 && visibleCount === 0 ? '' : 'none';
                }
            };

            searchInput?.addEventListener('input', applyFilters);
            statusFilter?.addEventListener('change', applyFilters);

            resetButton?.addEventListener('click', () => {
                if (searchInput) {
                    searchInput.value = '';
                }
                if (statusFilter) {
                    statusFilter.value = '';
                }
                applyFilters();
            });

            applyFilters();
        });
    </script>
    </div>
</x-app-layout>
