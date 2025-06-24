<?php

declare(strict_types = 1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimeEntryReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome'));

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/time-entries', [TimeEntryController::class, 'index'])->name('time-entries.index');
    Route::post('/time-entries', [TimeEntryController::class, 'store'])->name('time-entries.store');

    Route::middleware('admin')->group(function (): void {
        Route::resource('users', UserController::class);
        Route::get('/reports/time-entries', TimeEntryReportController::class)->name('time-entries.report');
    });
});

require __DIR__ . '/auth.php';
