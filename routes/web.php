<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes spécifiques au Global Admin
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats'])->name('admin.stats');
        Route::post('/users/{user}/ban', [AdminController::class, 'ban'])->name('admin.ban');
    });
});
