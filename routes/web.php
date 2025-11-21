<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoresExportController;
use App\Livewire\{
    Dashboard,
    Diaries\Index as DiariesIndex,
    Diaries\Create as DiariesCreate,
    Diaries\Edit as DiariesEdit,
    Affirmations\Index as AffirmationsIndex,
    Affirmations\Create as AffirmationsCreate,
    Affirmations\Edit as AffirmationsEdit,
    ScoreCalculator,
    Scores,
    Admin\Dashboard as AdminDashboard,
    Admin\Users\Index as AdminUsersIndex,
    Admin\Users\Create as AdminUsersCreate,
    Admin\Users\Edit as AdminUsersEdit,
    Admin\Diaries\Index as AdminDiariesIndex,
    Admin\Diaries\Create as AdminDiariesCreate,
    Admin\Diaries\Edit as AdminDiariesEdit,
    Admin\Checklists\Index as AdminChecklistsIndex,
    Admin\Checklists\Create as AdminChecklistsCreate,
    Admin\Checklists\Edit as AdminChecklistsEdit,
};

// -------------------------------
// Root Route
// -------------------------------
Route::get('/', function () {
    if (!Auth::check()) {
        return view('welcome');
    }

    return Auth::user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('dashboard');
});

// -------------------------------
// Dashboard (shared for all users)
// -------------------------------
Route::middleware(['auth', 'verified'])->get('/dashboard', Dashboard::class)->name('dashboard');

// -------------------------------
// Authenticated User Routes
// -------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Account / Profile ---
    Route::get('/account/password', \App\Livewire\Settings\Password::class)
        ->name('settings.password');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // --- Diaries ---
    Route::prefix('diaries')->name('diaries.')->group(function () {
        Route::get('/', DiariesIndex::class)->name('index');
        Route::get('/create', DiariesCreate::class)->name('create');
        Route::get('/{diary}/edit', DiariesEdit::class)->name('edit');
    });

    // --- Affirmations ---
    Route::prefix('affirmations')->name('affirmations.')->group(function () {
        Route::get('/', AffirmationsIndex::class)->name('index');
        Route::get('/create', AffirmationsCreate::class)->name('create');
        Route::get('/{affirmation}/edit', AffirmationsEdit::class)->name('edit');
    });

    // --- Score Calculator & Scores ---
    Route::get('/score-calculator/{date}', ScoreCalculator::class)
        ->name('score-calculator');

    Route::get('/scores', Scores::class)->name('scores');
    Route::get('/scores/export', [ScoresExportController::class, 'export'])
        ->name('scores.export');
});

// -------------------------------
// Admin Routes
// -------------------------------
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', AdminDashboard::class)->name('dashboard');

        // --- Users ---
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', AdminUsersIndex::class)->name('index');
            Route::get('/create', AdminUsersCreate::class)->name('create');
            Route::get('/{user}/edit', AdminUsersEdit::class)->name('edit');
        });

        // --- Diaries ---
        Route::prefix('diaries')->name('diaries.')->group(function () {
            Route::get('/', AdminDiariesIndex::class)->name('index');
            Route::get('/create', AdminDiariesCreate::class)->name('create');
            Route::get('/{diary}/edit', AdminDiariesEdit::class)->name('edit');
        });

        // --- Checklists ---
        Route::prefix('checklists')->name('checklists.')->group(function () {
            Route::get('/', AdminChecklistsIndex::class)->name('index');
            Route::get('/create', AdminChecklistsCreate::class)->name('create');
            Route::get('/{checklist}/edit', AdminChecklistsEdit::class)->name('edit');
        });
    });

// Breeze / Auth
require __DIR__ . '/auth.php';
