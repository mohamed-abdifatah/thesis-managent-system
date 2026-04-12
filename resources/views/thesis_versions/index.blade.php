<x-app-layout>
    <style>
                .chat-panel .card-body {
            flex: 1;
            min-height: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .chat-shell {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #e5ddd5;
        }

        .chat-row {
            display: flex;
            margin-bottom: 10px;
        }

        .chat-row.is-mine {
            justify-content: flex-end;
        }

        .chat-bubble {
            max-width: 75%;
            padding: 10px 12px;
            border-radius: 12px;
            background: #ffffff;
            color: #1b1f24;
            box-shadow: 0 4px 12px rgba(15, 20, 27, 0.08);
        }

        .chat-row.is-mine .chat-bubble {
            background: #dcf8c6;
        }

        .chat-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .chat-input {
            background: #ffffff;
            border: 1px solid rgba(27, 31, 36, 0.15);
            border-radius: 999px;
            padding: 10px 14px;
        }

        .chat-send {
            border-radius: 999px;
        }

        .chat-fab {
            position: fixed !important;
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

        .chat-overlay {
            position: fixed;
            inset: 0;
            z-index: 1060;
            background: rgba(15, 20, 27, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .chat-panel {
            width: 100%;
            max-width: 1100px;
            max-height: 85vh;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 20, 27, 0.2);
            overflow: scroll;
        }

        .chat-panel .card {
            border: 0;
            border-radius: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chat-panel .card-body {
            flex: 1;
            min-height: 0;
            overflow: hidden;
            padding: 1rem;
        }

        .chat-panel .card-footer {
            background: #ffffff;
            flex: 0 0 auto;
            border-top: 1px solid rgba(27, 31, 36, 0.08);
            padding: 14px 16px;
        }

        .chat-shell::-webkit-scrollbar {
            width: 8px;
        }

        .chat-shell::-webkit-scrollbar-track {
            background: rgba(27, 31, 36, 0.08);
            border-radius: 999px;
        }

        .chat-shell::-webkit-scrollbar-thumb {
            background: rgba(27, 31, 36, 0.35);
            border-radius: 999px;
        }

        .chat-shell::-webkit-scrollbar-thumb:hover {
            background: rgba(27, 31, 36, 0.5);
        }

        .chat-panel .card-header {
            border-bottom: 1px solid rgba(27, 31, 36, 0.08);
        }

        .unit-loader {
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(27, 31, 36, 0.2);
            border-top-color: #1b84ff;
            border-radius: 50%;
            animation: unit-spin 0.7s linear infinite;
        }

        .unit-create-wrap {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        @keyframes unit-spin {
            to {
                transform: rotate(360deg);
            }
        }

    </style>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Thesis Units</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Units</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to Proposals
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title fw-bold mb-0">Upload Unit Document</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('thesis.versions.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required accept=".pdf,.doc,.docx">
                            <div class="form-text">PDF, DOC, DOCX (max 10MB)</div>
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
                        <div class="mb-3">
                            <label for="comments" class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea id="comments" name="comments" rows="3" class="form-control" placeholder="Add a short summary of changes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather-upload-cloud me-2"></i> Upload Unit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0">Unit History</h5>
                    <span class="badge bg-soft-primary text-primary">{{ $versions->count() }} Entries</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Unit</th>
                                    <th>No.</th>
                                    <th>Document</th>
                                    <th>Uploaded</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($versions as $version)
                                    <tr>
                                        <td>
                                            <span class="badge bg-soft-info text-info">{{ $version->unit?->name ?? 'General' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $version->unit_number ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-light-brand" title="Open Document">
                                                <i class="feather-file-text"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $version->created_at->format('M d, Y') }}</span>
                                                <span class="fs-12 text-muted">{{ $version->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($version->status) {
                                                    'approved' => 'bg-success',
                                                    'needs_changes' => 'bg-warning',
                                                    'reviewed' => 'bg-info',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} text-uppercase">
                                                {{ str_replace('_', ' ', $version->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($version->comments)
                                                <span class="text-truncate d-inline-block" style="max-width: 220px;">
                                                    {{ $version->comments }}
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic">No notes</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editVersionModal{{ $version->id }}" title="Edit Unit Entry">
                                                <i class="feather-edit"></i>
                                            </button>
                                            <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-light-brand" title="Download">
                                                <i class="feather-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="feather-inbox fs-1 text-muted opacity-50 mb-3"></i>
                                            <p class="text-muted mb-0">No unit documents uploaded yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                                <i class="feather-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <button type="button" class="btn btn-primary chat-fab" data-chat-toggle="thesis-chat" data-chat-button>
        <i class="feather-message-circle me-1"></i> Chat
    </button>

    <div class="chat-overlay d-none" data-chatbox="thesis-chat">
        <div class="chat-panel">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0">Thesis Chat</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-chat-toggle="thesis-chat">
                        <i class="feather-x me-1"></i> Close
                    </button>
                </div>
                <div class="card-body">
                    <div class="chat-shell">
                        @php
                            $chatItems = $thesis->feedbacks->sortBy('created_at');
                        @endphp
                        @forelse($chatItems as $feedback)
                            @php
                                $isMine = $feedback->user_id === auth()->id();
                            @endphp
                            <div class="chat-row {{ $isMine ? 'is-mine' : '' }}">
                                <div class="chat-bubble">
                                    <div class="chat-meta">
                                        <span class="fw-semibold">{{ $feedback->user->name ?? 'User' }}</span>
                                        <span>· {{ $feedback->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($feedback->topic)
                                        <div class="mb-1"><span class="badge bg-light text-dark">{{ $feedback->topic }}</span></div>
                                    @endif
                                    @if($feedback->thesisVersion)
                                        <div class="mb-1"><span class="badge bg-soft-primary text-primary">{{ $feedback->thesisVersion->unit?->name ?? 'Unit' }} {{ $feedback->thesisVersion->unit_number ?? '' }}</span></div>
                                    @endif
                                    <div>{{ $feedback->comment }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted fst-italic">No messages yet.</div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('thesis.feedback.store', $thesis) }}" class="w-100">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-4">
                                <input type="text" name="topic" class="form-control" placeholder="Topic (optional)">
                            </div>
                            <div class="col-12 col-md-3">
                                <select name="thesis_version_id" class="form-select">
                                    <option value="">General</option>
                                    @foreach($versions as $version)
                                        <option value="{{ $version->id }}">{{ $version->unit?->name ?? 'Unit' }} {{ $version->unit_number ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control chat-input" placeholder="Type a message..." required>
                                    <button class="btn btn-primary chat-send" type="submit">
                                        <i class="feather-send"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                            'Accept': 'application/json',
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
                            'Accept': 'application/json',
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

                    // Refresh list on open so users see new units created in other dialogs.
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

            const buttons = document.querySelectorAll('[data-chat-toggle]');
            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-chat-toggle');
                    const boxes = document.querySelectorAll(`[data-chatbox="${target}"]`);
                    if (!boxes.length) {
                        return;
                    }
                    const isHidden = boxes[0].classList.contains('d-none');

                    boxes.forEach((box) => box.classList.toggle('d-none', !isHidden));
                    button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                    if (button.hasAttribute('data-chat-button')) {
                        button.innerHTML = isHidden
                            ? '<i class="feather-x me-1"></i> Close'
                            : '<i class="feather-message-circle me-1"></i> Chat';
                    }
                });
            });
        });
    </script>
</x-app-layout>
