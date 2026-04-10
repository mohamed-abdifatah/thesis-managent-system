<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use App\Notifications\ThesisVersionStatusUpdated;
use App\Notifications\ThesisVersionUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThesisVersionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $thesis = $student?->accessibleThesis();

        if (!$student || !$thesis) {
            return redirect()->route('proposals.create')
                ->with('error', 'You need an approved thesis to upload versions.');
        }

        $thesis = $thesis->load([
            'versions.feedbacks.user',
            'versions.reviewer',
            'feedbacks.user',
            'feedbacks.thesisVersion',
            'student.group.students.user',
        ]);
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
        $thesis = $student?->accessibleThesis();

        if (!$student || !$thesis) {
            return redirect()->route('proposals.create')
                ->with('error', 'You need an approved thesis to upload versions.');
        }

        if ($thesis->status === 'rejected') {
            return back()->with('error', 'Rejected theses cannot accept new versions.');
        }

        $nextVersion = (int) $thesis->versions()->max('version_number') + 1;
        $path = $request->file('file')->store('thesis_versions', 'public');

        $version = ThesisVersion::create([
            'thesis_id' => $thesis->id,
            'version_number' => $nextVersion,
            'file_path' => $path,
            'comments' => $request->comments,
            'status' => 'draft',
        ]);

        if ($request->filled('comments')) {
            Feedback::create([
                'thesis_id' => $thesis->id,
                'thesis_version_id' => $version->id,
                'user_id' => auth()->id(),
                'comment' => $request->comments,
            ]);
        }

        if ($thesis->status === 'proposal_approved') {
            $thesis->update(['status' => 'in_progress']);
        }

        $this->notifyVersionUploaded($thesis, $version);

        return back()->with('success', 'Thesis version uploaded successfully.');
    }

    public function updateStatus(Request $request, ThesisVersion $version)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', ThesisVersion::STATUSES),
        ]);

        $thesis = $version->thesis()->with('defense.committeeMembers')->first();

        if (!$this->canReview($thesis)) {
            abort(403);
        }

        $version->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $studentUser = $thesis->student?->user;
        $studentRecipients = collect();

        if ($thesis->student?->student_group_id) {
            $studentRecipients = $thesis->student->group?->students()
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter();
        } elseif ($studentUser) {
            $studentRecipients = collect([$studentUser]);
        }

        $studentRecipients
            ->reject(fn ($user) => $user->id === auth()->id())
            ->unique('id')
            ->each(fn ($user) => $user->notify(new ThesisVersionStatusUpdated($thesis, $version)));

        return back()->with('success', 'Version status updated.');
    }

    private function canReview(Thesis $thesis): bool
    {
        $user = auth()->user();

        if ($user->hasRole('supervisor')) {
            return $thesis->supervisor_id === $user->supervisor?->id;
        }

        if ($user->hasRole('examiner')) {
            return (bool) $thesis->defense?->committeeMembers()
                ->where('user_id', $user->id)
                ->exists();
        }

        return false;
    }

    private function notifyVersionUploaded(Thesis $thesis, ThesisVersion $version): void
    {
        $thesis->loadMissing(['supervisor.user', 'defense.committeeMembers.user']);

        $recipients = collect();
        if ($thesis->supervisor?->user) {
            $recipients->push($thesis->supervisor->user);
        }

        $committeeUsers = $thesis->defense?->committeeMembers->pluck('user');
        if ($committeeUsers) {
            $recipients = $recipients->merge($committeeUsers);
        }

        $recipients
            ->filter()
            ->unique('id')
            ->each(fn ($user) => $user->notify(new ThesisVersionUploaded($thesis, $version)));
    }
}
