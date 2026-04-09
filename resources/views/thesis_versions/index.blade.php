<x-app-layout>
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="page-header-title h3 mb-0">Thesis Versions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Versions</li>
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
                    <h5 class="card-title fw-bold mb-0">Upload New Version</h5>
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
                        <div class="mb-3">
                            <label for="comments" class="form-label fw-semibold">Notes (Optional)</label>
                            <textarea id="comments" name="comments" rows="3" class="form-control" placeholder="Add a short summary of changes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="feather-upload-cloud me-2"></i> Upload Version
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0">Version History</h5>
                    <span class="badge bg-soft-primary text-primary">{{ $versions->count() }} Versions</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Version</th>
                                    <th>Uploaded</th>
                                    <th>Notes</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($versions as $version)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">v{{ $version->version_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $version->created_at->format('M d, Y') }}</span>
                                                <span class="fs-12 text-muted">{{ $version->created_at->diffForHumans() }}</span>
                                            </div>
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
                                            <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-light-brand" title="Download">
                                                <i class="feather-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="feather-inbox fs-1 text-muted opacity-50 mb-3"></i>
                                            <p class="text-muted mb-0">No thesis versions uploaded yet.</p>
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
</x-app-layout>
