<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressiveMarketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If no user, create a demo user session
        if (!$user) {
            session(['demo_user' => [
                'current_market_id' => 1,
                'current_product_index' => 0,
                'purchased_products' => [],
                'unlocked_markets' => [1],
                'balance' => 50.00
            ]]);
        }
        
        $markets = Market::with('products')->orderBy('order')->get();
        $userData = $user ? $user : (object) session('demo_user');
        
        // Auto-unlock السوق المفتوح if user reached market 4
        if (($userData->current_market_id ?? 1) >= 4) {
            if ($user) {
                $unlockedMarkets = $user->unlocked_markets ?? [1];
                if (!in_array(5, $unlockedMarkets)) {
                    $unlockedMarkets[] = 5;
                    $user->unlocked_markets = $unlockedMarkets;
                    $user->save();
                    $userData = $user; // Update userData
                }
            } else {
                $demoUser = session('demo_user');
                if (!in_array(5, $demoUser['unlocked_markets'])) {
                    $demoUser['unlocked_markets'][] = 5;
                    session(['demo_user' => $demoUser]);
                    $userData = (object) $demoUser; // Update userData
                }
            }
        }
        
        return view('progressive-market', compact('markets', 'userData'));
    }
    
    public function showMarket($marketId)
    {
        $user = Auth::user();
        $userData = $user ? $user : (object) session('demo_user', [
            'current_market_id' => 1,
            'current_product_index' => 0,
            'purchased_products' => [],
            'unlocked_markets' => [1],
            'balance' => 50.00 // رصيد افتراضي للمستخدم التجريبي
        ]);
        
        // Check if user can access this market (السوق المفتوح متاح دائماً للعرض)
        if ($marketId != 5 && !in_array($marketId, $userData->unlocked_markets ?? [1])) {
            return redirect()->route('progressive.market')->with('error', 'لا يمكنك الوصول لهذا السوق بعد!');
        }
        
        $market = Market::with('products')->findOrFail($marketId);
        $products = $market->products()->orderBy('id')->get();
        
        return view('progressive-market-show', compact('market', 'products', 'userData'));
    }
    
    public function purchaseProduct(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            $product = Product::findOrFail($productId);
            
            if ($user) {
                // Check if user can access this product's market (السوق المفتوح متاح للعرض دائماً)
                if ($product->market_id != 5 && !in_array($product->market_id, $user->unlocked_markets ?? [1])) {
                    return response()->json(['success' => false, 'message' => 'لا يمكنك الوصول لهذا السوق بعد!'], 403);
                }
                
                // Check if user already purchased this product
                if (in_array($productId, $user->purchased_products ?? [])) {
                    return response()->json(['success' => false, 'message' => 'تم شراء هذا المنتج مسبقاً!'], 400);
                }
                
                // Special check for السوق المفتوح (market_id = 5)
                if ($product->market_id == 5) {
                    if ($user->balance < 100) {
                        return response()->json(['success' => false, 'message' => 'يجب أن يكون رصيدك 100 دولار على الأقل للشراء من السوق المفتوح!'], 400);
                    }
                    // Deduct product price from balance
                    $user->balance -= $product->purchase_price;
                }
                
                $user->purchaseProduct($productId);
                $message = 'تم شراء المنتج بنجاح! ';
                
                if ($product->market_id == 5) {
                    $message .= 'تم خصم ' . number_format($product->purchase_price, 2) . ' دولار من رصيدك.';
                } else {
                    $message .= ($user->current_market_id > $product->market_id ? 'تم فتح السوق التالي!' : '');
                }
                
            } else {
                // Handle demo user
                $demoUser = session('demo_user', [
                    'current_market_id' => 1,
                    'current_product_index' => 0,
                    'purchased_products' => [],
                    'unlocked_markets' => [1],
                    'balance' => 50.00
                ]);
                
                $purchasedProducts = $demoUser['purchased_products'] ?? [];
                
                // Check if already purchased
                if (in_array($productId, $purchasedProducts)) {
                    return response()->json(['success' => false, 'message' => 'تم شراء هذا المنتج مسبقاً!'], 400);
                }
                
                // Check if user can access this product's market (السوق المفتوح متاح للعرض دائماً)
                if ($product->market_id != 5 && !in_array($product->market_id, $demoUser['unlocked_markets'] ?? [1])) {
                    return response()->json(['success' => false, 'message' => 'لا يمكنك الوصول لهذا السوق بعد!'], 403);
                }
                
                // Special check for السوق المفتوح (market_id = 5)
                if ($product->market_id == 5) {
                    if ($demoUser['balance'] < 100) {
                        return response()->json(['success' => false, 'message' => 'يجب أن يكون رصيدك 100 دولار على الأقل للشراء من السوق المفتوح!'], 400);
                    }
                    // Deduct product price from balance
                    $demoUser['balance'] -= $product->purchase_price;
                }
                
                $purchasedProducts[] = $productId;
                $demoUser['purchased_products'] = $purchasedProducts;
                
                if ($product->market_id != 5) {
                    // Check if all products in current market are purchased (not applicable for السوق المفتوح)
                    $currentMarket = Market::find($demoUser['current_market_id']);
                    $marketProducts = $currentMarket->products()->orderBy('id')->get();
                    
                    $allPurchased = true;
                    foreach ($marketProducts as $prod) {
                        if (!in_array($prod->id, $purchasedProducts)) {
                            $allPurchased = false;
                            break;
                        }
                    }
                    
                    if ($allPurchased && $demoUser['current_market_id'] < 4) { // Changed from 5 to 4
                        $demoUser['unlocked_markets'][] = $demoUser['current_market_id'] + 1;
                        $demoUser['current_market_id']++;
                        $demoUser['current_product_index'] = 0;
                        $message = 'تم شراء المنتج بنجاح! تم فتح السوق التالي!';
                    } else {
                        $demoUser['current_product_index']++;
                        $message = 'تم شراء المنتج بنجاح!';
                    }
                    
                    // فتح السوق المفتوح عند الوصول للسوق الرابع
                    if ($demoUser['current_market_id'] == 4 && !in_array(5, $demoUser['unlocked_markets'])) {
                        $demoUser['unlocked_markets'][] = 5;
                        $message .= ' تم فتح السوق المفتوح!';
                    }
                } else {
                    $message = 'تم شراء المنتج بنجاح من السوق المفتوح! تم خصم ' . number_format($product->purchase_price, 2) . ' دولار من رصيدك.';
                }
                
                session(['demo_user' => $demoUser]);
            }
            
            return response()->json(['success' => true, 'message' => $message]);
            
        } catch (\Exception $e) {
            \Log::error('Purchase error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء المعالجة'], 500);
        }
    }
}
