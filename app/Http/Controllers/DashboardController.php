<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->role) {
            return view('dashboard', ['error' => 'No role assigned']);
        }

        $roleName = $user->role->name;

        if ($roleName === 'admin') {
            return view('dashboard.admin');
        } elseif ($roleName === 'student') {
            return view('dashboard.student');
        } elseif ($roleName === 'supervisor') {
            return view('dashboard.supervisor');
        } elseif ($roleName === 'cosupervisor') {
            return view('dashboard.supervisor');
        } elseif ($roleName === 'coordinator') {
            return view('dashboard.admin');
        } elseif ($roleName === 'examiner') {
            return view('dashboard.examiner');
        } elseif ($roleName === 'librarian') {
            return view('dashboard.librarian');
        }

        return view('dashboard', ['error' => 'No dedicated dashboard for role: ' . $roleName]);
    }
}
