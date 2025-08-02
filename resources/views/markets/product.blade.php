<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - تطبيق IAM</title>
    
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
            --error-500: #ef4444;
            
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
        
        .product-container {
            background: var(--bg-primary);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        .product-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .product-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .market-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            display: inline-block;
            font-size: 0.9rem;
        }
        
        .product-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem;
        }
        
        .product-images {
            position: relative;
        }
        
        .image-gallery {
            display: grid;
            gap: 1rem;
        }
        
        .main-image {
            aspect-ratio: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            background: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-thumbnails {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.5rem;
        }
        
        .thumbnail {
            aspect-ratio: 1;
            border-radius: 0.25rem;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }
        
        .thumbnail.active {
            border-color: var(--primary-500);
        }
        
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .description-section {
            background: var(--bg-secondary);
            padding: 1.5rem;
            border-radius: 0.5rem;
        }
        
        .description-section h3 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }
        
        .description-section p {
            color: var(--text-secondary);
            line-height: 1.8;
        }
        
        .pricing-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .price-card {
            background: var(--bg-secondary);
            padding: 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            position: relative;
        }
        
        .price-card.purchase {
            border-top: 4px solid var(--warning-500);
        }
        
        .price-card.selling {
            border-top: 4px solid var(--success-500);
        }
        
        .price-label {
            font-size: 0.9rem;
            color: var(--text-tertiary);
            margin-bottom: 0.5rem;
        }
        
        .price-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .commissions-section {
            background: var(--primary-50);
            padding: 1.5rem;
            border-radius: 0.5rem;
        }
        
        .commissions-section h3 {
            color: var(--primary-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .commission-list {
            display: grid;
            gap: 0.75rem;
        }
        
        .commission-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .commission-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .commission-value {
            font-weight: 600;
            color: var(--primary-600);
        }
        
        .commission-amount {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .no-images {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-tertiary);
            background: var(--bg-secondary);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
        }
        
        .no-images svg {
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .product-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }
            
            .pricing-section {
                grid-template-columns: 1fr;
            }
            
            .product-header {
                padding: 1.5rem;
            }
            
            .product-header h1 {
                font-size: 1.5rem;
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
            <a href="{{ route('markets.show', $product->market) }}" class="back-btn">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للسوق
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="product-container">
            <div class="product-header">
                <h1>{{ $product->name }}</h1>
                <div class="market-badge">{{ $product->market->name }}</div>
            </div>
            
            <div class="product-content">
                <!-- Product Images -->
                <div class="product-images">
                    @if($product->images && count($product->images) > 0)
                        <div class="image-gallery">
                            <div class="main-image">
                                <img id="mainImage" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                            </div>
                            
                            @if(count($product->images) > 1)
                                <div class="image-thumbnails">
                                    @foreach($product->images as $index => $image)
                                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="no-images">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3>لا توجد صور</h3>
                            <p>لم يتم إضافة صور لهذا المنتج</p>
                        </div>
                    @endif
                </div>
                
                <!-- Product Information -->
                <div class="product-info">
                    <!-- Description -->
                    <div class="description-section">
                        <h3>وصف المنتج</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                    
                    <!-- Pricing -->
                    <div class="pricing-section">
                        <div class="price-card purchase">
                            <div class="price-label">سعر الشراء</div>
                            <div class="price-value">{{ number_format($product->purchase_price, 2) }} دولار</div>
                        </div>
                        <div class="price-card selling">
                            <div class="price-label">سعر البيع المتوقع</div>
                            <div class="price-value">{{ number_format($product->expected_selling_price, 2) }} دولار</div>
                        </div>
                    </div>
                    
                    <!-- Commissions -->
                    <div class="commissions-section">
                        <h3>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            تفاصيل العمولات
                        </h3>
                        <div class="commission-list">
                            <div class="commission-item">
                                <div class="commission-name">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    عمولة النظام
                                </div>
                                <div>
                                    <div class="commission-value">{{ $product->system_commission }}%</div>
                                    <div class="commission-amount">{{ number_format($product->system_commission_amount, 2) }} دولار</div>
                                </div>
                            </div>
                            <div class="commission-item">
                                <div class="commission-name">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    عمولة التسويق والبيع التلقائي
                                </div>
                                <div>
                                    <div class="commission-value">{{ $product->marketing_commission }}%</div>
                                    <div class="commission-amount">{{ number_format($product->marketing_commission_amount, 2) }} دولار</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function changeMainImage(src, thumbnail) {
            document.getElementById('mainImage').src = src;
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            thumbnail.classList.add('active');
        }
    </script>
</body>
</html>
