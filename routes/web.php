<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeTransportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/demandes', [DemandeTransportController::class, 'store'])->name('demandes.store');
    Route::get('/demandes/{demande}/pdf', [DemandeTransportController::class, 'pdf'])->name('demandes.pdf');

    Route::middleware('role:directeur-general|admin')->prefix('validation')->name('validation.')->group(function () {
        Route::get('/', [ValidationController::class, 'index'])->name('index');
        Route::post('/{demande}/valider', [ValidationController::class, 'valider'])->name('valider');
        Route::post('/{demande}/rejeter', [ValidationController::class, 'rejeter'])->name('rejeter');
    });
});

require __DIR__.'/auth.php';
