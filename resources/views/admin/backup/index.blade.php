<x-app-layout>
    <style>
        .bk-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .bk-list {
            display: grid;
            gap: 10px;
        }

        .bk-item {
            border: 1px solid var(--adm-border);
            border-radius: 14px;
            padding: 12px 14px;
            background: #fffaf4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .bk-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .bk-name {
            font-weight: 700;
            color: var(--adm-ink);
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bk-sub {
            color: var(--adm-muted);
            font-size: 0.78rem;
        }

        .bk-actions {
            display: inline-flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .bk-actions form {
            margin: 0;
        }

        @media (max-width: 991px) {
            .bk-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">
        <div class="ta-page-head">
            <div>
                <span class="ta-page-kicker">Administration</span>
                <h1 class="ta-page-title">Backup & Restore</h1>
                <p class="ta-page-subtitle">Create a full snapshot of the database, uploads, and environment file, then restore when needed.</p>
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

        <div class="bk-grid">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Create Backup</h3>
                </div>
                <div class="ta-panel-body">
                    <p class="text-muted small mb-3">This creates a zip file with <strong>database.sql</strong>, <strong>storage/app/public</strong>, and optional <strong>.env</strong>.</p>
                    <form method="POST" action="{{ route('admin.backup.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="feather-download-cloud me-1"></i>
                            Create Backup
                        </button>
                    </form>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Restore Backup</h3>
                </div>
                <div class="ta-panel-body">
                    <p class="text-muted small mb-3">Restoring will replace the database and uploads. Use carefully.</p>
                    <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="backup">Backup File (.zip)</label>
                            <input type="file" class="form-control" id="backup" name="backup" accept=".zip" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="restore_env" name="restore_env">
                            <label class="form-check-label" for="restore_env">Restore .env from backup</label>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="feather-refresh-ccw me-1"></i>
                            Restore Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="ta-panel mt-3">
            <div class="ta-panel-head">
                <h3>Available Backups</h3>
            </div>
            <div class="ta-panel-body">
                @if($backups->isEmpty())
                    <p class="text-muted mb-0">No backups created yet.</p>
                @else
                    <div class="bk-list">
                        @foreach($backups as $backup)
                            <div class="bk-item">
                                <div class="bk-meta">
                                    <span class="bk-name">{{ $backup['name'] }}</span>
                                    <span class="bk-sub">{{ $backup['size_label'] }} • {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="bk-actions">
                                    <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm btn-light">
                                        <i class="feather-download"></i>
                                        Download
                                    </a>
                                    <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" onsubmit="return confirm('Delete this backup?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="feather-trash-2"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
