<x-app-layout>
    <style>
        .pc-grid {
            display: grid;
            grid-template-columns: 1.45fr 0.75fr;
            gap: 16px;
        }

        .pc-note {
            border-radius: 12px;
            border: 1px solid #d8e5f7;
            background: #f7fbff;
            color: #4a5e77;
            padding: 10px 12px;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 14px;
        }

        .pc-section-title {
            margin: 0 0 10px;
            font-size: 0.88rem;
            color: #10233e;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .pc-checklist {
            display: grid;
            gap: 8px;
        }

        .pc-check {
            border: 1px solid #dce7f5;
            border-radius: 10px;
            background: #fbfdff;
            padding: 9px 10px;
            font-size: 0.79rem;
            color: #4f6380;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pc-check i {
            color: #1d4ed8;
            font-size: 0.85rem;
        }

        .pc-upload {
            border-radius: 12px;
            border: 1px dashed #cddff5;
            background: #f9fcff;
            padding: 12px;
        }

        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 16px;
        }

        html.app-skin-dark .pc-note {
            color: #a3b1c4;
            background: #1a2534;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .pc-section-title {
            color: #e6edf7;
        }

        html.app-skin-dark .pc-check {
            color: #a3b1c4;
            border-color: rgba(255, 255, 255, 0.14);
            background: #172232;
        }

        html.app-skin-dark .pc-check i {
            color: #9fc1ff;
        }

        html.app-skin-dark .pc-upload {
            background: #172232;
            border-color: rgba(159, 193, 255, 0.3);
        }

        @media (max-width: 1199px) {
            .pc-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Submit New Proposal</h1>
            <p class="ta-page-subtitle">Complete your research brief and upload one proposal document for supervisor review.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('proposals.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Back to Proposals
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-3" role="alert">
            Please review the form. Some required fields are missing or invalid.
        </div>
    @endif

    <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data" id="proposalCreateForm">
        @csrf

        <div class="pc-grid">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <div>
                        <h3>Research Proposal Details</h3>
                        <span class="text-muted small">Provide clear objective and methodology context.</span>
                    </div>
                </div>
                <div class="ta-panel-body">
                    <div class="pc-note">
                        Keep your writing concise and evidence-based. This page captures the first official draft for approval.
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Research Title <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            autofocus
                            placeholder="e.g. Impact of AI on Modern Software Engineering"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="abstract" class="form-label fw-semibold">Abstract <span class="text-danger">*</span></label>
                        <textarea
                            class="form-control @error('abstract') is-invalid @enderror"
                            id="abstract"
                            name="abstract"
                            rows="4"
                            required
                            placeholder="Brief summary of problem, method, and expected outcomes..."
                        >{{ old('abstract') }}</textarea>
                        <div class="form-text text-muted">Target a concise summary of around 150-250 words.</div>
                        @error('abstract')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="objectives" class="form-label fw-semibold">Objectives <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control @error('objectives') is-invalid @enderror"
                                id="objectives"
                                name="objectives"
                                rows="6"
                                required
                                placeholder="- To analyze...&#10;- To design...&#10;- To evaluate..."
                            >{{ old('objectives') }}</textarea>
                            @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="methodology" class="form-label fw-semibold">Methodology <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control @error('methodology') is-invalid @enderror"
                                id="methodology"
                                name="methodology"
                                rows="6"
                                required
                                placeholder="Describe approach, tools, and data collection strategy..."
                            >{{ old('methodology') }}</textarea>
                            @error('methodology')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="literature_review" class="form-label fw-semibold">Literature Review <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea
                            class="form-control @error('literature_review') is-invalid @enderror"
                            id="literature_review"
                            name="literature_review"
                            rows="3"
                            placeholder="Short overview of references, gaps, and theoretical context..."
                        >{{ old('literature_review') }}</textarea>
                        @error('literature_review')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pc-upload mt-3">
                        <label for="file" class="form-label fw-semibold mb-2">Proposal Document <span class="text-danger">*</span></label>
                        <input
                            type="file"
                            class="form-control @error('file') is-invalid @enderror"
                            id="file"
                            name="file"
                            required
                            accept=".pdf,.doc,.docx"
                        >
                        <div class="form-text mt-2">Accepted formats: PDF, DOC, DOCX. Maximum size: 10MB.</div>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pc-actions">
                        <a href="{{ route('proposals.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="feather-send me-1"></i>
                            Submit Proposal
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-3">
                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>Submission Checklist</h3>
                    </div>
                    <div class="ta-panel-body">
                        <div class="pc-checklist">
                            <div class="pc-check"><i class="feather-check-circle"></i> Title is specific and measurable.</div>
                            <div class="pc-check"><i class="feather-check-circle"></i> Abstract summarizes problem and expected output.</div>
                            <div class="pc-check"><i class="feather-check-circle"></i> Objectives are clear and actionable.</div>
                            <div class="pc-check"><i class="feather-check-circle"></i> Methodology describes approach and validation.</div>
                            <div class="pc-check"><i class="feather-check-circle"></i> Document file is attached and readable.</div>
                        </div>
                    </div>
                </div>

                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>What Happens Next</h3>
                    </div>
                    <div class="ta-panel-body">
                        <p class="small text-muted mb-2">Your supervisor reviews this proposal and may approve, reject, or request revisions.</p>
                        <p class="small text-muted mb-0">After approval, you can begin uploading thesis unit versions from the Thesis Versions page.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
