<x-app-layout>
    @php
        $oldStudents = old('students');
        if (!is_array($oldStudents) || count($oldStudents) === 0) {
            $oldStudents = [['name' => '', 'email' => '']];
        }

        $selectedExisting = collect(old('existing_students', []))
            ->map(fn ($id) => (string) $id)
            ->all();

        $mailFromAddress = config('mail.from.address');
        $defaultEmailDomain = 'example.com';
        if (is_string($mailFromAddress) && str_contains($mailFromAddress, '@')) {
            $defaultEmailDomain = substr(strrchr($mailFromAddress, '@'), 1);
        }

        $selectedExistingCount = count($selectedExisting);
    @endphp

    <style>
        .gc-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .gc-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .gc-stat .icon {
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

        .gc-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .gc-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .gc-sidebar {
            position: sticky;
            top: 78px;
            align-self: flex-start;
        }

        .gc-helper {
            border-radius: 12px;
            border: 1px solid #d8e5f7;
            background: #f7fbff;
            color: #4a5e77;
            padding: 10px 12px;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .gc-table tbody td {
            vertical-align: middle;
        }

        .gc-table tbody tr:hover td {
            background: #fbfdff;
        }

        .gc-search-wrap {
            max-width: 280px;
            width: 100%;
        }

        .gc-action-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 16px;
        }

        html.app-skin-dark .gc-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .gc-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .gc-stat .label,
        html.app-skin-dark .gc-helper {
            color: #a3b1c4;
        }

        html.app-skin-dark .gc-stat .value {
            color: #e6edf7;
        }

        html.app-skin-dark .gc-helper {
            background: #1a2534;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .gc-table tbody tr:hover td {
            background: rgba(30, 44, 62, 0.8);
        }

        @media (max-width: 1399px) {
            .gc-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .gc-sidebar {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .gc-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Create Student Group</h1>
            <p class="ta-page-subtitle">Build a new group, generate student users in bulk, and assign supervisor ownership in one flow.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.groups.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Back to Groups
            </a>
        </div>
    </div>

    <section class="gc-stats" aria-label="Group create summary cards">
        <article class="gc-stat">
            <span class="icon"><i class="feather-grid"></i></span>
            <div>
                <p class="label">Departments</p>
                <p class="value">{{ number_format($departments->count()) }}</p>
            </div>
        </article>
        <article class="gc-stat">
            <span class="icon"><i class="feather-user-check"></i></span>
            <div>
                <p class="label">Supervisors</p>
                <p class="value">{{ number_format($supervisors->count()) }}</p>
            </div>
        </article>
        <article class="gc-stat">
            <span class="icon"><i class="feather-users"></i></span>
            <div>
                <p class="label">Existing Students</p>
                <p class="value">{{ number_format($studentUsers->count()) }}</p>
            </div>
        </article>
        <article class="gc-stat">
            <span class="icon"><i class="feather-check-square"></i></span>
            <div>
                <p class="label">Selected Existing</p>
                <p class="value">{{ number_format($selectedExistingCount) }}</p>
            </div>
        </article>
    </section>

    <form method="POST" action="{{ route('admin.groups.store') }}" id="createGroupForm">
        @csrf

        @if($errors->has('students') || $errors->has('existing_students'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                {{ $errors->first('students') ?: $errors->first('existing_students') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-xl-4 gc-sidebar">
                <div class="ta-panel mb-4">
                    <div class="ta-panel-head">
                        <h3>Group Setup</h3>
                    </div>
                    <div class="ta-panel-body">
                        <div class="gc-helper">
                            Choose department and supervisor first. Students added below will inherit these values.
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
                            <select class="form-select js-department-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                <option value="">Select Department...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->code }})
                                    </option>
                                @endforeach
                                <option value="__create__">+ Create new department...</option>
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

                        <div>
                            <label class="form-label fw-semibold" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>Password For New Accounts</h3>
                    </div>
                    <div class="ta-panel-body">
                        <label class="form-label fw-semibold" for="default_password">Default Password</label>
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control @error('default_password') is-invalid @enderror"
                                id="default_password"
                                name="default_password"
                                value="{{ old('default_password') }}"
                                placeholder="Generate once and use for all new users"
                                autocomplete="off"
                            >
                            <button class="btn btn-outline-primary" type="button" id="generatePasswordBtn">
                                <i class="feather-refresh-cw me-1"></i> Generate
                            </button>
                        </div>
                        <div class="form-text">
                            This password is applied only to newly generated or newly entered users. Existing students keep their current password.
                        </div>
                        <div id="passwordHint" class="small mt-2 text-muted"></div>
                        @error('default_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="ta-panel mb-4">
                    <div class="ta-panel-head">
                        <h3>Bulk User Generator</h3>
                        <span class="text-muted small">Example: year4_0001 - year4_0100</span>
                    </div>
                    <div class="ta-panel-body">
                        <div class="gc-helper">
                            Generate usernames and emails in one click, then adjust any row manually before submit.
                        </div>

                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" for="rangePrefix">Prefix</label>
                                <input type="text" id="rangePrefix" name="generated_prefix" class="form-control" value="{{ old('generated_prefix', 'year4') }}" placeholder="year4">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="rangeStart">From</label>
                                <input type="number" id="rangeStart" name="generated_start" class="form-control" value="{{ old('generated_start', 1) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="rangeEnd">To</label>
                                <input type="number" id="rangeEnd" name="generated_end" class="form-control" value="{{ old('generated_end', 100) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="rangePadding">Digits</label>
                                <input type="number" id="rangePadding" name="generated_padding" class="form-control" value="{{ old('generated_padding', 4) }}" min="1" max="6">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold" for="rangeDomain">Email Domain</label>
                                <input type="text" id="rangeDomain" name="generated_email_domain" class="form-control" value="{{ old('generated_email_domain', $defaultEmailDomain) }}" placeholder="university.edu">
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-primary" id="generateRangeBtn">
                                <i class="feather-zap me-1"></i> Generate Range
                            </button>
                            <button type="button" class="btn btn-light" id="clearGeneratedBtn">
                                <i class="feather-x-circle me-1"></i> Clear Table
                            </button>
                        </div>
                        <div id="generationHint" class="small mt-3 text-muted"></div>
                    </div>
                </div>

                <div class="ta-panel mb-4">
                    <div class="ta-panel-head">
                        <h3>New Student Users</h3>
                        <button type="button" class="ta-chip-link" id="addStudentRow">
                            <i class="feather-plus"></i>
                            Add Manual Row
                        </button>
                    </div>
                    <div class="ta-panel-body">
                        <p class="text-muted small mb-3">You can mix generated users with manual rows. Leave both fields empty for unused rows.</p>
                        <div class="ta-table-shell">
                            <table class="table table-hover align-middle mb-0 gc-table" id="studentTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">User Detail</th>
                                        <th style="width: 50%">Email</th>
                                        <th class="text-end" style="width: 10%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($oldStudents as $index => $student)
                                        <tr>
                                            <td>
                                                <input
                                                    type="text"
                                                    name="students[{{ $index }}][name]"
                                                    class="form-control @error('students.'.$index.'.name') is-invalid @enderror"
                                                    value="{{ $student['name'] ?? '' }}"
                                                    placeholder="e.g. year4_0001"
                                                >
                                            </td>
                                            <td>
                                                <input
                                                    type="email"
                                                    name="students[{{ $index }}][email]"
                                                    class="form-control @error('students.'.$index.'.email') is-invalid @enderror"
                                                    value="{{ $student['email'] ?? '' }}"
                                                    placeholder="e.g. year4_0001@{{ $defaultEmailDomain }}"
                                                >
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>Existing Student Users</h3>
                        <div class="gc-search-wrap">
                            <input type="text" id="existingStudentSearch" class="form-control form-control-sm" placeholder="Search name or email...">
                        </div>
                    </div>
                    <div class="ta-panel-body">
                        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                            <i class="feather-info me-2"></i>
                            <span>Select existing students to include them in this group without changing their current password.</span>
                        </div>
                        <div class="ta-table-shell">
                            <table class="table table-hover align-middle mb-0 gc-table" id="existingStudentTable">
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
                                        <tr data-existing-row>
                                            <td>
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="existing_students[]"
                                                        value="{{ $studentUser->id }}"
                                                        {{ in_array((string) $studentUser->id, $selectedExisting, true) ? 'checked' : '' }}
                                                    >
                                                </div>
                                            </td>
                                            <td class="fw-semibold">{{ $studentUser->name }}</td>
                                            <td class="text-muted">{{ $studentUser->email }}</td>
                                            <td>{{ $studentUser->student?->group?->name ?? '—' }}</td>
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
                </div>

                <div class="gc-action-row">
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="feather-check-circle me-1"></i> Create Group
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.getElementById('addStudentRow');
            const generateRangeBtn = document.getElementById('generateRangeBtn');
            const clearGeneratedBtn = document.getElementById('clearGeneratedBtn');
            const generatePasswordBtn = document.getElementById('generatePasswordBtn');
            const tableBody = document.querySelector('#studentTable tbody');
            const existingSearch = document.getElementById('existingStudentSearch');
            const existingRows = document.querySelectorAll('[data-existing-row]');
            const generationHint = document.getElementById('generationHint');
            const passwordHint = document.getElementById('passwordHint');
            const defaultPasswordInput = document.getElementById('default_password');

            const rangePrefix = document.getElementById('rangePrefix');
            const rangeStart = document.getElementById('rangeStart');
            const rangeEnd = document.getElementById('rangeEnd');
            const rangePadding = document.getElementById('rangePadding');
            const rangeDomain = document.getElementById('rangeDomain');

            const randomString = (length = 12) => {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*';
                let value = '';
                for (let i = 0; i < length; i += 1) {
                    value += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return value;
            };

            const createRow = (name = '', email = '') => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="text" name="students[0][name]" class="form-control" placeholder="e.g. year4_0001" value="${name}">
                    </td>
                    <td>
                        <input type="email" name="students[0][email]" class="form-control" placeholder="e.g. year4_0001@example.com" value="${email}">
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                            <i class="feather-trash-2"></i>
                        </button>
                    </td>
                `;
                return row;
            };

            const updateIndexes = () => {
                const rows = [...tableBody.querySelectorAll('tr')];

                if (rows.length === 0) {
                    tableBody.appendChild(createRow());
                }

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
                    if (removeButton) {
                        removeButton.disabled = tableBody.querySelectorAll('tr').length === 1;
                    }
                });
            };

            const setGenerationHint = (message, isError = false) => {
                generationHint.textContent = message;
                generationHint.classList.toggle('text-danger', isError);
                generationHint.classList.toggle('text-success', !isError);
            };

            addButton.addEventListener('click', () => {
                tableBody.appendChild(createRow());
                updateIndexes();
                setGenerationHint('Manual row added.', false);
            });

            clearGeneratedBtn.addEventListener('click', () => {
                tableBody.innerHTML = '';
                tableBody.appendChild(createRow());
                updateIndexes();
                setGenerationHint('Table cleared. You can generate a fresh range now.', false);
            });

            generatePasswordBtn.addEventListener('click', () => {
                const generated = randomString(12);
                defaultPasswordInput.value = generated;
                passwordHint.textContent = `Generated password: ${generated}. Save it before submitting.`;
                passwordHint.classList.remove('text-muted');
                passwordHint.classList.add('text-success');
            });

            generateRangeBtn.addEventListener('click', () => {
                const prefix = (rangePrefix.value || '').trim();
                const domain = (rangeDomain.value || '').trim().toLowerCase();
                const start = Number.parseInt(rangeStart.value, 10);
                const end = Number.parseInt(rangeEnd.value, 10);
                const padding = Number.parseInt(rangePadding.value, 10);

                if (!prefix || !/^[a-zA-Z0-9_-]+$/.test(prefix)) {
                    setGenerationHint('Prefix is required and can include letters, numbers, _ or - only.', true);
                    return;
                }

                if (!domain || domain.includes('@') || !domain.includes('.')) {
                    setGenerationHint('Enter a valid email domain such as university.edu.', true);
                    return;
                }

                if (Number.isNaN(start) || Number.isNaN(end) || start < 0 || end < start) {
                    setGenerationHint('Range is invalid. Ensure "From" is less than or equal to "To".', true);
                    return;
                }

                if (Number.isNaN(padding) || padding < 1 || padding > 6) {
                    setGenerationHint('Digits must be between 1 and 6.', true);
                    return;
                }

                const count = end - start + 1;
                if (count > 300) {
                    setGenerationHint('For performance, generate up to 300 users at once.', true);
                    return;
                }

                const rows = [];
                for (let number = start; number <= end; number += 1) {
                    const suffix = String(number).padStart(padding, '0');
                    const account = `${prefix}_${suffix}`.toLowerCase();
                    rows.push(createRow(account, `${account}@${domain}`));
                }

                tableBody.innerHTML = '';
                rows.forEach((row) => tableBody.appendChild(row));
                updateIndexes();

                const firstAccount = `${prefix}_${String(start).padStart(padding, '0')}`.toLowerCase();
                const lastAccount = `${prefix}_${String(end).padStart(padding, '0')}`.toLowerCase();
                setGenerationHint(`Generated ${count} users (${firstAccount} to ${lastAccount}).`, false);
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

            existingSearch?.addEventListener('input', () => {
                const term = existingSearch.value.trim().toLowerCase();
                existingRows.forEach((row) => {
                    const text = row.textContent.toLowerCase();
                    row.classList.toggle('d-none', term.length > 0 && !text.includes(term));
                });
            });
        });
    </script>
    @include('partials.department-select-create')
        </div>
</x-app-layout>
