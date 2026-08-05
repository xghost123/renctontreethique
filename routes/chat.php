<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ModerationController;
use App\Http\Middleware\EnsureModerator;

// Member chat (authenticated)
Route::prefix('api/chat')->middleware(['web', 'auth:web,sanctum'])->group(function () {
    Route::get('conversations', [ChatController::class, 'conversations']);
    Route::post('open', [ChatController::class, 'open']);
    Route::get('messages/{conversationId}', [ChatController::class, 'messages']);
    Route::post('send', [ChatController::class, 'send']);
});

// Moderation queue — admin + moderators (imams)
Route::prefix('api/moderation')
    ->middleware(['web', 'auth:web,sanctum', EnsureModerator::class])
    ->group(function () {
        Route::get('queue', [ModerationController::class, 'queue']);
        Route::post('approve', [ModerationController::class, 'approve']);
        Route::post('reject', [ModerationController::class, 'reject']);
    });
