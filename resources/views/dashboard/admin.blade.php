<x-app-layout>
    @php
        $usersCount = \App\Models\User::count();
        $thesesActive = \App\Models\Thesis::where('status', 'in_progress')->count();
        $proposalsCount = \App\Models\Proposal::count();
        $thesesCompleted = \App\Models\Thesis::where('status', 'completed')->count();

        // Fetch recent theses with student relation
        $recentTheses = \App\Models\Thesis::with('student.user')->latest()->take(5)->get();
    @endphp

    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Admin Dashboard</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->
    
    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Stats Widgets -->
        <div class="row">
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <h5 class="text-muted small text-uppercase fw-bold mb-1">Total Users</h5>
                                <h3 class="mb-0 text-dark fw-bold">{{ $usersCount }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                                <i class="feather-users" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="card-footer fs-11 fw-bold text-uppercase text-center bg-light p-2 text-decoration-none text-muted hover-primary">
                        Manage Users <i class="feather-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <h5 class="text-muted small text-uppercase fw-bold mb-1">Active Theses</h5>
                                <h3 class="mb-0 text-dark fw-bold">{{ $thesesActive }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-info text-info rounded-3">
                                <i class="feather-book-open" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.theses.index') }}" class="card-footer fs-11 fw-bold text-uppercase text-center bg-light p-2 text-decoration-none text-muted hover-primary">
                        View Theses <i class="feather-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <h5 class="text-muted small text-uppercase fw-bold mb-1">Total Proposals</h5>
                                <h3 class="mb-0 text-dark fw-bold">{{ $proposalsCount }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                                <i class="feather-file-text" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="card-footer fs-11 fw-bold text-uppercase text-center bg-light p-2 text-decoration-none text-muted hover-primary">
                        View Proposals <i class="feather-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <h5 class="text-muted small text-uppercase fw-bold mb-1">Completed</h5>
                                <h3 class="mb-0 text-dark fw-bold">{{ $thesesCompleted }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                                <i class="feather-check-circle" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="card-footer fs-11 fw-bold text-uppercase text-center bg-light p-2 text-decoration-none text-muted hover-primary">
                        View Archive <i class="feather-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Theses Table -->
            <div class="col-xxl-8 col-xl-8">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <h5 class="card-title fw-bold mb-0">Recent Theses</h5>
                        <a href="{{ route('admin.theses.index') }}" class="btn btn-sm btn-light-brand text-primary">
                            View All <i class="feather-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Title</th>
                                        <th>Student</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTheses as $thesis)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="d-inline-block text-truncate fw-bold text-dark" style="max-width: 250px;">
                                                {{ $thesis->title }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-sm bg-primary-subtle text-primary rounded-circle">
                                                    {{ substr($thesis->student->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <span class="fs-13 fw-medium">{{ $thesis->student->user->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($thesis->status) {
                                                    'completed' => 'bg-soft-success text-success',
                                                    'rejected' => 'bg-soft-danger text-danger',
                                                    'proposal_pending' => 'bg-soft-warning text-warning',
                                                    default => 'bg-soft-primary text-primary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $thesis->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 text-muted fs-12">
                                            {{ $thesis->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            No recent theses found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-xxl-4 col-xl-4">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center p-3">
                                <i class="feather-user-plus me-2 fs-5"></i>
                                <span class="fw-bold">Add New User</span>
                            </a>
                            <a href="{{ route('admin.theses.index') }}" class="btn btn-light-brand d-flex align-items-center justify-content-center p-3">
                                <i class="feather-book me-2 fs-5"></i>
                                <span class="fw-bold">Manage Theses</span>
                            </a>
                            <button class="btn btn-light-danger d-flex align-items-center justify-content-center p-3">
                                <i class="feather-settings me-2 fs-5"></i>
                                <span class="fw-bold">System Settings</span>
                            </button>
                        </div>

                        <!-- System Status (Mock) -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-muted text-uppercase fs-11 mb-3">System Status</h6>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fs-13">Server Uptime</span>
                                <span class="badge bg-soft-success text-success">99.9%</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fs-13">Last Backup</span>
                                <span class="text-muted fs-13">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
