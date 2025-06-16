<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkladchinaController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkladchinaImportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
Route::view('about', 'static.about')->name('about');
Route::view('contacts', 'static.contacts')->name('contacts');
Route::get('images/{path}', ImageController::class)
    ->where('path', '.*')
    ->name('image');

// Страница конкретной категории по slug
Route::get('categories/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $skladchinas = $user?->skladchinas()->with('category')->get() ?? collect();
    $transactions = $user?->transactions()->latest()->take(10)->get() ?? collect();
    $viewMode = request('view', 'cards');
    return view('dashboard', compact('skladchinas', 'transactions', 'viewMode'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/balance', [AccountController::class, 'balance'])->name('account.balance');
    Route::get('/transactions', [AccountController::class, 'balance'])->name('account.transactions');
    Route::get('/my-skladchinas', [AccountController::class, 'participations'])->name('account.participations');
    Route::get('/notifications', [AccountController::class, 'notifications'])->name('account.notifications');
    Route::post('/notifications', [AccountController::class, 'updateNotifications'])->name('account.notifications.update');

    Route::post('/topups', [\App\Http\Controllers\TopupController::class, 'store'])->name('topups.store');
    Route::get('/topups/{topup}/thanks', [\App\Http\Controllers\TopupController::class, 'thanks'])->name('topups.thanks');
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
    Route::get('skladchinas/{skladchina}/participants', [SkladchinaController::class, 'participants'])
        ->name('skladchinas.participants');
    Route::patch('skladchinas/{skladchina}/participants/{user}', [SkladchinaController::class, 'togglePaid'])
        ->name('skladchinas.participants.toggle');
    Route::patch('skladchinas/{skladchina}/participants/{user}/access', [SkladchinaController::class, 'updateAccess'])
        ->name('skladchinas.participants.access');

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::patch('users/{user}/ban', [UserController::class, 'toggleBan'])
        ->name('users.toggleBan')
        ->middleware('role:admin');
    Route::get('users/{user}/skladchinas', [UserController::class, 'participations'])
        ->name('users.participations')
        ->middleware('role:admin');
    Route::resource('users', UserController::class)
        ->except(['show', 'create', 'store'])
        ->middleware('role:admin');

    Route::get('topups', [\App\Http\Controllers\Admin\TopupController::class, 'index'])->name('topups.index');
    Route::patch('topups/{topup}', [\App\Http\Controllers\Admin\TopupController::class, 'update'])->name('topups.update');

    Route::get('settings', [SettingController::class, 'edit'])
        ->name('settings.edit')
        ->middleware('role:admin');
    Route::post('settings', [SettingController::class, 'update'])
        ->name('settings.update')
        ->middleware('role:admin');

    Route::get('import', [SkladchinaImportController::class, 'index'])
        ->name('import.index')
        ->middleware('role:admin');
    Route::post('import/preview', [SkladchinaImportController::class, 'preview'])
        ->name('import.preview')
        ->middleware('role:admin');
    Route::post('import/execute', [SkladchinaImportController::class, 'import'])
        ->name('import.execute')
        ->middleware('role:admin');

});


require __DIR__.'/auth.php';
