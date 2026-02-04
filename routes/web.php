<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoresExportController;
use App\Livewire\Admin\Checklists\Create as AdminChecklistsCreate;
use App\Livewire\Admin\Checklists\Edit as AdminChecklistsEdit;
use App\Livewire\Admin\Checklists\Index as AdminChecklistsIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Diaries\Create as AdminDiariesCreate;
use App\Livewire\Admin\Diaries\Edit as AdminDiariesEdit;
use App\Livewire\Admin\Diaries\Index as AdminDiariesIndex;
use App\Livewire\Admin\Users\Create as AdminUsersCreate;
use App\Livewire\Admin\Users\Edit as AdminUsersEdit;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Affirmations\Create as AffirmationsCreate;
use App\Livewire\Affirmations\Edit as AffirmationsEdit;
use App\Livewire\Affirmations\Index as AffirmationsIndex;
use App\Livewire\Dashboard;
use App\Livewire\Diaries\Create as DiariesCreate;
use App\Livewire\Diaries\Edit as DiariesEdit;
use App\Livewire\Diaries\Index as DiariesIndex;
use App\Livewire\ScoreCalculator;
use App\Livewire\Scores;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// -------------------------------
// Root Route
// -------------------------------
Route::get('/', function () {
    if (! Auth::check()) {
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
require __DIR__.'/auth.php';
