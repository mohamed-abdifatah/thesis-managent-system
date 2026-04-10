<x-app-layout>
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">My Defense Evaluations</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Defenses</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($sessions as $session)
                        <div class="border rounded p-4 mb-3">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $session->thesis->title ?? 'Thesis' }}</h5>
                                    <div class="text-muted">Student: {{ $session->thesis->student->user->name ?? 'N/A' }}</div>
                                    <div class="text-muted">Scheduled: {{ $session->scheduled_at->format('M d, Y h:i A') }}</div>
                                    <div class="text-muted">Location: {{ $session->location ?? 'TBD' }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-soft-primary text-primary text-uppercase">{{ $session->status }}</span>
                                    <a href="{{ route('examiner.theses.show', $session->thesis) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="feather-file-text me-1"></i> Thesis
                                    </a>
                                </div>
                            </div>

                            @php
                                $evaluation = $evaluations->get($session->id);
                            @endphp

                            <div class="mt-4">
                                <h6 class="fw-bold">Evaluation</h6>
                                <form method="POST" action="{{ route('examiner.defenses.evaluate', $session) }}" class="row g-3">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label">Score (0-100)</label>
                                        <input type="number" step="0.01" min="0" max="100" name="score" class="form-control" value="{{ old('score', $evaluation?->score) }}" required>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks...">{{ old('remarks', $evaluation?->remarks) }}</textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="feather-send me-1"></i> {{ $evaluation ? 'Update' : 'Submit' }} Evaluation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="feather-inbox fs-1 opacity-50 mb-3"></i>
                            <p class="mb-0">No defense sessions assigned yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
