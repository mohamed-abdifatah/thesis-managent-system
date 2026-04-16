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
            overflow: scroll;
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
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Thesis Review</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('examiner.defenses.index') }}">Defenses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thesis</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('examiner.defenses.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to Defenses
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card stretch stretch-full border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="h4 text-dark fw-bold mb-0">{{ $thesis->title }}</h3>
                        <span class="badge bg-soft-primary text-primary text-uppercase">
                            {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                        </span>
                    </div>
                    <div class="hstack gap-3 text-muted mb-4">
                        <div><i class="feather-user me-1"></i> <strong>{{ $thesis->student->user->name }}</strong></div>
                        <div class="vr"></div>
                        <div><i class="feather-user-check me-1"></i> Supervisor: {{ $thesis->supervisor->user->name ?? 'N/A' }}</div>
                    </div>
                    <p class="text-muted mb-0">Review thesis versions and leave feedback for the student.</p>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm">
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

        <div class="col-lg-4">
        </div>
    </div>

    <x-thesis-chat-overlay :thesis="$thesis" :chat-items="$thesis->feedbacks" />

</x-app-layout>
