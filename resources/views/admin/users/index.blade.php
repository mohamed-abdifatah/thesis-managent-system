<x-app-layout>
    <style>
        .ta-user-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .ta-user-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ta-user-stat .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: #eaf1ff;
            border: 1px solid #d2e1ff;
        }

        .ta-user-stat .label {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
            margin: 0;
        }

        .ta-user-stat .value {
            margin: 2px 0 0;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
            line-height: 1.1;
        }

        .ta-filter-body {
            padding: 14px 16px 16px;
            border-top: 1px solid var(--ta-border);
        }

        .ta-user-filters {
            display: grid;
            grid-template-columns: minmax(220px, 1.2fr) repeat(2, minmax(140px, 0.6fr)) auto;
            gap: 10px;
            align-items: end;
        }

        .ta-user-filters .btn {
            white-space: nowrap;
        }

        .ta-field-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #627892;
            font-weight: 700;
        }

        .ta-user-table tbody td {
            vertical-align: middle;
        }

        .ta-user-person {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .ta-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eaf1ff;
            color: #1d4ed8;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .ta-user-name {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
        }

        .ta-user-email {
            margin: 2px 0 0;
            color: #66758d;
            font-size: 0.82rem;
            line-height: 1.3;
        }

        .ta-role-note {
            display: block;
            margin-top: 5px;
            font-size: 0.76rem;
            color: #66758d;
        }

        .ta-row-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .ta-row-actions form {
            margin: 0;
        }

        .ta-action-btn {
            min-height: 34px;
            padding: 0.35rem 0.7rem;
            border-radius: 10px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .ta-action-btn i {
            font-size: 0.88rem;
        }

        .ta-action-edit {
            color: #1d4ed8;
            background: #eef4ff;
            border-color: #cfe0ff;
        }

        .ta-action-edit:hover {
            color: #1e40af;
            background: #e3edff;
            border-color: #b9d0ff;
            transform: translateY(-1px);
        }

        .ta-action-delete {
            color: #b42318;
            background: #fff2f0;
            border-color: #f7d0cb;
        }

        .ta-action-delete:hover {
            color: #912018;
            background: #ffe9e6;
            border-color: #f1b8b0;
            transform: translateY(-1px);
        }

        html.app-skin-dark .ta-action-edit {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .ta-action-delete {
            color: #ffb4ac;
            background: rgba(170, 40, 40, 0.34);
            border-color: rgba(223, 121, 114, 0.45);
        }

        html.app-skin-dark .ta-user-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .ta-user-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .ta-user-stat .label {
            color: #a3b1c4;
        }

        html.app-skin-dark .ta-user-stat .value,
        html.app-skin-dark .ta-user-name {
            color: #e6edf7;
        }

        html.app-skin-dark .ta-user-email,
        html.app-skin-dark .ta-role-note,
        html.app-skin-dark .ta-empty,
        html.app-skin-dark .ta-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .ta-empty h4 {
            color: #e6edf7;
        }

        .ta-empty {
            text-align: center;
            padding: 38px 16px;
            color: #66758d;
        }

        .ta-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .ta-empty h4 {
            margin: 0;
            font-size: 1.03rem;
            color: #10233e;
        }

        .ta-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        @media (max-width: 1199px) {
            .ta-user-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .ta-user-filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767px) {
            .ta-user-stats {
                grid-template-columns: 1fr;
            }

            .ta-user-filters {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">User Management</h1>
            <p class="ta-page-subtitle">Manage user accounts, role assignments, and department ownership from one clean control panel.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.users.create') }}" class="ta-chip-link ta-primary">
                <i class="feather-user-plus"></i>
                Add New User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <section class="ta-user-stats" aria-label="User summary cards">
        <article class="ta-user-stat">
            <span class="icon"><i class="feather-users"></i></span>
            <div>
                <p class="label">Total Users</p>
                <p class="value">{{ number_format($totalUsers) }}</p>
            </div>
        </article>
        <article class="ta-user-stat">
            <span class="icon"><i class="feather-user-check"></i></span>
            <div>
                <p class="label">Students</p>
                <p class="value">{{ number_format($studentCount) }}</p>
            </div>
        </article>
        <article class="ta-user-stat">
            <span class="icon"><i class="feather-briefcase"></i></span>
            <div>
                <p class="label">Supervisors</p>
                <p class="value">{{ number_format($supervisorCount) }}</p>
            </div>
        </article>
        <article class="ta-user-stat">
            <span class="icon"><i class="feather-shield"></i></span>
            <div>
                <p class="label">Admins & Coordinators</p>
                <p class="value">{{ number_format($adminCount) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel mb-3">
        <div class="ta-panel-head">
            <div>
                <h3>Filter Directory</h3>
                <span class="text-muted small">{{ number_format($filteredCount) }} matching records</span>
            </div>
        </div>
        <div class="ta-filter-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="ta-user-filters">
                <div>
                    <label class="ta-field-label" for="q">Search</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Name, email, role, department...">
                </div>

                <div>
                    <label class="ta-field-label" for="role">Role</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">All roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $roleFilter === $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="ta-field-label" for="per_page">Rows per page</label>
                    <select id="per_page" name="per_page" class="form-select">
                        <option value="10" {{ $perPage === 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="feather-search me-1"></i>
                        Apply
                    </button>
                    @if($search !== '' || $roleFilter !== '' || $perPage !== 10)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm px-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>User Directory</h3>
                <span class="text-muted small">{{ $users->total() }} records across {{ $users->lastPage() }} pages</span>
            </div>
        </div>

        <div class="ta-table-shell">
            <table class="table table-hover mb-0 ta-user-table" id="userList">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $roleName = strtolower($user->role->name ?? 'guest');
                            $roleLabel = ucfirst($user->role->name ?? 'Guest');
                            $badgeClass = match($roleName) {
                                'admin', 'coordinator' => 'bg-soft-danger text-danger',
                                'supervisor', 'cosupervisor' => 'bg-soft-primary text-primary',
                                'student' => 'bg-soft-success text-success',
                                'examiner' => 'bg-soft-warning text-warning',
                                'librarian' => 'bg-soft-info text-info',
                                default => 'bg-soft-dark text-dark',
                            };
                            $roleNote = match($roleName) {
                                'admin', 'coordinator' => 'System control and user operations',
                                'supervisor', 'cosupervisor' => 'Supervision and evaluation workflow',
                                'student' => 'Proposal and thesis submission workflow',
                                'examiner' => 'Defense review and evaluations',
                                'librarian' => 'Catalog and publication management',
                                default => 'General access scope',
                            };
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="ta-user-person">
                                    <span class="ta-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    <span>
                                        <span class="ta-user-name">{{ $user->name }}</span>
                                        <span class="ta-user-email">{{ $user->email }}</span>
                                    </span>
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $roleLabel }}</span>
                                <span class="ta-role-note">{{ $roleNote }}</span>
                            </td>
                            <td>
                                <strong>{{ $user->department->code ?? 'Unassigned' }}</strong>
                                <div class="text-muted small">{{ $user->department->name ?? 'No department assigned' }}</div>
                            </td>
                            <td>
                                {{ $user->created_at?->format('M d, Y') ?? '-' }}
                                <div class="text-muted small">{{ $user->created_at?->diffForHumans() ?? '' }}</div>
                            </td>
                            <td class="text-end">
                                <div class="ta-row-actions">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="ta-action-btn ta-action-edit" aria-label="Edit {{ $user->name }}">
                                        <i class="feather-edit-3 me-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this user account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ta-action-btn ta-action-delete" aria-label="Delete {{ $user->name }}">
                                            <i class="feather-trash-2 me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ta-empty">
                                    <i class="feather-search"></i>
                                    <h4>No users found</h4>
                                    <p>Try changing filters, clearing the search query, or adding a new user.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2 px-1">
        <span class="text-muted small">
            Showing {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records
        </span>
        {{ $users->links() }}
    </div>
</x-app-layout>
