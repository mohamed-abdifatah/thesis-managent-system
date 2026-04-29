<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use ZipArchive;

class AdminBackupController extends Controller
{
    public function index()
    {
        $this->ensureBackupDirectory();

        $disk = Storage::disk('local');
        $backups = collect($disk->files('backups'))
            ->filter(fn ($path) => Str::endsWith($path, '.zip'))
            ->map(function ($path) use ($disk) {
                return [
                    'name' => basename($path),
                    'size' => $disk->size($path),
                    'last_modified' => $disk->lastModified($path),
                    'size_label' => $this->formatBytes($disk->size($path)),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return view('admin.backup.index', compact('backups'));
    }

    public function store(): RedirectResponse
    {
        $this->ensureBackupDirectory();

        $backupBase = 'backup-' . now()->format('Ymd-His');
        $workDir = storage_path('app/backups/tmp/' . $backupBase);
        File::makeDirectory($workDir, 0755, true, true);

        try {
            $dumpPath = $workDir . '/database.sql';
            $this->dumpDatabase($dumpPath);

            $storagePublic = storage_path('app/public');
            if (File::isDirectory($storagePublic)) {
                File::copyDirectory($storagePublic, $workDir . '/storage_public');
            }

            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                File::copy($envPath, $workDir . '/.env');
            }

            $manifest = [
                'created_at' => now()->toIso8601String(),
                'database' => config('database.default'),
                'includes' => [
                    'database.sql',
                    'storage_public/',
                    '.env',
                ],
            ];
            File::put($workDir . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));

            $zipPath = storage_path('app/backups/' . $backupBase . '.zip');
            $this->zipDirectory($workDir, $zipPath);
        } catch (\Throwable $e) {
            File::deleteDirectory($workDir);
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }

        File::deleteDirectory($workDir);

        return back()->with('success', 'Backup created successfully.');
    }

    public function download(string $file)
    {
        $this->ensureBackupDirectory();

        $fileName = basename($file);
        $path = 'backups/' . $fileName;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path);
    }

    public function destroy(string $file): RedirectResponse
    {
        $this->ensureBackupDirectory();

        $fileName = basename($file);
        $path = 'backups/' . $fileName;
        Storage::disk('local')->delete($path);

        return back()->with('success', 'Backup deleted.');
    }

    public function restore(Request $request): RedirectResponse
    {
        $request->validate([
            'backup' => 'required|file|mimes:zip|max:512000',
            'restore_env' => 'nullable|boolean',
        ]);

        $this->ensureBackupDirectory();

        $restoreBase = 'restore-' . now()->format('Ymd-His') . '-' . Str::random(6);
        $zipName = $restoreBase . '.zip';
        $zipPath = storage_path('app/backups/' . $zipName);
        $workDir = storage_path('app/backups/tmp/' . $restoreBase);

        File::makeDirectory($workDir, 0755, true, true);
        $request->file('backup')->move(storage_path('app/backups'), $zipName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            File::deleteDirectory($workDir);
            return back()->with('error', 'Unable to open backup archive.');
        }

        $zip->extractTo($workDir);
        $zip->close();

        $sqlPath = $workDir . '/database.sql';
        if (!File::exists($sqlPath)) {
            File::deleteDirectory($workDir);
            return back()->with('error', 'Backup archive missing database.sql');
        }

        try {
            $this->restoreDatabase($sqlPath);

            $storageSource = $workDir . '/storage_public';
            if (File::isDirectory($storageSource)) {
                $storageDest = storage_path('app/public');
                File::deleteDirectory($storageDest);
                File::makeDirectory($storageDest, 0755, true, true);
                File::copyDirectory($storageSource, $storageDest);
            }

            if ($request->boolean('restore_env')) {
                $envSource = $workDir . '/.env';
                if (File::exists($envSource)) {
                    File::copy($envSource, base_path('.env'));
                }
            }
        } catch (\Throwable $e) {
            File::deleteDirectory($workDir);
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }

        File::deleteDirectory($workDir);

        return back()->with('success', 'Backup restored successfully.');
    }

    private function ensureBackupDirectory(): void
    {
        Storage::disk('local')->makeDirectory('backups');
        Storage::disk('local')->makeDirectory('backups/tmp');
    }

    private function dumpDatabase(string $outputPath): void
    {
        $config = $this->databaseConfig();

        $process = new Process([
            'mysqldump',
            '--host=' . $config['host'],
            '--port=' . $config['port'],
            '--user=' . $config['username'],
            '--single-transaction',
            '--routines',
            '--triggers',
            '--add-drop-table',
            $config['database'],
        ], null, ['MYSQL_PWD' => $config['password']], null, 300);

        $process->mustRun();
        File::put($outputPath, $process->getOutput());
    }

    private function restoreDatabase(string $sqlPath): void
    {
        $config = $this->databaseConfig();
        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s %s < %s',
            escapeshellarg($config['host']),
            escapeshellarg((string) $config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['database']),
            escapeshellarg($sqlPath)
        );

        $process = Process::fromShellCommandline($command, null, ['MYSQL_PWD' => $config['password']], null, 300);
        $process->mustRun();
    }

    private function databaseConfig(): array
    {
        $connection = config('database.default');
        $config = config('database.connections.' . $connection);

        if (!$config || ($config['driver'] ?? null) !== 'mysql') {
            throw new \RuntimeException('Backup supports only MySQL connections.');
        }

        return [
            'host' => $config['host'] ?? '127.0.0.1',
            'port' => $config['port'] ?? 3306,
            'username' => $config['username'] ?? '',
            'password' => $config['password'] ?? '',
            'database' => $config['database'] ?? '',
        ];
    }

    private function zipDirectory(string $source, string $zipPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create backup archive.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $relativePath = ltrim(str_replace($source, '', $filePath), DIRECTORY_SEPARATOR);

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $bytes;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return sprintf('%.2f %s', $size, $units[$unitIndex]);
    }
}
