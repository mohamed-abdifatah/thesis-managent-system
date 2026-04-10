<x-app-layout>
    <style>
        .chat-panel .card-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #efeae2;
        }

        .chat-shell {
            /* No background or padding needed here anymore */
        }

        .chat-overlay {
            position: fixed;
            inset: 0;
            z-index: 1060;
            background: rgba(15, 20, 27, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .chat-panel {
            width: 100%;
            max-width: 1100px;
            max-height: 85vh;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 20, 27, 0.2);
            overflow: scroll;
        }

        .chat-panel .card {
            border: 0;
            border-radius: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chat-panel .card-body {
            flex: 1;
            min-height: 0;
            overflow: hidden;
            padding: 1rem;
        }

        .chat-panel .card-footer {
            background: #ffffff;
            flex: 0 0 auto;
            border-top: 1px solid rgba(27, 31, 36, 0.08);
            padding: 14px 16px;
        }

        .chat-row {
            display: flex;
            margin-bottom: 10px;
        }

        .chat-row.is-mine {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: 75%;
            padding: 10px 12px;
            border-radius: 12px;
            background: #ffffff;
            color: #1b1f24;
            box-shadow: 0 4px 12px rgba(15, 20, 27, 0.08);
        }

        .chat-row.is-mine .chat-bubble {
            background: #dcf8c6;
        }

        .chat-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .chat-input {
            background: #ffffff;
            border: 1px solid rgba(27, 31, 36, 0.15);
            border-radius: 999px;
            padding: 10px 14px;
        }

        .chat-send {
            border-radius: 999px;
        }

        .chat-fab {
            position: fixed !important;
            right: 24px;
            bottom: 24px;
            z-index: 2000;
            border-radius: 999px;
            padding: 12px 18px;
            box-shadow: 0 10px 24px rgba(15, 20, 27, 0.18);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .chat-shell::-webkit-scrollbar {
            width: 8px;
        }

        .chat-shell::-webkit-scrollbar-track {
            background: rgba(27, 31, 36, 0.08);
            border-radius: 999px;
        }

        .chat-shell::-webkit-scrollbar-thumb {
            background: rgba(27, 31, 36, 0.35);
            border-radius: 999px;
        }

        .chat-shell::-webkit-scrollbar-thumb:hover {
            background: rgba(27, 31, 36, 0.5);
        }

        .chat-panel .card-header {
            border-bottom: 1px solid rgba(27, 31, 36, 0.08);
        }
    </style>
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

            <div class="card stretch stretch-full border-0 shadow-sm mt-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0">Thesis Versions</h5>
                </div>
                <div class="card-body">
                    @forelse($thesis->versions->sortByDesc('version_number') as $version)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                <div>
                                    <div class="fw-semibold">Version v{{ $version->version_number }}</div>
                                    <div class="text-muted small">Uploaded {{ $version->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark text-uppercase">
                                        {{ str_replace('_', ' ', $version->status) }}
                                    </span>
                                    <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="feather-download"></i>
                                    </a>
                                </div>
                            </div>

                            @if($version->comments)
                                <div class="mt-2 text-muted small">Student notes: {{ $version->comments }}</div>
                            @endif

                            <div class="row g-2 mt-3">
                                <div class="col-md-12">
                                    <form method="POST" action="{{ route('thesis.versions.status', $version) }}" class="d-flex gap-2">
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
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-3">No versions uploaded yet.</div>
                    @endforelse
                </div>
            </div>

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

    <button type="button" class="btn btn-primary chat-fab" data-chat-toggle="thesis-chat" data-chat-button>
        <i class="feather-message-circle me-1"></i> Chat
    </button>

    <div class="chat-overlay d-none" data-chatbox="thesis-chat">
        <div class="chat-panel">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0">Thesis Chat</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-chat-toggle="thesis-chat">
                        <i class="feather-x me-1"></i> Close
                    </button>
                </div>
                <div class="card-body">
                    <div class="chat-shell">
                        @php
                            $chatItems = $thesis->feedbacks->sortBy('created_at');
                        @endphp
                        @forelse($chatItems as $feedback)
                            @php
                                $isMine = $feedback->user_id === auth()->id();
                            @endphp
                            <div class="chat-row {{ $isMine ? 'is-mine' : '' }}">
                                <div class="chat-bubble">
                                    <div class="chat-meta">
                                        <span class="fw-semibold">{{ $feedback->user->name ?? 'User' }}</span>
                                        <span>· {{ $feedback->created_at->diffForHumans() }}</span>
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
                <div class="card-footer">
                    <form method="POST" action="{{ route('thesis.feedback.store', $thesis) }}" class="w-100">
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
                                    <input type="text" name="comment" class="form-control chat-input" placeholder="Type a message..." required>
                                    <button class="btn btn-primary chat-send" type="submit">
                                        <i class="feather-send"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('[data-chat-toggle]');
            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-chat-toggle');
                    const boxes = document.querySelectorAll(`[data-chatbox="${target}"]`);
                    if (!boxes.length) {
                        return;
                    }
                    const isHidden = boxes[0].classList.contains('d-none');

                    boxes.forEach((box) => box.classList.toggle('d-none', !isHidden));
                    button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                    if (button.hasAttribute('data-chat-button')) {
                        button.innerHTML = isHidden
                            ? '<i class="feather-x me-1"></i> Close'
                            : '<i class="feather-message-circle me-1"></i> Chat';
                    }
                });
            });
        });
    </script>

</x-app-layout>
