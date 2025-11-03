<?php

use App\Http\Controllers\ProfileController;
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
use App\Livewire\Diaries\Create as DiariesCreate;
use App\Livewire\Diaries\Edit as DiariesEdit;
use App\Livewire\Diaries\Index as DiariesIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('diaries.index');
    }

    // Guests see the welcome page (aligns with auth scaffolding tests)
    return view('welcome');
});

// Provide a dashboard route for auth scaffolding/tests; redirect based on role to existing pages
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();
    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('diaries.index');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    // Password settings page (non-admins can change their own password here)
    Route::get('/account/password', \App\Livewire\Settings\Password::class)->name('settings.password');

    // Breeze profile routes retained for tests and account management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Non-admin users can create, update, and read diaries (no delete) via Livewire pages
    Route::get('/diaries', DiariesIndex::class)->name('diaries.index');
    Route::get('/diaries/create', DiariesCreate::class)->name('diaries.create');
    Route::get('/diaries/{diary}/edit', DiariesEdit::class)->name('diaries.edit');

    // Affirmations - accessible to all authenticated users (admin and non-admin)
    Route::get('/affirmations', AffirmationsIndex::class)->name('affirmations.index');
    Route::get('/affirmations/create', AffirmationsCreate::class)->name('affirmations.create');
    Route::get('/affirmations/{affirmation}/edit', AffirmationsEdit::class)->name('affirmations.edit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', AdminUsersIndex::class)->name('users.index');
    Route::get('/users/create', AdminUsersCreate::class)->name('users.create');
    Route::get('/users/{user}/edit', AdminUsersEdit::class)->name('users.edit');
    Route::get('/diaries', AdminDiariesIndex::class)->name('diaries.index');
    Route::get('/diaries/create', AdminDiariesCreate::class)->name('diaries.create');
    Route::get('/diaries/{diary}/edit', AdminDiariesEdit::class)->name('diaries.edit');
    Route::get('/checklists', AdminChecklistsIndex::class)->name('checklists.index');
    Route::get('/checklists/create', AdminChecklistsCreate::class)->name('checklists.create');
    Route::get('/checklists/{checklist}/edit', AdminChecklistsEdit::class)->name('checklists.edit');
    // Removed unused moderation route
});

require __DIR__.'/auth.php';
