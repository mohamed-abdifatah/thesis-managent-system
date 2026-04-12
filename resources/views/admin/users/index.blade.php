<x-app-layout>
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">User Management</h1>
            <p class="ta-page-subtitle">Manage user accounts, roles, department assignments, and account lifecycle actions.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.users.create') }}" class="ta-chip-link">
                <i class="feather-user-plus"></i>
                Add New User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>User Directory</h3>
                <span class="text-muted small">{{ $users->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                <select name="role" class="form-select form-select-sm" style="min-width: 150px;" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $roleFilter === $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>

                <select name="per_page" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
                    <option value="10" {{ $perPage === 10 ? 'selected' : '' }}>10 / page</option>
                    <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25 / page</option>
                    <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100 / page</option>
                </select>

                @if($roleFilter !== '' || $perPage !== 10)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">Reset</a>
                @endif
            </form>
        </div>
        <div class="ta-table-shell">
            <table class="table table-hover mb-0" id="userList">
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
                    @forelse($users as $user)
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
                                    <a href="{{ route('admin.users.edit', $user) }}" class="avatar-text avatar-md">
                                        <i class="feather feather-edit-3"></i>
                                    </a>
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No users found for the selected filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 px-1">
        {{ $users->links() }}
    </div>
</x-app-layout>
