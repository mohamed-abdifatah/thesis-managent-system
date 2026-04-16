<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Thesis;
use App\Models\StudentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function users()
    {
        $perPage = (int) request('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $roleFilter = trim((string) request('role', ''));
        $search = trim((string) request('q', ''));

        $query = User::with(['role', 'department']);

        if ($roleFilter !== '') {
            $query->whereHas('role', function ($roleQuery) use ($roleFilter) {
                $roleQuery->where('name', $roleFilter);
            });
        }

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($departmentQuery) use ($search) {
                        $departmentQuery
                            ->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        $filteredCount = (clone $query)->count();

        $users = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        $totalUsers = User::count();
        $roleCounts = User::query()
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->selectRaw('LOWER(roles.name) as role_name, COUNT(*) as total')
            ->groupBy('roles.name')
            ->pluck('total', 'role_name');

        $studentCount = (int) ($roleCounts['student'] ?? 0);
        $supervisorCount = (int) (($roleCounts['supervisor'] ?? 0) + ($roleCounts['cosupervisor'] ?? 0));
        $adminCount = (int) (($roleCounts['admin'] ?? 0) + ($roleCounts['coordinator'] ?? 0));

        return view('admin.users.index', compact(
            'users',
            'roles',
            'perPage',
            'roleFilter',
            'search',
            'filteredCount',
            'totalUsers',
            'studentCount',
            'supervisorCount',
            'adminCount'
        ));
    }

    public function createUser()
    {
        $roles = Role::all();
        $departments = Department::all();
        $supervisors = Supervisor::with('user')->get();
        return view('admin.users.create', compact('roles', 'departments', 'supervisors'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'nullable|exists:supervisors,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
        ]);

        $roleName = $user->role->name;

        if ($roleName === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id_number' => Student::generateStudentIdNumber($user->id),
                'program' => 'General',
                'supervisor_id' => $request->supervisor_id,
            ]);
        } elseif (in_array($roleName, ['supervisor', 'cosupervisor'])) {
            Supervisor::create([
                'user_id' => $user->id,
                'specialization' => 'General',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();
        $supervisors = Supervisor::with('user')->get();
        return view('admin.users.edit', compact('user', 'roles', 'departments', 'supervisors'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'nullable|exists:supervisors,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle Role Profile Creation & Update (Basic)
        $roleName = $user->role->name;
        if ($roleName === 'student') {
            if (!$user->student) {
                 Student::create([
                    'user_id' => $user->id,
                    'student_id_number' => Student::generateStudentIdNumber($user->id),
                    'program' => 'General',
                    'supervisor_id' => $request->supervisor_id,
                ]);
            } else {
                $user->student->update([
                    'supervisor_id' => $request->supervisor_id
                ]);
                if ($user->student->thesis && $request->supervisor_id) {
                    $user->student->thesis->update(['supervisor_id' => $request->supervisor_id]);
                }
            }
        } elseif (in_array($roleName, ['supervisor', 'cosupervisor']) && !$user->supervisor) {
            Supervisor::create([
                'user_id' => $user->id,
                'specialization' => 'General',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function theses()
    {
        $perPage = (int) request('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $search = trim((string) request('q', ''));
        $statusFilter = trim((string) request('status', ''));
        $supervisorFilter = (int) request('supervisor_id', 0);

        $statusOptions = [
            'proposal_pending' => 'Proposal Pending',
            'proposal_approved' => 'Proposal Approved',
            'in_progress' => 'In Progress',
            'ready_for_defense' => 'Ready for Defense',
            'defended' => 'Defended',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];

        if ($statusFilter !== '' && !array_key_exists($statusFilter, $statusOptions)) {
            $statusFilter = '';
        }

        $query = Thesis::with(['student.user', 'supervisor.user'])
            ->withCount(['proposals', 'versions']);

        if ($search !== '') {
            $query->where(function ($thesisQuery) use ($search) {
                $thesisQuery
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($studentUserQuery) use ($search) {
                        $studentUserQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery->where('student_id_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supervisor.user', function ($supervisorUserQuery) use ($search) {
                        $supervisorUserQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        if ($supervisorFilter > 0) {
            $query->where('supervisor_id', $supervisorFilter);
        }

        $filteredCount = (clone $query)->count();

        $theses = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $supervisors = Supervisor::with('user')->orderBy('id')->get();

        $totalTheses = Thesis::count();
        $assignedTheses = Thesis::whereNotNull('supervisor_id')->count();
        $readyDefenseTheses = Thesis::whereIn('status', ['ready_for_defense', 'defended', 'completed'])->count();
        $completedTheses = Thesis::where('status', 'completed')->count();

        return view('admin.theses.index', compact(
            'theses',
            'supervisors',
            'perPage',
            'search',
            'statusFilter',
            'supervisorFilter',
            'statusOptions',
            'filteredCount',
            'totalTheses',
            'assignedTheses',
            'readyDefenseTheses',
            'completedTheses'
        ));
    }

    public function assignSupervisor(Request $request, Thesis $thesis)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:supervisors,id',
        ]);

        $thesis->update([
            'supervisor_id' => $request->supervisor_id,
        ]);

        if ($thesis->student) {
            $thesis->student->update(['supervisor_id' => $request->supervisor_id]);
        }

        return back()->with('success', 'Supervisor assigned successfully.');
    }

    public function groupsIndex()
    {
        $perPage = (int) request('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $search = trim((string) request('q', ''));
        $departmentFilter = (int) request('department_id', 0);

        $query = StudentGroup::with(['supervisor.user', 'department'])
            ->withCount(['students', 'theses']);

        if ($search !== '') {
            $query->where(function ($groupQuery) use ($search) {
                $groupQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($departmentQuery) use ($search) {
                        $departmentQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supervisor.user', function ($supervisorQuery) use ($search) {
                        $supervisorQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($departmentFilter > 0) {
            $query->where('department_id', $departmentFilter);
        }

        $filteredCount = (clone $query)->count();

        $groups = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $departments = Department::orderBy('name')->get(['id', 'name', 'code']);

        $totalGroups = StudentGroup::count();
        $groupsWithSupervisor = StudentGroup::whereNotNull('supervisor_id')->count();
        $linkedStudents = Student::whereNotNull('student_group_id')->count();
        $groupsWithThesis = StudentGroup::has('theses')->count();

        return view('admin.groups.index', compact(
            'groups',
            'departments',
            'perPage',
            'search',
            'departmentFilter',
            'filteredCount',
            'totalGroups',
            'groupsWithSupervisor',
            'linkedStudents',
            'groupsWithThesis'
        ));
    }

    public function groupsCreate()
    {
        $departments = Department::all();
        $supervisors = Supervisor::with('user')->get();
        $studentUsers = User::with('student')
            ->whereHas('role', function ($query) {
                $query->where('name', 'student');
            })
            ->orderBy('name')
            ->get();

        return view('admin.groups.create', compact('departments', 'supervisors', 'studentUsers'));
    }

    public function groupsStore(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'required|exists:supervisors,id',
            'program' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'default_password' => 'nullable|string|min:8',
            'students' => 'nullable|array',
            'students.*.name' => 'nullable|string|max:255',
            'students.*.email' => 'nullable|email|distinct|unique:users,email',
            'existing_students' => 'nullable|array',
            'existing_students.*' => 'exists:users,id',
        ]);

        $studentRole = Role::where('name', 'student')->firstOrFail();

        $newStudents = collect($request->input('students', []))
            ->filter(function ($student) {
                return filled($student['name'] ?? null) || filled($student['email'] ?? null);
            })
            ->values();

        $existingStudentIds = collect($request->input('existing_students', []))
            ->filter()
            ->values();

        if ($newStudents->isEmpty() && $existingStudentIds->isEmpty()) {
            throw ValidationException::withMessages([
                'students' => 'Add at least one new or existing student to create a group.',
            ]);
        }

        foreach ($newStudents as $index => $studentData) {
            if (!filled($studentData['name'] ?? null) || !filled($studentData['email'] ?? null)) {
                throw ValidationException::withMessages([
                    "students.{$index}.name" => 'Each new student must have a full name and email.',
                ]);
            }
        }

        if ($newStudents->isNotEmpty() && !filled($request->default_password)) {
            throw ValidationException::withMessages([
                'default_password' => 'Default password is required only when creating new students.',
            ]);
        }

        DB::transaction(function () use ($request, $studentRole) {
            $group = StudentGroup::create([
                'name' => $request->group_name,
                'supervisor_id' => $request->supervisor_id,
                'department_id' => $request->department_id,
                'program' => $request->program,
                'academic_year' => $request->academic_year,
                'notes' => $request->notes,
            ]);

            $newStudents = collect($request->input('students', []))
                ->filter(function ($student) {
                    return filled($student['name'] ?? null) && filled($student['email'] ?? null);
                })
                ->values();

            foreach ($newStudents as $studentData) {
                    $user = User::create([
                        'name' => $studentData['name'],
                        'email' => $studentData['email'],
                        'password' => Hash::make($request->default_password),
                        'role_id' => $studentRole->id,
                        'department_id' => $request->department_id,
                    ]);

                    Student::create([
                        'user_id' => $user->id,
                        'student_id_number' => Student::generateStudentIdNumber($user->id),
                        'program' => $request->program ?? 'General',
                        'supervisor_id' => $request->supervisor_id,
                        'student_group_id' => $group->id,
                    ]);
            }

            if ($request->filled('existing_students')) {
                $existingUsers = User::with('student', 'role')
                    ->whereIn('id', $request->existing_students)
                    ->get();

                foreach ($existingUsers as $user) {
                    if (!$user->role || $user->role->name !== 'student') {
                        continue;
                    }

                    if (!$user->student) {
                        Student::create([
                            'user_id' => $user->id,
                            'student_id_number' => Student::generateStudentIdNumber($user->id),
                            'program' => $request->program ?? 'General',
                            'supervisor_id' => $request->supervisor_id,
                            'student_group_id' => $group->id,
                        ]);
                    } else {
                        $user->student->update([
                            'student_group_id' => $group->id,
                            'supervisor_id' => $request->supervisor_id,
                            'program' => $request->program ?? $user->student->program,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.groups.index')->with('success', 'Student group created successfully.');
    }
}
