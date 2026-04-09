<x-app-layout>
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Examiner Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Examiner</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('examiner.defenses.index') }}" class="btn btn-primary">
                <i class="feather-check-square me-1"></i> My Evaluations
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="feather-inbox fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="text-muted">Review and submit your defense evaluations</h5>
                    <p class="text-muted mb-3">All assigned defenses will appear in your evaluation list.</p>
                    <a href="{{ route('examiner.defenses.index') }}" class="btn btn-light-brand">
                        Open Evaluations
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
