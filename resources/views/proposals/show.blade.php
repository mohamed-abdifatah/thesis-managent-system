<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Proposal Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('proposals.index') }}">Proposals</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column: Proposal Info -->
        <div class="col-lg-8">
            <!-- Thesis Information Card -->
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h4 text-dark fw-bold mb-0">{{ $proposal->title }}</h3>
                        <span class="badge {{ match($proposal->status) { 'approved' => 'bg-success', 'rejected' => 'bg-danger', 'revision_required' => 'bg-warning', default => 'bg-primary' } }}">
                            {{ strtoupper(str_replace('_', ' ', $proposal->status)) }}
                        </span>
                    </div>
                    
                    <div class="hstack gap-3 text-muted mb-4">
                        <div><i class="feather-user me-1"></i> <strong>{{ $proposal->thesis->student->user->name }}</strong></div>
                        <div class="vr"></div>
                        <div><i class="feather-calendar me-1"></i> {{ $proposal->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Abstract</h5>
                    <p class="text-secondary lead fs-6" style="text-align: justify;">
                        {{ $proposal->abstract }}
                    </p>
                </div>
            </div>

            <!-- Detailed Sections -->
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-bold">Research Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted fs-12 mb-2">Objectives</h6>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $proposal->objectives }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted fs-12 mb-2">Methodology</h6>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $proposal->methodology }}</p>
                            </div>
                        </div>
                        @if($proposal->literature_review)
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted fs-12 mb-2">Literature Review</h6>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $proposal->literature_review }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Supervisor Feedback Section -->
            @if($proposal->supervisor_comments || $proposal->status !== 'pending')
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header bg-warning-subtle text-warning-emphasis">
                    <h5 class="card-title mb-0 fw-bold"><i class="feather-message-square me-2"></i>Supervisor Feedback</h5>
                </div>
                <div class="card-body">
                    @if($proposal->supervisor_comments)
                        <p class="lead fs-6 mb-0">{{ $proposal->supervisor_comments }}</p>
                    @else
                        <p class="text-muted mb-0"><i>No specific comments provided yet.</i></p>
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
                    <h5 class="card-title fw-bold mb-0">Attachments</h5>
                </div>
                <div class="card-body">
                    @if($proposal->file_path)
                        <div class="d-grid">
                            <a href="{{ Storage::url($proposal->file_path) }}" target="_blank" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="feather-download-cloud fs-4"></i>
                                <span>Download Document</span>
                            </a>
                            <small class="text-center text-muted mt-2">Submitted File</small>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">No file attached.</div>
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
                        
                        <!-- Submitted -->
                        <li class="mb-4 position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 bg-primary rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 text-primary">Submitted</h6>
                            <small class="text-muted">{{ $proposal->created_at->format('M d, Y h:i A') }}</small>
                        </li>

                        <!-- Reviewed -->
                        <li class="mb-4 position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 {{ $proposal->status !== 'pending' ? 'bg-primary' : 'bg-secondary opacity-50' }} rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 {{ $proposal->status !== 'pending' ? 'text-primary' : 'text-muted' }}">Under Review</h6>
                            @if($proposal->status !== 'pending')
                                <small class="text-muted text-capitalize">
                                    {{ str_replace('_', ' ', $proposal->status) }}
                                </small>
                            @endif
                        </li>

                        <!-- Final Decision -->
                        <li class="position-relative">
                            <span class="position-absolute top-0 start-0 translate-middle ms-0 {{ in_array($proposal->status, ['approved', 'rejected']) ? ($proposal->status == 'approved' ? 'bg-success' : 'bg-danger') : 'bg-secondary opacity-50' }} rounded-circle border border-white" style="width: 14px; height: 14px; left: -1px !important;"></span>
                            <h6 class="fw-bold mb-0 {{ in_array($proposal->status, ['approved', 'rejected']) ? ($proposal->status == 'approved' ? 'text-success' : 'text-danger') : 'text-muted' }}">
                                Final Decision
                            </h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>