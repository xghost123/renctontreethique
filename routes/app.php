<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\EnsureAuthenticated;

// Member app shell (authenticated, Inertia pages for the profile flow)
Route::prefix('app')->middleware(['web', 'auth:web'])->group(function () {
    // Profile wizard
    Route::get('profile/edit', fn () => Inertia::render('Profile/Wizard'))->name('profile.edit');
    // Profile status (pending approval / no profile / active)
    Route::get('status', fn () => Inertia::render('Profile/Status'))->name('profile.status');
    // Members browse (same-mosque)
    Route::get('members', fn () => Inertia::render('Profile/Members'))->name('members.browse');
    // Chat (moderated messages)
    Route::get('chat', fn () => Inertia::render('Profile/Chat'))->name('chat');
    // Moderation queue (admin/imam)
    Route::get('moderation', fn () => Inertia::render('Profile/Moderation'))->name('moderation');
});

// Redirect after login/register to the member app
Route::get('/dashboard', function () {
    return redirect()->route('profile.status');
})->middleware(['web', 'auth:web'])->name('dashboard');
