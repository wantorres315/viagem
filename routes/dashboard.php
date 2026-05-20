<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecklistViagemController;

Route::middleware(['auth'])->group(function () {
    Route::patch('/dashboard/checklist/{item}', [ChecklistViagemController::class, 'toggleConcluido'])->name('dashboard.checklist.toggle');
});
