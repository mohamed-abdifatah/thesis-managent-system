<?php

namespace App\Http\Controllers;

use App\Models\DefenseSession;
use App\Models\CommitteeMember;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefenseSessionController extends Controller
{
    public function index()
    {
        $sessions = DefenseSession::with(['thesis.student.user', 'committeeMembers.user'])
            ->latest('scheduled_at')
            ->paginate(10);

        return view('admin.defenses.index', compact('sessions'));
    }

    public function create()
    {
        $theses = Thesis::with('student.user')
            ->orderByDesc('created_at')
            ->get();

        $examiners = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', 'examiner');
            })
            ->orderBy('name')
            ->get();

        return view('admin.defenses.create', compact('theses', 'examiners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'committee' => 'required|array|min:1',
            'committee.*.user_id' => 'required|exists:users,id',
            'committee.*.role' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($request) {
            $session = DefenseSession::create([
                'thesis_id' => $request->thesis_id,
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'status' => $request->status,
            ]);

            foreach ($request->committee as $member) {
                CommitteeMember::create([
                    'defense_session_id' => $session->id,
                    'user_id' => $member['user_id'],
                    'role' => $member['role'],
                ]);
            }
        });

        return redirect()->route('admin.defenses.index')->with('success', 'Defense session created.');
    }

    public function edit(DefenseSession $defense)
    {
        $defense->load(['committeeMembers.user', 'thesis.student.user']);

        $theses = Thesis::with('student.user')
            ->orderByDesc('created_at')
            ->get();

        $examiners = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', 'examiner');
            })
            ->orderBy('name')
            ->get();

        return view('admin.defenses.edit', compact('defense', 'theses', 'examiners'));
    }

    public function update(Request $request, DefenseSession $defense)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'committee' => 'required|array|min:1',
            'committee.*.user_id' => 'required|exists:users,id',
            'committee.*.role' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($request, $defense) {
            $defense->update([
                'thesis_id' => $request->thesis_id,
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'status' => $request->status,
            ]);

            $defense->committeeMembers()->delete();

            foreach ($request->committee as $member) {
                CommitteeMember::create([
                    'defense_session_id' => $defense->id,
                    'user_id' => $member['user_id'],
                    'role' => $member['role'],
                ]);
            }
        });

        return redirect()->route('admin.defenses.index')->with('success', 'Defense session updated.');
    }
}
