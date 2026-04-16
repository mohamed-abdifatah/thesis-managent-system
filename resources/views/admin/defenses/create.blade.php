<x-app-layout>
    @php
        $committeeRows = old('committee');
        if (!is_array($committeeRows) || count($committeeRows) === 0) {
            $committeeRows = [['user_id' => '', 'role' => 'examiner']];
        }

        $readyForDefenseCount = $theses->where('status', 'ready_for_defense')->count();
    @endphp

    <style>
        .dc-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .dc-stat {
            border: 1px solid var(--ta-border);
            border-radius: 16px;
            background: linear-gradient(165deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dc-stat .icon {
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

        .dc-stat .label {
            margin: 0;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #657892;
            font-weight: 700;
        }

        .dc-stat .value {
            margin: 2px 0 0;
            font-size: 1.32rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .dc-form-note {
            border-radius: 12px;
            border: 1px solid #d8e5f7;
            background: #f7fbff;
            color: #4a5e77;
            padding: 10px 12px;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-bottom: 14px;
        }

        .dc-table tbody td {
            vertical-align: middle;
        }

        .dc-table .form-select {
            min-width: 170px;
        }

        .dc-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d8e6f8;
            background: #f7faff;
            padding: 4px 10px;
            font-size: 0.74rem;
            font-weight: 700;
            color: #3f5874;
        }

        .dc-action-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 16px;
        }

        html.app-skin-dark .dc-stat {
            background: linear-gradient(165deg, #151e2b 0%, #1b2636 100%);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.25);
        }

        html.app-skin-dark .dc-stat .icon {
            color: #9fc1ff;
            background: rgba(48, 88, 168, 0.35);
            border-color: rgba(110, 154, 242, 0.45);
        }

        html.app-skin-dark .dc-stat .label,
        html.app-skin-dark .dc-form-note {
            color: #a3b1c4;
        }

        html.app-skin-dark .dc-stat .value {
            color: #e6edf7;
        }

        html.app-skin-dark .dc-form-note {
            background: #1a2534;
            border-color: rgba(255, 255, 255, 0.14);
        }

        html.app-skin-dark .dc-role-pill {
            color: #c8d3e3;
            background: #1c2736;
            border-color: rgba(255, 255, 255, 0.14);
        }

        @media (max-width: 1199px) {
            .dc-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .dc-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.admin-account-refresh')

    <div class="adm-refresh">

    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Administration</span>
            <h1 class="ta-page-title">Create Defense Session</h1>
            <p class="ta-page-subtitle">Schedule a defense, choose thesis candidate, and assign committee members with clear roles.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('admin.defenses.index') }}" class="ta-chip-link">
                <i class="feather-arrow-left"></i>
                Back to Sessions
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-3" role="alert">
            Please review the form. Some fields are invalid.
        </div>
    @endif

    <section class="dc-stats" aria-label="Defense create summary cards">
        <article class="dc-stat">
            <span class="icon"><i class="feather-book-open"></i></span>
            <div>
                <p class="label">Available Theses</p>
                <p class="value">{{ number_format($theses->count()) }}</p>
            </div>
        </article>
        <article class="dc-stat">
            <span class="icon"><i class="feather-flag"></i></span>
            <div>
                <p class="label">Ready For Defense</p>
                <p class="value">{{ number_format($readyForDefenseCount) }}</p>
            </div>
        </article>
        <article class="dc-stat">
            <span class="icon"><i class="feather-users"></i></span>
            <div>
                <p class="label">Available Examiners</p>
                <p class="value">{{ number_format($examiners->count()) }}</p>
            </div>
        </article>
    </section>

    <form method="POST" action="{{ route('admin.defenses.store') }}" id="createDefenseForm">
        @csrf

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="ta-panel mb-4">
                    <div class="ta-panel-head">
                        <h3>Session Details</h3>
                    </div>
                    <div class="ta-panel-body">
                        <div class="dc-form-note">
                            Set the thesis and schedule first, then assign committee members in the next section.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="thesis_id">Thesis <span class="text-danger">*</span></label>
                            <select class="form-select @error('thesis_id') is-invalid @enderror" id="thesis_id" name="thesis_id" required>
                                <option value="">Select thesis...</option>
                                @foreach($theses as $thesis)
                                    <option value="{{ $thesis->id }}" {{ old('thesis_id') == $thesis->id ? 'selected' : '' }}>
                                        {{ $thesis->title }} - {{ $thesis->student->user->name ?? 'Student' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('thesis_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="scheduled_at">Scheduled Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Room 201, Main Hall">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <h3>Guidelines</h3>
                    </div>
                    <div class="ta-panel-body">
                        <span class="dc-role-pill mb-2"><i class="feather-check"></i> Minimum one committee member required</span>
                        <p class="small text-muted mb-2">Prefer assigning at least one chair and one secretary for formal defense sessions.</p>
                        <p class="small text-muted mb-0">Use completed status only when the defense has been fully conducted.</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="ta-panel">
                    <div class="ta-panel-head">
                        <div>
                            <h3>Committee Members</h3>
                            <span class="text-muted small">Assign examiners and define role distribution.</span>
                        </div>
                        <button type="button" class="ta-chip-link" id="addCommitteeRow">
                            <i class="feather-plus"></i>
                            Add Member
                        </button>
                    </div>
                    <div class="ta-panel-body">
                        <div class="ta-table-shell">
                            <table class="table table-hover align-middle mb-0 dc-table" id="committeeTable">
                                <thead>
                                    <tr>
                                        <th style="width: 56%">Examiner <span class="text-danger">*</span></th>
                                        <th style="width: 30%">Role <span class="text-danger">*</span></th>
                                        <th class="text-end" style="width: 14%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($committeeRows as $index => $member)
                                        <tr>
                                            <td>
                                                <select name="committee[{{ $index }}][user_id]" class="form-select @error('committee.'.$index.'.user_id') is-invalid @enderror" required>
                                                    <option value="">Select examiner...</option>
                                                    @foreach($examiners as $examiner)
                                                        <option value="{{ $examiner->id }}" {{ (string) ($member['user_id'] ?? '') === (string) $examiner->id ? 'selected' : '' }}>
                                                            {{ $examiner->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('committee.'.$index.'.user_id')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select name="committee[{{ $index }}][role]" class="form-select @error('committee.'.$index.'.role') is-invalid @enderror" required>
                                                    <option value="examiner" {{ ($member['role'] ?? 'examiner') === 'examiner' ? 'selected' : '' }}>Examiner</option>
                                                    <option value="chair" {{ ($member['role'] ?? '') === 'chair' ? 'selected' : '' }}>Chair</option>
                                                    <option value="secretary" {{ ($member['role'] ?? '') === 'secretary' ? 'selected' : '' }}>Secretary</option>
                                                </select>
                                                @error('committee.'.$index.'.role')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-committee" {{ count($committeeRows) === 1 ? 'disabled' : '' }}>
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="dc-action-row">
                            <a href="{{ route('admin.defenses.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-check-circle me-1"></i>
                                Create Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.getElementById('addCommitteeRow');
            const tableBody = document.querySelector('#committeeTable tbody');

            const updateIndexes = () => {
                const rows = [...tableBody.querySelectorAll('tr')];

                if (rows.length === 0) {
                    tableBody.appendChild(createRow());
                }

                [...tableBody.querySelectorAll('tr')].forEach((row, index) => {
                    const examinerSelect = row.querySelector('select[name*="user_id"]');
                    const roleSelect = row.querySelector('select[name*="role"]');

                    examinerSelect.name = `committee[${index}][user_id]`;
                    roleSelect.name = `committee[${index}][role]`;

                    const removeButton = row.querySelector('.remove-committee');
                    if (removeButton) {
                        removeButton.disabled = tableBody.querySelectorAll('tr').length === 1;
                    }
                });
            };

            const createRow = () => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <select name="committee[0][user_id]" class="form-select" required>
                            <option value="">Select examiner...</option>
                            @foreach($examiners as $examiner)
                                <option value="{{ $examiner->id }}">{{ $examiner->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="committee[0][role]" class="form-select" required>
                            <option value="examiner">Examiner</option>
                            <option value="chair">Chair</option>
                            <option value="secretary">Secretary</option>
                        </select>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-committee">
                            <i class="feather-trash-2"></i>
                        </button>
                    </td>
                `;

                return row;
            };

            addButton.addEventListener('click', () => {
                tableBody.appendChild(createRow());
                updateIndexes();
            });

            tableBody.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-committee');
                if (!button) {
                    return;
                }

                const row = button.closest('tr');
                row.remove();
                updateIndexes();
            });

            updateIndexes();
        });
    </script>
        </div>
</x-app-layout>
