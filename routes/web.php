

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    $user = Auth::user();
    return view('profile', compact('user'));
})->middleware(['auth', 'verified'])->name('profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/wallet', function () {
    return view('wallet');
})->middleware(['auth', 'verified'])->name('wallet');

// Market Routes
Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
Route::get('/markets/{market}', [MarketController::class, 'show'])->name('markets.show');
Route::get('/products/{product}', [MarketController::class, 'product'])->name('products.show');

// Progressive Market Routes
Route::get('/progressive-market', [App\Http\Controllers\ProgressiveMarketController::class, 'index'])->name('progressive.market');
Route::get('/progressive-market/{market}', [App\Http\Controllers\ProgressiveMarketController::class, 'showMarket'])->name('progressive.market.show');
Route::post('/progressive-market/purchase/{product}', [App\Http\Controllers\ProgressiveMarketController::class, 'purchaseProduct'])->name('progressive.purchase');

// Keep the old market route for backward compatibility
Route::get('/market', function () {
    return redirect()->route('progressive.market');
})->middleware(['auth', 'verified'])->name('market');


