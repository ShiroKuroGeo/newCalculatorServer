<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::delete('entries', [EntryController::class, 'clear']);
    Route::patch('entries/{entry}/toggle-status', [EntryController::class, 'toggleStatus']);
    Route::apiResource('entries', EntryController::class);

    Route::prefix('agenda/{kind}')->whereIn('kind', ['meeting', 'event'])->group(function () {
        Route::get('/', [AgendaController::class, 'index']);
        Route::post('/', [AgendaController::class, 'store']);
        Route::delete('/', [AgendaController::class, 'clear']);
        Route::put('{item}', [AgendaController::class, 'update']);
        Route::patch('{item}/toggle-done', [AgendaController::class, 'toggleDone']);
        Route::delete('{item}', [AgendaController::class, 'destroy']);
    });
});
