<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $market->name }} - تطبيق IAM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-800: #075985;
            --primary-900: #0c4a6e;
            
            --success-500: #22c55e;
            --warning-500: #f59e0b;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
            
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo-icon {
            width: 2rem;
            height: 2rem;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .market-header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }
        
        .market-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .market-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .product-card {
            background: var(--bg-primary);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .product-images {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .product-images img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-images img {
            transform: scale(1.05);
        }
        
        .images-count {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
        }
        
        .product-content {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .product-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .product-prices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .price-item {
            text-align: center;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: var(--bg-secondary);
        }
        
        .price-item.purchase {
            border-right: 3px solid var(--warning-500);
        }
        
        .price-item.selling {
            border-right: 3px solid var(--success-500);
        }
        
        .price-label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            margin-bottom: 0.25rem;
        }
        
        .price-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .commissions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .commission-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.5rem;
            background: var(--primary-50);
            border-radius: 0.5rem;
            font-size: 0.9rem;
            color: var(--primary-700);
        }
        
        .commission-item svg {
            width: 1rem;
            height: 1rem;
        }
        
        .no-products {
            text-align: center;
            background: var(--bg-primary);
            padding: 3rem 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
        }
        
        .no-products svg {
            width: 4rem;
            height: 4rem;
            color: var(--text-tertiary);
            margin-bottom: 1rem;
        }
        
        .no-products h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .no-products p {
            color: var(--text-secondary);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .market-header h1 {
                font-size: 2rem;
            }
            
            .product-content {
                padding: 1rem;
            }
            
            .product-prices {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <div class="logo-icon">🚀</div>
                <span>تطبيق IAM</span>
            </a>
            <a href="{{ route('markets.index') }}" class="back-btn">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للأسواق
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="market-header">
            <h1>{{ $market->name }}</h1>
            <p>{{ $market->description }}</p>
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" class="product-card">
                    <div class="product-images">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                            @if(count($product->images) > 1)
                                <div class="images-count">
                                    {{ count($product->images) }} صور
                                </div>
                            @endif
                        @else
                            <div style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;height:100%;color:#64748b;">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->description }}</p>
                        
                        <div class="product-prices">
                            <div class="price-item purchase">
                                <div class="price-label">سعر الشراء</div>
                                <div class="price-value">{{ number_format($product->purchase_price, 2) }} دولار</div>
                            </div>
                            <div class="price-item selling">
                                <div class="price-label">سعر البيع المتوقع</div>
                                <div class="price-value">{{ number_format($product->expected_selling_price, 2) }} دولار</div>
                            </div>
                        </div>
                        
                        <div class="commissions">
                            <div class="commission-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                عمولة النظام {{ $product->system_commission }}%
                            </div>
                            <div class="commission-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                عمولة التسويق {{ $product->marketing_commission }}%
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="no-products">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3>لا توجد منتجات</h3>
                <p>لم يتم إضافة أي منتجات في هذا السوق بعد</p>
            </div>
        @endif
    </main>
</body>
</html>
