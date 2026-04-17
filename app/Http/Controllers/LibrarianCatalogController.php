<?php

namespace App\Http\Controllers;

use App\Models\ThesisCatalogEvent;
use App\Models\Thesis;
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
                'finalThesisVersion.unit',
                'approvedVersions',
                'approvedVersions.unit',
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
        ]);

        if ($thesis->is_public) {
            return back()->with('error', 'This thesis is already published and locked.');
        }

        if (!$thesis->is_library_approved || $thesis->status !== 'completed') {
            return back()->with('error', 'Only validated completed theses can be published.');
        }

        $finalVersion = $thesis->finalThesisVersion()
            ->where('status', 'approved')
            ->first();

        if (!$finalVersion) {
            return back()->with('error', 'Supervisor must assign an approved "Final Thesis Selected" version before publishing.');
        }

        DB::transaction(function () use ($thesis, $payload, $finalVersion) {
            if (!$finalVersion->finalized_at) {
                $finalVersion->update([
                    'finalized_at' => now(),
                ]);
            }

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
                    'final_unit_number' => $finalVersion->unit_sequence,
                    'final_unit_label' => $finalVersion->unit_label,
                    'source' => 'librarian.publish.locked_final',
                ]
            );
        });

        return back()->with('success', 'Thesis published to the public catalog with supervisor-selected final thesis ' . $finalVersion->unit_label . '.');
    }

    public function unpublishCatalogEntry(Request $request, Thesis $thesis)
    {
        $payload = $request->validate([
            'unpublish_reason' => 'required|string|min:8|max:2000',
        ]);

        if (!$thesis->is_public) {
            return back()->with('error', 'This thesis is already private.');
        }

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
                    'final_unit_label' => $thesis->finalThesisVersion?->unit_label,
                    'source' => 'librarian.manual_unpublish',
                ]
            );
        });

        return back()->with('success', 'Thesis unpublished from the public catalog. You can publish again from library when ready.');
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
