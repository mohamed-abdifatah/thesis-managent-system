<x-app-layout>
    @include('partials.admin-account-refresh')

    <div class="adm-refresh">
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Edit Defense Session</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.defenses.index') }}">Defense Sessions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.defenses.index') }}" class="btn btn-outline-secondary">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.defenses.update', $defense) }}">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-3">Session Details</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="thesis_id">Thesis <span class="text-danger">*</span></label>
                            <select class="form-select @error('thesis_id') is-invalid @enderror" id="thesis_id" name="thesis_id" required>
                                <option value="">Select thesis...</option>
                                @foreach($theses as $thesis)
                                    <option value="{{ $thesis->id }}" {{ old('thesis_id', $defense->thesis_id) == $thesis->id ? 'selected' : '' }}>
                                        {{ $thesis->title }} — {{ $thesis->student->user->name ?? 'Student' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('thesis_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="scheduled_at">Scheduled Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', $defense->scheduled_at?->format('Y-m-d\TH:i')) }}" required>
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $defense->location) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status', $defense->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status', $defense->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $defense->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card stretch stretch-full border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Committee Members</h5>
                                <p class="text-muted small mb-0">Assign examiners and their roles.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-brand" id="addCommitteeRow">
                                <i class="feather-plus me-1"></i> Add Member
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="committeeTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60%">Examiner <span class="text-danger">*</span></th>
                                        <th style="width: 30%">Role <span class="text-danger">*</span></th>
                                        <th class="text-end" style="width: 10%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($defense->committeeMembers as $index => $member)
                                        <tr>
                                            <td>
                                                <select name="committee[{{ $index }}][user_id]" class="form-select" required>
                                                    <option value="">Select examiner...</option>
                                                    @foreach($examiners as $examiner)
                                                        <option value="{{ $examiner->id }}" {{ $examiner->id == $member->user_id ? 'selected' : '' }}>
                                                            {{ $examiner->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="committee[{{ $index }}][role]" class="form-select" required>
                                                    <option value="examiner" {{ $member->role === 'examiner' ? 'selected' : '' }}>Examiner</option>
                                                    <option value="chair" {{ $member->role === 'chair' ? 'selected' : '' }}>Chair</option>
                                                    <option value="secretary" {{ $member->role === 'secretary' ? 'selected' : '' }}>Secretary</option>
                                                </select>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-committee" {{ $defense->committeeMembers->count() === 1 ? 'disabled' : '' }}>
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-4">
                            <a href="{{ route('admin.defenses.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-1"></i> Update Session
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
                [...tableBody.querySelectorAll('tr')].forEach((row, index) => {
                    const examinerSelect = row.querySelector('select[name*="user_id"]');
                    const roleSelect = row.querySelector('select[name*="role"]');
                    examinerSelect.name = `committee[${index}][user_id]`;
                    roleSelect.name = `committee[${index}][role]`;

                    const removeButton = row.querySelector('.remove-committee');
                    removeButton.disabled = tableBody.querySelectorAll('tr').length === 1;
                });
            };

            addButton.addEventListener('click', () => {
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
                tableBody.appendChild(row);
                updateIndexes();
            });

            tableBody.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-committee');
                if (!button) {
                    return;
                }
                button.closest('tr').remove();
                updateIndexes();
            });

            updateIndexes();
        });
    </script>
    </div>
</x-app-layout>
