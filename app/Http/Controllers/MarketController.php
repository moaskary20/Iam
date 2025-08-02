<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Market;
use App\Models\Product;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::where('active', true)
            ->orderBy('order')
            ->withCount('products')
            ->get();
            
        return view('markets.index', compact('markets'));
    }
    
    public function show(Market $market)
    {
        $products = $market->products()
            ->where('active', true)
            ->orderBy('order')
            ->paginate(12);
            
        return view('markets.show', compact('market', 'products'));
    }
    
    public function product(Product $product)
    {
        return view('markets.product', compact('product'));
    }
}
