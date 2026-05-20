<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MinhaViagemController;
use App\Http\Controllers\MalaController;
use App\Http\Controllers\AmigoController;
use App\Http\Controllers\RelatorioViagemController;

Route::get('/', function () {
    return view('auth.login');
});

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
        Route::delete('/{mala}', 'destroy')->name('mala.destroy');  
    });

    Route::prefix('amigos')->controller(\App\Http\Controllers\AmigoController::class)->group(function () {
        Route::get('/', 'index')->name('amigo.index');
        Route::get('/create', 'create')->name('amigo.create');
        Route::post('/', 'store')->name('amigo.store');
        Route::get('/{amigoId}/editar', 'edit')->name('amigo.edit');
        Route::put('/{amigo}', 'update')->name('amigo.update');
        Route::delete('/{amigo}', 'destroy')->name('amigo.destroy');
    });
    // Checklist da Viagem
    Route::get('/checklist', [\App\Http\Controllers\ChecklistViagemController::class, 'index'])->name('checklist.index');
    Route::post('/checklist', [\App\Http\Controllers\ChecklistViagemController::class, 'store'])->name('checklist.store');
    Route::put('/checklist/{id}', [\App\Http\Controllers\ChecklistViagemController::class, 'update'])->name('checklist.update');
    Route::delete('/checklist/{id}', [\App\Http\Controllers\ChecklistViagemController::class, 'destroy'])->name('checklist.destroy');

    // PATCH para AJAX do dashboard
    Route::patch('/dashboard/checklist/{id}', [\App\Http\Controllers\ChecklistViagemController::class, 'toggleConcluido'])->name('dashboard.checklist.toggle');

    Route::get('/impressao_viagem', [RelatorioViagemController::class, 'index'])->name('relatorio.viagem');
    Route::get('/relatorio-viagem/pdf', [RelatorioViagemController::class, 'pdf'])->name('relatorio.viagem.pdf');
    Route::get('/relatorio-viagem/itinerario-pdf', [RelatorioViagemController::class, 'itinerarioPdf'])->name('relatorio.viagem.itinerario.pdf');

});

require __DIR__.'/auth.php';
