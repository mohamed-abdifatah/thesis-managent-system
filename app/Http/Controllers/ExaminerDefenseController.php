<?php

namespace App\Http\Controllers;

use App\Models\DefenseSession;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class ExaminerDefenseController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $sessions = DefenseSession::with(['thesis.student.user', 'committeeMembers'])
            ->whereHas('committeeMembers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderByDesc('scheduled_at')
            ->get();

        $evaluations = Evaluation::where('user_id', $userId)->get()->keyBy('defense_session_id');

        return view('examiner.defenses.index', compact('sessions', 'evaluations'));
    }

    public function storeEvaluation(Request $request, DefenseSession $defense)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $userId = auth()->id();

        if (!$defense->committeeMembers()->where('user_id', $userId)->exists()) {
            abort(403);
        }

        Evaluation::updateOrCreate(
            ['defense_session_id' => $defense->id, 'user_id' => $userId],
            ['score' => $request->score, 'remarks' => $request->remarks]
        );

        return back()->with('success', 'Evaluation submitted.');
    }
}
