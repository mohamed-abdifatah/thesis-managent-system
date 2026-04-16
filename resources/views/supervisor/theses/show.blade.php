<x-app-layout>
    <style>
        .sv-page {
            --sv-surface: #ffffff;
            --sv-soft: #f6f8fc;
            --sv-border: #d9e2ef;
            --sv-ink: #0f172a;
            --sv-muted: #64748b;
            --sv-primary: #2563eb;
            --sv-primary-soft: #eaf1ff;
            --sv-success: #16a34a;
            --sv-warning: #d97706;
            --sv-danger: #dc2626;
        }

        html.app-skin-dark .sv-page {
            --sv-surface: #151d28;
            --sv-soft: #1c2736;
            --sv-border: rgba(198, 215, 237, 0.16);
            --sv-ink: #e7eef8;
            --sv-muted: #a8b7ca;
            --sv-primary: #8fb2ff;
            --sv-primary-soft: rgba(143, 178, 255, 0.18);
            --sv-success: #67d69b;
            --sv-warning: #ffc46a;
            --sv-danger: #ff9f9f;
        }

        .sv-hero {
            position: relative;
            border: 1px solid var(--sv-border);
            border-radius: 20px;
            background: linear-gradient(135deg, #f8fbff 0%, #eff5ff 56%, #eef9ff 100%);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 18px;
            overflow: hidden;
        }

        html.app-skin-dark .sv-hero {
            background: linear-gradient(135deg, #1a2434 0%, #182131 56%, #152235 100%);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.34);
        }

        .sv-hero::before,
        .sv-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .sv-hero::before {
            width: 220px;
            height: 220px;
            top: -120px;
            right: -80px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.16) 0%, transparent 72%);
        }

        .sv-hero::after {
            width: 170px;
            height: 170px;
            left: -75px;
            bottom: -90px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.14) 0%, transparent 72%);
        }

        .sv-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .sv-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.73rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1e40af;
            font-weight: 700;
            margin-bottom: 8px;
        }

        html.app-skin-dark .sv-kicker {
            color: #9fb9ff;
        }

        .sv-kicker::before {
            content: "";
            width: 22px;
            height: 2px;
            background: #1e40af;
            border-radius: 999px;
        }

        html.app-skin-dark .sv-kicker::before {
            background: #9fb9ff;
        }

        .sv-title {
            margin: 0;
            color: var(--sv-ink);
            font-size: clamp(1.35rem, 2.8vw, 1.95rem);
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .sv-breadcrumb {
            margin: 8px 0 0;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 0.82rem;
            color: var(--sv-muted);
        }

        .sv-breadcrumb a {
            color: #1d4ed8;
            text-decoration: none;
        }

        html.app-skin-dark .sv-breadcrumb a {
            color: #aac3ff;
        }

        .sv-breadcrumb a:hover {
            text-decoration: underline;
        }

        .sv-chip-row {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .sv-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 10px;
            background: #f0f6ff;
            border: 1px solid #d6e6ff;
            color: #19407d;
            font-size: 0.79rem;
            font-weight: 600;
        }

        html.app-skin-dark .sv-chip {
            background: #1f2a3b;
            border-color: #334864;
            color: #d5e3f6;
        }

        .sv-panel {
            border: 1px solid var(--sv-border);
            border-radius: 18px;
            background: var(--sv-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .sv-panel-header {
            border-bottom: 1px solid var(--sv-border);
            padding: 14px 16px;
            background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
        }

        html.app-skin-dark .sv-panel-header {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .sv-panel-body {
            padding: 16px;
        }

        .sv-subtitle {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: var(--sv-ink);
            letter-spacing: -0.01em;
        }

        .sv-soft-block {
            border: 1px solid var(--sv-border);
            border-radius: 14px;
            padding: 12px;
            background: var(--sv-soft);
        }

        .sv-soft-title {
            margin: 0 0 8px;
            color: #334155;
            font-size: 0.72rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            font-weight: 800;
        }

        html.app-skin-dark .sv-soft-title {
            color: #9fb1c7;
        }

        .sv-soft-text {
            margin: 0;
            color: #0f172a;
            font-size: 0.88rem;
            line-height: 1.65;
            white-space: pre-line;
        }

        html.app-skin-dark .sv-soft-text {
            color: #e0e9f7;
        }

        .sv-abstract {
            margin: 0;
            color: #334155;
            line-height: 1.75;
            text-align: justify;
            font-size: 0.92rem;
        }

        html.app-skin-dark .sv-abstract {
            color: #d8e3f4;
        }

        .sv-action-form textarea {
            min-height: 120px;
            border-radius: 12px;
            border-color: #c8d5e6;
        }

        .sv-action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .sv-version-list {
            display: grid;
            gap: 12px;
        }

        .sv-version-item {
            border: 1px solid var(--sv-border);
            border-radius: 14px;
            background: #fcfdff;
            padding: 12px;
        }

        html.app-skin-dark .sv-version-item {
            background: #121b2a;
        }

        .sv-version-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .sv-version-title {
            margin: 0;
            color: var(--sv-ink);
            font-weight: 700;
        }

        .sv-version-meta {
            margin: 0;
            color: var(--sv-muted);
            font-size: 0.82rem;
        }

        .sv-version-controls {
            margin-top: 12px;
            display: grid;
            gap: 8px;
        }

        .sv-status-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
        }

        .sv-final-form {
            display: flex;
            justify-content: flex-end;
        }

        .sv-download-btn {
            width: 100%;
            min-height: 54px;
            border-radius: 14px;
            font-weight: 700;
        }

        .sv-timeline {
            list-style: none;
            margin: 0;
            padding: 4px 0 0 18px;
            border-left: 2px solid #dbe7fb;
            display: grid;
            gap: 14px;
        }

        html.app-skin-dark .sv-timeline {
            border-left-color: #334b68;
        }

        .sv-timeline-item {
            position: relative;
            padding-left: 12px;
        }

        .sv-timeline-item::before {
            content: "";
            position: absolute;
            left: -25px;
            top: 3px;
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: #cbd5e1;
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 2px #dbe7fb;
        }

        html.app-skin-dark .sv-timeline-item::before {
            background: #556982;
            border-color: #151d28;
            box-shadow: 0 0 0 2px #334b68;
        }

        .sv-timeline-item.is-done::before {
            background: #2563eb;
            box-shadow: 0 0 0 2px #bfd5ff;
        }

        html.app-skin-dark .sv-timeline-item.is-done::before {
            background: #7ea7ff;
            box-shadow: 0 0 0 2px #355184;
        }

        .sv-timeline-item.is-success::before {
            background: #16a34a;
            box-shadow: 0 0 0 2px #b8ebcc;
        }

        html.app-skin-dark .sv-timeline-item.is-success::before {
            background: #62d59a;
            box-shadow: 0 0 0 2px #355f4d;
        }

        .sv-timeline-label {
            margin: 0;
            font-weight: 700;
            color: var(--sv-ink);
            font-size: 0.9rem;
        }

        .sv-timeline-date {
            margin: 2px 0 0;
            color: var(--sv-muted);
            font-size: 0.8rem;
        }

        .sv-chat-fab {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 2200;
            border-radius: 999px;
            min-height: 44px;
            padding: 10px 16px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sv-chat-overlay {
            position: fixed;
            inset: 0;
            z-index: 2201;
            background: rgba(15, 23, 42, 0.46);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .sv-chat-panel {
            width: min(1020px, 100%);
            max-height: min(86vh, 760px);
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid var(--sv-border);
            box-shadow: 0 26px 52px rgba(15, 23, 42, 0.28);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        html.app-skin-dark .sv-chat-panel {
            background: #111827;
            border-color: #324a68;
        }

        .sv-chat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 12px 14px;
            border-bottom: 1px solid var(--sv-border);
            background: linear-gradient(180deg, #fbfdff 0%, #f7faff 100%);
        }

        html.app-skin-dark .sv-chat-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
            border-bottom-color: #324a68;
        }

        .sv-chat-body {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 14px;
            background: #eef4ff;
        }

        html.app-skin-dark .sv-chat-body {
            background: #0f1a2a;
        }

        .sv-chat-list {
            display: grid;
            gap: 10px;
        }

        .sv-chat-row {
            display: flex;
            justify-content: flex-start;
        }

        .sv-chat-row.is-mine {
            justify-content: flex-end;
        }

        .sv-chat-bubble {
            width: min(720px, 84%);
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.1);
            background: #ffffff;
            color: #0f172a;
            padding: 10px 12px;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.1);
        }

        html.app-skin-dark .sv-chat-bubble {
            background: #162235;
            border-color: #324a68;
            color: #e8eef7;
        }

        .sv-chat-row.is-mine .sv-chat-bubble {
            background: #def7ec;
            border-color: #b7e6d0;
        }

        html.app-skin-dark .sv-chat-row.is-mine .sv-chat-bubble {
            background: #17382a;
            border-color: #2f6a50;
            color: #defbea;
        }

        .sv-chat-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 0.75rem;
            margin-bottom: 5px;
        }

        html.app-skin-dark .sv-chat-meta {
            color: #9fb1c8;
        }

        .sv-chat-footer {
            border-top: 1px solid var(--sv-border);
            padding: 12px;
            background: #ffffff;
        }

        html.app-skin-dark .sv-chat-footer {
            background: #111827;
            border-top-color: #324a68;
        }

        html.app-skin-dark .sv-page .alert-light {
            background: #1b2637;
            color: #dbe8f7;
            border-color: #324a68 !important;
        }

        html.app-skin-dark .sv-page .text-muted {
            color: #9fb1c8 !important;
        }

        html.app-skin-dark .sv-page .form-control,
        html.app-skin-dark .sv-page .form-select,
        html.app-skin-dark .sv-page .input-group-text {
            background-color: #101a29;
            color: #e6edf3;
            border-color: #324a68;
        }

        html.app-skin-dark .sv-page .form-control::placeholder,
        html.app-skin-dark .sv-page .form-select::placeholder {
            color: #8fa2bb;
        }

        html.app-skin-dark .sv-page .btn-outline-secondary {
            color: #d0dced;
            border-color: #4f6481;
        }

        html.app-skin-dark .sv-page .btn-outline-secondary:hover {
            background: #243247;
            color: #e8f0ff;
            border-color: #5e779a;
        }

        html.app-skin-dark .sv-page .badge.bg-light.text-dark {
            background-color: #243247 !important;
            color: #d6e4f8 !important;
        }

        html.app-skin-dark .sv-page .badge.bg-soft-primary.text-primary {
            background-color: rgba(123, 161, 255, 0.2) !important;
            color: #b8ceff !important;
        }

        html.app-skin-dark .sv-page .badge.bg-soft-success.text-success {
            background-color: rgba(103, 214, 155, 0.18) !important;
            color: #8de6b6 !important;
        }

        html.app-skin-dark .sv-page .badge.bg-soft-warning.text-warning {
            background-color: rgba(255, 196, 106, 0.2) !important;
            color: #ffdca1 !important;
        }

        html.app-skin-dark .sv-page .badge.bg-soft-danger.text-danger {
            background-color: rgba(255, 159, 159, 0.18) !important;
            color: #ffc2c2 !important;
        }

        body.sv-chat-open {
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            .sv-action-buttons {
                justify-content: stretch;
            }

            .sv-action-buttons .btn {
                flex: 1 1 auto;
            }
        }

        @media (max-width: 767.98px) {
            .sv-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .sv-breadcrumb {
                font-size: 0.78rem;
            }

            .sv-status-form {
                grid-template-columns: 1fr;
            }

            .sv-final-form {
                justify-content: stretch;
            }

            .sv-final-form .btn {
                width: 100%;
            }

            .sv-chat-fab {
                right: 12px;
                bottom: 12px;
            }

            .sv-chat-overlay {
                padding: 0;
                align-items: stretch;
            }

            .sv-chat-panel {
                width: 100%;
                max-height: 100dvh;
                border-radius: 0;
                border: 0;
            }

            .sv-chat-head,
            .sv-chat-footer {
                padding: 10px;
            }

            .sv-chat-bubble {
                width: min(100%, 100%);
                max-width: 92%;
            }

            .sv-chat-footer .row > [class*="col-"] {
                margin-bottom: 6px;
            }
        }
    </style>

    @php
        $thesisStatusClass = match($thesis->status) {
            'proposal_approved', 'completed' => 'bg-soft-success text-success',
            'rejected' => 'bg-soft-danger text-danger',
            default => 'bg-soft-warning text-warning',
        };

        $currentFinalVersion = $thesis->versions->firstWhere('is_final_thesis', true);
        $chatItems = $thesis->feedbacks->sortBy('created_at');

        $isProposalSubmitted = !is_null($latestProposal);
        $isProposalApproved = in_array($thesis->status, ['proposal_approved', 'in_progress', 'completed'], true);
        $isDefenseDone = $thesis->status === 'completed';
    @endphp

    <div class="sv-page">
        <section class="sv-hero">
            <div class="sv-hero-content">
                <div>
                    <span class="sv-kicker">Supervisor Workspace</span>
                    <h1 class="sv-title">Thesis Details</h1>
                    <div class="sv-breadcrumb">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span>/</span>
                        <a href="{{ route('supervisor.students.index') }}">My Students</a>
                        <span>/</span>
                        <span>Thesis</span>
                    </div>

                    <div class="sv-chip-row">
                        <span class="sv-chip"><i class="feather-user"></i> {{ $thesis->student->user->name ?? 'Unknown Student' }}</span>
                        <span class="sv-chip"><i class="feather-book-open"></i> {{ $thesis->student->program ?? 'General Program' }}</span>
                        <span class="sv-chip"><i class="feather-calendar"></i> Created {{ $thesis->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <span class="badge {{ $thesisStatusClass }} text-uppercase">{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</span>
                    <a href="{{ route('supervisor.students.index') }}" class="btn btn-outline-secondary">
                        <i class="feather-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </section>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <section class="sv-panel mb-4">
                    <div class="sv-panel-header">
                        <h2 class="sv-subtitle mb-0">{{ $thesis->title }}</h2>
                    </div>
                    <div class="sv-panel-body">
                        <h6 class="sv-soft-title">Abstract</h6>
                        <p class="sv-abstract">{{ $latestProposal?->abstract ?? 'No abstract available yet.' }}</p>
                    </div>
                </section>

                @if($latestProposal)
                    @php
                        $proposalBadgeClass = match($latestProposal->status) {
                            'approved' => 'bg-soft-success text-success',
                            'rejected' => 'bg-soft-danger text-danger',
                            'revision_required' => 'bg-soft-warning text-warning',
                            default => 'bg-soft-primary text-primary',
                        };
                    @endphp

                    <section class="sv-panel mb-4">
                        <div class="sv-panel-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <h3 class="sv-subtitle mb-0">Proposal Review</h3>
                            <span class="badge {{ $proposalBadgeClass }} text-uppercase">
                                {{ str_replace('_', ' ', $latestProposal->status) }}
                            </span>
                        </div>

                        <div class="sv-panel-body">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="sv-soft-block h-100">
                                        <h6 class="sv-soft-title">Objectives</h6>
                                        <p class="sv-soft-text">{{ $latestProposal->objectives }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="sv-soft-block h-100">
                                        <h6 class="sv-soft-title">Methodology</h6>
                                        <p class="sv-soft-text">{{ $latestProposal->methodology }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($latestProposal->status === 'pending' || $latestProposal->status === 'revision_required')
                                <div class="sv-soft-block">
                                    <h6 class="fw-bold mb-3"><i class="feather-check-square me-2"></i>Supervisor Action</h6>
                                    <form action="{{ route('supervisor.proposals.review', $latestProposal) }}" method="POST" class="sv-action-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-uppercase text-muted">Feedback / Comments</label>
                                            <textarea name="comments" class="form-control" placeholder="Enter detailed feedback for the student..." required></textarea>
                                        </div>
                                        <div class="sv-action-buttons">
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
                                <div class="alert alert-light border mb-0">
                                    <h6 class="fw-bold mb-2">Supervisor Feedback</h6>
                                    <p class="mb-0 small">{{ $latestProposal->supervisor_comments ?? 'No comments provided.' }}</p>
                                </div>
                            @endif
                        </div>
                    </section>
                @else
                    <section class="sv-panel mb-4">
                        <div class="sv-panel-body text-center py-4 text-muted">
                            No proposal submitted yet.
                        </div>
                    </section>
                @endif

                <section class="sv-panel">
                    <div class="sv-panel-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                        <h3 class="sv-subtitle mb-0">Thesis Versions</h3>
                        <span class="small text-muted">Only approved versions can be marked as Final Thesis.</span>
                    </div>

                    <div class="sv-panel-body">
                        <div class="alert alert-light border mb-3">
                            @if($currentFinalVersion)
                                <span class="fw-semibold">Current Final Thesis:</span> v{{ $currentFinalVersion->version_number }}
                            @else
                                <span class="fw-semibold">Current Final Thesis:</span> Not selected yet.
                            @endif
                            <div class="small text-muted mt-1">
                                When defense is completed, setting a final thesis version will publish it to the public books portal.
                            </div>
                        </div>

                        <div class="sv-version-list">
                            @forelse($thesis->versions->sortByDesc('version_number') as $version)
                                @php
                                    $versionBadgeClass = match($version->status) {
                                        'approved' => 'bg-soft-success text-success',
                                        'rejected' => 'bg-soft-danger text-danger',
                                        'revision_required' => 'bg-soft-warning text-warning',
                                        default => 'bg-soft-primary text-primary',
                                    };
                                @endphp

                                <article class="sv-version-item">
                                    <div class="sv-version-top">
                                        <div>
                                            <p class="sv-version-title">Version v{{ $version->version_number }}</p>
                                            <p class="sv-version-meta">Uploaded {{ $version->created_at->format('M d, Y') }}</p>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge {{ $versionBadgeClass }} text-uppercase">
                                                {{ str_replace('_', ' ', $version->status) }}
                                            </span>
                                            @if($version->is_final_thesis)
                                                <span class="badge bg-soft-success text-success text-uppercase">Final Thesis</span>
                                            @endif
                                            <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download version">
                                                <i class="feather-download"></i>
                                            </a>
                                        </div>
                                    </div>

                                    @if($version->comments)
                                        <div class="small text-muted mt-2">Student notes: {{ $version->comments }}</div>
                                    @endif

                                    <div class="sv-version-controls">
                                        <form method="POST" action="{{ route('thesis.versions.status', $version) }}" class="sv-status-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select">
                                                @foreach(\App\Models\ThesisVersion::STATUSES as $status)
                                                    <option value="{{ $status }}" @selected($version->status === $status)>
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>

                                        @if($version->status === 'approved')
                                            <form method="POST" action="{{ route('supervisor.theses.final-version', $thesis) }}" class="sv-final-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="final_version_id" value="{{ $version->id }}" />
                                                <button type="submit" class="btn btn-outline-success" @disabled($version->is_final_thesis)>
                                                    @if($version->is_final_thesis)
                                                        Final Thesis Selected
                                                    @else
                                                        Set as Final Thesis
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="text-muted text-center py-3">No versions uploaded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="sv-panel mb-4">
                    <div class="sv-panel-header">
                        <h3 class="sv-subtitle mb-0">Documents</h3>
                    </div>
                    <div class="sv-panel-body">
                        @if($latestProposal && $latestProposal->file_path)
                            <a href="{{ Storage::url($latestProposal->file_path) }}" target="_blank" class="btn btn-primary sv-download-btn d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="feather-download-cloud"></i>
                                Download Proposal
                            </a>
                            <small class="d-block text-center text-muted mt-2">File Format: PDF/DOC</small>
                        @else
                            <div class="text-center text-muted py-2">No documents available.</div>
                        @endif
                    </div>
                </section>

                <section class="sv-panel">
                    <div class="sv-panel-header">
                        <h3 class="sv-subtitle mb-0">Status Timeline</h3>
                    </div>
                    <div class="sv-panel-body">
                        <ul class="sv-timeline">
                            <li class="sv-timeline-item is-done">
                                <p class="sv-timeline-label">Registered</p>
                                <p class="sv-timeline-date">{{ $thesis->created_at->format('M d, Y') }}</p>
                            </li>

                            <li class="sv-timeline-item {{ $isProposalSubmitted ? 'is-done' : '' }}">
                                <p class="sv-timeline-label {{ $isProposalSubmitted ? '' : 'text-muted' }}">Proposal Submitted</p>
                                @if($isProposalSubmitted)
                                    <p class="sv-timeline-date">{{ $latestProposal->created_at->format('M d, Y') }}</p>
                                @endif
                            </li>

                            <li class="sv-timeline-item {{ $isProposalApproved ? 'is-success' : '' }}">
                                <p class="sv-timeline-label {{ $isProposalApproved ? '' : 'text-muted' }}">Proposal Approved</p>
                                @if($isProposalApproved)
                                    <p class="sv-timeline-date">{{ $thesis->updated_at->format('M d, Y') }}</p>
                                @endif
                            </li>

                            <li class="sv-timeline-item {{ $isDefenseDone ? 'is-success' : '' }}">
                                <p class="sv-timeline-label {{ $isDefenseDone ? '' : 'text-muted' }}">Defense Completed</p>
                                @if($isDefenseDone)
                                    <p class="sv-timeline-date">{{ optional($thesis->published_at)->format('M d, Y') ?? $thesis->updated_at->format('M d, Y') }}</p>
                                @endif
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary sv-chat-fab" data-chat-toggle="thesis-chat" data-chat-button aria-expanded="false">
        <i class="feather-message-circle me-1"></i> Chat
    </button>

    <div class="sv-chat-overlay d-none" data-chatbox="thesis-chat" role="dialog" aria-modal="true" aria-label="Thesis chat dialog">
        <div class="sv-chat-panel">
            <div class="sv-chat-head">
                <h5 class="card-title fw-bold mb-0">Thesis Chat</h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-chat-toggle="thesis-chat">
                    <i class="feather-x me-1"></i> Close
                </button>
            </div>

            <div class="sv-chat-body">
                <div class="sv-chat-list">
                    @forelse($chatItems as $feedback)
                        @php
                            $isMine = $feedback->user_id === auth()->id();
                        @endphp
                        <div class="sv-chat-row {{ $isMine ? 'is-mine' : '' }}">
                            <div class="sv-chat-bubble">
                                <div class="sv-chat-meta">
                                    <span class="fw-semibold">{{ $feedback->user->name ?? 'User' }}</span>
                                    <span>• {{ $feedback->created_at->diffForHumans() }}</span>
                                </div>
                                @if($feedback->topic)
                                    <div class="mb-1"><span class="badge bg-light text-dark">{{ $feedback->topic }}</span></div>
                                @endif
                                @if($feedback->thesisVersion)
                                    <div class="mb-1"><span class="badge bg-soft-primary text-primary">v{{ $feedback->thesisVersion->version_number }}</span></div>
                                @endif
                                <div>{{ $feedback->comment }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted fst-italic">No messages yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="sv-chat-footer">
                <form method="POST" action="{{ route('thesis.feedback.store', $thesis) }}">
                    @csrf
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <input type="text" name="topic" class="form-control" placeholder="Topic (optional)">
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="thesis_version_id" class="form-select">
                                <option value="">General</option>
                                @foreach($thesis->versions->sortBy('version_number') as $version)
                                    <option value="{{ $version->id }}">Version v{{ $version->version_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="input-group">
                                <input type="text" name="comment" class="form-control" placeholder="Type a message..." required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="feather-send"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const buttons = document.querySelectorAll('[data-chat-toggle]');

            const setChatState = (target, open) => {
                const boxes = document.querySelectorAll(`[data-chatbox="${target}"]`);
                boxes.forEach((box) => {
                    box.classList.toggle('d-none', !open);
                });

                body.classList.toggle('sv-chat-open', open);

                buttons.forEach((button) => {
                    if (button.getAttribute('data-chat-toggle') !== target) {
                        return;
                    }

                    button.setAttribute('aria-expanded', open ? 'true' : 'false');
                    if (button.hasAttribute('data-chat-button')) {
                        button.innerHTML = open
                            ? '<i class="feather-x me-1"></i> Close'
                            : '<i class="feather-message-circle me-1"></i> Chat';
                    }
                });
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-chat-toggle');
                    const box = document.querySelector(`[data-chatbox="${target}"]`);
                    if (!box) {
                        return;
                    }

                    const isOpening = box.classList.contains('d-none');
                    setChatState(target, isOpening);
                });
            });

            document.querySelectorAll('[data-chatbox]').forEach((box) => {
                box.addEventListener('click', (event) => {
                    if (event.target !== box) {
                        return;
                    }

                    const target = box.getAttribute('data-chatbox');
                    setChatState(target, false);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') {
                    return;
                }

                document.querySelectorAll('[data-chatbox]').forEach((box) => {
                    if (box.classList.contains('d-none')) {
                        return;
                    }
                    const target = box.getAttribute('data-chatbox');
                    setChatState(target, false);
                });
            });
        });
    </script>
</x-app-layout>