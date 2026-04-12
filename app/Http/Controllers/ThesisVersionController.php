<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Thesis;
use App\Models\ThesisUnit;
use App\Models\ThesisVersion;
use App\Notifications\ThesisVersionStatusUpdated;
use App\Notifications\ThesisVersionUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThesisVersionController extends Controller
{
    public function unitsList()
    {
        $student = auth()->user()->student;
        $thesis = $student?->accessibleThesis();

        if (!$student || !$thesis) {
            return response()->json(['message' => 'Thesis not found.'], 404);
        }

        $units = $thesis->units()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['data' => $units]);
    }

    public function unitStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
        ]);

        $student = auth()->user()->student;
        $thesis = $student?->accessibleThesis();

        if (!$student || !$thesis) {
            return response()->json(['message' => 'Thesis not found.'], 404);
        }

        $name = trim((string) $request->name);
        $unit = ThesisUnit::firstOrCreate(
            [
                'thesis_id' => $thesis->id,
                'name' => $name,
            ],
            [
                'created_by' => auth()->id(),
            ]
        );

        return response()->json([
            'data' => [
                'id' => $unit->id,
                'name' => $unit->name,
            ],
        ]);
    }

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
            'versions.unit',
            'units',
            'feedbacks.user',
            'feedbacks.thesisVersion',
            'student.group.students.user',
        ]);
        $versions = $thesis->versions()->orderByDesc('version_number')->get();
        $units = $thesis->units()->orderBy('name')->get();

        return view('thesis_versions.index', compact('thesis', 'versions', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'comments' => 'nullable|string',
            'thesis_unit_id' => 'nullable|exists:thesis_units,id',
            'new_unit_name' => 'nullable|string|max:120',
            'unit_number' => 'required|integer|min:1|max:999',
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

        if (!$request->filled('thesis_unit_id') && !$request->filled('new_unit_name')) {
            return back()
                ->withErrors(['thesis_unit_id' => 'Select an existing unit or create a new one.'])
                ->withInput();
        }

        $unitId = $this->resolveUnitId($request, $thesis);

        $nextVersion = (int) $thesis->versions()->max('version_number') + 1;
        $path = $request->file('file')->store('thesis_versions', 'public');

        $version = ThesisVersion::create([
            'thesis_id' => $thesis->id,
            'thesis_unit_id' => $unitId,
            'unit_number' => (int) $request->unit_number,
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

    public function updateStudent(Request $request, ThesisVersion $version)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'comments' => 'nullable|string',
            'thesis_unit_id' => 'nullable|exists:thesis_units,id',
            'new_unit_name' => 'nullable|string|max:120',
            'unit_number' => 'required|integer|min:1|max:999',
        ]);

        $student = auth()->user()->student;
        $thesis = $student?->accessibleThesis();

        if (!$student || !$thesis || $version->thesis_id !== $thesis->id) {
            abort(403);
        }

        if (!$request->filled('thesis_unit_id') && !$request->filled('new_unit_name')) {
            return back()
                ->withErrors(['thesis_unit_id' => 'Select an existing unit or create a new one.'])
                ->withInput();
        }

        $unitId = $this->resolveUnitId($request, $thesis);

        $payload = [
            'thesis_unit_id' => $unitId,
            'unit_number' => (int) $request->unit_number,
            'comments' => $request->comments,
        ];

        if ($request->hasFile('file')) {
            if ($version->file_path && Storage::disk('public')->exists($version->file_path)) {
                Storage::disk('public')->delete($version->file_path);
            }

            $payload['file_path'] = $request->file('file')->store('thesis_versions', 'public');
        }

        $version->update($payload);

        return back()->with('success', 'Version updated successfully.');
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

    private function resolveUnitId(Request $request, Thesis $thesis): ?int
    {
        if ($request->filled('new_unit_name')) {
            $unit = ThesisUnit::firstOrCreate(
                [
                    'thesis_id' => $thesis->id,
                    'name' => trim((string) $request->new_unit_name),
                ],
                [
                    'created_by' => auth()->id(),
                ]
            );

            return $unit->id;
        }

        if (!$request->filled('thesis_unit_id')) {
            return null;
        }

        $unit = ThesisUnit::where('id', $request->thesis_unit_id)
            ->where('thesis_id', $thesis->id)
            ->first();

        if (!$unit) {
            abort(403);
        }

        return $unit->id;
    }
}
