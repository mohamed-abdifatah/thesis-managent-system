<?php

namespace App\Http\Controllers;

use App\Models\ThesisCatalogEvent;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibrarianCatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $workflow = trim((string) $request->query('workflow', 'all'));

        $baseCatalogQuery = Thesis::query()
            ->whereIn('status', ['defended', 'completed']);

        $catalogQuery = (clone $baseCatalogQuery);

        if ($search !== '') {
            $catalogQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('student.user', fn ($studentQuery) => $studentQuery->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('group', fn ($groupQuery) => $groupQuery->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($workflow === 'ready') {
            $catalogQuery
                ->where('status', 'defended')
                ->where('is_library_approved', false)
                ->whereHas('defense', fn ($query) => $query->where('status', 'completed'))
                ->whereHas('approvedVersions');
        } elseif ($workflow === 'validated') {
            $catalogQuery
                ->where('is_library_approved', true)
                ->where('is_public', false);
        } elseif ($workflow === 'published') {
            $catalogQuery->where('is_public', true);
        }

        $readyForValidationCount = (clone $baseCatalogQuery)
            ->where('status', 'defended')
            ->where('is_library_approved', false)
            ->whereHas('defense', fn ($query) => $query->where('status', 'completed'))
            ->whereHas('approvedVersions')
            ->count();

        $validatedCount = (clone $baseCatalogQuery)
            ->where('is_library_approved', true)
            ->count();

        $publishedCount = (clone $baseCatalogQuery)
            ->where('is_public', true)
            ->count();

        $pendingIssuesCount = (clone $baseCatalogQuery)
            ->where('status', 'defended')
            ->where(function ($query) {
                $query->doesntHave('defense')
                    ->orWhereHas('defense', fn ($defenseQuery) => $defenseQuery->where('status', '!=', 'completed'))
                    ->orDoesntHave('approvedVersions');
            })
            ->count();

        $theses = $catalogQuery
            ->with([
                'student.user',
                'group.students.user',
                'supervisor.user',
                'defense',
                'latestApprovedVersion',
                'finalThesisVersion',
                'approvedVersions',
            ])
            ->withCount('approvedVersions')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $recentEvents = ThesisCatalogEvent::query()
            ->with(['thesis', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('library.catalog.index', compact(
            'readyForValidationCount',
            'validatedCount',
            'publishedCount',
            'pendingIssuesCount',
            'theses',
            'recentEvents',
            'search',
            'workflow'
        ));
    }

    public function validateCatalogEntry(Request $request, Thesis $thesis)
    {
        $payload = $request->validate([
            'catalog_notes' => 'nullable|string|max:2000',
        ]);

        if (!$this->isReadyForCatalogValidation($thesis)) {
            return back()->with('error', 'This thesis is not ready for library validation yet.');
        }

        DB::transaction(function () use ($thesis, $payload) {
            $thesis->update([
                'status' => 'completed',
                'is_library_approved' => true,
                'library_approved_by' => auth()->id(),
                'library_approved_at' => now(),
                'catalog_notes' => $payload['catalog_notes'] ?? null,
            ]);

            $this->recordCatalogEvent(
                $thesis,
                'validated',
                $payload['catalog_notes'] ?? null,
                [
                    'status' => $thesis->status,
                    'is_library_approved' => $thesis->is_library_approved,
                    'is_public' => $thesis->is_public,
                ]
            );
        });

        return back()->with('success', 'Thesis validated and moved to completed library status.');
    }

    public function publishCatalogEntry(Request $request, Thesis $thesis)
    {
        $payload = $request->validate([
            'publish_notes' => 'nullable|string|max:2000',
            'final_version_id' => 'nullable|integer|exists:thesis_versions,id',
        ]);

        if (!$thesis->is_library_approved || $thesis->status !== 'completed') {
            return back()->with('error', 'Only validated completed theses can be published.');
        }

        if (!$thesis->approvedVersions()->exists()) {
            return back()->with('error', 'An approved thesis version is required before publishing.');
        }

        $finalVersion = $this->resolveFinalThesisVersion($thesis, $payload['final_version_id'] ?? null);

        if (!$finalVersion) {
            return back()->with('error', 'Select an approved version to publish as the final thesis.');
        }

        DB::transaction(function () use ($thesis, $payload, $finalVersion) {
            $thesis->versions()->where('is_final_thesis', true)->update([
                'is_final_thesis' => false,
                'finalized_at' => null,
            ]);

            $finalVersion->update([
                'is_final_thesis' => true,
                'finalized_at' => now(),
            ]);

            $thesis->update([
                'is_public' => true,
                'published_by' => auth()->id(),
                'published_at' => now(),
            ]);

            $this->recordCatalogEvent(
                $thesis,
                'published',
                $payload['publish_notes'] ?? null,
                [
                    'status' => $thesis->status,
                    'is_library_approved' => $thesis->is_library_approved,
                    'is_public' => $thesis->is_public,
                    'final_version_id' => $finalVersion->id,
                    'final_version_number' => $finalVersion->version_number,
                ]
            );
        });

        return back()->with('success', 'Thesis published to the public catalog.');
    }

    public function unpublishCatalogEntry(Request $request, Thesis $thesis)
    {
        $payload = $request->validate([
            'unpublish_reason' => 'required|string|min:8|max:2000',
        ]);

        DB::transaction(function () use ($thesis, $payload) {
            $thesis->update([
                'is_public' => false,
                'published_by' => null,
                'published_at' => null,
            ]);

            $this->recordCatalogEvent(
                $thesis,
                'unpublished',
                $payload['unpublish_reason'],
                [
                    'status' => $thesis->status,
                    'is_library_approved' => $thesis->is_library_approved,
                    'is_public' => $thesis->is_public,
                ]
            );
        });

        return back()->with('success', 'Thesis removed from the public catalog.');
    }

    private function isReadyForCatalogValidation(Thesis $thesis): bool
    {
        if (!in_array($thesis->status, ['defended', 'completed'], true)) {
            return false;
        }

        $hasCompletedDefense = $thesis->defense()
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedDefense) {
            return false;
        }

        return $thesis->approvedVersions()->exists();
    }

    private function resolveFinalThesisVersion(Thesis $thesis, ?int $finalVersionId): ?ThesisVersion
    {
        if ($finalVersionId) {
            return $thesis->versions()
                ->whereKey($finalVersionId)
                ->where('status', 'approved')
                ->first();
        }

        return $thesis->approvedVersions()
            ->orderByDesc('version_number')
            ->first();
    }

    private function recordCatalogEvent(Thesis $thesis, string $action, ?string $notes = null, ?array $metadata = null): void
    {
        ThesisCatalogEvent::create([
            'thesis_id' => $thesis->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'notes' => $notes,
            'metadata' => array_merge($metadata ?? [], [
                'ip' => request()->ip(),
                'user_agent' => (string) request()->userAgent(),
            ]),
        ]);
    }
}
