<x-app-layout>
    @php
        $groupCount = $groups->count();
        $groupedStudentCount = $groups->sum('students_count');
        $ungroupedCount = $ungroupedStudents->count();
        $activeThesisCount = $groupTheses->filter(function ($thesis) {
            return in_array($thesis->status, ['proposal_approved', 'in_progress', 'ready_for_defense', 'completed'], true);
        })->count();
    @endphp

    <style>
        .svs-page {
            --svs-surface: #ffffff;
            --svs-border: #d9e3f2;
            --svs-muted: #64758d;
            --svs-ink: #0f172a;
            --svs-primary: #2563eb;
            --svs-primary-soft: #eaf1ff;
        }

        html.app-skin-dark .svs-page {
            --svs-surface: #151d28;
            --svs-border: rgba(196, 213, 238, 0.16);
            --svs-muted: #9fb2c9;
            --svs-ink: #e8eef8;
            --svs-primary: #8fb2ff;
            --svs-primary-soft: rgba(143, 178, 255, 0.16);
        }

        .svs-hero {
            border: 1px solid #d7e5ff;
            border-radius: 22px;
            background: linear-gradient(130deg, #f5f9ff 0%, #edf4ff 58%, #f2f8ff 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }

        html.app-skin-dark .svs-hero {
            border-color: #35507a;
            background: linear-gradient(130deg, #1b2739 0%, #162131 58%, #182737 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .svs-hero::before {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            top: -110px;
            right: -80px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.16) 0%, transparent 74%);
            pointer-events: none;
        }

        .svs-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .svs-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #1d4ed8;
            font-weight: 800;
        }

        html.app-skin-dark .svs-kicker {
            color: #9fbeff;
        }

        .svs-title {
            margin: 0;
            color: var(--svs-ink);
            font-size: clamp(1.35rem, 2.7vw, 1.95rem);
            letter-spacing: -0.02em;
        }

        .svs-subtitle {
            margin: 8px 0 0;
            color: var(--svs-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .svs-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .svs-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--svs-border);
            background: var(--svs-surface);
            color: #173762;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .svs-action {
            color: #d4e1f4;
        }

        .svs-action:hover {
            color: #1d4ed8;
            border-color: #bfd2ef;
            transform: translateY(-1px);
        }

        .svs-stat {
            border: 1px solid var(--svs-border);
            border-radius: 16px;
            background: var(--svs-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .svs-stat-label {
            margin: 0;
            color: var(--svs-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .svs-stat-value {
            margin: 3px 0 0;
            color: var(--svs-ink);
            font-size: 1.45rem;
            line-height: 1;
        }

        .svs-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: var(--svs-primary-soft);
            border: 1px solid #cfddf8;
        }

        html.app-skin-dark .svs-stat-icon {
            border-color: #35507a;
            color: #acc6ff;
        }

        .svs-card {
            border: 1px solid var(--svs-border);
            border-radius: 18px;
            background: var(--svs-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            height: 100%;
        }

        .svs-card-head {
            border-bottom: 1px solid var(--svs-border);
            padding: 14px 16px;
            background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        html.app-skin-dark .svs-card-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .svs-card-title {
            margin: 0;
            color: var(--svs-ink);
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .svs-card-sub {
            margin: 4px 0 0;
            color: var(--svs-muted);
            font-size: 0.78rem;
        }

        .svs-card-body {
            padding: 14px;
        }

        .svs-thesis-title {
            margin: 0;
            color: #1f3f69;
            font-size: 0.87rem;
            font-weight: 700;
            line-height: 1.45;
        }

        html.app-skin-dark .svs-thesis-title {
            color: #d4e2f6;
        }

        .svs-thesis-label {
            margin: 0 0 6px;
            color: var(--svs-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.66rem;
            font-weight: 800;
        }

        .svs-member-row {
            border: 1px solid var(--svs-border);
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: #f9fbff;
        }

        html.app-skin-dark .svs-member-row {
            background: #1a2737;
        }

        .svs-empty {
            border: 1px dashed var(--svs-border);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            color: var(--svs-muted);
            background: #f9fbff;
        }

        html.app-skin-dark .svs-empty {
            background: #1a2737;
        }

        .svs-members-modal .modal-content {
            border: 1px solid var(--svs-border);
            border-radius: 16px;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.2);
            background: var(--svs-surface);
        }

        .svs-members-modal .modal-header {
            border-bottom: 1px solid var(--svs-border);
            background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
        }

        html.app-skin-dark .svs-members-modal .modal-header {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .svs-members-modal .modal-title {
            color: var(--svs-ink);
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .svs-members-modal .modal-footer {
            border-top: 1px solid var(--svs-border);
        }

        html.app-skin-dark .svs-members-modal .btn-close {
            filter: invert(1) grayscale(100%);
        }

        @media (max-width: 767.98px) {
            .svs-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .svs-actions {
                width: 100%;
            }

            .svs-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="svs-page">
        <section class="svs-hero">
            <div class="svs-hero-content">
                <div>
                    <p class="svs-kicker">Supervisor Workspace</p>
                    <h1 class="svs-title">My Assigned Students</h1>
                    <p class="svs-subtitle">
                        Manage grouped and ungrouped supervisees, monitor thesis momentum, and jump directly into group thesis workflows.
                    </p>
                </div>
                <div class="svs-actions">
                    <a href="{{ route('dashboard') }}" class="svs-action">
                        <i class="feather-home"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('defense.schedule') }}" class="svs-action">
                        <i class="feather-calendar"></i>
                        Defense Schedule
                    </a>
                </div>
            </div>
        </section>

        @if($groups->isEmpty() && $ungroupedStudents->isEmpty())
            <div class="svs-empty">
                <i class="feather-users d-block mb-2" style="font-size: 2rem;"></i>
                <h4 class="mb-1" style="color: var(--svs-ink);">No Students Assigned Yet</h4>
                <p class="mb-0">You do not have assigned thesis students at the moment.</p>
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <article class="svs-stat">
                        <div>
                            <p class="svs-stat-label">Groups</p>
                            <h3 class="svs-stat-value">{{ $groupCount }}</h3>
                        </div>
                        <span class="svs-stat-icon"><i class="feather-layers"></i></span>
                    </article>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <article class="svs-stat">
                        <div>
                            <p class="svs-stat-label">Grouped Students</p>
                            <h3 class="svs-stat-value">{{ $groupedStudentCount }}</h3>
                        </div>
                        <span class="svs-stat-icon"><i class="feather-users"></i></span>
                    </article>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <article class="svs-stat">
                        <div>
                            <p class="svs-stat-label">Ungrouped</p>
                            <h3 class="svs-stat-value">{{ $ungroupedCount }}</h3>
                        </div>
                        <span class="svs-stat-icon"><i class="feather-user-plus"></i></span>
                    </article>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <article class="svs-stat">
                        <div>
                            <p class="svs-stat-label">Active Group Theses</p>
                            <h3 class="svs-stat-value">{{ $activeThesisCount }}</h3>
                        </div>
                        <span class="svs-stat-icon"><i class="feather-activity"></i></span>
                    </article>
                </div>
            </div>

            <div class="row g-4">
                @foreach($groups as $group)
                    @php
                        $groupThesis = $groupTheses->get($group->id);
                        $membersModalId = 'group-members-modal-' . $group->id;
                        $groupStatusClass = $groupThesis
                            ? match($groupThesis->status) {
                                'completed' => 'bg-soft-success text-success',
                                'rejected' => 'bg-soft-danger text-danger',
                                default => 'bg-soft-warning text-warning',
                            }
                            : 'bg-soft-dark text-dark';
                    @endphp

                    <div class="col-lg-6">
                        <article class="svs-card">
                            <header class="svs-card-head">
                                <div>
                                    <h3 class="svs-card-title">{{ $group->name }}</h3>
                                    <p class="svs-card-sub">
                                        {{ $group->department?->name ?? 'Department not set' }}
                                        @if($group->program)
                                            • {{ $group->program }}
                                        @endif
                                    </p>
                                </div>
                                <span class="badge bg-soft-primary text-primary">{{ $group->students_count }} Students</span>
                            </header>

                            <div class="svs-card-body">
                                @if($groupThesis)
                                    <p class="svs-thesis-label">Shared Group Thesis</p>
                                    <p class="svs-thesis-title" title="{{ $groupThesis->title }}">
                                        {{ \Illuminate\Support\Str::limit($groupThesis->title, 96) }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mt-3 mb-3">
                                        <span class="badge {{ $groupStatusClass }}">{{ ucfirst(str_replace('_', ' ', $groupThesis->status)) }}</span>
                                        <a href="{{ route('supervisor.theses.show', $groupThesis) }}" class="btn btn-sm btn-primary">
                                            <i class="feather-edit me-1"></i>
                                            Manage Group Thesis
                                        </a>
                                    </div>
                                @else
                                    <div class="svs-empty mb-3">
                                        <p class="mb-2">No shared thesis has been submitted for this group.</p>
                                        <button class="btn btn-sm btn-light" disabled>Awaiting Group Proposal</button>
                                    </div>
                                @endif

                                @if($group->students->isEmpty())
                                    <p class="text-muted mb-0">No students in this group yet.</p>
                                @else
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $membersModalId }}"
                                        aria-controls="{{ $membersModalId }}"
                                    >
                                        <i class="feather-users me-1"></i>
                                        View Members ({{ $group->students_count }})
                                    </button>

                                    <div class="modal fade svs-members-modal" id="{{ $membersModalId }}" tabindex="-1" aria-labelledby="{{ $membersModalId }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="{{ $membersModalId }}Label">{{ $group->name }} Members</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small mb-3">
                                                        {{ $group->department?->name ?? 'Department not set' }}
                                                        @if($group->program)
                                                            • {{ $group->program }}
                                                        @endif
                                                    </p>

                                                    <div class="d-grid gap-2">
                                                        @foreach($group->students as $student)
                                                            <div class="svs-member-row">
                                                                <div>
                                                                    <div class="fw-semibold" style="color: var(--svs-ink);">{{ $student->user->name ?? 'Unknown Student' }}</div>
                                                                    <small class="text-muted">{{ $student->student_id_number }}</small>
                                                                </div>
                                                                <span class="badge bg-soft-primary text-primary">Member</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach

                @if($ungroupedStudents->isNotEmpty())
                    <div class="col-12">
                        <article class="svs-card">
                            <header class="svs-card-head">
                                <div>
                                    <h3 class="svs-card-title">Ungrouped Students</h3>
                                    <p class="svs-card-sub">Assigned directly to you without a student group.</p>
                                </div>
                                <span class="badge bg-soft-dark text-dark">{{ $ungroupedStudents->count() }} Students</span>
                            </header>

                            <div class="svs-card-body">
                                <div class="d-grid gap-2">
                                    @foreach($ungroupedStudents as $student)
                                        <div class="svs-member-row">
                                            <div>
                                                <div class="fw-semibold" style="color: var(--svs-ink);">{{ $student->user->name ?? 'Unknown Student' }}</div>
                                                <small class="text-muted">{{ $student->student_id_number }}</small>
                                            </div>

                                            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                                @if($student->thesis)
                                                    <span class="badge bg-soft-success text-success">{{ ucfirst(str_replace('_', ' ', $student->thesis->status)) }}</span>
                                                    <a href="{{ route('supervisor.theses.show', $student->thesis) }}" class="btn btn-sm btn-primary">Manage</a>
                                                @else
                                                    <span class="badge bg-soft-warning text-warning">No Thesis</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
