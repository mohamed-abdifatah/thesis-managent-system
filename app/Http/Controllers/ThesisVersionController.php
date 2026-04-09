<?php

namespace App\Http\Controllers;

use App\Models\ThesisVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThesisVersionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student || !$student->thesis) {
            return redirect()->route('proposals.create')
                ->with('error', 'You need an approved thesis to upload versions.');
        }

        $thesis = $student->thesis->load('versions');
        $versions = $thesis->versions()->orderByDesc('version_number')->get();

        return view('thesis_versions.index', compact('thesis', 'versions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'comments' => 'nullable|string',
        ]);

        $student = auth()->user()->student;

        if (!$student || !$student->thesis) {
            return redirect()->route('proposals.create')
                ->with('error', 'You need an approved thesis to upload versions.');
        }

        $thesis = $student->thesis;

        if ($thesis->status === 'rejected') {
            return back()->with('error', 'Rejected theses cannot accept new versions.');
        }

        $nextVersion = (int) $thesis->versions()->max('version_number') + 1;
        $path = $request->file('file')->store('thesis_versions', 'public');

        ThesisVersion::create([
            'thesis_id' => $thesis->id,
            'version_number' => $nextVersion,
            'file_path' => $path,
            'comments' => $request->comments,
        ]);

        if ($thesis->status === 'proposal_approved') {
            $thesis->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Thesis version uploaded successfully.');
    }
}
