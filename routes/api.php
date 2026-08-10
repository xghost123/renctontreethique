<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\AnalyticsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Photo routes (authenticated)
Route::middleware('auth:sanctum')->group(function () {
    // User can upload and delete their own photos
    Route::post('/photos/upload', [PhotoController::class, 'upload']);
    Route::delete('/photos/{id}', [PhotoController::class, 'delete']);
});

// Public photo viewing (approved only)
Route::get('/biodata/{id}/photos', [PhotoController::class, 'getByBiodata']);

// Admin photo routes
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/admin/photos/pending', [PhotoController::class, 'getPendingPhotos']);
});

// Search routes
Route::middleware('auth:sanctum')->group(function () {
    // Advanced search with filters
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/recommendations', [SearchController::class, 'recommendations']);
    Route::get('/search/filters', [SearchController::class, 'getFilterOptions']);

    // Saved searches
    Route::prefix('saved-searches')->group(function () {
        Route::get('/', [SavedSearchController::class, 'index']);
        Route::post('/', [SavedSearchController::class, 'store']);
        Route::put('/{id}', [SavedSearchController::class, 'update']);
        Route::delete('/{id}', [SavedSearchController::class, 'destroy']);
    });

    // Conversation routes
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::post('/', [ConversationController::class, 'store']);
        Route::get('/search', [ConversationController::class, 'search']);
        Route::get('/unread-count', [ConversationController::class, 'unreadCount']);
        Route::get('/{id}', [ConversationController::class, 'show']);
        Route::put('/{id}/close', [ConversationController::class, 'close']);
    });

    // Message routes - 1-on-1 chat API
    Route::prefix('messages')->group(function () {
        // Send a message
        Route::post('/send', [MessageController::class, 'send']);
        
        // Get messages in a conversation (with pagination)
        Route::get('/conversation/{conversationId}', [MessageController::class, 'getConversationMessages']);
        
        // Mark messages as read
        Route::post('/mark-read', [MessageController::class, 'markAsRead']);
        
        // Get unread message count
        Route::get('/unread-count', [MessageController::class, 'getUnreadCount']);
        
        // Poll for new messages (real-time updates without WebSocket)
        Route::get('/poll/{conversationId}', [MessageController::class, 'poll']);
        
        // Delete own message
        Route::delete('/{id}', [MessageController::class, 'delete']);
        
        // Flag a message for moderation
        Route::post('/{id}/flag', [MessageController::class, 'flag']);
    });

    // Analytics routes
    Route::prefix('analytics')->group(function () {
        Route::get('/profile-views', [AnalyticsController::class, 'profileViews']);
        Route::get('/likes', [AnalyticsController::class, 'likes']);
        Route::get('/messages', [AnalyticsController::class, 'messages']);
        Route::get('/proposals', [AnalyticsController::class, 'proposals']);
        Route::get('/activity-heatmap', [AnalyticsController::class, 'activityHeatmap']);
        Route::get('/demographics', [AnalyticsController::class, 'demographics']);
        Route::get('/profile-completion', [AnalyticsController::class, 'profileCompletion']);
        Route::get('/summary', [AnalyticsController::class, 'summary']);
    });
});


