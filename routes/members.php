<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileWizardController;
use App\Http\Controllers\MembersBrowseController;

// Profile wizard (authenticated)
Route::prefix('api/profile')->middleware(['web', 'auth:web,sanctum'])->group(function () {
    Route::get('state', [ProfileWizardController::class, 'state']);
    Route::post('save-step', [ProfileWizardController::class, 'saveStep']);
    Route::post('submit', [ProfileWizardController::class, 'submit']);
});

// Members browse (authenticated + mosque isolation enforced in controller)
Route::prefix('api/members')->middleware(['web', 'auth:web,sanctum'])->group(function () {
    Route::get('/', [MembersBrowseController::class, 'index']);
    Route::get('options', [MembersBrowseController::class, 'options']);
});
