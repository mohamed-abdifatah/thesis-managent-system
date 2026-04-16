<x-app-layout>
    @php
        $statusClass = match($proposal->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'revision_required' => 'warning',
            default => 'pending',
        };

        $studentName = $proposal->thesis?->student?->user?->name ?? 'Unknown Student';
        $supervisorName = $proposal->thesis?->supervisor?->user?->name ?? 'Unassigned';

        $reviewStarted = $proposal->status !== 'pending';
        $finalized = in_array($proposal->status, ['approved', 'rejected'], true);
    @endphp

    <style>
        .pv-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            border: 1px solid transparent;
            font-size: 0.73rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .pv-status.pending {
            color: #855800;
            background: #fff8e9;
            border-color: #f7dba6;
        }

        .pv-status.success {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .pv-status.warning {
            color: #9a6400;
            background: #fff4dd;
            border-color: #f6d49a;
        }

        .pv-status.danger {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .pv-hero-title {
            margin: 0;
            color: #10233e;
            font-weight: 800;
            line-height: 1.3;
            font-size: 1.08rem;
        }

        .pv-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .pv-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d8e6f8;
            background: #f7faff;
            padding: 4px 10px;
            font-size: 0.74rem;
            font-weight: 700;
            color: #415a76;
        }

        .pv-block {
            border: 1px solid #dce7f5;
            border-radius: 12px;
            background: #fbfdff;
            padding: 12px;
        }

        .pv-block h4 {
            margin: 0 0 8px;
            font-size: 0.8rem;
            color: #10233e;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .pv-block p {
            margin: 0;
            color: #4f6380;
            line-height: 1.55;
            white-space: pre-wrap;
        }

        .pv-timeline {
            display: grid;
            gap: 10px;
        }

        .pv-step {
            border: 1px solid #dce7f5;
            border-radius: 10px;
            padding: 10px;
            background: #fbfdff;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .pv-step .dot {
            width: 22px;
            height: 22px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d0ddf0;
            background: #f3f7fd;
            color: #6c7f98;
            flex-shrink: 0;
        }

        .pv-step h5 {
            margin: 0;
            color: #10233e;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .pv-step p {
            margin: 3px 0 0;
            color: #66758d;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .pv-step.done {
            border-color: #bfead1;
            background: #f4fcf7;
        }

        .pv-step.done .dot {
            border-color: #98d8b7;
            color: #0f7b46;
            background: #e8f7ef;
        }

        .pv-step.current {
            border-color: #cfddff;
            background: #f3f7ff;
        }

        .pv-step.current .dot {
            border-color: #b9d0ff;
            color: #1d4ed8;
            background: #e8f0ff;
        }

        html.app-skin-dark .pv-hero-title,
        html.app-skin-dark .pv-block h4,
        html.app-skin-dark .pv-step h5 {
            color: #e6edf7;
        }

        html.app-skin-dark .pv-chip {
            color: #c8d3e3;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .pv-status.pending {
            color: #ffd99b;
            background: rgba(120, 85, 20, 0.34);
            border-color: rgba(173, 132, 63, 0.45);
        }

        html.app-skin-dark .pv-status.success {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .pv-status.warning {
            color: #ffdeaa;
            background: rgba(138, 101, 39, 0.33);
            border-color: rgba(194, 154, 92, 0.47);
        }

        html.app-skin-dark .pv-status.danger {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        html.app-skin-dark .pv-block,
        html.app-skin-dark .pv-step {
            border-color: rgba(255, 255, 255, 0.14);
            background: #172232;
        }

        html.app-skin-dark .pv-block p,
        html.app-skin-dark .pv-step p {
            color: #a3b1c4;
        }

        html.app-skin-dark .pv-step .dot {
            border-color: rgba(255, 255, 255, 0.17);
            color: #a3b1c4;
            background: #243349;
        }

        html.app-skin-dark .pv-step.done {
            border-color: rgba(90, 175, 133, 0.42);
            background: rgba(35, 115, 73, 0.24);
        }

        html.app-skin-dark .pv-step.current {
            border-color: rgba(110, 154, 242, 0.45);
            background: rgba(48, 88, 168, 0.26);
        }
    </style>

    @include('partials.student-account-refresh')

    <div class="{{ auth()->user()->hasRole('student') ? 'stu-refresh' : '' }}">
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Proposal Details</h1>
            <p class="ta-page-subtitle">Review proposal content, attached files, and supervisor decision status.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('proposals.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Back to Proposals
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel mb-3">
                <div class="ta-panel-body">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                        <h3 class="pv-hero-title">{{ $proposal->title }}</h3>
                        <span class="pv-status {{ $statusClass }}">
                            <i class="feather-flag"></i>
                            {{ str_replace('_', ' ', $proposal->status) }}
                        </span>
                    </div>

                    <div class="pv-meta">
                        <span class="pv-chip"><i class="feather-user"></i> {{ $studentName }}</span>
                        <span class="pv-chip"><i class="feather-user-check"></i> {{ $supervisorName }}</span>
                        <span class="pv-chip"><i class="feather-calendar"></i> {{ $proposal->created_at->format('M d, Y') }}</span>
                        <span class="pv-chip"><i class="feather-hash"></i> Proposal {{ $proposal->id }}</span>
                    </div>
                </div>
            </div>

            <div class="ta-panel mb-3">
                <div class="ta-panel-head">
                    <h3>Abstract</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="pv-block">
                        <p>{{ $proposal->abstract }}</p>
                    </div>
                </div>
            </div>

            <div class="ta-panel mb-3">
                <div class="ta-panel-head">
                    <h3>Research Details</h3>
                </div>
                <div class="ta-panel-body d-grid gap-3">
                    <div class="pv-block">
                        <h4>Objectives</h4>
                        <p>{{ $proposal->objectives }}</p>
                    </div>

                    <div class="pv-block">
                        <h4>Methodology</h4>
                        <p>{{ $proposal->methodology }}</p>
                    </div>

                    @if($proposal->literature_review)
                        <div class="pv-block">
                            <h4>Literature Review</h4>
                            <p>{{ $proposal->literature_review }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($proposal->supervisor_comments || $proposal->status !== 'pending')
                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>Supervisor Feedback</h3>
                    </div>
                    <div class="ta-panel-body">
                        <div class="pv-block">
                            <p>{{ $proposal->supervisor_comments ?: 'No specific comments provided yet.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-4">
            <div class="ta-panel mb-3">
                <div class="ta-panel-head">
                    <h3>Attachments</h3>
                </div>
                <div class="ta-panel-body">
                    @if($proposal->file_path)
                        <a href="{{ Storage::url($proposal->file_path) }}" target="_blank" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2">
                            <i class="feather-download-cloud"></i>
                            Download Document
                        </a>
                        <p class="small text-muted mb-0 mt-2">PDF/DOC file submitted by student.</p>
                    @else
                        <p class="small text-muted mb-0">No file attached.</p>
                    @endif
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Status Timeline</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="pv-timeline">
                        <div class="pv-step done">
                            <span class="dot"><i class="feather-upload"></i></span>
                            <div>
                                <h5>Submitted</h5>
                                <p>{{ $proposal->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="pv-step {{ $reviewStarted ? 'current' : '' }} {{ $finalized ? 'done' : '' }}">
                            <span class="dot"><i class="feather-search"></i></span>
                            <div>
                                <h5>Under Review</h5>
                                <p>{{ $reviewStarted ? 'Supervisor review started.' : 'Waiting for supervisor review.' }}</p>
                            </div>
                        </div>

                        <div class="pv-step {{ $finalized ? 'done' : '' }}">
                            <span class="dot"><i class="feather-flag"></i></span>
                            <div>
                                <h5>Final Decision</h5>
                                <p>
                                    @if($finalized)
                                        {{ ucfirst($proposal->status) }}
                                    @else
                                        Pending final decision.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>