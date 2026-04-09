<x-app-layout>
    <!-- Page Header -->
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

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            @if($theses->isEmpty() && $studentsWithoutThesis->isEmpty())
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
                    @foreach($theses as $thesis)
                        <div class="col-md-6 col-xl-4">
                            <div class="card stretch stretch-full border-0 shadow-sm h-100 hover-shadow transition-all">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-text bg-primary-subtle text-primary rounded-3">
                                            {{ substr($thesis->student->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $thesis->student->user->name }}</h6>
                                            <span class="text-muted small">{{ $thesis->student->program ?? 'General Program' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $thesis->status === 'completed' ? 'bg-success-subtle text-success' : ($thesis->status === 'rejected' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h5 class="fw-bold text-dark mb-3 text-truncate" title="{{ $thesis->title }}">{{ $thesis->title }}</h5>
                                    
                                    <div class="p-3 bg-light rounded border mb-3">
                                        @php $latestProposal = $thesis->proposals->first(); @endphp
                                        @if($latestProposal)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Latest Proposal</small>
                                                <span class="badge {{ $latestProposal->status === 'approved' ? 'bg-success' : ($latestProposal->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ ucfirst($latestProposal->status) }}
                                                </span>
                                            </div>
                                            <small class="text-muted d-block">
                                                <i class="feather-clock me-1"></i> {{ $latestProposal->created_at->diffForHumans() }}
                                            </small>
                                        @else
                                            <small class="text-muted fst-italic">No proposal submitted yet.</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0 pb-4">
                                    <a href="{{ route('supervisor.theses.show', $thesis) }}" class="btn btn-primary w-100">
                                        Manage Thesis <i class="feather-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($studentsWithoutThesis as $student)
                        <div class="col-md-6 col-xl-4">
                            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-text bg-secondary-subtle text-secondary rounded-3">
                                            {{ substr($student->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $student->user->name }}</h6>
                                            <span class="text-muted small">{{ $student->program ?? 'General Program' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-soft-warning text-warning">No Thesis Yet</span>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-0">Student is assigned but has not submitted a proposal.</p>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0 pb-4">
                                    <button class="btn btn-light w-100" disabled>
                                        Awaiting Proposal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
