<x-app-layout>
    <style>
        .tg-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .tg-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tg-stat .icon {
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

        .tg-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .tg-stat .value {
            margin: 2px 0 0;
            font-size: 1.35rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .tg-filter-body {
            padding: 14px 16px 16px;
            border-top: 1px solid var(--ta-border);
        }

        .tg-filters {
            display: grid;
            grid-template-columns: minmax(220px, 1.3fr) minmax(180px, 0.8fr) minmax(130px, 0.5fr) auto;
            gap: 10px;
            align-items: end;
        }

        .tg-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #627892;
            font-weight: 700;
        }

        .tg-filters .btn {
            white-space: nowrap;
        }

        .tg-table tbody td {
            vertical-align: middle;
        }

        .tg-name {
            margin: 0;
            font-weight: 800;
            color: #10233e;
            line-height: 1.25;
        }

        .tg-sub {
            margin-top: 3px;
            color: #66758d;
            font-size: 0.8rem;
            line-height: 1.35;
        }

        .tg-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            padding: 4px 9px;
            font-size: 0.74rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .tg-pill.supervisor {
            color: #1d4ed8;
            background: #eef4ff;
            border-color: #cfddff;
        }

        .tg-pill.muted {
            color: #586b85;
            background: #f3f6fb;
            border-color: #dde6f2;
        }

        .tg-metric {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #d6e2f3;
            border-radius: 999px;
            background: #f8fbff;
            padding: 4px 9px;
            font-size: 0.76rem;
            font-weight: 700;
            color: #324a68;
        }

        html.app-skin-dark .tg-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .tg-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tg-stat .label,
        html.app-skin-dark .tg-sub,
        html.app-skin-dark .tg-empty,
        html.app-skin-dark .tg-empty p {
            color: #a3b1c4;
        }

        html.app-skin-dark .tg-stat .value,
        html.app-skin-dark .tg-name,
        html.app-skin-dark .tg-empty h4 {
            color: #e6edf7;
        }

        html.app-skin-dark .tg-pill.supervisor {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tg-pill.muted {
            color: #c4cedc;
            background: #202c3d;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tg-metric {
            color: #c8d3e3;
            background: #1a2433;
            border-color: rgba(255, 255, 255, 0.14);
        }

        .tg-empty {
            text-align: center;
            padding: 38px 16px;
            color: #66758d;
        }

        .tg-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .tg-empty h4 {
            margin: 0;
            font-size: 1.03rem;
            color: #10233e;
        }

        .tg-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        @media (max-width: 1199px) {
            .tg-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tg-filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767px) {
            .tg-stats {
                grid-template-columns: 1fr;
            }

            .tg-filters {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Student Groups</h1>
            <p class="ta-page-subtitle">Track each group, supervisor coverage, and thesis progress from one dashboard.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.groups.create') }}" class="ta-chip-link ta-primary">
                <i class="feather-plus"></i>
                New Group
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

    <section class="tg-stats" aria-label="Group summary cards">
        <article class="tg-stat">
            <span class="icon"><i class="feather-layers"></i></span>
            <div>
                <p class="label">Total Groups</p>
                <p class="value">{{ number_format($totalGroups) }}</p>
            </div>
        </article>
        <article class="tg-stat">
            <span class="icon"><i class="feather-user-check"></i></span>
            <div>
                <p class="label">With Supervisor</p>
                <p class="value">{{ number_format($groupsWithSupervisor) }}</p>
            </div>
        </article>
        <article class="tg-stat">
            <span class="icon"><i class="feather-users"></i></span>
            <div>
                <p class="label">Linked Students</p>
                <p class="value">{{ number_format($linkedStudents) }}</p>
            </div>
        </article>
        <article class="tg-stat">
            <span class="icon"><i class="feather-book-open"></i></span>
            <div>
                <p class="label">Groups With Thesis</p>
                <p class="value">{{ number_format($groupsWithThesis) }}</p>
            </div>
        </article>
    </section>

    <div class="ta-panel mb-3">
        <div class="ta-panel-head">
            <div>
                <h3>Filter Groups</h3>
                <span class="text-muted small">{{ number_format($filteredCount) }} matching records</span>
            </div>
        </div>
        <div class="tg-filter-body">
            <form method="GET" action="{{ route('admin.groups.index') }}" class="tg-filters">
                <div>
                    <label class="tg-label" for="q">Search</label>
                    <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Group, supervisor, department, year...">
                </div>

                <div>
                    <label class="tg-label" for="department_id">Department</label>
                    <select id="department_id" name="department_id" class="form-select">
                        <option value="0">All departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $departmentFilter === $department->id ? 'selected' : '' }}>
                                {{ $department->code }} - {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="tg-label" for="per_page">Rows per page</label>
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
                    @if($search !== '' || $departmentFilter > 0 || $perPage !== 10)
                        <a href="{{ route('admin.groups.index') }}" class="btn btn-light btn-sm px-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <h3>Groups Directory</h3>
                <span class="text-muted small">{{ $groups->total() }} records across {{ $groups->lastPage() }} pages</span>
            </div>
        </div>

        <div class="ta-table-shell">
            <table class="table table-hover mb-0 tg-table" id="groupList">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Supervisor</th>
                        <th>Department</th>
                        <th>Program / Year</th>
                        <th>Workload</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $group)
                        <tr>
                            <td>
                                <p class="tg-name">{{ $group->name }}</p>
                                <p class="tg-sub">{{ $group->notes ? \Illuminate\Support\Str::limit($group->notes, 90) : 'No notes added yet.' }}</p>
                            </td>
                            <td>
                                @if($group->supervisor?->user?->name)
                                    <span class="tg-pill supervisor">
                                        <i class="feather-user-check"></i>
                                        {{ $group->supervisor->user->name }}
                                    </span>
                                @else
                                    <span class="tg-pill muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $group->department?->code ?? '—' }}</strong>
                                <div class="text-muted small">{{ $group->department?->name ?? 'No department assigned' }}</div>
                            </td>
                            <td>
                                <div><strong>{{ $group->program ?? 'General Program' }}</strong></div>
                                <div class="text-muted small">{{ $group->academic_year ?? 'Academic year not set' }}</div>
                            </td>
                            <td>
                                <span class="tg-metric"><i class="feather-users"></i> {{ $group->students_count }} students</span>
                                <span class="tg-metric mt-1"><i class="feather-book-open"></i> {{ $group->theses_count }} theses</span>
                            </td>
                            <td>
                                {{ $group->updated_at?->format('M d, Y') ?? '-' }}
                                <div class="text-muted small">{{ $group->updated_at?->diffForHumans() ?? '' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="tg-empty">
                                    <i class="feather-folder-minus"></i>
                                    <h4>No groups found</h4>
                                    <p>Try changing filters or create a new group to get started.</p>
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
            Showing {{ $groups->firstItem() ?? 0 }} - {{ $groups->lastItem() ?? 0 }} of {{ $groups->total() }} records
        </span>
        {{ $groups->links() }}
    </div>
    </div>
</x-app-layout>
