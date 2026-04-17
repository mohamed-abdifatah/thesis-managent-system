<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Thesis;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    private function supervisorProfileOrFail()
    {
        $supervisor = auth()->user()?->supervisor;

        if (!$supervisor) {
            abort(403, 'Supervisor profile is not assigned to this account.');
        }

        return $supervisor;
    }

    public function myStudents()
    {
        $supervisor = $this->supervisorProfileOrFail();

        $groups = $supervisor->groups()
            ->with(['department', 'students.user'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $groupTheses = collect();
        if ($groups->isNotEmpty()) {
            $groupTheses = $supervisor->theses()
                ->with('student.user')
                ->whereIn('student_group_id', $groups->pluck('id'))
                ->latest('id')
                ->get()
                ->groupBy('student_group_id')
                ->map(function ($thesisCollection) {
                    return $thesisCollection->first();
                });
        }

        $ungroupedStudents = $supervisor->students()
            ->whereNull('student_group_id')
            ->with(['user', 'thesis'])
            ->get();

        return view('supervisor.students.index', compact('groups', 'ungroupedStudents', 'groupTheses'));
    }

    public function showThesis(Thesis $thesis)
    {
        $supervisor = $this->supervisorProfileOrFail();

        // Security check: ensure this thesis belongs to the supervisor
        if ((int) $thesis->supervisor_id !== (int) $supervisor->id) {
            abort(403);
        }

        $thesis->load([
            'student.user',
            'proposals',
            'versions.feedbacks.user',
            'versions.reviewer',
            'versions.unit',
            'feedbacks.user',
            'feedbacks.thesisVersion',
            'feedbacks.thesisVersion.unit',
            'student.user.department',
        ]);
        $latestProposal = $thesis->proposals()->latest()->first();

        return view('supervisor.theses.show', compact('thesis', 'latestProposal'));
    }

    public function reviewProposal(Request $request, Proposal $proposal)
    {
        $supervisor = $this->supervisorProfileOrFail();

        $request->validate([
            'status' => 'required|in:approved,rejected,revision_required',
            'comments' => 'required|string',
        ]);

        // Security check
        if ((int) $proposal->thesis->supervisor_id !== (int) $supervisor->id) {
            abort(403);
        }

        $proposal->update([
            'status' => $request->status,
            'supervisor_comments' => $request->comments,
        ]);

        // Update parent thesis status
        $thesisStatus = match($request->status) {
            'approved' => 'proposal_approved',
            'rejected' => 'rejected',
            'revision_required' => 'proposal_pending', // Kept pending if revision is needed
            default => 'proposal_pending'
        };

        if ($request->status === 'approved') {
             $thesisStatus = 'in_progress'; // Move directly to in progress if proposal approved? Or proposal_approved.
             // Let's stick to status flow: pending -> approved -> in_progress
             $thesisStatus = 'proposal_approved';
        }

        $proposal->thesis->update(['status' => $thesisStatus]);

        return back()->with('success', 'Proposal review submitted.');
    }

    public function setFinalVersion(Request $request, Thesis $thesis)
    {
        $supervisor = $this->supervisorProfileOrFail();

        if ((int) $thesis->supervisor_id !== (int) $supervisor->id) {
            abort(403);
        }

        $payload = $request->validate([
            'final_version_id' => 'required|integer|exists:thesis_versions,id',
        ]);

        $existingFinalVersion = $thesis->versions()
            ->where('is_final_thesis', true)
            ->latest('finalized_at')
            ->first();

        if ($existingFinalVersion) {
            if ((int) $existingFinalVersion->id === (int) $payload['final_version_id']) {
                return back()->with('success', 'Final thesis is already selected (' . $existingFinalVersion->unit_label . ').');
            }
        }

        $version = $thesis->versions()
            ->whereKey($payload['final_version_id'])
            ->where('status', 'approved')
            ->first();

        if (!$version) {
            return back()->with('error', 'Only approved units can be set as final thesis.');
        }

        $hasExistingFinal = (bool) $existingFinalVersion;
        $isChangingFinal = $hasExistingFinal && ((int) $existingFinalVersion->id !== (int) $version->id);
        $mustResetCatalogWorkflow = $isChangingFinal && ($thesis->is_library_approved || $thesis->is_public);

        DB::transaction(function () use ($thesis, $version, $mustResetCatalogWorkflow) {
            $thesis->versions()
                ->where('is_final_thesis', true)
                ->whereKeyNot($version->id)
                ->update([
                    'is_final_thesis' => false,
                    'finalized_at' => null,
                ]);

            $version->update([
                'is_final_thesis' => true,
                'finalized_at' => now(),
            ]);

            if ($mustResetCatalogWorkflow) {
                $thesis->update([
                    'status' => 'defended',
                    'is_library_approved' => false,
                    'library_approved_by' => null,
                    'library_approved_at' => null,
                    'is_public' => false,
                    'published_by' => null,
                    'published_at' => null,
                    'catalog_notes' => null,
                ]);
            }
        });

        if ($mustResetCatalogWorkflow) {
            return back()->with('success', 'Final thesis updated to ' . $version->unit_label . '. Library validation and publication were reset. Please validate and publish again from library.');
        }

        if ($isChangingFinal) {
            return back()->with('success', 'Final thesis updated to ' . $version->unit_label . '.');
        }

        return back()->with('success', 'Final thesis selected as ' . $version->unit_label . '. Librarian can validate and publish from library.');
    }
}
