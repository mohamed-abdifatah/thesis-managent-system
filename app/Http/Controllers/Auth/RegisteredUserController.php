<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $roleId = \App\Models\Role::where('name', 'student')->value('id');
        if (!$roleId) {
            $roleId = \App\Models\Role::create([
                'name' => 'student',
                'description' => 'University Student',
            ])->id;
        }

        $department = Department::query()->orderBy('id')->first();
        if (!$department) {
            $department = Department::create([
                'name' => 'General Studies',
                'code' => 'GEN',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId, // Default to student
            'department_id' => $department->id,
        ]);

        // Create student profile automatically
           Student::create([
             'user_id' => $user->id,
               'student_id_number' => Student::generateStudentIdNumber($user->id),
             'program' => 'General', // Default program
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
