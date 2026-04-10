<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Thesis;
use App\Models\Proposal;

class SupervisorController extends Controller
{
    public function myStudents()
    {
        $supervisor = auth()->user()->supervisor;
        // Check if user is actually a supervisor
        if (!$supervisor) {
            abort(403, 'User is not a supervisor profile');
        }

        $theses = $supervisor->theses()->with(['student.user', 'proposals' => function($q){
            $q->latest();
        }])->get();

        $studentsWithoutThesis = $supervisor->students()
            ->with(['user', 'group.students.thesis'])
            ->get()
            ->filter(function ($student) {
                return !$student->accessibleThesis();
            })
            ->values();

        return view('supervisor.students.index', compact('theses', 'studentsWithoutThesis'));
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
}
