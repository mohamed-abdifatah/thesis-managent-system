<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Install Thesis Management System</title>
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="mb-4 text-center">
                                <h2 class="fw-bold">Install Thesis Management System</h2>
                                <p class="text-muted">Configure database, app settings, and admin account using the CLI installer.</p>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(!empty($installChecks))
                                <div class="alert alert-warning">
                                    <h6 class="fw-bold">Environment checks</h6>
                                    <ul class="mb-0">
                                        @foreach($installChecks as $check)
                                            <li>{{ $check }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('install.store') }}">
                                @csrf
                                <input type="hidden" name="use_cli" value="1">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="install_token">Install Token</label>
                                    <input type="text" name="install_token" id="install_token" class="form-control" value="{{ old('install_token') }}" required>
                                </div>

                                <hr>
                                <h5 class="fw-bold mb-3">Application</h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="app_name">App Name</label>
                                        <input type="text" name="app_name" id="app_name" class="form-control" value="{{ old('app_name', 'Thesis Management System') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="app_url">App URL</label>
                                        <input type="url" name="app_url" id="app_url" class="form-control" value="{{ old('app_url', request()->root()) }}" required>
                                    </div>
                                </div>

                                <hr class="mt-4">
                                <h5 class="fw-bold mb-3">Database</h5>

                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="db_mode" id="dbModeEnv" value="env" {{ old('db_mode', 'env') === 'env' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dbModeEnv">Use existing .env database</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="db_mode" id="dbModeManual" value="manual" {{ old('db_mode') === 'manual' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dbModeManual">Enter database settings</label>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="reset_db" id="reset_db" value="1" {{ old('reset_db') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="reset_db">Reset existing database (drops and recreates tables)</label>
                                </div>

                                <div id="dbFields">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="db_host">DB Host</label>
                                            <input type="text" name="db_host" id="db_host" class="form-control" value="{{ old('db_host', '127.0.0.1') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="db_port">DB Port</label>
                                            <input type="number" name="db_port" id="db_port" class="form-control" value="{{ old('db_port', '3306') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="db_database">DB Name</label>
                                            <input type="text" name="db_database" id="db_database" class="form-control" value="{{ old('db_database') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="db_username">DB Username</label>
                                            <input type="text" name="db_username" id="db_username" class="form-control" value="{{ old('db_username') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="db_password">DB Password</label>
                                            <input type="password" name="db_password" id="db_password" class="form-control" value="{{ old('db_password') }}">
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-4">
                                <h5 class="fw-bold mb-3">Admin Account</h5>

                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_mode" id="accountSeed" value="seed" {{ old('account_mode', 'seed') === 'seed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="accountSeed">Use default seed users</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_mode" id="accountManual" value="manual" {{ old('account_mode') === 'manual' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="accountManual">Create admin manually</label>
                                    </div>
                                </div>

                                <div id="adminFields">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="admin_name">Admin Name</label>
                                            <input type="text" name="admin_name" id="admin_name" class="form-control" value="{{ old('admin_name') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="admin_email">Admin Email</label>
                                            <input type="email" name="admin_email" id="admin_email" class="form-control" value="{{ old('admin_email') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="admin_password">Admin Password</label>
                                            <input type="password" name="admin_password" id="admin_password" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="admin_password_confirmation">Confirm Password</label>
                                            <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-terminal me-1"></i> Install via CLI Engine
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center text-muted small mt-3">After installation, the install page will be locked.</div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dbModeEnv = document.getElementById('dbModeEnv');
                const dbModeManual = document.getElementById('dbModeManual');
                const dbFields = document.getElementById('dbFields');

                const accountSeed = document.getElementById('accountSeed');
                const accountManual = document.getElementById('accountManual');
                const adminFields = document.getElementById('adminFields');

                const toggleDbFields = () => {
                    if (dbModeManual.checked) {
                        dbFields.classList.remove('d-none');
                    } else {
                        dbFields.classList.add('d-none');
                    }
                };

                const toggleAdminFields = () => {
                    if (accountManual.checked) {
                        adminFields.classList.remove('d-none');
                    } else {
                        adminFields.classList.add('d-none');
                    }
                };

                dbModeEnv.addEventListener('change', toggleDbFields);
                dbModeManual.addEventListener('change', toggleDbFields);
                accountSeed.addEventListener('change', toggleAdminFields);
                accountManual.addEventListener('change', toggleAdminFields);

                toggleDbFields();
                toggleAdminFields();
            });
        </script>
    </body>
</html>
