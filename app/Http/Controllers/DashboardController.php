<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->role) {
            // Fallback if role is missing (should not happen with correct seeding/registration)
            return view('dashboard', ['error' => 'No role assigned']);
        }

        if ($user->role->name === 'admin') {
            return view('dashboard.admin');
        } elseif ($user->role->name === 'student') {
            return view('dashboard.student');
        } elseif ($user->role->name === 'supervisor') {
             return view('dashboard.supervisor');
        } elseif ($user->role->name === 'cosupervisor') {
             return view('dashboard.supervisor'); // Create specialized if needed
        } elseif ($user->role->name === 'coordinator') {
             return view('dashboard.admin'); // Re-use admin dashboard or create new
        } elseif ($user->role->name === 'examiner') {
             return view('dashboard.examiner');
        } elseif ($user->role->name === 'librarian') {
             return view('dashboard.librarian');
        }

        return view('dashboard');
    }
}
