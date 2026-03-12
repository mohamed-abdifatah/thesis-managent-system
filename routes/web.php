<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Student Routes
    Route::resource('proposals', ProposalController::class);

    // Admin Routes
    Route::middleware('role:admin,coordinator')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Thesis Management
        Route::get('/theses', [AdminController::class, 'theses'])->name('theses.index');
        Route::post('/theses/{thesis}/assign', [AdminController::class, 'assignSupervisor'])->name('theses.assign');
    });

    // Supervisor Routes
    Route::middleware('role:supervisor')->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/my-students', [SupervisorController::class, 'myStudents'])->name('students.index');
        Route::get('/theses/{thesis}', [SupervisorController::class, 'showThesis'])->name('theses.show');
        Route::post('/proposals/{proposal}/review', [SupervisorController::class, 'reviewProposal'])->name('proposals.review');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
