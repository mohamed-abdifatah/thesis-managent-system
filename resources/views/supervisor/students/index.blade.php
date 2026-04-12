<x-app-layout>
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">My Assigned Students</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Students</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($groups->isEmpty() && $ungroupedStudents->isEmpty())
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="feather-users text-muted fs-1 opacity-25" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted">No Students Assigned Yet</h4>
                        <p class="text-secondary small mb-0">You have not been assigned any thesis students at this time.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($groups as $group)
                        @php
                            $groupThesis = $groupTheses->get($group->id);
                            $membersCollapseId = 'group-members-' . $group->id;
                        @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                                <div class="card-header bg-white d-flex align-items-start justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ $group->name }}</h5>
                                        <p class="text-muted small mb-0">
                                            {{ $group->department?->name ?? 'Department not set' }}
                                            @if($group->program)
                                                • {{ $group->program }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="badge bg-soft-primary text-primary">{{ $group->students_count }} Students</span>
                                </div>
                                <div class="card-body">
                                    @if($groupThesis)
                                        @php
                                            $groupStatusClass = match($groupThesis->status) {
                                                'completed' => 'bg-soft-success text-success',
                                                'rejected' => 'bg-soft-danger text-danger',
                                                default => 'bg-soft-warning text-warning',
                                            };
                                        @endphp
                                        <p class="text-muted small mb-1">Shared Group Thesis</p>
                                        <p class="fw-semibold mb-2" title="{{ $groupThesis->title }}">
                                            {{ \Illuminate\Support\Str::limit($groupThesis->title, 68) }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                            <span class="badge {{ $groupStatusClass }}">{{ ucfirst(str_replace('_', ' ', $groupThesis->status)) }}</span>
                                            <a href="{{ route('supervisor.theses.show', $groupThesis) }}" class="btn btn-sm btn-primary">
                                                Manage Group Thesis
                                            </a>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                            <p class="text-muted small mb-0">No shared thesis submitted for this group yet.</p>
                                            <button class="btn btn-sm btn-light" disabled>Awaiting Group Proposal</button>
                                        </div>
                                    @endif

                                    @if($group->students->isEmpty())
                                        <p class="text-muted mb-0">No students in this group yet.</p>
                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary w-100"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $membersCollapseId }}"
                                            aria-expanded="false"
                                            aria-controls="{{ $membersCollapseId }}"
                                        >
                                            <i class="feather-users me-1"></i>
                                            View Members ({{ $group->students_count }})
                                        </button>

                                        <div class="collapse mt-3" id="{{ $membersCollapseId }}">
                                            <div class="d-grid gap-2">
                                                @foreach($group->students as $student)
                                                    <div class="d-flex align-items-center justify-content-between py-2 px-2 rounded-2 bg-light">
                                                        <div>
                                                            <div class="fw-semibold">{{ $student->user->name ?? 'Unknown Student' }}</div>
                                                            <small class="text-muted">{{ $student->student_id_number }}</small>
                                                        </div>
                                                        <span class="badge bg-soft-primary text-primary">Member</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($ungroupedStudents->isNotEmpty())
                        <div class="col-md-6 col-xl-4">
                            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                                <div class="card-header bg-white d-flex align-items-start justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-1">Ungrouped Students</h5>
                                        <p class="text-muted small mb-0">Assigned directly to you without a group.</p>
                                    </div>
                                    <span class="badge bg-soft-dark text-dark">{{ $ungroupedStudents->count() }} Students</span>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @foreach($ungroupedStudents as $student)
                                            <div class="d-flex align-items-center justify-content-between py-2 px-2 rounded-2 bg-light">
                                                <div>
                                                    <div class="fw-semibold">{{ $student->user->name ?? 'Unknown Student' }}</div>
                                                    <small class="text-muted">{{ $student->student_id_number }}</small>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($student->thesis)
                                                        <span class="badge bg-soft-success text-success">{{ ucfirst(str_replace('_', ' ', $student->thesis->status)) }}</span>
                                                        <a href="{{ route('supervisor.theses.show', $student->thesis) }}" class="btn btn-sm btn-light-brand">Manage</a>
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning">No Thesis</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
