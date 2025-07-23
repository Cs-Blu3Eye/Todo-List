<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Menggunakan TaskController untuk dashboard
Route::get('/dashboard', [TaskController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute resource untuk TaskController
    Route::resource('tasks', TaskController::class);

    // Rute khusus untuk menandai task sebagai selesai
    Route::patch('/tasks/{task}/mark-as-done', [TaskController::class, 'markAsDone'])->name('tasks.markAsDone');
});

require __DIR__.'/auth.php';
