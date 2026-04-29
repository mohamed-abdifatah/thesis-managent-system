<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class InstallController extends Controller
{
    public function index()
    {
        $checks = [];

        $logPath = storage_path('logs');
        if (!is_dir($logPath) || !is_writable($logPath)) {
            $checks[] = 'Storage logs directory is not writable: ' . $logPath;
        }

        $cachePath = base_path('bootstrap/cache');
        if (!is_dir($cachePath) || !is_writable($cachePath)) {
            $checks[] = 'Bootstrap cache directory is not writable: ' . $cachePath;
        }

        $viewCachePath = storage_path('framework/views');
        if (!is_dir($viewCachePath) || !is_writable($viewCachePath)) {
            $checks[] = 'View cache directory is not writable: ' . $viewCachePath;
        }

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            $checks[] = 'Missing .env file. Please create it from .env.example.';
        } elseif (!is_writable($envPath)) {
            $checks[] = '.env file is not writable. Update permissions before installing.';
        }

        try {
            return response()->make(
                view('install.index', ['installChecks' => $checks])->render()
            );
        } catch (\Throwable $e) {
            return response()->make(
                'Install page failed to render: ' . $e->getMessage(),
                500
            );
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'install_token' => 'required|string',
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_mode' => 'required|in:env,manual',
            'account_mode' => 'required|in:seed,manual',
        ]);

        if ($request->db_mode === 'manual') {
            $request->validate([
                'db_host' => 'required|string|max:255',
                'db_port' => 'required|numeric',
                'db_database' => 'required|string|max:255',
                'db_username' => 'required|string|max:255',
                'db_password' => 'nullable|string',
            ]);
        }

        if ($request->account_mode === 'manual') {
            $request->validate([
                'admin_name' => 'required|string|max:255',
                'admin_email' => 'required|email|max:255',
                'admin_password' => 'required|string|min:8|confirmed',
            ]);
        }

        $expectedToken = env('APP_INSTALL_TOKEN');
        if (!$expectedToken || $request->install_token !== $expectedToken) {
            return back()->withErrors(['install_token' => 'Invalid install token.'])->withInput();
        }

        if ($request->boolean('use_cli')) {
            $options = [
                '--app-name' => $request->app_name,
                '--app-url' => $request->app_url,
                '--db-mode' => $request->db_mode,
                '--account-mode' => $request->account_mode,
            ];

            if ($request->boolean('reset_db')) {
                $options['--reset-db'] = '1';
            }

            if ($request->db_mode === 'manual') {
                $options['--db-host'] = $request->db_host;
                $options['--db-port'] = (string) $request->db_port;
                $options['--db-database'] = $request->db_database;
                $options['--db-username'] = $request->db_username;
                $options['--db-password'] = $request->db_password ?? '';
            }

            if ($request->account_mode === 'manual') {
                $options['--admin-name'] = $request->admin_name;
                $options['--admin-email'] = $request->admin_email;
                $options['--admin-password'] = $request->admin_password;
            }

            $exitCode = Artisan::call('tms:install', $options);

            if ($exitCode !== 0) {
                return back()->withErrors(['install' => trim(Artisan::output()) ?: 'CLI install failed.'])->withInput();
            }

            return redirect()->route('login')->with('success', 'Installation complete. Please log in.');
        }

        $envUpdates = [
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
        ];

        if ($request->db_mode === 'manual') {
            $envUpdates['DB_HOST'] = $request->db_host;
            $envUpdates['DB_PORT'] = $request->db_port;
            $envUpdates['DB_DATABASE'] = $request->db_database;
            $envUpdates['DB_USERNAME'] = $request->db_username;
            $envUpdates['DB_PASSWORD'] = $request->db_password ?? '';
        }

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return back()->withErrors(['install' => 'Missing .env file. Please create it from .env.example first.'])->withInput();
        }

        $this->updateEnvFile($envPath, $envUpdates);

        try {
            Artisan::call('config:clear');
            config(['cache.default' => 'file']);
            Artisan::call('cache:clear');
            Artisan::call('key:generate', ['--force' => true]);

            if (!glob(database_path('migrations/*_create_sessions_table.php'))) {
                Artisan::call('session:table');
            }

            if (!glob(database_path('migrations/*_create_cache_table.php'))) {
                Artisan::call('cache:table');
            }

            if (!glob(database_path('migrations/*_create_jobs_table.php'))) {
                Artisan::call('queue:table');
            }

            if ($request->boolean('reset_db')) {
                Artisan::call('migrate:fresh', ['--force' => true]);
            } else {
                Artisan::call('migrate', ['--force' => true]);
            }

            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DepartmentSeeder']);

            if ($request->account_mode === 'seed') {
                Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\UserSeeder']);
            }

            if ($request->account_mode === 'manual') {
                $role = Role::where('name', 'admin')->first();
                $department = Department::first();
                if (!$department) {
                    $department = Department::create([
                        'name' => 'General Studies',
                        'code' => 'GEN',
                    ]);
                }

                User::updateOrCreate(
                    ['email' => $request->admin_email],
                    [
                        'name' => $request->admin_name,
                        'password' => Hash::make($request->admin_password),
                        'role_id' => $role?->id,
                        'department_id' => $department?->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Install failed: ' . $e->getMessage());
            $output = trim(Artisan::output());
            return back()->withErrors(['install' => $output ?: 'Install failed. Check logs for details.'])->withInput();
        }

        $this->updateEnvFile($envPath, ['APP_INSTALLED' => 'true']);
        Artisan::call('config:clear');

        return redirect()->route('login')->with('success', 'Installation complete. Please log in.');
    }

    private function updateEnvFile(string $path, array $values): void
    {
        $contents = file_get_contents($path);

        foreach ($values as $key => $value) {
            $escaped = $this->escapeEnvValue((string) $value);
            if (preg_match("/^{$key}=.*/m", $contents)) {
                $contents = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $contents);
            } else {
                $contents .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($path, $contents);
    }

    private function escapeEnvValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/\s/', $value)) {
            $escaped = str_replace('"', '\\"', $value);
            return '"' . $escaped . '"';
        }

        return $value;
    }
}
