<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MosqueController;

Route::prefix('api/mosque')->middleware(['auth:sanctum,web'])->group(function () {
    Route::get('index', [MosqueController::class, 'index']);              // public mosque list
    Route::get('my', [MosqueController::class, 'myMosque']);              // my mosque dashboard
    Route::get('{slug}', [MosqueController::class, 'show']);              // mosque members (isolation)
    Route::post('join', [MosqueController::class, 'join']);               // request to join
    Route::post('join/{id}/approve', [MosqueController::class, 'approveJoin']);
    Route::post('join/{id}/reject', [MosqueController::class, 'rejectJoin']);
    Route::post('propose', [MosqueController::class, 'propose']);         // brother → sister
    Route::post('proposals/{id}/accept', [MosqueController::class, 'acceptProposal']);
    Route::post('proposals/{id}/decline', [MosqueController::class, 'declineProposal']);
});
