<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Supervisor Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted small">Welcome back, <strong>{{ auth()->user()->name }}</strong>!</span>
        </div>
    </div>

    <!-- Stats Widgets -->
    <div class="row g-4 mb-4">
        <!-- Assigned Students -->
        <div class="col-xxl-4 col-md-4">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <h5 class="text-muted small text-uppercase fw-bold mb-1">My Students</h5>
                            <h2 class="mb-0 text-dark fw-bold display-6">
                                {{ auth()->user()->supervisor ? auth()->user()->supervisor->theses()->count() : 0 }}
                            </h2>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                            <i class="feather-users" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="col-xxl-4 col-md-4">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <h5 class="text-muted small text-uppercase fw-bold mb-1">Pending Reviews</h5>
                            <h2 class="mb-0 text-dark fw-bold display-6">
                                @php
                                    $pendingCount = auth()->user()->supervisor ? 
                                        auth()->user()->supervisor->theses()->whereHas('proposals', function($q) {
                                            $q->where('status', 'pending');
                                        })->count() : 0;
                                @endphp
                                {{ $pendingCount }}
                            </h2>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                            <i class="feather-clock" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Theses -->
        <div class="col-xxl-4 col-md-4">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <h5 class="text-muted small text-uppercase fw-bold mb-1">Completed Theses</h5>
                            <h2 class="mb-0 text-dark fw-bold display-6">
                                {{ auth()->user()->supervisor ? auth()->user()->supervisor->theses()->where('status', 'completed')->count() : 0 }}
                            </h2>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                            <i class="feather-check-circle" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Columns -->
    <div class="row g-4">
        <!-- Main Content: Student List -->
        <div class="col-lg-8">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h5 class="card-title fw-bold mb-0">My Students</h5>
                    <a href="{{ route('supervisor.students.index') }}" class="btn btn-sm btn-light-brand text-primary">
                        View All Students <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @php
                        $theses = auth()->user()->supervisor ? auth()->user()->supervisor->theses()->with('student.user')->latest()->take(5)->get() : collect([]);
                    @endphp

                    @if($theses->isEmpty())
                        <div class="text-center py-5">
                            <i class="feather-users text-muted fs-1 opacity-25"></i>
                            <p class="text-muted mt-2">No students assigned yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Student</th>
                                        <th>Thesis Title</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($theses as $thesis)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-text avatar-sm bg-primary-subtle text-primary rounded-circle">
                                                    {{ substr($thesis->student->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold fs-14">{{ $thesis->student->user->name ?? 'Unknown' }}</h6>
                                                    <small class="text-muted">{{ $thesis->student->matriculation_number ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $thesis->title }}">
                                                {{ $thesis->title }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $thesis->status === 'completed' ? 'bg-soft-success text-success' : 
                                               ($thesis->status === 'rejected' ? 'bg-soft-danger text-danger' : 'bg-soft-warning text-warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('supervisor.theses.show', $thesis) }}" class="btn btn-sm btn-primary">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity / Notifications (Placeholder) -->
        <div class="col-lg-4">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0">Recent Activity</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-muted" style="min-height: 200px;">
                        <i class="feather-bell-off fs-1 opacity-25 mb-3"></i>
                        <p class="mb-0">No recent activity to show.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
