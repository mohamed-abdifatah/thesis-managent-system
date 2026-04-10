<?php

namespace App\Http\Controllers;

use App\Models\Thesis;

class ExaminerThesisController extends Controller
{
    public function show(Thesis $thesis)
    {
        $userId = auth()->id();

        $thesis->load([
            'student.user',
            'supervisor.user',
            'versions.feedbacks.user',
            'versions.reviewer',
            'feedbacks.user',
            'feedbacks.thesisVersion',
            'defense.committeeMembers',
        ]);

        if (!$thesis->defense?->committeeMembers()->where('user_id', $userId)->exists()) {
            abort(403);
        }

        return view('examiner.theses.show', compact('thesis'));
    }
}
