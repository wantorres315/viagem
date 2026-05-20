<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MinhaViagemController;
use App\Http\Controllers\MalaController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('minha-viagem')->controller(MinhaViagemController::class)->group(function () {
        Route::get('/', 'index')->name('minha-viagem.index');
        Route::get('/create', 'create')->name('minha-viagem.create');
        Route::post('/', 'store')->name('minha-viagem.store');
        Route::get('/{viagemId}/editar', 'edit')->name('minha-viagem.edit');
        Route::put('/{viagem}', 'update')->name('minha-viagem.update');
        Route::delete('/{viagem}', 'destroy')->name('minha-viagem.destroy');
    });
    Route::prefix('malas')->controller(MalaController::class)->group(function () {
        Route::get('/', 'index')->name('mala.index');
        Route::get('/create', 'create')->name('mala.create');
        Route::post('/', 'store')->name('mala.store');
        Route::get('/{malaId}/editar', 'edit')->name('mala.edit');
        Route::put('/{mala}', 'update')->name('mala.update');
    });
    });

require __DIR__.'/auth.php';
