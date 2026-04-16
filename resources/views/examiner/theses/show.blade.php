<x-app-layout>
    @php
        $versions = $thesis->versions->sortByDesc('version_number')->values();
        $totalVersions = $versions->count();
        $approvedVersions = $versions->where('status', 'approved')->count();
        $revisionsNeeded = $versions->whereIn('status', ['revision_required', 'under_review'])->count();
        $committeeMembers = $thesis->defense?->committeeMembers ?? collect();
    @endphp

    <style>
        .exv-page {
            --exv-surface: #ffffff;
            --exv-border: #d9e3f2;
            --exv-muted: #64758d;
            --exv-ink: #0f172a;
            --exv-primary: #7c2d12;
            --exv-primary-soft: #feede6;
        }

        html.app-skin-dark .exv-page {
            --exv-surface: #151d28;
            --exv-border: rgba(196, 213, 238, 0.16);
            --exv-muted: #9fb2c9;
            --exv-ink: #e8eef8;
            --exv-primary: #f6b297;
            --exv-primary-soft: rgba(246, 178, 151, 0.2);
        }

        .exv-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid #f0cabd;
            border-radius: 22px;
            background: linear-gradient(120deg, #fff7f2 0%, #feede6 56%, #fff8f3 100%);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            padding: 20px;
            margin-bottom: 16px;
        }

        html.app-skin-dark .exv-hero {
            border-color: #4b5f7d;
            background: linear-gradient(120deg, #2c2522 0%, #271f1d 56%, #251d1b 100%);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34);
        }

        .exv-hero::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            top: -130px;
            right: -90px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(124, 45, 18, 0.18) 0%, transparent 74%);
            pointer-events: none;
        }

        .exv-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .exv-kicker {
            margin: 0 0 7px;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9a3412;
            font-weight: 800;
        }

        html.app-skin-dark .exv-kicker {
            color: #ffc9b6;
        }

        .exv-title {
            margin: 0;
            color: var(--exv-ink);
            font-size: clamp(1.25rem, 2.6vw, 1.85rem);
            letter-spacing: -0.02em;
        }

        .exv-subtitle {
            margin: 8px 0 0;
            color: var(--exv-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .exv-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .exv-action {
            min-height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid var(--exv-border);
            background: var(--exv-surface);
            color: #66311b;
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 700;
            transition: all 0.18s ease;
        }

        html.app-skin-dark .exv-action {
            color: #d4e1f4;
        }

        .exv-action:hover {
            color: #9a3412;
            border-color: #eeb7a4;
            transform: translateY(-1px);
        }

        .exv-stat {
            border: 1px solid var(--exv-border);
            border-radius: 16px;
            background: var(--exv-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            padding: 14px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .exv-stat-label {
            margin: 0;
            color: var(--exv-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 0.67rem;
            font-weight: 800;
        }

        .exv-stat-value {
            margin: 3px 0 0;
            color: var(--exv-ink);
            font-size: 1.35rem;
            line-height: 1;
        }

        .exv-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--exv-primary);
            background: var(--exv-primary-soft);
            border: 1px solid #efd8cf;
        }

        html.app-skin-dark .exv-stat-icon {
            border-color: #4b5f7d;
            color: #ffc9b6;
        }

        .exv-panel {
            border: 1px solid var(--exv-border);
            border-radius: 18px;
            background: var(--exv-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .exv-panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--exv-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fffdfb 0%, #fff8f4 100%);
        }

        html.app-skin-dark .exv-panel-head {
            background: linear-gradient(180deg, #1c2736 0%, #192332 100%);
        }

        .exv-panel-title {
            margin: 0;
            color: var(--exv-ink);
            font-size: 0.98rem;
            letter-spacing: -0.01em;
            font-weight: 800;
        }

        .exv-version {
            border: 1px solid var(--exv-border);
            border-radius: 14px;
            padding: 12px;
            background: #fffcf9;
        }

        html.app-skin-dark .exv-version {
            background: #1a2737;
        }

        .exv-version-name {
            margin: 0;
            color: var(--exv-ink);
            font-size: 0.94rem;
            font-weight: 800;
        }

        .exv-sub {
            margin: 3px 0 0;
            color: var(--exv-muted);
            font-size: 0.76rem;
        }

        .exv-note {
            margin-top: 8px;
            padding: 8px;
            border: 1px solid var(--exv-border);
            border-radius: 10px;
            background: #ffffff;
            color: var(--exv-muted);
            font-size: 0.76rem;
            line-height: 1.55;
        }

        html.app-skin-dark .exv-note {
            background: #162131;
        }

        .exv-side-item {
            border: 1px solid var(--exv-border);
            border-radius: 12px;
            padding: 10px;
            background: #fffcf9;
        }

        html.app-skin-dark .exv-side-item {
            background: #1a2737;
        }

        @media (max-width: 767.98px) {
            .exv-hero {
                border-radius: 16px;
                padding: 16px;
            }

            .exv-actions {
                width: 100%;
            }

            .exv-action {
                flex: 1 1 auto;
                justify-content: center;
            }
        }
    </style>

    <div class="exv-page">
        <section class="exv-hero">
            <div class="exv-hero-content">
                <div>
                    <p class="exv-kicker">Examiner Thesis Review</p>
                    <h1 class="exv-title">{{ $thesis->title }}</h1>
                    <p class="exv-subtitle">Inspect thesis versions, update review status, and collaborate through feedback chat.</p>
                </div>
                <div class="exv-actions">
                    <a href="{{ route('examiner.defenses.index') }}" class="exv-action">
                        <i class="feather-arrow-left"></i>
                        Back to Defenses
                    </a>
                    <span class="badge bg-soft-primary text-primary text-uppercase align-self-center">
                        {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                    </span>
                </div>
            </div>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <article class="exv-stat">
                    <div>
                        <p class="exv-stat-label">Student</p>
                        <h3 class="exv-stat-value">{{ $thesis->student->user->name ?? 'N/A' }}</h3>
                    </div>
                    <span class="exv-stat-icon"><i class="feather-user"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exv-stat">
                    <div>
                        <p class="exv-stat-label">Total Versions</p>
                        <h3 class="exv-stat-value">{{ $totalVersions }}</h3>
                    </div>
                    <span class="exv-stat-icon"><i class="feather-layers"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exv-stat">
                    <div>
                        <p class="exv-stat-label">Approved</p>
                        <h3 class="exv-stat-value">{{ $approvedVersions }}</h3>
                    </div>
                    <span class="exv-stat-icon"><i class="feather-check-circle"></i></span>
                </article>
            </div>
            <div class="col-sm-6 col-xl-3">
                <article class="exv-stat">
                    <div>
                        <p class="exv-stat-label">Need Revision</p>
                        <h3 class="exv-stat-value">{{ $revisionsNeeded }}</h3>
                    </div>
                    <span class="exv-stat-icon"><i class="feather-refresh-ccw"></i></span>
                </article>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xxl-8">
                <section class="exv-panel">
                    <div class="exv-panel-head">
                        <h3 class="exv-panel-title">Thesis Versions</h3>
                        <span class="small text-muted">Newest first</span>
                    </div>

                    <div class="p-3 d-grid gap-3">
                        @forelse($versions as $version)
                            <article class="exv-version">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h4 class="exv-version-name">Version v{{ $version->version_number }}</h4>
                                        <p class="exv-sub">Uploaded {{ $version->created_at->format('M d, Y') }} | Feedback items: {{ $version->feedbacks->count() }}</p>
                                        @if($version->reviewer)
                                            <p class="exv-sub mb-0">Last reviewer: {{ $version->reviewer->name }}</p>
                                        @endif
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
                                    <div class="exv-note">Student notes: {{ $version->comments }}</div>
                                @endif

                                <form method="POST" action="{{ route('thesis.versions.status', $version) }}" class="row g-2 mt-2 align-items-end">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-9">
                                        <label class="form-label small text-muted">Review Status</label>
                                        <select name="status" class="form-select">
                                            @foreach(\App\Models\ThesisVersion::STATUSES as $status)
                                                <option value="{{ $status }}" @selected($version->status === $status)>
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-grid">
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </div>
                                </form>
                            </article>
                        @empty
                            <p class="text-muted text-center py-3 mb-0">No versions uploaded yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-4">
                <section class="exv-panel mb-4">
                    <div class="exv-panel-head">
                        <h3 class="exv-panel-title">Thesis Metadata</h3>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        <article class="exv-side-item">
                            <p class="text-muted small mb-1">Supervisor</p>
                            <p class="fw-semibold mb-0">{{ $thesis->supervisor->user->name ?? 'N/A' }}</p>
                        </article>
                        <article class="exv-side-item">
                            <p class="text-muted small mb-1">Current Thesis Status</p>
                            <p class="fw-semibold mb-0">{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</p>
                        </article>
                        <article class="exv-side-item">
                            <p class="text-muted small mb-1">Defense Schedule</p>
                            <p class="fw-semibold mb-0">{{ optional(optional($thesis->defense)->scheduled_at)->format('M d, Y h:i A') ?? 'Not scheduled' }}</p>
                        </article>
                    </div>
                </section>

                <section class="exv-panel">
                    <div class="exv-panel-head">
                        <h3 class="exv-panel-title">Committee Members</h3>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        @forelse($committeeMembers as $member)
                            <article class="exv-side-item">
                                <p class="fw-semibold mb-0">{{ $member->user->name ?? 'Committee Member' }}</p>
                                <p class="text-muted small mb-0">{{ $member->role ?? 'Examiner' }}</p>
                            </article>
                        @empty
                            <p class="text-muted mb-0">No committee members listed.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <x-thesis-chat-overlay :thesis="$thesis" :chat-items="$thesis->feedbacks" />
    </div>
</x-app-layout>
