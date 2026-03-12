<x-app-layout>
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Theses</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Thesis Management</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex d-md-none">
                    <a href="javascript:void(0)" class="page-header-right-close-toggle">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
            <div class="d-md-none d-flex align-items-center">
                <a href="javascript:void(0)" class="page-header-right-open-toggle">
                    <i class="feather-align-right fs-20"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @if(session('success'))
                                <div class="alert alert-success m-3" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger m-3" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <table class="table table-hover" id="thesisList">
                                <thead>
                                    <tr>
                                        <th class="wd-30">
                                            <div class="btn-group mb-1">
                                                <div class="custom-control custom-checkbox ms-1">
                                                    <input type="checkbox" class="custom-control-input" id="checkAllTheses">
                                                    <label class="custom-control-label" for="checkAllTheses"></label>
                                                </div>
                                            </div>
                                        </th>
                                        <th>Thesis Title</th>
                                        <th>Student</th>
                                        <th>Status</th>
                                        <th>Supervisor</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($theses as $thesis)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox ms-1">
                                                    <input type="checkbox" class="custom-control-input" id="checkBox_{{ $thesis->id }}">
                                                    <label class="custom-control-label" for="checkBox_{{ $thesis->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="javascript:void(0)" class="text-truncate fw-bold text-dark" style="max-width: 250px;" title="{{ $thesis->title }}">
                                                        {{ $thesis->title }}
                                                    </a>
                                                    <span class="fs-12 text-muted">Created {{ $thesis->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-text avatar-sm bg-primary-subtle text-primary rounded-circle">
                                                        {{ substr($thesis->student->user->name ?? 'U', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0)" class="text-truncate fw-bold text-dark d-block">
                                                            {{ $thesis->student->user->name ?? 'Unknown' }}
                                                        </a>
                                                        <span class="fs-12 text-muted">{{ $thesis->student->student_id_number ?? 'N/A' }}</span>
                                                    </div>
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
                                                <span class="badge {{ $statusClass }} text-uppercase">
                                                    {{ str_replace('_', ' ', $thesis->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($thesis->supervisor)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-text avatar-sm bg-info-subtle text-info rounded-circle">
                                                            {{ substr($thesis->supervisor->user->name ?? 'S', 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <span class="text-truncate fw-bold text-dark d-block">
                                                                {{ $thesis->supervisor->user->name }}
                                                            </span>
                                                            <span class="fs-12 text-muted">{{ $thesis->supervisor->specialization ?? 'General' }}</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light-brand" 
                                                            onclick="openAssignModal({{ $thesis->id }})"
                                                            title="{{ $thesis->supervisor ? 'Reassign' : 'Assign' }} Supervisor">
                                                        <i class="feather-user-plus"></i>
                                                    </button>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                                            <i class="feather-more-horizontal"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="feather-eye me-3"></i>
                                                                    <span>View Details</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="feather-folder-minus fs-1 text-muted opacity-50 mb-3 block"></i>
                                                <p class="text-muted">No theses found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-top-0">
                            {{ $theses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Assign Supervisor Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="supervisor_id" class="form-label">Select Supervisor</label>
                            <select name="supervisor_id" id="supervisor_id" class="form-select" required>
                                <option value="" selected disabled>-- Choose Supervisor --</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">
                                        {{ $supervisor->user->name }} ({{ $supervisor->specialization ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.openAssignModal = function(thesisId) {
                const form = document.getElementById('assignForm');
                // Construct the route dynamically
                form.action = `/admin/theses/${thesisId}/assign`;
                
                // Show the Bootstrap modal via JS (assuming bootstrap is available globally)
                // If not using a build step that adds it to window, you might use jQuery or direct DOM if enabled
                // For this theme, bootstrap is usually available
                try {
                    const modalEl = document.getElementById('assignModal');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } catch (e) {
                    console.error("Bootstrap modal error:", e);
                    // Fallback using jQuery if available or just styling
                    $('#assignModal').modal('show');
                }
            }
        });
    </script>
</x-app-layout>
