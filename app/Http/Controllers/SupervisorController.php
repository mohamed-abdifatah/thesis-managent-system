<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Thesis;
use App\Models\ThesisCatalogEvent;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function myStudents()
    {
        $supervisor = auth()->user()->supervisor;
        // Check if user is actually a supervisor
        if (!$supervisor) {
            abort(403, 'User is not a supervisor profile');
        }

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
        // Security check: ensure this thesis belongs to the supervisor
        if ($thesis->supervisor_id !== auth()->user()->supervisor->id) {
            abort(403);
        }

        $thesis->load([
            'student.user',
            'proposals',
            'versions.feedbacks.user',
            'versions.reviewer',
            'feedbacks.user',
            'feedbacks.thesisVersion',
            'student.user.department',
        ]);
        $latestProposal = $thesis->proposals()->latest()->first();

        return view('supervisor.theses.show', compact('thesis', 'latestProposal'));
    }

    public function reviewProposal(Request $request, Proposal $proposal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,revision_required',
            'comments' => 'required|string',
        ]);

        // Security check
        if ($proposal->thesis->supervisor_id !== auth()->user()->supervisor->id) {
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
        if ($thesis->supervisor_id !== auth()->user()->supervisor->id) {
            abort(403);
        }

        $payload = $request->validate([
            'final_version_id' => 'required|integer|exists:thesis_versions,id',
        ]);

        $version = $thesis->versions()
            ->whereKey($payload['final_version_id'])
            ->where('status', 'approved')
            ->first();

        if (!$version) {
            return back()->with('error', 'Only approved versions can be set as final thesis.');
        }

        DB::transaction(function () use ($thesis, $version) {
            $thesis->versions()->where('is_final_thesis', true)->update([
                'is_final_thesis' => false,
                'finalized_at' => null,
            ]);

            $version->update([
                'is_final_thesis' => true,
                'finalized_at' => now(),
            ]);

            $isDefenseCompleted = $thesis->defense()
                ->where('status', 'completed')
                ->exists();

            if ($isDefenseCompleted) {
                $thesis->update([
                    'status' => 'completed',
                    'is_library_approved' => true,
                    'library_approved_by' => auth()->id(),
                    'library_approved_at' => now(),
                    'is_public' => true,
                    'published_by' => auth()->id(),
                    'published_at' => now(),
                ]);

                ThesisCatalogEvent::create([
                    'thesis_id' => $thesis->id,
                    'user_id' => auth()->id(),
                    'action' => 'published',
                    'notes' => 'Final thesis version selected by supervisor after completed defense.',
                    'metadata' => [
                        'final_version_id' => $version->id,
                        'final_version_number' => $version->version_number,
                        'source' => 'supervisor.final_version',
                    ],
                ]);
            }
        });

        $isNowPublic = $thesis->fresh()->is_public;

        if ($isNowPublic) {
            return back()->with('success', 'Final thesis version set to v' . $version->version_number . ' and published to the books portal.');
        }

        return back()->with('success', 'Final thesis version set to v' . $version->version_number . '. It will become public after defense is marked completed.');
    }
}
