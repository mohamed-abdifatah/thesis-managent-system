<x-app-layout>
    @php
        $student = auth()->user()->student;
        $thesis = $student ? $student->accessibleThesis() : null;
        $proposals = $thesis ? $thesis->proposals()->latest()->take(6)->get() : collect();
        $latestProposal = $proposals->first();
        $versionCount = $thesis ? $thesis->versions()->count() : 0;
        $feedbackCount = $thesis ? $thesis->feedbacks()->count() : 0;
        $defense = $thesis ? $thesis->defense : null;
        $isDefenseUpcoming = $defense && $defense->scheduled_at && $defense->scheduled_at->isFuture();
    @endphp

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Thesis Progress Dashboard</h1>
            <p class="ta-page-subtitle">Track your proposal status, latest revisions, and next milestones from one place.</p>
        </div>
        <div class="ta-page-actions">
            @if(!$thesis)
                <a href="{{ route('proposals.create') }}" class="ta-chip-link">
                    <i class="feather-plus"></i>
                    Submit Proposal
                </a>
            @else
                <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link">
                    <i class="feather-upload-cloud"></i>
                    Thesis Versions
                </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="ta-chip-link">
                <i class="feather-user"></i>
                Profile
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Thesis Status</p>
                        <h3 class="mb-0">{{ $thesis ? ucfirst(str_replace('_', ' ', $thesis->status)) : 'Not Started' }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                        <i class="feather-book-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Supervisor</p>
                        <h3 class="mb-0 text-truncate" style="max-width: 180px;">{{ $thesis && $thesis->supervisor ? $thesis->supervisor->user->name : 'Unassigned' }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                        <i class="feather-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Proposal Submissions</p>
                        <h3 class="mb-0">{{ $proposals->count() }}</h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                        <i class="feather-file-text"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="ta-panel h-100">
                <div class="ta-panel-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Defense</p>
                        <h3 class="mb-0">
                            @if($defense)
                                {{ $isDefenseUpcoming ? 'Upcoming' : ucfirst($defense->status) }}
                            @else
                                Unscheduled
                            @endif
                        </h3>
                    </div>
                    <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                        <i class="feather-calendar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Recent Proposal Activity</h3>
                    <a href="{{ route('proposals.index') }}" class="ta-chip-link">View All</a>
                </div>
                <div class="ta-table-shell">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $proposal)
                                <tr>
                                    <td>
                                        <span class="fw-semibold d-inline-block text-truncate" style="max-width: 300px;">
                                            {{ \Illuminate\Support\Str::limit($proposal->title, 70) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($proposal->status) {
                                                'approved' => 'bg-soft-success text-success',
                                                'rejected' => 'bg-soft-danger text-danger',
                                                'revision_required' => 'bg-soft-warning text-warning',
                                                default => 'bg-soft-info text-info',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} text-uppercase">{{ str_replace('_', ' ', $proposal->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $proposal->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-light-brand">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No proposal history yet.
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
                    <h3>Current Snapshot</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Latest Proposal</span>
                        <span class="fw-semibold small">{{ $latestProposal ? ucfirst(str_replace('_', ' ', $latestProposal->status)) : 'None' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Thesis Versions</span>
                        <span class="fw-semibold small">{{ $versionCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Feedback Entries</span>
                        <span class="fw-semibold small">{{ $feedbackCount }}</span>
                    </div>
                    @if($defense && $defense->scheduled_at)
                        <div class="mt-3 p-3 rounded-3 bg-light">
                            <p class="mb-1 small text-muted">Defense Date</p>
                            <p class="mb-0 fw-semibold">{{ $defense->scheduled_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Quick Links</h3>
                </div>
                <div class="ta-panel-body d-grid gap-2">
                    <a href="{{ route('proposals.index') }}" class="ta-chip-link justify-content-between">
                        My Proposals <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('thesis.versions.index') }}" class="ta-chip-link justify-content-between">
                        Thesis Versions <i class="feather-arrow-right"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="ta-chip-link justify-content-between">
                        Profile Settings <i class="feather-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
