<?php

use App\Http\Controllers\ColocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SettlementController;
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
    Route::get('/colocation/create', [ColocationController::class, 'index'])->name('colocation.create');
    Route::post('/colocation', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocation/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::put('/colocation/{colocation}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::delete('/colocation/{colocation}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
    Route::post('/colocation/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::post('/colocation/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('colocations.cancel');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // invitation routes
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations', [InvitationController::class, 'search'])->name('invitations.search');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::post('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{token}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

    // categories/expenses/settlements
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('expenses', ExpenseController::class);
    Route::post('settlements', [SettlementController::class, 'store'])->name('settlements.store');
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats'])->name('admin.stats');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/ban', [AdminController::class, 'ban'])->name('admin.ban');
        Route::post('/users/{user}/unban', [AdminController::class, 'unban'])->name('admin.unban');
        Route::get('/colocations', [AdminController::class, 'colocations'])->name('admin.colocations');
        Route::get('/expenses', [AdminController::class, 'expenses'])->name('admin.expenses');
    });
});
