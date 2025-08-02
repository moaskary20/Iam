<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - مشاركة المنتج</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --success-500: #22c55e;
            --warning-500: #f59e0b;
            --error-500: #ef4444;
            --purple-500: #8b5cf6;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
            
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--gradient-purple);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .share-container {
            background: var(--bg-primary);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .product-header {
            background: var(--gradient-primary);
            padding: 2rem;
            text-align: center;
            color: white;
        }
        
        .product-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .product-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .product-market {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        
        .product-price {
            font-size: 2rem;
            font-weight: 800;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            display: inline-block;
        }
        
        .product-content {
            padding: 2rem;
            text-align: center;
        }
        
        .product-description {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .referral-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 2px solid #bae6fd;
        }
        
        .referral-info h3 {
            color: var(--primary-700);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .referral-info p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: grid;
            gap: 1rem;
        }
        
        .purchase-btn {
            background: linear-gradient(135deg, var(--success-500), #16a34a);
            color: white;
            border: none;
            border-radius: 1rem;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .purchase-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .back-btn {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: 1rem;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .back-btn:hover {
            background: var(--bg-primary);
            border-color: var(--primary-500);
            color: var(--primary-600);
        }
        
        .share-stats {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1rem;
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }
        
        .stat-item {
            padding: 0.5rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-600);
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
        }
        
        @media (max-width: 768px) {
            .product-header {
                padding: 1.5rem;
            }
            
            .product-content {
                padding: 1.5rem;
            }
            
            .product-icon {
                font-size: 3rem;
            }
            
            .product-name {
                font-size: 1.25rem;
            }
            
            .product-price {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="share-container">
        <div class="product-header">
            <div class="product-icon">📦</div>
            <h1 class="product-name">{{ $product->name }}</h1>
            <p class="product-market">{{ $product->market->name }}</p>
            <div class="product-price">
                {{ number_format($product->purchase_price, 2) }}
                @if($product->market_id == 5) دولار @else دولار @endif
            </div>
        </div>
        
        <div class="product-content">
            <div class="product-description">
                {{ $product->description ?? 'منتج رائع وعالي الجودة من متجرنا المميز.' }}
            </div>
            
            @if($referrerId)
                <div class="referral-info">
                    <h3>🎁 مشاركة من صديق</h3>
                    <p>هذا المنتج تم مشاركته معك من أحد أصدقائك المسوقين. عند الشراء، ستحصل على خصم وصديقك سيحصل على عمولة!</p>
                </div>
            @endif
            
            <div class="action-buttons">
                <a href="{{ route('progressive.market') }}" class="purchase-btn">
                    🛍️ اشتري الآن
                </a>
                
                <a href="{{ route('progressive.market') }}" class="back-btn">
                    ← العودة للسوق
                </a>
            </div>
            
            <div class="share-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ rand(50, 200) }}</div>
                    <div class="stat-label">مشاهدة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ rand(10, 50) }}</div>
                    <div class="stat-label">مشاركة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ rand(5, 25) }}</div>
                    <div class="stat-label">مبيعات</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
