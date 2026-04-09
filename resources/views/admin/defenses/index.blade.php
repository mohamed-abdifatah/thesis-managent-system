<x-app-layout>
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Defense Sessions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Defense Sessions</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.defenses.create') }}" class="btn btn-primary">
                <i class="feather-plus me-1"></i> New Session
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover" id="defenseList">
                            <thead>
                                <tr>
                                    <th>Thesis</th>
                                    <th>Student</th>
                                    <th>Schedule</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Committee</th>
                                    <th class="text-end">Actions</th>
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
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ $session->committeeMembers->count() }} members
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.defenses.edit', $session) }}" class="btn btn-sm btn-light-brand">
                                                <i class="feather-edit-3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="feather-calendar fs-1 text-muted opacity-50 mb-3"></i>
                                            <p class="text-muted mb-0">No defense sessions scheduled yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0">
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
