<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tms:install
    {--app-name= : Set APP_NAME}
    {--app-url= : Set APP_URL}
    {--db-mode= : env or manual}
    {--db-host= : DB host}
    {--db-port= : DB port}
    {--db-database= : DB name}
    {--db-username= : DB username}
    {--db-password= : DB password}
    {--reset-db : Drop and recreate all tables}
    {--account-mode= : seed or manual}
    {--admin-name= : Admin name}
    {--admin-email= : Admin email}
    {--admin-password= : Admin password}
', function () {
    $this->info('Thesis Management System installer');

    $dbMode = $this->option('db-mode') ?: $this->choice('Database settings', ['env', 'manual'], 0);
    $accountMode = $this->option('account-mode') ?: $this->choice('Account setup', ['seed', 'manual'], 0);

    $envUpdates = [
        'APP_INSTALLED' => 'true',
    ];

    if ($this->option('app-name')) {
        $envUpdates['APP_NAME'] = $this->option('app-name');
    }

    if ($this->option('app-url')) {
        $envUpdates['APP_URL'] = $this->option('app-url');
    }

    if ($dbMode === 'manual') {
        $envUpdates['DB_HOST'] = $this->option('db-host') ?: $this->ask('DB host', env('DB_HOST', '127.0.0.1'));
        $envUpdates['DB_PORT'] = $this->option('db-port') ?: $this->ask('DB port', env('DB_PORT', '3306'));
        $envUpdates['DB_DATABASE'] = $this->option('db-database') ?: $this->ask('DB name', env('DB_DATABASE', 'thesis_management'));
        $envUpdates['DB_USERNAME'] = $this->option('db-username') ?: $this->ask('DB username', env('DB_USERNAME', 'root'));
        $envUpdates['DB_PASSWORD'] = $this->option('db-password') ?? $this->secret('DB password') ?? '';
    }

    $envPath = base_path('.env');
    if (!file_exists($envPath)) {
        $this->error('Missing .env file. Create it from .env.example first.');
        return 1;
    }

    $updateEnv = function (string $path, array $values): void {
        $contents = file_get_contents($path);

        foreach ($values as $key => $value) {
            $escaped = $value;
            if ($escaped === '') {
                $escaped = '';
            } elseif (preg_match('/\s/', $escaped)) {
                $escaped = '"' . str_replace('"', '\\"', $escaped) . '"';
            }

            if (preg_match("/^{$key}=.*/m", $contents)) {
                $contents = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $contents);
            } else {
                $contents .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($path, $contents);
    };

    $updateEnv($envPath, $envUpdates);

    Artisan::call('config:clear');
    config(['cache.default' => 'file']);
    Artisan::call('cache:clear');
    Artisan::call('key:generate', ['--force' => true]);
    if ($this->option('reset-db')) {
        Artisan::call('migrate:fresh', ['--force' => true]);
    } else {
        Artisan::call('migrate', ['--force' => true]);
    }
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DepartmentSeeder']);

    if ($accountMode === 'seed') {
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\UserSeeder']);
        $this->info('Seed users created.');
        return 0;
    }

    $role = Role::where('name', 'admin')->first();
    $department = Department::first();

    $adminName = $this->option('admin-name') ?: $this->ask('Admin name');
    $adminEmail = $this->option('admin-email') ?: $this->ask('Admin email');
    $adminPassword = $this->option('admin-password') ?? $this->secret('Admin password');

    if (!$adminName || !$adminEmail || !$adminPassword) {
        $this->error('Admin name, email, and password are required.');
        return 1;
    }

    User::updateOrCreate(
        ['email' => $adminEmail],
        [
            'name' => $adminName,
            'password' => Hash::make($adminPassword),
            'role_id' => $role?->id,
            'department_id' => $department?->id,
        ]
    );

    $this->info('Admin user created.');
    return 0;
})->purpose('Install Thesis Management System');
