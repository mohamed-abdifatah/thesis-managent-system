<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Student Groups</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Groups</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary">
                <i class="feather-plus me-1"></i> New Group
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if(session('success'))
                            <div class="alert alert-success m-3" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table class="table table-hover" id="groupList">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Supervisor</th>
                                    <th>Department</th>
                                    <th>Program</th>
                                    <th>Students</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr>
                                        <td class="fw-semibold">{{ $group->name }}</td>
                                        <td>{{ $group->supervisor?->user?->name ?? 'Unassigned' }}</td>
                                        <td>{{ $group->department?->code ?? '-' }}</td>
                                        <td>{{ $group->program ?? '-' }}</td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $group->students_count }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.groups.create') }}" class="btn btn-sm btn-light-brand">
                                                <i class="feather-copy"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="feather-folder-minus fs-1 text-muted opacity-50 mb-3"></i>
                                            <p class="text-muted mb-0">No groups created yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0">
                    {{ $groups->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
