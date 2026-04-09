<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

        if (!$request->boolean('use_cli')) {
            $request->merge(['use_cli' => true]);
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
    }
}
