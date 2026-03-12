<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Thesis Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supervisor.students.index') }}">My Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thesis</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('supervisor.students.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column: Thesis Info & Proposal -->
        <div class="col-lg-8">
            <!-- Thesis Information Card -->
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h4 text-dark fw-bold mb-0">{{ $thesis->title }}</h3>
                        <span class="badge {{ $thesis->status === 'proposal_approved' ? 'bg-success' : 'bg-warning' }} text-white">
                            {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                        </span>
                    </div>
                    
                    <div class="hstack gap-3 text-muted mb-4">
                        <div><i class="feather-user me-1"></i> <strong>{{ $thesis->student->user->name }}</strong></div>
                        <div class="vr"></div>
                        <div><i class="feather-book me-1"></i> {{ $thesis->student->program ?? 'General Program' }}</div>
                        <div class="vr"></div>
                        <div><i class="feather-calendar me-1"></i> {{ $thesis->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Abstract</h5>
                    <p class="text-secondary lead fs-6" style="text-align: justify;">
                        {{ $latestProposal?->abstract ?? 'No abstract available yet.' }}
                    </p>
                </div>
            </div>

            <!-- Proposal Details & Review -->
            @if($latestProposal)
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Proposal Review</h5>
                        <span class="badge {{ match($latestProposal->status) { 'approved' => 'bg-success', 'rejected' => 'bg-danger', 'revision_required' => 'bg-warning', default => 'bg-primary' } }}">
                            {{ strtoupper(str_replace('_', ' ', $latestProposal->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-uppercase text-muted fs-12 mb-2">Objectives</h6>
                                    <p class="mb-0 small text-dark">{{ $latestProposal->objectives }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-uppercase text-muted fs-12 mb-2">Methodology</h6>
                                    <p class="mb-0 small text-dark">{{ $latestProposal->methodology }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Action Form -->
                        @if($latestProposal->status === 'pending' || $latestProposal->status === 'revision_required')
                            <div class="p-4 border rounded bg-light-subtle">
                                <h6 class="fw-bold text-dark mb-3"><i class="feather-check-square me-2"></i>Supervisor Action</h6>
                                <form action="{{ route('supervisor.proposals.review', $latestProposal) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Feedback / Comments</label>
                                        <textarea name="comments" rows="3" class="form-control" placeholder="Enter detailed feedback for the student..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" name="status" value="revision_required" class="btn btn-warning">
                                            <i class="feather-refresh-cw me-1"></i> Request Revision
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="btn btn-danger">
                                            <i class="feather-x-circle me-1"></i> Reject
                                        </button>
                                        <button type="submit" name="status" value="approved" class="btn btn-success">
                                            <i class="feather-check-circle me-1"></i> Approve Proposal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-secondary d-flex align-items-start" role="alert">
                                <i class="feather-message-square fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading fw-bold">Supervisor Feedback</h6>
                                    <p class="mb-0 small">{{ $latestProposal->supervisor_comments ?? 'No comments provided.' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="col-lg-4">
            <!-- Documents Card -->
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title fw-bold mb-0">Documents</h5>
                </div>
                <div class="card-body">
                    @if($latestProposal && $latestProposal->file_path)
                        <div class="d-grid">
                            <a href="{{ Storage::url($latestProposal->file_path) }}" target="_blank" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="feather-download-cloud fs-4"></i>
                                <span>Download Proposal</span>
                            </a>
                            <small class="text-center text-muted mt-2">File Format: PDF/DOC</small>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">No documents available.</div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header">
                     <h5 class="card-title fw-bold mb-0">Status Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled position-relative border-start border-2 border-primary ms-3 ps-4 mb-0" style="border-color: #e2e5e8 !important;">
                        
                        <!-- Registered -->
                        <li class="mb-4 position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 bg-primary rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 text-primary">Registered</h6>
                            <small class="text-muted">{{ $thesis->created_at->format('M d, Y') }}</small>
                        </li>

                        <!-- Proposal Submitted -->
                        <li class="mb-4 position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 {{ $latestProposal ? 'bg-primary' : 'bg-secondary opacity-50' }} rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 {{ $latestProposal ? 'text-primary' : 'text-muted' }}">Proposal Submitted</h6>
                            @if($latestProposal)
                                <small class="text-muted">{{ $latestProposal->created_at->format('M d, Y') }}</small>
                            @endif
                        </li>

                        <!-- Proposal Approved -->
                        <li class="mb-4 position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 {{ $thesis->status === 'proposal_approved' ? 'bg-success' : 'bg-secondary opacity-50' }} rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 {{ $thesis->status === 'proposal_approved' ? 'text-success' : 'text-muted' }}">Proposal Approved</h6>
                            @if($thesis->status === 'proposal_approved')
                                <small class="text-muted">{{ $thesis->updated_at->format('M d, Y') }}</small>
                            @endif
                        </li>

                         <!-- Defense -->
                         <li class="position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 bg-secondary opacity-25 rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 text-muted">Defense Scheduled</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
