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
        $perPage = (int) request('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $search = trim((string) request('q', ''));
        $statusFilter = trim((string) request('status', ''));

        $statusOptions = [
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        if ($statusFilter !== '' && !array_key_exists($statusFilter, $statusOptions)) {
            $statusFilter = '';
        }

        $query = DefenseSession::with(['thesis.student.user', 'committeeMembers.user']);

        if ($search !== '') {
            $query->where(function ($sessionQuery) use ($search) {
                $sessionQuery
                    ->where('location', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('thesis', function ($thesisQuery) use ($search) {
                        $thesisQuery
                            ->where('title', 'like', "%{$search}%")
                            ->orWhereHas('student.user', function ($studentQuery) use ($search) {
                                $studentQuery
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        $filteredCount = (clone $query)->count();

        $sessions = $query
            ->latest('scheduled_at')
            ->paginate($perPage)
            ->withQueryString();

        $totalSessions = DefenseSession::count();
        $scheduledSessions = DefenseSession::where('status', 'scheduled')->count();
        $completedSessions = DefenseSession::where('status', 'completed')->count();
        $upcomingSessions = DefenseSession::where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->count();

        return view('admin.defenses.index', compact(
            'sessions',
            'perPage',
            'search',
            'statusFilter',
            'statusOptions',
            'filteredCount',
            'totalSessions',
            'scheduledSessions',
            'completedSessions',
            'upcomingSessions'
        ));
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

            $this->syncThesisStatusFromDefense($session);
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

            $this->syncThesisStatusFromDefense($defense);
        });

        return redirect()->route('admin.defenses.index')->with('success', 'Defense session updated.');
    }

    private function syncThesisStatusFromDefense(DefenseSession $defense): void
    {
        $thesis = Thesis::find($defense->thesis_id);

        if (!$thesis) {
            return;
        }

        if ($defense->status === 'completed') {
            $thesis->update([
                'status' => 'defended',
                'is_library_approved' => false,
                'library_approved_by' => null,
                'library_approved_at' => null,
                'is_public' => false,
                'published_by' => null,
                'published_at' => null,
            ]);

            return;
        }

        if (in_array($thesis->status, ['defended', 'completed'], true)) {
            $thesis->update([
                'status' => 'ready_for_defense',
                'is_library_approved' => false,
                'library_approved_by' => null,
                'library_approved_at' => null,
                'is_public' => false,
                'published_by' => null,
                'published_at' => null,
            ]);
        }
    }
}
