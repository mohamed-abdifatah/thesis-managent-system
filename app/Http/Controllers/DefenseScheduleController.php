<?php

namespace App\Http\Controllers;

use App\Models\DefenseSession;

class DefenseScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('student') && $user->student && $user->student->thesis) {
            $sessions = DefenseSession::with(['thesis.student.user', 'committeeMembers.user'])
                ->where('thesis_id', $user->student->thesis->id)
                ->orderByDesc('scheduled_at')
                ->get();
        } elseif ($user->hasRole(['supervisor', 'cosupervisor']) && $user->supervisor) {
            $sessions = DefenseSession::with(['thesis.student.user', 'committeeMembers.user'])
                ->whereHas('thesis', function ($query) use ($user) {
                    $query->where('supervisor_id', $user->supervisor->id);
                })
                ->orderByDesc('scheduled_at')
                ->get();
        } else {
            $sessions = collect();
        }

        return view('defenses.schedule', compact('sessions'));
    }
}
