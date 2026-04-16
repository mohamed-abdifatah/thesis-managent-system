<x-app-layout>
    @php
        $catalogThesisQuery = \App\Models\Thesis::query()
            ->whereIn('status', ['defended', 'completed']);

        $catalogReadyCount = (clone $catalogThesisQuery)
            ->where('status', 'defended')
            ->where('is_library_approved', false)
            ->whereHas('defense', fn ($query) => $query->where('status', 'completed'))
            ->whereHas('approvedVersions')
            ->count();

        $pendingValidationCount = (clone $catalogThesisQuery)
            ->where('status', 'defended')
            ->where(function ($query) {
                $query->doesntHave('defense')
                    ->orWhereHas('defense', fn ($defenseQuery) => $defenseQuery->where('status', '!=', 'completed'))
                    ->orDoesntHave('approvedVersions');
            })
            ->count();

        $publishedCount = (clone $catalogThesisQuery)
            ->where('is_public', true)
            ->count();

        $totalCatalogCount = (clone $catalogThesisQuery)
            ->where('is_library_approved', true)
            ->count();

        $catalogQueue = \App\Models\Thesis::with(['student.user', 'supervisor.user', 'defense'])
            ->whereIn('status', ['defended', 'completed'])
            ->latest()
            ->take(8)
            ->get();
    @endphp

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Library Workspace</span>
            <h1 class="ta-page-title">Librarian Dashboard</h1>
            <p class="ta-page-subtitle">Review completed theses, validate defense completion, and keep the digital catalog current.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('profile.edit') }}" class="ta-chip-link">
                <i class="feather-user"></i>
                Profile
            </a>
            <a href="{{ route('library.catalog.index') }}" class="ta-chip-link">
                <i class="feather-book"></i>
                Manage Catalog
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Catalog Ready</p>
                        <h3 class="mb-0">{{ $catalogReadyCount }}</h3>
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
                        <p class="text-muted mb-1 small">Pending Validation</p>
                        <h3 class="mb-0">{{ $pendingValidationCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                        <i class="feather-alert-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Published Books</p>
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
                        <p class="text-muted mb-1 small">Completed Theses</p>
                        <h3 class="mb-0">{{ $totalCatalogCount }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                        <i class="feather-archive"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Catalog Processing Queue</h3>
                    <span class="text-muted small">{{ $catalogQueue->count() }} recent entries</span>
                </div>
                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Thesis</th>
                                <th>Student</th>
                                <th>Supervisor</th>
                                <th>Defense</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($catalogQueue as $thesis)
                                @php
                                    $isDefenseComplete = $thesis->defense && $thesis->defense->status === 'completed';
                                    $isValidated = (bool) $thesis->is_library_approved;
                                    $isPublished = (bool) $thesis->is_public;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="fw-semibold d-inline-block text-truncate" style="max-width: 260px;">
                                            {{ \Illuminate\Support\Str::limit($thesis->title, 60) }}
                                        </span>
                                    </td>
                                    <td>{{ $thesis->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $thesis->supervisor->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="small text-muted">
                                            {{ optional(optional($thesis->defense)->scheduled_at)->format('M d, Y') ?? 'Unscheduled' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($isPublished)
                                            <span class="badge bg-soft-primary text-primary">Public</span>
                                        @elseif($isValidated)
                                            <span class="badge bg-soft-info text-info">Validated</span>
                                        @elseif($isDefenseComplete)
                                            <span class="badge bg-soft-success text-success">Ready to Validate</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Needs Review</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No completed theses are ready for catalog processing yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="ta-panel mb-4">
                <div class="ta-panel-head">
                    <h3>Workflow Guide</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="mb-3">
                        <p class="small text-muted mb-1">1. Validate Defense Completion</p>
                        <p class="mb-0 small">Ensure the defense session is marked completed before catalog publication.</p>
                    </div>
                    <div class="mb-3">
                        <p class="small text-muted mb-1">2. Confirm Digital Version</p>
                        <p class="mb-0 small">Verify at least one thesis file version exists for the final archive copy.</p>
                    </div>
                    <div>
                        <p class="small text-muted mb-1">3. Final Catalog Check</p>
                        <p class="mb-0 small">Review title, student identity, and supervisor data for catalog accuracy.</p>
                    </div>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Quick Links</h3>
                </div>
                <div class="ta-panel-body d-grid gap-2">
                    <a href="{{ route('library.catalog.index') }}" class="ta-chip-link justify-content-between">
                        Catalog Review <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('books.index') }}" class="ta-chip-link justify-content-between" target="_blank" rel="noopener noreferrer">
                        Public Books Portal <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('dashboard') }}" class="ta-chip-link justify-content-between">
                        Dashboard Home <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="ta-chip-link justify-content-between">
                        Profile Settings <i class="feather-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
