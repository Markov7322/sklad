<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkladchinaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/dashboard', function () {
    $skladchinas = auth()->user()?->skladchinas()->with('category')->get() ?? collect();
    return view('dashboard', compact('skladchinas'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->post('skladchinas/{skladchina}/join', [SkladchinaController::class, 'join'])->name('skladchinas.join');
Route::resource('skladchinas', SkladchinaController::class);

Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('skladchinas', SkladchinaController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show', 'create', 'store'])->middleware('role:admin');
});

require __DIR__.'/auth.php';
