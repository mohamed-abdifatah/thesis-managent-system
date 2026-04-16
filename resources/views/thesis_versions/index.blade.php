<x-app-layout>
    @php
        $approvedCount = $versions->where('status', 'approved')->count();
        $needsChangesCount = $versions->where('status', 'needs_changes')->count();
        $reviewedCount = $versions->where('status', 'reviewed')->count();
        $draftCount = $versions->where('status', 'draft')->count();
        $latestVersion = $versions->first();
        $feedbackTotal = $thesis->feedbacks->count();
    @endphp

    <style>
        .tv-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .tv-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tv-stat .icon {
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

        .tv-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .tv-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .tv-layout {
            display: grid;
            grid-template-columns: 0.95fr 1.35fr;
            gap: 16px;
        }

        .tv-sidebar {
            position: sticky;
            top: 78px;
            align-self: flex-start;
        }

        .tv-note {
            border-radius: 12px;
            border: 1px solid #d8e5f7;
            background: #f7fbff;
            color: #4a5e77;
            padding: 10px 12px;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 14px;
        }

        .tv-table tbody td {
            vertical-align: middle;
        }

        .tv-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 9px;
            border: 1px solid transparent;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .tv-status.draft {
            color: #66758d;
            background: #f1f5fb;
            border-color: #dbe4f1;
        }

        .tv-status.reviewed {
            color: #1d4ed8;
            background: #eef4ff;
            border-color: #cfe0ff;
        }

        .tv-status.needs_changes {
            color: #9a6400;
            background: #fff4dd;
            border-color: #f6d49a;
        }

        .tv-status.approved {
            color: #0f7b46;
            background: #edfdf3;
            border-color: #bfead1;
        }

        .tv-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d8e6f8;
            background: #f7faff;
            padding: 4px 10px;
            font-size: 0.73rem;
            font-weight: 700;
            color: #415a76;
        }

        .tv-sub {
            color: #66758d;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .tv-row-title {
            margin: 0;
            color: #10233e;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .tv-kpi-list {
            display: grid;
            gap: 8px;
        }

        .tv-kpi {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #dce7f5;
            border-radius: 10px;
            padding: 8px 10px;
            background: #fbfdff;
            font-size: 0.79rem;
            color: #4f6380;
        }

        .tv-kpi strong {
            color: #10233e;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .tv-action-btn {
            min-height: 32px;
            padding: 0.32rem 0.65rem;
            border-radius: 9px;
            border: 1px solid #cfe0ff;
            color: #1d4ed8;
            background: #eef4ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.74rem;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .tv-action-btn:hover {
            color: #1e40af;
            background: #e3edff;
            border-color: #b9d0ff;
            transform: translateY(-1px);
        }

        .tv-empty {
            text-align: center;
            padding: 38px 16px;
            color: #66758d;
        }

        .tv-empty i {
            font-size: 1.7rem;
            color: #90a4bf;
            display: inline-block;
            margin-bottom: 10px;
        }

        .tv-empty h4 {
            margin: 0;
            color: #10233e;
            font-size: 1.04rem;
        }

        .tv-empty p {
            margin: 6px 0 0;
            font-size: 0.84rem;
        }

        .unit-loader {
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(27, 31, 36, 0.2);
            border-top-color: #1b84ff;
            border-radius: 50%;
            animation: unit-spin 0.7s linear infinite;
        }

        @keyframes unit-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .tv-chat-fab {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 2000;
            border-radius: 999px;
            padding: 12px 18px;
            box-shadow: 0 10px 24px rgba(15, 20, 27, 0.18);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .tv-chat-overlay {
            position: fixed;
            inset: 0;
            z-index: 1060;
            background: rgba(15, 20, 27, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .tv-chat-panel {
            width: 100%;
            max-width: 1100px;
            max-height: 85vh;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 20, 27, 0.2);
            overflow: hidden;
        }

        .tv-chat-shell {
            max-height: calc(85vh - 180px);
            overflow-y: auto;
            padding: 1rem;
            background: #e5ddd5;
        }

        .tv-chat-row {
            display: flex;
            margin-bottom: 10px;
        }

        .tv-chat-row.is-mine {
            justify-content: flex-end;
        }

        .tv-chat-bubble {
            max-width: 75%;
            padding: 10px 12px;
            border-radius: 12px;
            background: #ffffff;
            color: #1b1f24;
            box-shadow: 0 4px 12px rgba(15, 20, 27, 0.08);
        }

        .tv-chat-row.is-mine .tv-chat-bubble {
            background: #dcf8c6;
        }

        .tv-chat-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .tv-chat-input {
            background: #ffffff;
            border: 1px solid rgba(27, 31, 36, 0.15);
            border-radius: 999px;
            padding: 10px 14px;
        }

        html.app-skin-dark .tv-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .tv-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tv-stat .label,
        html.app-skin-dark .tv-note,
        html.app-skin-dark .tv-sub,
        html.app-skin-dark .tv-empty,
        html.app-skin-dark .tv-empty p,
        html.app-skin-dark .tv-kpi {
            color: #a3b1c4;
        }

        html.app-skin-dark .tv-stat .value,
        html.app-skin-dark .tv-row-title,
        html.app-skin-dark .tv-empty h4,
        html.app-skin-dark .tv-kpi strong {
            color: #e6edf7;
        }

        html.app-skin-dark .tv-note {
            background: #1a2534;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tv-kpi {
            border-color: rgba(255, 255, 255, 0.14);
            background: #172232;
        }

        html.app-skin-dark .tv-chip {
            color: #c8d3e3;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tv-status.draft {
            color: #c4cedc;
            background: #202c3d;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .tv-status.reviewed {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .tv-status.needs_changes {
            color: #ffdeaa;
            background: rgba(138, 101, 39, 0.33);
            border-color: rgba(194, 154, 92, 0.47);
        }

        html.app-skin-dark .tv-status.approved {
            color: #a6f0c8;
            background: rgba(35, 115, 73, 0.32);
            border-color: rgba(90, 175, 133, 0.42);
        }

        html.app-skin-dark .tv-action-btn {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        @media (max-width: 1399px) {
            .tv-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .tv-layout {
                grid-template-columns: 1fr;
            }

            .tv-sidebar {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .tv-stats {
                grid-template-columns: 1fr;
            }

            .tv-chat-fab {
                right: 14px;
                bottom: 14px;
                padding: 10px 14px;
            }
        }
    </style>

    @include('partials.student-account-refresh')

    <div class="stu-refresh">
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Student Workspace</span>
            <h1 class="ta-page-title">Thesis Versions</h1>
            <p class="ta-page-subtitle">Upload unit versions, monitor review status, and coordinate feedback with your supervisor and committee.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('proposals.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Proposals
            </a>
            <a href="{{ route('defense.schedule') }}" class="ta-chip-link">
                <i class="feather-calendar"></i>
                Defense Schedule
            </a>
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

    <section class="tv-stats" aria-label="Thesis version summary cards">
        <article class="tv-stat">
            <span class="icon"><i class="feather-layers"></i></span>
            <div>
                <p class="label">Total Versions</p>
                <p class="value">{{ number_format($versions->count()) }}</p>
            </div>
        </article>
        <article class="tv-stat">
            <span class="icon"><i class="feather-check-circle"></i></span>
            <div>
                <p class="label">Approved</p>
                <p class="value">{{ number_format($approvedCount) }}</p>
            </div>
        </article>
        <article class="tv-stat">
            <span class="icon"><i class="feather-edit-3"></i></span>
            <div>
                <p class="label">Needs Changes</p>
                <p class="value">{{ number_format($needsChangesCount) }}</p>
            </div>
        </article>
        <article class="tv-stat">
            <span class="icon"><i class="feather-message-square"></i></span>
            <div>
                <p class="label">Feedback Messages</p>
                <p class="value">{{ number_format($feedbackTotal) }}</p>
            </div>
        </article>
    </section>

    <div class="tv-layout">
        <div class="tv-sidebar d-grid gap-3">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Upload Unit Document</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="tv-note">
                        Select an existing unit or create a new one directly in the dropdown before upload.
                    </div>

                    <form method="POST" action="{{ route('thesis.versions.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required accept=".pdf,.doc,.docx">
                            <div class="form-text">PDF, DOC, DOCX up to 10MB</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2 unit-binding" data-unit-binding>
                            <div class="col-12">
                                <label for="thesis_unit_id" class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <span>Unit</span>
                                    <span class="unit-loader d-none" data-unit-list-spinner></span>
                                    <span class="unit-loader d-none" data-unit-create-spinner></span>
                                </label>
                                <select id="thesis_unit_id" name="thesis_unit_id" class="form-select @error('thesis_unit_id') is-invalid @enderror js-unit-select">
                                    <option value="">Select existing unit...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" @selected(old('thesis_unit_id') == $unit->id)>{{ $unit->name }}</option>
                                    @endforeach
                                    <option value="__create__">+ Create new unit...</option>
                                </select>
                                @error('thesis_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="unit_number" class="form-label fw-semibold">Unit Number <span class="text-danger">*</span></label>
                                <input type="number" min="1" max="999" id="unit_number" name="unit_number" class="form-control @error('unit_number') is-invalid @enderror" value="{{ old('unit_number', 1) }}" required>
                                @error('unit_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3 mb-3">
                            <label for="comments" class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea id="comments" name="comments" rows="3" class="form-control" placeholder="Add a short summary of changes...">{{ old('comments') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather-upload-cloud me-2"></i>
                            Upload Version
                        </button>
                    </form>
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Current Snapshot</h3>
                </div>
                <div class="ta-panel-body">
                    <div class="tv-kpi-list">
                        <div class="tv-kpi">
                            <span>Latest Version</span>
                            <strong>{{ $latestVersion ? '#'.$latestVersion->version_number : 'Not Uploaded' }}</strong>
                        </div>
                        <div class="tv-kpi">
                            <span>Latest Status</span>
                            <strong>{{ $latestVersion ? ucfirst(str_replace('_', ' ', $latestVersion->status)) : 'N/A' }}</strong>
                        </div>
                        <div class="tv-kpi">
                            <span>Reviewed</span>
                            <strong>{{ $reviewedCount }}</strong>
                        </div>
                        <div class="tv-kpi">
                            <span>Draft</span>
                            <strong>{{ $draftCount }}</strong>
                        </div>
                        <div class="tv-kpi">
                            <span>Thesis Status</span>
                            <strong>{{ ucfirst(str_replace('_', ' ', $thesis->status)) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ta-panel">
            <div class="ta-panel-head">
                <div>
                    <h3>Version History</h3>
                    <span class="text-muted small">{{ $versions->count() }} uploaded entries</span>
                </div>
                <span class="tv-chip"><i class="feather-layers"></i> {{ $units->count() }} unit(s)</span>
            </div>

            <div class="ta-table-shell">
                <table class="table table-hover mb-0 tv-table">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>No.</th>
                            <th>Status</th>
                            <th>Uploaded</th>
                            <th>Reviewer</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($versions as $version)
                            <tr>
                                <td>
                                    <p class="tv-row-title">{{ $version->unit?->name ?? 'General Unit' }}</p>
                                    <span class="tv-sub">Version #{{ $version->version_number }}</span>
                                </td>
                                <td>
                                    <span class="tv-chip">{{ $version->unit_number ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="tv-status {{ $version->status }}">{{ str_replace('_', ' ', $version->status) }}</span>
                                </td>
                                <td>
                                    <span class="tv-sub d-block">{{ $version->created_at->format('M d, Y') }}</span>
                                    <span class="tv-sub">{{ $version->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="tv-sub">{{ $version->reviewer?->name ?? 'Not reviewed' }}</span>
                                </td>
                                <td>
                                    @if($version->comments)
                                        <span class="tv-sub d-inline-block" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $version->comments }}
                                        </span>
                                    @else
                                        <span class="tv-sub">No notes</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="tv-action-btn" data-bs-toggle="modal" data-bs-target="#editVersionModal{{ $version->id }}">
                                        <i class="feather-edit"></i>
                                        Edit
                                    </button>
                                    <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="tv-action-btn ms-1">
                                        <i class="feather-download"></i>
                                        File
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="tv-empty">
                                        <i class="feather-inbox"></i>
                                        <h4>No version uploads yet</h4>
                                        <p>Start by uploading your first thesis unit document.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($versions as $version)
        <div class="modal fade" id="editVersionModal{{ $version->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit {{ $version->unit?->name ?? 'Unit' }} {{ $version->unit_number ?? '' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('thesis.versions.update', $version) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row g-3 unit-binding" data-unit-binding>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                        <span>Use Existing Unit</span>
                                        <span class="unit-loader d-none" data-unit-list-spinner></span>
                                        <span class="unit-loader d-none" data-unit-create-spinner></span>
                                    </label>
                                    <select name="thesis_unit_id" class="form-select js-unit-select">
                                        <option value="">Select existing unit...</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" @selected($version->thesis_unit_id === $unit->id)>{{ $unit->name }}</option>
                                        @endforeach
                                        <option value="__create__">+ Create new unit...</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Unit Number <span class="text-danger">*</span></label>
                                    <input type="number" min="1" max="999" name="unit_number" class="form-control" value="{{ $version->unit_number ?? 1 }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Replace Document (Optional)</label>
                                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Notes</label>
                                    <textarea name="comments" rows="3" class="form-control" placeholder="Update notes for this version...">{{ $version->comments }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-1"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <x-thesis-chat-overlay :thesis="$thesis" :chat-items="$thesis->feedbacks" />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const unitsListUrl = '{{ route('thesis.versions.units.list') }}';
            const unitsCreateUrl = '{{ route('thesis.versions.units.store') }}';
            const csrfToken = '{{ csrf_token() }}';

            const syncUnitOptions = (allSelects, units) => {
                allSelects.forEach((select) => {
                    const selectedValue = select.value;
                    select.innerHTML = '<option value="">Select existing unit...</option>';

                    units.forEach((unit) => {
                        const option = document.createElement('option');
                        option.value = String(unit.id);
                        option.textContent = unit.name;
                        select.appendChild(option);
                    });

                    const createOption = document.createElement('option');
                    createOption.value = '__create__';
                    createOption.textContent = '+ Create new unit...';
                    select.appendChild(createOption);

                    if (selectedValue && selectedValue !== '__create__' && [...select.options].some((opt) => opt.value === selectedValue)) {
                        select.value = selectedValue;
                    }
                });
            };

            const fetchUnits = async (spinner) => {
                spinner?.classList.remove('d-none');
                try {
                    const response = await fetch(unitsListUrl, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });
                    if (!response.ok) {
                        throw new Error('Failed to load units');
                    }

                    const payload = await response.json();
                    return payload.data ?? [];
                } finally {
                    spinner?.classList.add('d-none');
                }
            };

            const createUnit = async (name, spinner) => {
                spinner?.classList.remove('d-none');
                try {
                    const response = await fetch(unitsCreateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ name }),
                    });

                    if (!response.ok) {
                        throw new Error('Failed to create unit');
                    }

                    const payload = await response.json();
                    return payload.data;
                } finally {
                    spinner?.classList.add('d-none');
                }
            };

            const bindUnitControls = (container, allSelects) => {
                const select = container.querySelector('.js-unit-select');
                const listSpinner = container.querySelector('[data-unit-list-spinner]');
                const createSpinner = container.querySelector('[data-unit-create-spinner]');

                if (!select) {
                    return;
                }

                let isLoadedOnce = false;

                const loadUnitsIntoSelects = async () => {
                    const units = await fetchUnits(listSpinner);
                    syncUnitOptions(allSelects, units);
                    isLoadedOnce = true;
                };

                const openLoader = async () => {
                    if (!isLoadedOnce) {
                        await loadUnitsIntoSelects();
                        return;
                    }

                    const units = await fetchUnits(listSpinner);
                    syncUnitOptions(allSelects, units);
                };

                select.addEventListener('focus', openLoader);
                select.addEventListener('mousedown', () => {
                    void openLoader();
                });

                select.addEventListener('change', async () => {
                    if (select.value !== '__create__') {
                        return;
                    }

                    const name = window.prompt('Enter new unit name (e.g. Chapter):');
                    if (!name || !name.trim()) {
                        select.value = '';
                        return;
                    }

                    try {
                        const created = await createUnit(name.trim(), createSpinner);
                        const units = await fetchUnits(listSpinner);
                        syncUnitOptions(allSelects, units);
                        if (created?.id) {
                            select.value = String(created.id);
                        } else {
                            select.value = '';
                        }
                    } catch (error) {
                        console.error(error);
                        select.value = '';
                    }
                });
            };

            const unitBindings = document.querySelectorAll('[data-unit-binding]');
            const allUnitSelects = document.querySelectorAll('.js-unit-select');
            unitBindings.forEach((el) => bindUnitControls(el, allUnitSelects));

        });
    </script>
    </div>
</x-app-layout>
