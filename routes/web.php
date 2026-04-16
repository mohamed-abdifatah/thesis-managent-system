<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DefenseSessionController;
use App\Http\Controllers\ExaminerDefenseController;
use App\Http\Controllers\DefenseScheduleController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ThesisVersionController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ExaminerThesisController;
use App\Http\Controllers\LibrarianCatalogController;
use App\Http\Controllers\PublicCatalogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', [PublicCatalogController::class, 'index'])->name('books.index');
Route::get('/books/{thesis}/download', [PublicCatalogController::class, 'download'])
    ->middleware('throttle:120,1')
    ->name('books.download');
Route::get('/books/{thesis}', [PublicCatalogController::class, 'show'])->name('books.show');

Route::get('/install', [InstallController::class, 'index'])->middleware('install')->name('install.index');
Route::post('/install', [InstallController::class, 'store'])->middleware('install')->name('install.store');
Route::get('/install/ping', function () {
    return response('install ok', 200);
})->name('install.ping');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Student Routes
    Route::resource('proposals', ProposalController::class);
    Route::get('/thesis/versions', [ThesisVersionController::class, 'index'])->name('thesis.versions.index');
    Route::post('/thesis/versions', [ThesisVersionController::class, 'store'])->name('thesis.versions.store');
    Route::get('/thesis/versions/units', [ThesisVersionController::class, 'unitsList'])
        ->middleware('role:student')
        ->name('thesis.versions.units.list');
    Route::post('/thesis/versions/units', [ThesisVersionController::class, 'unitStore'])
        ->middleware('role:student')
        ->name('thesis.versions.units.store');
    Route::patch('/thesis/versions/{version}', [ThesisVersionController::class, 'updateStudent'])
        ->middleware('role:student')
        ->name('thesis.versions.update');
    Route::patch('/thesis/versions/{version}/status', [ThesisVersionController::class, 'updateStatus'])
        ->middleware('role:supervisor,examiner')
        ->name('thesis.versions.status');
    Route::post('/thesis/versions/{version}/feedback', [FeedbackController::class, 'storeVersion'])
        ->middleware('role:student,supervisor,examiner')
        ->name('thesis.versions.feedback.store');
    Route::post('/thesis/{thesis}/feedback', [FeedbackController::class, 'storeThesis'])
        ->middleware('role:student,supervisor,examiner')
        ->name('thesis.feedback.store');
    Route::patch('/feedback/{feedback}', [FeedbackController::class, 'update'])
        ->middleware('role:student,supervisor,examiner')
        ->name('feedback.update');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])
        ->middleware('role:student,supervisor,examiner')
        ->name('feedback.destroy');
    Route::get('/defense/schedule', [DefenseScheduleController::class, 'index'])->name('defense.schedule');

    // Admin Routes
    Route::middleware('role:admin,coordinator')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Student Group Management
        Route::get('/groups', [AdminController::class, 'groupsIndex'])->name('groups.index');
        Route::get('/groups/create', [AdminController::class, 'groupsCreate'])->name('groups.create');
        Route::post('/groups', [AdminController::class, 'groupsStore'])->name('groups.store');

        // Thesis Management
        Route::get('/theses', [AdminController::class, 'theses'])->name('theses.index');
        Route::post('/theses/{thesis}/assign', [AdminController::class, 'assignSupervisor'])->name('theses.assign');

        // Defense Sessions
        Route::get('/defenses', [DefenseSessionController::class, 'index'])->name('defenses.index');
        Route::get('/defenses/create', [DefenseSessionController::class, 'create'])->name('defenses.create');
        Route::post('/defenses', [DefenseSessionController::class, 'store'])->name('defenses.store');
        Route::get('/defenses/{defense}/edit', [DefenseSessionController::class, 'edit'])->name('defenses.edit');
        Route::put('/defenses/{defense}', [DefenseSessionController::class, 'update'])->name('defenses.update');
    });

    // Supervisor Routes
    Route::middleware('role:supervisor')->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/my-students', [SupervisorController::class, 'myStudents'])->name('students.index');
        Route::get('/theses/{thesis}', [SupervisorController::class, 'showThesis'])->name('theses.show');
        Route::patch('/theses/{thesis}/final-version', [SupervisorController::class, 'setFinalVersion'])->name('theses.final-version');
        Route::post('/proposals/{proposal}/review', [SupervisorController::class, 'reviewProposal'])->name('proposals.review');
    });

    // Examiner Routes
    Route::middleware('role:examiner')->prefix('examiner')->name('examiner.')->group(function () {
        Route::get('/defenses', [ExaminerDefenseController::class, 'index'])->name('defenses.index');
        Route::post('/defenses/{defense}/evaluation', [ExaminerDefenseController::class, 'storeEvaluation'])->name('defenses.evaluate');
        Route::get('/theses/{thesis}', [ExaminerThesisController::class, 'show'])->name('theses.show');
    });

    // Librarian Routes
    Route::middleware('role:librarian')->prefix('library')->name('library.')->group(function () {
        Route::get('/catalog', [LibrarianCatalogController::class, 'index'])->name('catalog.index');
        Route::patch('/catalog/{thesis}/validate', [LibrarianCatalogController::class, 'validateCatalogEntry'])->name('catalog.validate');
        Route::patch('/catalog/{thesis}/publish', [LibrarianCatalogController::class, 'publishCatalogEntry'])->name('catalog.publish');
        Route::patch('/catalog/{thesis}/unpublish', [LibrarianCatalogController::class, 'unpublishCatalogEntry'])->name('catalog.unpublish');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
