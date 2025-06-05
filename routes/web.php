<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkladchinaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $skladchinas = $user?->skladchinas()->with('category')->get() ?? collect();
    $transactions = $user?->transactions()->latest()->take(10)->get() ?? collect();
    return view('dashboard', compact('skladchinas', 'transactions'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->post('skladchinas/{skladchina}/join', [SkladchinaController::class, 'join'])->name('skladchinas.join');
Route::middleware('auth')->post('skladchinas/{skladchina}/pay', [SkladchinaController::class, 'pay'])->name('skladchinas.pay');
Route::middleware('auth')->post('skladchinas/{skladchina}/renew', [SkladchinaController::class, 'renew'])->name('skladchinas.renew');
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('skladchinas', [SkladchinaController::class, 'my'])->name('skladchinas.index');
});
Route::middleware(['auth','role:admin,moderator,organizer'])->group(function () {
    Route::resource('skladchinas', SkladchinaController::class)->except(['index','show']);
});
Route::resource('skladchinas', SkladchinaController::class)->only(['index','show']);

Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('skladchinas', SkladchinaController::class)->except(['show']);
    Route::get('skladchinas/{skladchina}/participants', [SkladchinaController::class, 'participants'])->name('skladchinas.participants');
    Route::patch('skladchinas/{skladchina}/participants/{user}', [SkladchinaController::class, 'togglePaid'])->name('skladchinas.participants.toggle');
    Route::patch('skladchinas/{skladchina}/participants/{user}/access', [SkladchinaController::class, 'updateAccess'])->name('skladchinas.participants.access');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::patch('users/{user}/ban', [UserController::class, 'toggleBan'])->name('users.toggleBan')->middleware('role:admin');
    Route::get('users/{user}/skladchinas', [UserController::class, 'participations'])->name('users.participations')->middleware('role:admin');
    Route::resource('users', UserController::class)->except(['show', 'create', 'store'])->middleware('role:admin');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit')->middleware('role:admin');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('role:admin');
});

require __DIR__.'/auth.php';
