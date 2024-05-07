<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiaryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard Route handled by DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Diary Route handled by DiaryController
Route::get('/diary', [DiaryController::class, 'index'])
    ->middleware('auth') // You can add any necessary middleware here
    ->name('diary');

Route::post('/diary/store',[DiaryController::class, 'store'])
    ->middleware('auth')
    ->name('admin.diary.store');


    

Route::post('/generate-time-slots', function (Request $request) {
    $startTime = $request->input('startTime');
    $endTime = $request->input('endTime');
    return view('admin.diary.index', compact('startTime', 'endTime'));
})->name('diaryTimeSlot');

Route::post('/generate-priority-slots', function (Request $request) {
    $priority1 = $request->input('priority1');
    $priority2 = $request->input('priority2');
    $priority3 = $request->input('priority3');
    return view('admin.diary.index', compact('priority1', 'priority2', 'priority3'));
})->name('diaryPrioritySlot');
    
    
    




require __DIR__.'/auth.php';
