<?php

declare(strict_types = 1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome'));

// Rotas acessíveis apenas para usuários autenticados
Route::middleware('auth')->group(function (): void {
    // Dashboard
    Route::get('/dashboard', static fn () => view('dashboard'))->name('dashboard');

    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para funcionários (registro de ponto)
    Route::get('/time-entries', [TimeEntryController::class, 'index'])->name('time-entries.index');
    Route::post('/time-entries', [TimeEntryController::class, 'store'])->name('time-entries.store');

    Route::resource('users', UserController::class);

    Route::get('/reports/time-entries', [TimeEntryController::class, 'report'])->name('time-entries.report');
});

require __DIR__ . '/auth.php';
