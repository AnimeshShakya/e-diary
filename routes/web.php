<?php

use App\Http\Controllers\DiaryController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Diaries\Create as AdminDiariesCreate;
use App\Livewire\Admin\Diaries\Index as AdminDiariesIndex;
use App\Livewire\Admin\Diaries\Edit as AdminDiariesEdit;
use App\Livewire\Admin\Moderation\Index as AdminModerationIndex;
use App\Livewire\Admin\Users\Create as AdminUsersCreate;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Admin\Users\Edit as AdminUsersEdit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('diaries', DiaryController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', AdminUsersIndex::class)->name('users.index');
    Route::get('/users/create', AdminUsersCreate::class)->name('users.create');
    Route::get('/users/{user}/edit', AdminUsersEdit::class)->name('users.edit');
    Route::get('/diaries', AdminDiariesIndex::class)->name('diaries.index');
    Route::get('/diaries/create', AdminDiariesCreate::class)->name('diaries.create');
    Route::get('/diaries/{diary}/edit', AdminDiariesEdit::class)->name('diaries.edit');
    Route::get('/moderation', AdminModerationIndex::class)->name('moderation');
});

require __DIR__.'/auth.php';
