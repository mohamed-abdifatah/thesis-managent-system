<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use App\Notifications\ThesisFeedbackAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FeedbackController extends Controller
{
    public function storeThesis(Request $request, Thesis $thesis)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
            'topic' => 'nullable|string|max:120',
            'thesis_version_id' => 'nullable|exists:thesis_versions,id',
        ]);

        if ($request->filled('thesis_version_id')) {
            $hasVersion = $thesis->versions()->whereKey($request->thesis_version_id)->exists();
            if (! $hasVersion) {
                abort(403);
            }
        }

        if (!$this->canAccess($thesis)) {
            abort(403);
        }

        $feedback = Feedback::create([
            'thesis_id' => $thesis->id,
            'thesis_version_id' => $request->thesis_version_id,
            'user_id' => auth()->id(),
            'topic' => $request->topic,
            'comment' => $request->comment,
        ]);

        $this->notifyFeedback($thesis, $feedback);

        return back()->with('success', 'Message sent.');
    }

    public function storeVersion(Request $request, ThesisVersion $version)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
            'topic' => 'nullable|string|max:120',
        ]);

        $thesis = $version->thesis()->with('defense.committeeMembers')->first();

        if (!$this->canAccess($thesis)) {
            abort(403);
        }

        $feedback = Feedback::create([
            'thesis_id' => $thesis->id,
            'thesis_version_id' => $version->id,
            'user_id' => auth()->id(),
            'topic' => $request->topic,
            'comment' => $request->comment,
        ]);

        $this->notifyFeedback($thesis, $feedback, $version);

        return back()->with('success', 'Feedback added.');
    }

    private function canAccess(Thesis $thesis): bool
    {
        $user = auth()->user();

        if ($user->hasRole('student')) {
            $student = $user->student;
            if (!$student) {
                return false;
            }

            if ($thesis->student_id === $student->id) {
                return true;
            }

            if (!$student->student_group_id) {
                return false;
            }

            return $thesis->student()
                ->where('student_group_id', $student->student_group_id)
                ->exists();
        }

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

    private function notifyFeedback(Thesis $thesis, Feedback $feedback, ?ThesisVersion $version = null): void
    {
        $thesis->loadMissing(['student.user', 'student.group.students.user', 'supervisor.user', 'defense.committeeMembers.user']);
        $author = auth()->user();
        $authorId = $feedback->user_id;

        $recipients = collect();

        if ($author->hasRole('student')) {
            if ($thesis->supervisor?->user) {
                $recipients->push($thesis->supervisor->user);
            }

            $committeeUsers = $thesis->defense?->committeeMembers->pluck('user');
            if ($committeeUsers) {
                $recipients = $recipients->merge($committeeUsers);
            }

            $recipients = $recipients->merge($this->thesisStudentUsers($thesis));
        } else {
            $recipients = $recipients->merge($this->thesisStudentUsers($thesis));
        }

        $recipients
            ->filter()
            ->reject(fn ($user) => $user->id === $authorId)
            ->unique('id')
            ->each(fn ($user) => $user->notify(new ThesisFeedbackAdded($thesis, $feedback, $version)));
    }

    private function thesisStudentUsers(Thesis $thesis): Collection
    {
        $primaryStudent = $thesis->student;
        if (!$primaryStudent) {
            return collect();
        }

        if ($primaryStudent->student_group_id) {
            return $primaryStudent->group?->students()
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter() ?? collect();
        }

        return collect([$primaryStudent->user])->filter();
    }
}
