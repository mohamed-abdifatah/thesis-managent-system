<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::with(['role', 'department'])->paginate(10);
        return view('admin.users.index', compact('users'));
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
                'student_id_number' => 'STD' . date('Y') . mt_rand(1000, 9999),
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
                    'student_id_number' => 'STD' . date('Y') . mt_rand(1000, 9999),
                    'program' => 'General',
                    'supervisor_id' => $request->supervisor_id,
                ]);
            } else {
                $user->student->update([
                    'supervisor_id' => $request->supervisor_id
                ]);
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
        $theses = Thesis::with(['student.user', 'supervisor.user', 'proposals' => function($query) {
            $query->latest()->limit(1);
        }])->latest()->paginate(10);
        
        $supervisors = Supervisor::with('user')->get();

        return view('admin.theses.index', compact('theses', 'supervisors'));
    }

    public function assignSupervisor(Request $request, Thesis $thesis)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:supervisors,id',
        ]);

        $thesis->update([
            'supervisor_id' => $request->supervisor_id,
        ]);

        return back()->with('success', 'Supervisor assigned successfully.');
    }
}
