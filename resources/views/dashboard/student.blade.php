<x-app-layout>
    @php
        $student = auth()->user()->student;
        $hasThesis = $student && $student->thesis;
        $thesis = $hasThesis ? $student->thesis : null;
        $proposal = $thesis ? $thesis->proposals()->latest()->first() : null;
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
                <div class="card-body bg-primary text-white rounded position-relative overflow-hidden">
                    <div class="row">
                        <div class="col-xl-8">
                            <h2 class="text-white fw-bold">Start Your Research Journey</h2>
                             <p class="text-white-50 mt-3 fs-14">Submit your thesis proposal, track your progress, and collaborate with your supervisor to achieve academic excellence.</p>
                             @if(!$hasThesis)
                             <a href="{{ route('proposals.create') }}" class="btn btn-light text-primary mt-3 fw-bold">
                                <i class="feather-send me-2"></i> Submit Proposal
                            </a>
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
           <div class="card stretch stretch-full">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <div class="fs-12 fw-medium mb-2 text-muted text-uppercase">Thesis Status</div>
                           <h3 class="mb-1 text-dark fw-bold">{{ $hasThesis ? ucfirst($thesis->status) : 'Not Started' }}</h3>
                           <div class="fs-12 text-muted">Current progress</div>
                       </div>
                       <div class="avatar-text avatar-lg bg-soft-primary text-primary border-primary rounded">
                           <i class="feather-file-text"></i>
                       </div>
                   </div>
               </div>
           </div>
       </div>

        <!-- Supervisor -->
        <div class="col-xxl-4 col-md-6">
           <div class="card stretch stretch-full">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <div class="fs-12 fw-medium mb-2 text-muted text-uppercase">Supervisor</div>
                           <h3 class="mb-1 text-dark fw-bold">{{ $hasThesis && $thesis->supervisor ? $thesis->supervisor->user->name : 'Pending' }}</h3>
                           <div class="fs-12 text-muted">Assigned advisor</div>
                       </div>
                       <div class="avatar-text avatar-lg bg-soft-success text-success border-success rounded">
                           <i class="feather-user"></i>
                       </div>
                   </div>
               </div>
           </div>
       </div>

        <!-- Latest Proposal -->
       <div class="col-xxl-4 col-md-6">
           <div class="card stretch stretch-full">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <div class="fs-12 fw-medium mb-2 text-muted text-uppercase">Latest Proposal</div>
                           <h3 class="mb-1 text-dark fw-bold">{{ $proposal ? ucfirst($proposal->status) : 'None' }}</h3>
                           <div class="fs-12 text-muted">Proposal State</div>
                       </div>
                       <div class="avatar-text avatar-lg bg-soft-warning text-warning border-warning rounded">
                           <i class="feather-activity"></i>
                       </div>
                   </div>
               </div>
           </div>
       </div>
    </div>

    <!-- Recent Proposals Table -->
    <div class="row">
        <div class="col-xxl-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">My Proposals</h5>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Date</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($proposal)
                                    <tr>
                                        <td><a href="#" class="fw-bold">#{{ $proposal->id }}</a></td>
                                        <td>
                                            <span class="badge bg-soft-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'warning') }} text-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($proposal->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($proposal->title ?? 'Proposal', 50) }}</td>
                                        <td>{{ optional($proposal->created_at)->diffForHumans() }}</td>
                                        <td class="text-end">
                                            <a href="#" class="avatar-text avatar-md bg-soft-dark text-dark border-dark rounded"><i class="feather-eye"></i></a>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No proposals yet. Submit your first proposal.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
