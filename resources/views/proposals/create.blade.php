<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Submit New Proposal</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('proposals.index') }}">Proposals</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Proposal</li>
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
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark">Research Proposal Details</h5>
                        <p class="text-muted small">Please provide the initial details of your research. You can update these later based on supervisor feedback.</p>
                    </div>

                    <hr class="text-muted opacity-25" />

                    <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- Title -->
                            <div class="col-12">
                                <label for="title" class="form-label fw-semibold">Research Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required autofocus 
                                       placeholder="e.g. Impact of AI on Modern Software Engineering">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Abstract -->
                            <div class="col-12">
                                <label for="abstract" class="form-label fw-semibold">Abstract <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('abstract') is-invalid @enderror" 
                                          id="abstract" name="abstract" rows="4" required 
                                          placeholder="Brief summary of your research problem, methodology, and expected outcomes...">{{ old('abstract') }}</textarea>
                                <div class="form-text text-muted">A concise summary (approx. 150-250 words).</div>
                                @error('abstract')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Objectives -->
                            <div class="col-md-6">
                                <label for="objectives" class="form-label fw-semibold">Objectives <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                          id="objectives" name="objectives" rows="5" required 
                                          placeholder="- To analyze...&#10;- To design...&#10;- To evaluate...">{{ old('objectives') }}</textarea>
                                @error('objectives')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Methodology -->
                            <div class="col-md-6">
                                <label for="methodology" class="form-label fw-semibold">Methodology <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('methodology') is-invalid @enderror" 
                                          id="methodology" name="methodology" rows="5" required 
                                          placeholder="Describe your research approach, tools, and data collection methods...">{{ old('methodology') }}</textarea>
                                @error('methodology')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Review (Optional) -->
                            <div class="col-12">
                                <label for="literature_review" class="form-label fw-semibold">Literature Review <span class="text-muted fw-normal">(Optional)</span></label>
                                <textarea class="form-control @error('literature_review') is-invalid @enderror" 
                                          id="literature_review" name="literature_review" rows="3" 
                                          placeholder="Brief overview of existing research or key references...">{{ old('literature_review') }}</textarea>
                            </div>

                            <!-- File Upload -->
                            <div class="col-12">
                                <div class="p-4 rounded-3 border border-1 border-dashed bg-light mt-2">
                                    <label for="file" class="form-label fw-bold text-dark mb-2">Proposal Document <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                        <div class="flex-grow-1 w-100">
                                            <input type="file" class="form-control form-control-lg @error('file') is-invalid @enderror" 
                                                   id="file" name="file" required accept=".pdf,.doc,.docx">
                                            <div class="form-text mt-2">Supported formats: PDF, DOC, DOCX. Max size: 10MB.</div>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="{{ route('proposals.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 btn-lg">
                                <i class="feather-send me-2"></i> Submit Proposal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
