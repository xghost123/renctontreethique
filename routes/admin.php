<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\EnsureAdmin;

// Modern admin API — web session + sanctum + EnsureAdmin (role=admin)
Route::prefix('api/admin')
    ->middleware(['web', 'auth:web,sanctum', EnsureAdmin::class])
    ->group(function () {

        // Dashboard metrics
        Route::get('dashboard', [AdminController::class, 'dashboard']);

        // Users
        Route::get('users', [AdminController::class, 'users']);
        Route::post('users/biodata-status', [AdminController::class, 'setBiodataStatus']);
        Route::post('users/role', [AdminController::class, 'setRole']);

        // Mosques
        Route::get('mosques', [AdminController::class, 'mosques']);
        Route::post('mosques/status', [AdminController::class, 'setMosqueStatus']);

        // Proposals
        Route::get('proposals', [AdminController::class, 'proposals']);

        // Join requests (candidate acceptance)
        Route::get('join-requests', [AdminController::class, 'joinRequests']);
        Route::post('join-requests/decide', [AdminController::class, 'decideJoinRequest']);

        // Photos (approval queue)
        Route::get('photos/pending', [AdminController::class, 'pendingPhotos']);
        Route::post('photos/{id}/approve', [AdminController::class, 'approvePhoto']);
        Route::post('photos/{id}/reject', [AdminController::class, 'rejectPhoto']);
    });
