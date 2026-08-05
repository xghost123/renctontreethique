<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\EnsureAdmin;

// Modern admin panel (web, role=admin) — served at /panel
Route::prefix('panel')->middleware(['web', 'auth:web', EnsureAdmin::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard', [
            'csrf_token' => csrf_token(),
        ]);
    })->name('panel.dashboard');
});
