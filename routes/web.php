<?php

use App\Http\Controllers\Auth\WebLoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Home
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Guest routes - only for non-authenticated users
Route::middleware('guest')->group(function () {
    Route::get('login', [WebLoginController::class, 'create'])->name('login');
    Route::post('login', [WebLoginController::class, 'store'])->name('login.store');
});

// Protected routes - require authentication
Route::middleware(['auth:web,sanctum'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('wallet', function () {
        return Inertia::render('wallet/Index');
    })->name('wallet');

    Route::post('logout', [WebLoginController::class, 'destroy'])->name('logout');
});

// Debug route - remove after testing
Route::get('debug-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'user_id' => Auth::id(),
        'guard' => Auth::guard()->getName(),
    ]);
});
