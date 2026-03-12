<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Edit User</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark">User Information</h5>
                        <p class="text-muted small">Update the details for <strong>{{ $user->name }}</strong>.</p>
                    </div>
                    
                    <hr class="text-muted opacity-25" />
                    
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus placeholder="e.g. John Doe">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="e.g. john@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6">
                                <label for="role_id" class="form-label fw-semibold">System Role <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg @error('role_id') is-invalid @enderror" 
                                        id="role_id" name="role_id" onchange="toggleSupervisorField()">
                                    <option value="">Select Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" 
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}
                                            data-role-name="{{ $role->name }}">
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div class="col-md-6">
                                <label for="department_id" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">Select Department...</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }} ({{ $department->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Supervisor Field (Dynamic) -->
                            <div class="col-12 {{ $user->role->name === 'student' ? '' : 'd-none' }} transition-all" id="supervisor-field">
                                <div class="p-4 rounded-3 border border-1 border-primary bg-primary-subtle bg-opacity-10">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="feather-user-check text-primary fs-4 me-2"></i>
                                        <label for="supervisor_id" class="form-label mb-0 fw-bold text-primary">Assign Supervisor</label>
                                    </div>
                                    <p class="small text-muted mb-3">Optional: Link this student to a supervisor.</p>
                                    
                                    <select class="form-select form-select-lg @error('supervisor_id') is-invalid @enderror" id="supervisor_id" name="supervisor_id">
                                        <option value="">Select Supervisor...</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $user->student->supervisor_id ?? '') == $supervisor->id ? 'selected' : '' }}>
                                                {{ $supervisor->user->name }} ({{ $supervisor->specialization }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supervisor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 mt-4 mb-2">
                                <h6 class="fw-bold text-dark border-bottom pb-2">Passsword Management</h6>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">New Password</label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current">
                                <div class="form-text text-muted">Only fill this if you want to change the user's password.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                <input type="password" class="form-control form-control-lg" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 btn-lg">
                                <i class="feather-save me-2"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSupervisorField() {
            const roleSelect = document.getElementById('role_id');
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption ? selectedOption.getAttribute('data-role-name') : null;
            const supervisorField = document.getElementById('supervisor-field');

            if (roleName === 'student') {
                supervisorField.classList.remove('d-none');
            } else {
                supervisorField.classList.add('d-none');
                document.getElementById('supervisor_id').value = ""; // Reset
            }
        }
        
        // No need for DOMContentLoaded listener here because we set the class on server-side render
        // But kept for safety if needed later or for dynamic changes
    </script>
</x-app-layout>

