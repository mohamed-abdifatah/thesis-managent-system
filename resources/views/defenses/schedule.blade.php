<x-app-layout>
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Academic Workflow</span>
            <h1 class="ta-page-title">Defense Schedule</h1>
            <p class="ta-page-subtitle">Track upcoming sessions, committee assignments, and current defense status.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('dashboard') }}" class="ta-chip-link">
                <i class="feather-grid"></i>
                Dashboard
            </a>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <h3>Upcoming Sessions</h3>
            <span class="text-muted small">{{ $sessions->count() }} scheduled</span>
        </div>
        <div class="ta-table-shell">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Thesis</th>
                        <th>Student</th>
                        <th>Schedule</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Committee</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td class="fw-semibold text-truncate" style="max-width: 220px;">
                                {{ $session->thesis->title ?? 'N/A' }}
                            </td>
                            <td>{{ $session->thesis->student->user->name ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $session->scheduled_at->format('M d, Y') }}</span>
                                    <span class="text-muted fs-12">{{ $session->scheduled_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td>{{ $session->location ?? 'TBD' }}</td>
                            <td>
                                @php
                                    $statusClass = match($session->status) {
                                        'completed' => 'bg-soft-success text-success',
                                        'cancelled' => 'bg-soft-danger text-danger',
                                        default => 'bg-soft-warning text-warning'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-uppercase">{{ $session->status }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @foreach($session->committeeMembers as $member)
                                        <span class="text-muted">{{ $member->user->name ?? 'Examiner' }} ({{ ucfirst($member->role) }})</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="feather-calendar fs-1 text-muted opacity-50 mb-3"></i>
                                <p class="text-muted mb-0">No defense sessions scheduled yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
