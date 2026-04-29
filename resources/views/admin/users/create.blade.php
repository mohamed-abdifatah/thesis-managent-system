<x-app-layout>
    @php
        $selectedRoleId = old('role_id');
        $selectedRole = $roles->firstWhere('id', (int) $selectedRoleId);
        $showSupervisor = ($selectedRole->name ?? '') === 'student';
    @endphp

    <style>
        .uf-shell {
            --uf-surface: #ffffff;
            --uf-surface-soft: #f6faff;
            --uf-surface-strong: #eaf2ff;
            --uf-border: #dbe6f4;
            --uf-text: #0f172a;
            --uf-muted: #607086;
            --uf-primary: #1d4ed8;
            --uf-danger: #b42318;
        }

        html.app-skin-dark .uf-shell {
            --uf-surface: #131b26;
            --uf-surface-soft: #192331;
            --uf-surface-strong: #223041;
            --uf-border: rgba(255, 255, 255, 0.12);
            --uf-text: #e6edf7;
            --uf-muted: #a3b1c4;
            --uf-primary: #8fb6ff;
            --uf-danger: #ffb3ac;
        }

        .uf-layout {
            max-width: 1040px;
            margin: 0 auto;
        }

        .uf-card {
            border: 1px solid var(--uf-border);
            border-radius: 18px;
            background: var(--uf-surface);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .uf-card-head {
            padding: 18px 18px 14px;
            border-bottom: 1px solid var(--uf-border);
            background: linear-gradient(165deg, var(--uf-surface-soft) 0%, var(--uf-surface) 100%);
        }

        .uf-card-head h3 {
            margin: 0;
            font-size: 1.06rem;
            color: var(--uf-text);
            font-weight: 800;
        }

        .uf-card-head p {
            margin: 6px 0 0;
            color: var(--uf-muted);
            font-size: 0.84rem;
        }

        .uf-card-body {
            padding: 16px 18px 18px;
        }

        .uf-section + .uf-section {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px dashed var(--uf-border);
        }

        .uf-section h4 {
            margin: 0;
            color: var(--uf-text);
            font-size: 0.9rem;
            font-weight: 800;
        }

        .uf-section p {
            margin: 4px 0 0;
            color: var(--uf-muted);
            font-size: 0.8rem;
        }

        .uf-section .row {
            margin-top: 2px;
        }

        .uf-card .form-label {
            color: var(--uf-muted);
        }

        .uf-card .form-control,
        .uf-card .form-select {
            background: var(--uf-surface-soft);
            color: var(--uf-text);
            border-color: var(--uf-border);
        }

        .uf-card .form-control::placeholder {
            color: var(--uf-muted);
        }

        .uf-supervisor {
            margin-top: 10px;
            padding: 12px;
            border: 1px solid var(--uf-border);
            border-radius: 14px;
            background: linear-gradient(165deg, var(--uf-surface-strong) 0%, var(--uf-surface-soft) 100%);
        }

        .uf-supervisor-title {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--uf-primary);
            font-size: 0.84rem;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .uf-help {
            margin-top: 5px;
            color: var(--uf-muted);
            font-size: 0.76rem;
            line-height: 1.45;
        }

        .uf-actions {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid var(--uf-border);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .uf-guide-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 8px;
        }

        .uf-guide-list li {
            border: 1px solid var(--uf-border);
            border-radius: 12px;
            background: var(--uf-surface-soft);
            padding: 9px 10px;
            color: var(--uf-text);
            font-size: 0.8rem;
            line-height: 1.45;
        }

        .uf-guide-list strong {
            display: block;
            color: var(--uf-muted);
            font-size: 0.73rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .uf-modal {
            --uf-surface: #ffffff;
            --uf-surface-soft: #f6faff;
            --uf-border: #dbe6f4;
            --uf-text: #0f172a;
            --uf-muted: #607086;
            z-index: 2300 !important;
        }

        html.app-skin-dark .uf-modal {
            --uf-surface: #131b26;
            --uf-surface-soft: #192331;
            --uf-border: rgba(255, 255, 255, 0.12);
            --uf-text: #e6edf7;
            --uf-muted: #a3b1c4;
        }

        .uf-modal .modal-content {
            border: 1px solid var(--uf-border) !important;
            background-color: var(--uf-surface) !important;
            color: var(--uf-text) !important;
        }

        .uf-modal .modal-header,
        .uf-modal .modal-footer {
            border-color: var(--uf-border) !important;
        }

        .uf-modal.show {
            background: transparent !important;
        }

        .modal-backdrop.show {
            display: block !important;
            background-color: #0f172a !important;
            opacity: 0.58 !important;
        }

        html.app-skin-dark .modal-backdrop.show {
            background-color: #030910 !important;
            opacity: 0.74 !important;
        }

        .uf-modal .modal-dialog {
            margin-top: max(4.8rem, 8vh);
            z-index: 2301;
        }

        .uf-modal.fade .modal-dialog,
        .uf-modal.show .modal-dialog {
            transform: none !important;
            transition: none !important;
        }

        .uf-modal .modal-content {
            opacity: 1;
            background: var(--uf-surface) !important;
            color: var(--uf-text) !important;
            filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            text-shadow: none !important;
            box-shadow: 0 26px 54px rgba(15, 23, 42, 0.34);
        }

        @media (max-width: 767px) {
            .uf-modal .modal-dialog {
                margin-top: 0.8rem;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">
    <div class="ta-page-head uf-shell">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Create User</h1>
            <p class="ta-page-subtitle">Create a new account with role and department access in one clear flow.</p>
        </div>
        <div class="ta-page-actions">
            <button type="button" class="ta-chip-link" data-bs-toggle="modal" data-bs-target="#quickGuideModalCreate">
                <i class="feather-help-circle"></i>
                Quick Guide
            </button>
            <a href="{{ route('admin.users.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Back to Users
            </a>
        </div>
    </div>

    <div class="uf-shell">
        <div class="uf-layout">
            <section class="uf-card" aria-label="Create user form">
                <div class="uf-card-head">
                    <h3>New Account Details</h3>
                    <p>Complete profile, role, and security fields to create the account.</p>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="uf-card-body">
                    @csrf

                    <section class="uf-section">
                        <h4>Profile</h4>
                        <p>Basic identity details shown in dashboards and records.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. John Doe">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="e.g. john@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="uf-section">
                        <h4>Access Assignment</h4>
                        <p>Assign role and department to define workspace scope.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="role_id" class="form-label">System Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" onchange="toggleSupervisorField()">
                                    <option value="">Select role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }} data-role-name="{{ $role->name }}">
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select js-department-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                                    <option value="">Select department...</option>
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
                        </div>

                        <div class="uf-supervisor {{ $showSupervisor ? '' : 'd-none' }}" id="supervisor-field">
                            <span class="uf-supervisor-title"><i class="feather-user-check"></i> Optional Student Supervisor</span>
                            <p class="uf-help">Visible only when role is Student.</p>
                            <label for="supervisor_id" class="form-label mt-2">Supervisor</label>
                            <select class="form-select @error('supervisor_id') is-invalid @enderror" id="supervisor_id" name="supervisor_id">
                                <option value="">Select supervisor...</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->user->name }} ({{ $supervisor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    <section class="uf-section">
                        <h4>Security</h4>
                        <p>Set secure credentials for first login.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Repeat the password">
                            </div>
                        </div>
                    </section>

                    <div class="uf-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather-user-plus me-1"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="modal fade uf-modal" id="quickGuideModalCreate" tabindex="-1" aria-labelledby="quickGuideModalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickGuideModalCreateLabel">Quick Guide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="uf-guide-list">
                        <li>
                            <strong>Student</strong>
                            Submits proposals, uploads thesis versions, and tracks defense status.
                        </li>
                        <li>
                            <strong>Supervisor</strong>
                            Reviews student submissions, gives feedback, and manages milestones.
                        </li>
                        <li>
                            <strong>Examiner</strong>
                            Handles defense evaluation and final grading decisions.
                        </li>
                        <li>
                            <strong>Admin / Coordinator</strong>
                            Manages users, assignments, and operational workflows.
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSupervisorField() {
            const roleSelect = document.getElementById('role_id');
            const selectedOption = roleSelect ? roleSelect.options[roleSelect.selectedIndex] : null;
            const roleName = selectedOption ? selectedOption.getAttribute('data-role-name') : null;
            const supervisorField = document.getElementById('supervisor-field');
            const supervisorSelect = document.getElementById('supervisor_id');

            if (!supervisorField || !supervisorSelect) {
                return;
            }

            if (roleName === 'student') {
                supervisorField.classList.remove('d-none');
            } else {
                supervisorField.classList.add('d-none');
                supervisorSelect.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', toggleSupervisorField);
    </script>
    @include('partials.department-select-create')
    </div>
</x-app-layout>
