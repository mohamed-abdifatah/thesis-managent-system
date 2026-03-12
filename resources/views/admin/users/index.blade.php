<x-app-layout>
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Users</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User Management</li>
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
                    
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Add New User</span>
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
                            <table class="table table-hover" id="userList">
                                <thead>
                                    <tr>
                                        <th class="wd-30">
                                            <div class="btn-group mb-1">
                                                <div class="custom-control custom-checkbox ms-1">
                                                    <input type="checkbox" class="custom-control-input" id="checkAllUser">
                                                    <label class="custom-control-label" for="checkAllUser"></label>
                                                </div>
                                            </div>
                                        </th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Joined Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr class="single-item">
                                        <td>
                                            <div class="item-checkbox ms-1">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkbox" id="checkBox_{{ $user->id }}">
                                                    <label class="custom-control-label" for="checkBox_{{ $user->id }}"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="hstack gap-3">
                                                @php
                                                    $initial = substr($user->name, 0, 1);
                                                @endphp
                                                <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                                    {{ $initial }}
                                                </div>
                                                <div>
                                                    <span class="text-truncate-1-line">{{ $user->name }}</span>
                                                    <small class="fs-12 fw-normal text-muted">{{ $user->email }}</small>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $roleName = $user->role->name ?? 'Guest';
                                                $badgeClass = match(strtolower($roleName)) {
                                                    'admin' => 'bg-soft-danger text-danger',
                                                    'supervisor' => 'bg-soft-primary text-primary',
                                                    'student' => 'bg-soft-success text-success',
                                                    default => 'bg-soft-dark text-dark',
                                                };
                                            @endphp
                                            <div class="badge {{ $badgeClass }}">{{ ucfirst($roleName) }}</div>
                                        </td>
                                        <td>
                                            <span class="text-truncate-1-line">{{ $user->department->code ?? '-' }}</span>
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="hstack gap-2 justify-content-end">
                                                <!-- Edit -->
                                                <a href="{{ route('admin.users.edit', $user) }}" class="avatar-text avatar-md">
                                                    <i class="feather feather-edit-3"></i>
                                                </a>
                                                <!-- Delete -->
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="avatar-text avatar-md text-danger bg-transparent border-0">
                                                        <i class="feather feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                 <div class="mt-4 px-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</x-app-layout>
