<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ensure student/supervisor relations exist before queryiing
        if ($user->hasRole(['admin', 'coordinator'])) {
            $proposals = Proposal::with(['thesis.student.user'])->latest()->get();
        } elseif ($user->hasRole('supervisor') && $user->supervisor) {
             $proposals = Proposal::whereHas('thesis', function($q) use ($user) {
                 $q->where('supervisor_id', $user->supervisor->id);
             })->with(['thesis.student.user'])->latest()->get();
        } elseif ($user->hasRole('student') && $user->student) {
             $proposals = Proposal::whereHas('thesis', function($q) use ($user) {
                 $q->where('student_id', $user->student->id);
             })->latest()->get();
        } else {
            $proposals = collect();
        }

        return view('proposals.index', compact('proposals'));
    }

    public function create()
    {
        // Check if student already has a thesis
        $student = auth()->user()->student;
        if ($student->thesis) {
             return redirect()->route('dashboard')->with('error', 'You already have a thesis proposal.');
        }
        
        return view('proposals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'objectives' => 'required|string',
            'methodology' => 'required|string',
            'literature_review' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
        ]);

        $student = auth()->user()->student;
        
        // Transaction to ensure data integrity
        \DB::transaction(function () use ($request, $student) {
            
            // 1. Create Thesis record (Container)
            $thesis = Thesis::create([
                'student_id' => $student->id,
                'title' => $request->title,
                'status' => 'proposal_pending',
            ]);

            // 2. Handle File Upload
            $path = $request->file('file')->store('proposals', 'public');

            // 3. Create Proposal record
            Proposal::create([
                'thesis_id' => $thesis->id,
                'title' => $request->title,
                'abstract' => $request->abstract,
                'objectives' => $request->objectives,
                'methodology' => $request->methodology,
                'literature_review' => $request->literature_review,
                'file_path' => $path,
                'status' => 'pending'
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Proposal submitted successfully.');
    }
    
    public function show(Proposal $proposal)
    {
        return view('proposals.show', compact('proposal'));
    }
}
