

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\SkrillController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ServerTestController;
use App\Http\Controllers\CloudflareTestController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/statistics', [HomeController::class, 'getStatistics'])
    ->name('api.statistics')
    ->middleware('web');

// Server Test Routes
Route::get('/test-server', [ServerTestController::class, 'showTestPage'])->name('test.server');
Route::get('/test-upload-api', [ServerTestController::class, 'testUpload'])->name('test.upload.api');

// Cloudflare Test Routes
Route::get('/test-cloudflare', [CloudflareTestController::class, 'showTestPage'])->name('test.cloudflare');
Route::get('/test-cloudflare-api', [CloudflareTestController::class, 'testCloudflare'])->name('test.cloudflare.api');

// Admin User Creation Routes
Route::get('/admin/create-user', [AdminUserController::class, 'showCreateForm'])->name('admin.create-user-form');
Route::post('/admin/create-user', [AdminUserController::class, 'createUser'])->name('admin.create-user');

// Test PayPal route
Route::get('/test-paypal-debug', function () {
    return response()->json([
        'status' => 'success',
        'paypal_client_id_exists' => !empty(env('PAYPAL_CLIENT_ID')),
        'paypal_client_secret_exists' => !empty(env('PAYPAL_CLIENT_SECRET')),
        'paypal_mode' => env('PAYPAL_MODE', 'not_set'),
        'test_time' => now()->toDateTimeString()
    ]);
});

// Test PayPal create payment without CSRF for testing
Route::post('/test-paypal-create', function (Illuminate\Http\Request $request) {
    try {
        $controller = new App\Http\Controllers\PayPalController();
        $response = $controller->createPayment($request);
        
        return $response;
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ في الاختبار: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->withoutMiddleware(['web', 'csrf']);

// Simple PayPal test route
Route::get('/simple-paypal-test', function () {
    try {
        echo "<h1>🔍 اختبار PayPal في Laravel</h1>";
        
        echo "<div style='background: #f0f8ff; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>متغيرات البيئة:</h3>";
        echo "PAYPAL_CLIENT_ID: " . (env('PAYPAL_CLIENT_ID') ? '✅ موجود (' . substr(env('PAYPAL_CLIENT_ID'), 0, 15) . '...)' : '❌ غير موجود') . "<br>";
        echo "PAYPAL_CLIENT_SECRET: " . (env('PAYPAL_CLIENT_SECRET') ? '✅ موجود (' . substr(env('PAYPAL_CLIENT_SECRET'), 0, 15) . '...)' : '❌ غير موجود') . "<br>";
        echo "PAYPAL_MODE: " . (env('PAYPAL_MODE') ?: 'غير محدد') . "<br>";
        echo "</div>";
        
        // اختبار PayPalService
        echo "<div style='background: #f0fff0; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>اختبار PayPalService:</h3>";
        
        try {
            $paypalService = new App\Services\PayPalService();
            echo "✅ تم إنشاء PayPalService بنجاح<br>";
            
            $token = $paypalService->getAccessToken();
            echo "✅ تم الحصول على Access Token: " . substr($token, 0, 20) . "...<br>";
            
        } catch (Exception $e) {
            echo "❌ فشل في PayPalService: " . $e->getMessage() . "<br>";
        }
        echo "</div>";
    
    } catch (Exception $e) {
        echo "<div style='background: #ffeeee; padding: 15px; margin: 10px 0; border-radius: 5px; color: red;'>";
        echo "❌ خطأ عام: " . $e->getMessage();
        echo "</div>";
    }
});

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
        'withdrawals' => $user->withdrawals()->latest()->take(5)->get(), // استخدام جدول withdrawals بدلاً من transactions
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

// PayPal Routes
Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment'])->name('paypal.create');
Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

// Skrill Routes
Route::post('/skrill/create-payment', [SkrillController::class, 'createPayment'])->name('skrill.create');
Route::get('/skrill/success', [SkrillController::class, 'success'])->name('skrill.success');
Route::get('/skrill/cancel', [SkrillController::class, 'cancel'])->name('skrill.cancel');
Route::post('/skrill/status', [SkrillController::class, 'status'])->name('skrill.status');

// Withdrawal Routes
Route::post('/withdrawal/request', [WithdrawalController::class, 'store'])->middleware('auth')->name('withdrawal.request');

// Deposit page
Route::get('/deposit', function () {
    return view('deposit');
})->middleware('auth')->name('deposit');



