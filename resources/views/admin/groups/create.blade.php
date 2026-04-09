<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Create Student Group</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.groups.index') }}">Groups</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.groups.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.groups.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark">Group Details</h5>
                            <p class="text-muted small">Create a group and assign a supervisor.</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="group_name">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('group_name') is-invalid @enderror" id="group_name" name="group_name" value="{{ old('group_name') }}" required>
                            @error('group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="department_id">Department <span class="text-danger">*</span></label>
                            <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                <option value="">Select Department...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="supervisor_id">Supervisor <span class="text-danger">*</span></label>
                            <select class="form-select @error('supervisor_id') is-invalid @enderror" id="supervisor_id" name="supervisor_id" required>
                                <option value="">Select Supervisor...</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->user->name }} ({{ $supervisor->specialization ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="program">Program</label>
                            <input type="text" class="form-control" id="program" name="program" value="{{ old('program') }}" placeholder="e.g. Software Engineering">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="academic_year">Academic Year</label>
                            <input type="text" class="form-control" id="academic_year" name="academic_year" value="{{ old('academic_year') }}" placeholder="e.g. 2025/2026">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="default_password">Default Student Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('default_password') is-invalid @enderror" id="default_password" name="default_password" required>
                            <div class="form-text">All new students will use this password. They can change it later.</div>
                            @error('default_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Students in Group</h5>
                                <p class="text-muted small mb-0">Create new students or pick existing ones from the system.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="modeNew" value="new" checked>
                                <label class="form-check-label" for="modeNew">Create New Students</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="mode" id="modeExisting" value="existing">
                                <label class="form-check-label" for="modeExisting">Select Existing Students</label>
                            </div>
                        </div>

                        <div id="newStudentsSection">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted small">Add students to this group. One row per student.</span>
                                <button type="button" class="btn btn-sm btn-light-brand" id="addStudentRow">
                                    <i class="feather-plus me-1"></i> Add Row
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="studentTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 45%">Full Name <span class="text-danger">*</span></th>
                                            <th style="width: 45%">Email <span class="text-danger">*</span></th>
                                            <th class="text-end" style="width: 10%">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="students[0][name]" class="form-control" required placeholder="Student Name">
                                            </td>
                                            <td>
                                                <input type="email" name="students[0][email]" class="form-control" required placeholder="student@example.com">
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" disabled>
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="existingStudentsSection" class="d-none">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="feather-info me-2"></i>
                                <span>Select existing student users. They will be linked to this group and supervisor.</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%"></th>
                                            <th style="width: 45%">Student</th>
                                            <th style="width: 30%">Email</th>
                                            <th style="width: 20%">Current Group</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($studentUsers as $studentUser)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="existing_students[]" value="{{ $studentUser->id }}">
                                                    </div>
                                                </td>
                                                <td class="fw-semibold">{{ $studentUser->name }}</td>
                                                <td class="text-muted">{{ $studentUser->email }}</td>
                                                <td>
                                                    {{ $studentUser->student?->group?->name ?? '—' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">No existing students found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-4">
                            <a href="{{ route('admin.groups.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-check-circle me-1"></i> Create Group
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.getElementById('addStudentRow');
            const tableBody = document.querySelector('#studentTable tbody');
            const modeNew = document.getElementById('modeNew');
            const modeExisting = document.getElementById('modeExisting');
            const newSection = document.getElementById('newStudentsSection');
            const existingSection = document.getElementById('existingStudentsSection');

            const updateIndexes = () => {
                [...tableBody.querySelectorAll('tr')].forEach((row, index) => {
                    row.querySelectorAll('input').forEach((input) => {
                        if (input.name.includes('[name]')) {
                            input.name = `students[${index}][name]`;
                        }
                        if (input.name.includes('[email]')) {
                            input.name = `students[${index}][email]`;
                        }
                    });

                    const removeButton = row.querySelector('.remove-row');
                    removeButton.disabled = tableBody.querySelectorAll('tr').length === 1;
                });
            };

            addButton.addEventListener('click', () => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="text" name="students[0][name]" class="form-control" required placeholder="Student Name">
                    </td>
                    <td>
                        <input type="email" name="students[0][email]" class="form-control" required placeholder="student@example.com">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                            <i class="feather-trash-2"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
                updateIndexes();
            });

            tableBody.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-row');
                if (!button) {
                    return;
                }
                const row = button.closest('tr');
                row.remove();
                updateIndexes();
            });

            updateIndexes();

            const toggleMode = () => {
                if (modeExisting.checked) {
                    newSection.classList.add('d-none');
                    existingSection.classList.remove('d-none');
                } else {
                    newSection.classList.remove('d-none');
                    existingSection.classList.add('d-none');
                }
            };

            modeNew.addEventListener('change', toggleMode);
            modeExisting.addEventListener('change', toggleMode);
            toggleMode();
        });
    </script>
</x-app-layout>
