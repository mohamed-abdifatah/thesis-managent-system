<x-app-layout>
    @php
        $student = auth()->user()->student;
        $thesis = $student ? $student->thesis : null;
        $proposals = $thesis ? $thesis->proposals()->latest()->take(5)->get() : collect();
        $latestProposal = $proposals->first();
        $pendingTasks = 0; // Placeholder for now
    @endphp

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dashboard</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ul>
        </div>
    </div>
    
    <!-- Hero / Main Action -->
    <div class="row">
       <div class="col-xxl-12">
            <div class="card stretch stretch-full">
                <div class="card-body rounded position-relative overflow-hidden" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('assets/images/banner/1.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="row">
                        <div class="col-xl-8">
                            <h2 class="text-white fw-bold mb-3">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="text-white-50 fs-16 mb-4">Start your research journey, submit your thesis proposal, track your progress, and collaborate with your supervisor to achieve academic excellence.</p>
                            
                            @if(!$thesis)
                                <a href="{{ route('proposals.create') }}" class="btn btn-primary btn-lg fw-bold">
                                    <i class="feather-plus me-2"></i> Submit New Proposal
                                </a>
                            @else
                                <div class="d-flex gap-3">
                                    <a href="{{ route('proposals.index') }}" class="btn btn-light fw-bold text-primary">
                                        <i class="feather-eye me-2"></i> View Proposals
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>

    <!-- Stats Grid -->
    <div class="row">
        <!-- Thesis Status -->
       <div class="col-xxl-4 col-md-6">
           <div class="card stretch stretch-full border-0 shadow-sm">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center mb-3">
                       <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                           <i class="feather-book fs-24"></i>
                       </div>
                       <span class="badge bg-soft-{{ $thesis ? 'success' : 'secondary' }} text-{{ $thesis ? 'success' : 'secondary' }}">
                           {{ $thesis ? 'Active' : 'Inactive' }}
                       </span>
                   </div>
                   <div class="mt-3">
                       <h6 class="text-muted text-uppercase fs-12 fw-bold mb-1">Thesis Status</h6>
                       <h3 class="mb-0 fw-bolder text-dark">{{ $thesis ? ucfirst($thesis->status) : 'Not Started' }}</h3>
                   </div>
               </div>
           </div>
       </div>

        <!-- Supervisor -->
        <div class="col-xxl-4 col-md-6">
           <div class="card stretch stretch-full border-0 shadow-sm">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center mb-3">
                       <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                           <i class="feather-user-check fs-24"></i>
                       </div>
                       @if($thesis && $thesis->supervisor)
                           <span class="badge bg-soft-success text-success">Assigned</span>
                       @else
                           <span class="badge bg-soft-warning text-warning">Pending</span>
                       @endif
                   </div>
                   <div class="mt-3">
                       <h6 class="text-muted text-uppercase fs-12 fw-bold mb-1">Supervisor</h6>
                       <h3 class="mb-0 fw-bolder text-dark text-truncate">{{ $thesis && $thesis->supervisor ? $thesis->supervisor->user->name : 'No Supervisor' }}</h3>
                   </div>
               </div>
           </div>
       </div>

        <!-- Latest Proposal -->
       <div class="col-xxl-4 col-md-6">
           <div class="card stretch stretch-full border-0 shadow-sm">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center mb-3">
                       <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                           <i class="feather-file-text fs-24"></i>
                       </div>
                       <span class="badge bg-soft-info text-info">{{ $proposals->count() }} Submissions</span>
                   </div>
                   <div class="mt-3">
                       <h6 class="text-muted text-uppercase fs-12 fw-bold mb-1">Latest Proposal</h6>
                       <h3 class="mb-0 fw-bolder text-dark">{{ $latestProposal ? ucfirst($latestProposal->status) : 'No Proposals' }}</h3>
                   </div>
               </div>
           </div>
       </div>
    </div>

    <!-- Recent Proposals Table -->
    <div class="row">
        <div class="col-xxl-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Proposal Submissions</h5>
                    <a href="{{ route('proposals.index') }}" class="btn btn-sm btn-light-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">Title</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Submitted</th>
                                    <th scope="col">Supervisor Feedback</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proposals as $prop)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-text avatar-md bg-soft-primary text-primary rounded me-3">
                                                    <i class="feather-file-text"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('proposals.show', $prop->id) }}" class="fw-bold text-dark mb-0 d-block text-truncate" style="max-width: 250px;">
                                                        {{ \Illuminate\Support\Str::limit($prop->title, 50) }}
                                                    </a>
                                                    <span class="fs-12 text-muted">ID: #{{ $prop->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($prop->status == 'approved')
                                                <span class="badge bg-soft-success text-success">Approved</span>
                                            @elseif($prop->status == 'rejected')
                                                <span class="badge bg-soft-danger text-danger">Rejected</span>
                                            @elseif($prop->status == 'revision_required')
                                                <span class="badge bg-soft-warning text-warning">Revision Required</span>
                                            @else
                                                <span class="badge bg-soft-info text-info">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $prop->created_at->format('M d, Y') }}</span>
                                                <span class="fs-12 text-muted">{{ $prop->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($prop->remarks)
                                                <span class="text-truncate d-inline-block" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $prop->remarks }}">
                                                    {{ $prop->remarks }}
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic">No feedback yet</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('proposals.show', $prop->id) }}" class="btn btn-sm btn-light-brand" data-bs-toggle="tooltip" title="View Details">
                                                <i class="feather-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center">
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <div class="avatar-text avatar-xl bg-light text-muted mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                                    <i class="feather-inbox"></i>
                                                </div>
                                                <h6 class="text-muted fw-bold">No proposals submitted yet</h6>
                                                <p class="text-muted fs-12 mb-3">Start by submitting your thesis proposal for approval.</p>
                                                <a href="{{ route('proposals.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="feather-plus me-1"></i> Submit Proposal
                                                </a>
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
</x-app-layout>
