

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/statistics', [HomeController::class, 'getStatistics'])->name('api.statistics');

Route::get('/profile', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    return view('profile', compact('user'));
})->middleware(['auth'])->name('profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// Statistics Route
Route::get('/statistics', [StatisticsController::class, 'index'])->middleware('auth')->name('statistics');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/wallet', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    $wallet = $user->wallet ?? $user->wallet()->create(['balance' => $user->balance ?? 0]);
    $transactions = $wallet->transactions()->latest()->take(10)->get();
    
    return view('wallet', [
        'wallet' => $wallet,
        'transactions' => $transactions,
        'deposits' => $wallet->transactions()->where('type', 'deposit')->latest()->take(5)->get(),
        'withdrawals' => $wallet->transactions()->where('type', 'withdraw')->latest()->take(5)->get(),
    ]);
})->middleware(['auth'])->name('wallet');

// Market Routes
Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
Route::get('/markets/{market}', [MarketController::class, 'show'])->name('markets.show');
Route::get('/products/{product}', [MarketController::class, 'product'])->name('products.show');

// Progressive Market Routes
Route::get('/progressive-market', [App\Http\Controllers\ProgressiveMarketController::class, 'index'])->name('progressive.market');
Route::get('/progressive-market/{market}', [App\Http\Controllers\ProgressiveMarketController::class, 'showMarket'])->name('progressive.market.show');
Route::post('/progressive-market/purchase/{product}', [App\Http\Controllers\ProgressiveMarketController::class, 'purchaseProduct'])->name('progressive.purchase');

// Shared Product Route
Route::get('/share-product/{product}', [App\Http\Controllers\ProgressiveMarketController::class, 'shareProduct'])->name('share.product');

// Keep the old market route for backward compatibility
Route::get('/market', function () {
    return redirect()->route('progressive.market');
})->middleware(['auth', 'verified'])->name('market');

// Admin upload route for slider images
Route::post('/admin/upload-slider-image', function (Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
    
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('sliders', $filename, 'public');
        
        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path)
        ]);
    }
    
    return response()->json(['error' => 'No file uploaded'], 400);
})->middleware(['auth'])->name('admin.upload.slider');

// إصلاح مسارات Livewire لدعم GET و POST
Route::match(['GET', 'POST'], '/livewire/upload-file', function (Illuminate\Http\Request $request) {
    if ($request->isMethod('GET')) {
        return response()->json([
            'message' => 'Upload endpoint ready',
            'method' => 'POST',
            'csrf_token' => csrf_token(),
            'status' => 'ready'
        ], 200);
    }
    
    // للطلبات POST، نسمح للـ request بالمرور للمعالج الافتراضي
    // لكن نحتاج للتعامل مع رفع الملفات هنا
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        // التحقق من صحة الملف
        if ($file && $file->isValid()) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('livewire-tmp', $filename, 'public');
            
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'url' => asset('storage/' . $path)
            ], 200);
        }
    }
    
    return response()->json(['error' => 'Invalid file upload'], 400);
})->middleware(['web'])->name('livewire.upload-file.custom');

Route::match(['GET', 'POST'], '/livewire/preview-file/{filename}', function ($filename, Illuminate\Http\Request $request) {
    if ($request->isMethod('GET')) {
        $path = storage_path('app/public/livewire-tmp/' . $filename);
        
        if (file_exists($path)) {
            return response()->file($path);
        }
        
        return response()->json(['error' => 'File not found'], 404);
    }
    
    return response()->json(['preview' => asset('storage/livewire-tmp/' . $filename)]);
})->middleware(['web'])->name('livewire.preview-file.custom');


