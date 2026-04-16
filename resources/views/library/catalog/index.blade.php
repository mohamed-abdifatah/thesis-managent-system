<x-app-layout>
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Library Operations</span>
            <h1 class="ta-page-title">Catalog Review</h1>
            <p class="ta-page-subtitle">Validate defended theses, then publish approved records to the public books portal.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('books.index') }}" class="ta-chip-link" target="_blank" rel="noopener noreferrer">
                <i class="feather-globe"></i>
                Open Public Portal
            </a>
            <a href="{{ route('dashboard') }}" class="ta-chip-link">
                <i class="feather-grid"></i>
                Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Ready for Validation</p>
                        <h3 class="mb-0">{{ $readyForValidationCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                        <i class="feather-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Validated</p>
                        <h3 class="mb-0">{{ $validatedCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                        <i class="feather-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Published</p>
                        <h3 class="mb-0">{{ $publishedCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                        <i class="feather-globe"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Needs Follow-up</p>
                        <h3 class="mb-0">{{ $pendingIssuesCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                        <i class="feather-alert-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ta-panel mb-4">
        <div class="ta-panel-body">
            <form method="GET" action="{{ route('library.catalog.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label small text-muted" for="q">Search</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Title, student, or group" />
                </div>
                <div class="col-lg-3">
                    <label class="form-label small text-muted" for="workflow">Workflow</label>
                    <select id="workflow" name="workflow" class="form-select">
                        <option value="all" @selected($workflow === 'all')>All</option>
                        <option value="ready" @selected($workflow === 'ready')>Ready for validation</option>
                        <option value="validated" @selected($workflow === 'validated')>Validated (not public)</option>
                        <option value="published" @selected($workflow === 'published')>Published</option>
                    </select>
                </div>
                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                    <a href="{{ route('library.catalog.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <p class="small text-muted mb-0 mt-3">
                Working versions (drafts, revisions, and review feedback) stay private. Only one selected final thesis version is published to the public portal.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Catalog Queue</h3>
                    <span class="text-muted small">{{ $theses->total() }} records</span>
                </div>

                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Thesis</th>
                                <th>Owner</th>
                                <th>Defense</th>
                                <th>Approved Versions</th>
                                <th>Workflow</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($theses as $thesis)
                                @php
                                    $isDefenseComplete = $thesis->defense && $thesis->defense->status === 'completed';
                                    $hasApprovedVersion = $thesis->approved_versions_count > 0;
                                    $canValidate = !$thesis->is_library_approved && $thesis->status === 'defended' && $isDefenseComplete && $hasApprovedVersion;
                                    $canPublish = $thesis->is_library_approved && $thesis->status === 'completed' && !$thesis->is_public;
                                    $approvedVersionOptions = $thesis->approvedVersions->sortByDesc('version_number')->values();
                                    $selectedFinalVersionId = $thesis->finalThesisVersion?->id ?? $approvedVersionOptions->first()?->id;
                                    $owner = $thesis->group
                                        ? $thesis->group->name
                                        : ($thesis->student->user->name ?? 'Unknown');
                                @endphp
                                <tr>
                                    <td>
                                        <span class="fw-semibold d-inline-block text-truncate" style="max-width: 280px;">
                                            {{ \Illuminate\Support\Str::limit($thesis->title, 72) }}
                                        </span>
                                        <div class="small text-muted mt-1">{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $owner }}</span>
                                        <div class="small text-muted">Supervisor: {{ $thesis->supervisor->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        @if($isDefenseComplete)
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        @elseif($thesis->defense)
                                            <span class="badge bg-soft-warning text-warning">{{ ucfirst($thesis->defense->status) }}</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Not Scheduled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary">{{ $thesis->approved_versions_count }}</span>
                                        <div class="small text-muted mt-1">
                                            @if($thesis->finalThesisVersion)
                                                Final thesis: v{{ $thesis->finalThesisVersion->version_number }}
                                            @else
                                                Final thesis not selected
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($thesis->is_public)
                                            <span class="badge bg-soft-primary text-primary">Public</span>
                                        @elseif($thesis->is_library_approved)
                                            <span class="badge bg-soft-info text-info">Validated</span>
                                        @elseif($canValidate)
                                            <span class="badge bg-soft-success text-success">Ready to Validate</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Blocked</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-grid gap-2" style="min-width: 220px;">
                                            @if($canValidate)
                                                <form method="POST" action="{{ route('library.catalog.validate', $thesis) }}" class="d-grid gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <textarea name="catalog_notes" rows="2" class="form-control form-control-sm" placeholder="Validation notes (optional)"></textarea>
                                                    <button type="submit" class="btn btn-sm btn-success">Validate</button>
                                                </form>
                                            @endif

                                            @if($canPublish)
                                                <form method="POST" action="{{ route('library.catalog.publish', $thesis) }}" class="d-grid gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($approvedVersionOptions->isNotEmpty())
                                                        <select name="final_version_id" class="form-select form-select-sm" required>
                                                            @foreach($approvedVersionOptions as $approvedVersion)
                                                                <option value="{{ $approvedVersion->id }}" @selected($selectedFinalVersionId === $approvedVersion->id)>
                                                                    v{{ $approvedVersion->version_number }} @if($approvedVersion->unit_number) (unit {{ $approvedVersion->unit_number }}) @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    <textarea name="publish_notes" rows="2" class="form-control form-control-sm" placeholder="Publication notes (optional)"></textarea>
                                                    <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                                </form>
                                            @endif

                                            @if($thesis->is_public)
                                                <a href="{{ route('books.show', $thesis) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener noreferrer">View Public Book</a>
                                                <form method="POST" action="{{ route('library.catalog.unpublish', $thesis) }}" class="d-grid gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <textarea name="unpublish_reason" rows="2" class="form-control form-control-sm" placeholder="Required reason to unpublish" required></textarea>
                                                    <button type="submit" class="btn btn-sm btn-light">Unpublish</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No defended or completed theses found for catalog operations.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="ta-panel-body border-top">
                    {{ $theses->links() }}
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Recent Catalog Activity</h3>
                </div>
                <div class="ta-panel-body">
                    @forelse($recentEvents as $event)
                        <div class="d-flex align-items-start justify-content-between gap-2 py-2 border-bottom">
                            <div>
                                <div class="fw-semibold text-truncate" style="max-width: 220px;">{{ \Illuminate\Support\Str::limit($event->thesis->title ?? 'Thesis', 46) }}</div>
                                <div class="small text-muted">
                                    {{ ucfirst($event->action) }} by {{ $event->user->name ?? 'System' }}
                                </div>
                                @if($event->notes)
                                    <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($event->notes, 88) }}</div>
                                @endif
                            </div>
                            <span class="small text-muted">{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No activity recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
