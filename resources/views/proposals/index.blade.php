<x-app-layout>
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Proposals</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Proposals</li>
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
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                        <span class="reportrange-picker-field"></span>
                    </div>
                    <div class="dropdown filter-dropdown">
                        <a class="btn btn-md btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                            <i class="feather-filter me-2"></i>
                            <span>Filter</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="roleOne">
                                    <label class="custom-control-label" for="roleOne">Pending</label>
                                </div>
                            </div>
                            <div class="dropdown-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="roleTwo">
                                    <label class="custom-control-label" for="roleTwo">Approved</label>
                                </div>
                            </div>
                            <div class="dropdown-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="roleThree">
                                    <label class="custom-control-label" for="roleThree">Rejected</label>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="feather-rotate-ccw me-2"></i>
                                <span>Reset Filter</span>
                            </a>
                        </div>
                    </div>
                    @role('student')
                    <a href="{{ route('proposals.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Create Proposal</span>
                    </a>
                    @endrole
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
                            <table class="table table-hover" id="proposalList">
                                <thead>
                                    <tr>
                                        <th class="wd-30">
                                            <div class="btn-group mb-1">
                                                <div class="custom-control custom-checkbox ms-1">
                                                    <input type="checkbox" class="custom-control-input" id="checkAllProposal">
                                                    <label class="custom-control-label" for="checkAllProposal"></label>
                                                </div>
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($proposals as $proposal)
                                    <tr class="single-item">
                                        <td>
                                            <div class="item-checkbox ms-1">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkbox" id="checkBox_{{ $proposal->id }}">
                                                    <label class="custom-control-label" for="checkBox_{{ $proposal->id }}"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="{{ route('proposals.show', $proposal) }}" class="fw-bold">#{{ $proposal->id }}</a></td>
                                        <td>
                                            <a href="javascript:void(0)" class="hstack gap-3">
                                                @php
                                                    $studentName = optional(optional(optional($proposal->thesis)->student)->user)->name ?? 'Unknown';
                                                    $studentEmail = optional(optional(optional($proposal->thesis)->student)->user)->email ?? '';
                                                    $initial = substr($studentName, 0, 1);
                                                @endphp
                                                <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                                    {{ $initial }}
                                                </div>
                                                <div>
                                                    <span class="text-truncate-1-line">{{ $studentName }}</span>
                                                    <small class="fs-12 fw-normal text-muted">{{ $studentEmail }}</small>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-truncate-1-line" style="max-width: 250px; display: inline-block;">{{ $proposal->title }}</span>
                                        </td>
                                        <td>{{ $proposal->created_at->format('Y-m-d, h:iA') }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($proposal->status) {
                                                    'approved' => 'bg-soft-success text-success',
                                                    'rejected' => 'bg-soft-danger text-danger',
                                                    default => 'bg-soft-warning text-warning',
                                                };
                                            @endphp
                                            <div class="badge {{ $badgeClass }}">{{ ucfirst($proposal->status) }}</div>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="{{ route('proposals.show', $proposal) }}" class="avatar-text avatar-md">
                                                    <i class="feather feather-eye"></i>
                                                </a>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                                        <i class="feather feather-more-horizontal"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('proposals.show', $proposal) }}">
                                                                <i class="feather feather-eye me-3"></i>
                                                                <span>View</span>
                                                            </a>
                                                        </li>
                                                        <!-- Add other actions like Edit/Delete based on permissions -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="feather-file-minus fs-1 text-muted mb-2"></i>
                                                <span class="text-muted">No proposals found.</span>
                                                @role('student')
                                                <a href="{{ route('proposals.create') }}" class="btn btn-primary mt-3">Create Proposal</a>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</x-app-layout>
