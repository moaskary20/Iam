<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>السوق التدريجي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-50: #eff6ff;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --warning-500: #f59e0b;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--gradient-primary);
            min-height: 100vh;
            color: var(--gray-900);
            direction: rtl;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 2rem 0;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .main-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideDown 1s ease-out;
        }
        
        .subtitle {
            font-size: 1.2rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            animation: slideUp 1s ease-out;
        }
        
        .progress-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 1rem 2rem;
            margin: 0 auto;
            max-width: fit-content;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .progress-step {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .progress-step.current {
            background: var(--gradient-warning);
            color: white;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.5);
            animation: pulse 2s infinite;
        }
        
        .progress-step.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
        }
        
        .progress-step.locked {
            background: rgba(107, 114, 128, 0.3);
            color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(5px);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(245, 158, 11, 0.5); }
            50% { transform: scale(1.1); box-shadow: 0 0 30px rgba(245, 158, 11, 0.8); }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .markets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            animation: slideUp 1s ease-out 0.5s both;
        }
        
        .market-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .market-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .market-card.locked {
            opacity: 0.7;
            filter: grayscale(30%);
        }
        
        .market-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
        }
        
        .status-badge {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge.current {
            background: var(--gradient-warning);
            animation: pulse 2s infinite;
        }
        
        .status-badge.completed {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .status-badge.locked {
            background: rgba(107, 114, 128, 0.8);
        }
        
        .market-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .market-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-conic-gradient(from 0deg at 50% 50%, transparent 0deg 10deg, rgba(255,255,255,0.1) 10deg 20deg);
            animation: rotate 20s linear infinite;
            opacity: 0.3;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .market-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .market-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .market-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }
        
        .market-content {
            padding: 2rem;
        }
        
        .products-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .product-mini {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .product-mini::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            transition: left 0.5s ease;
        }
        
        .product-mini:hover::before {
            left: 100%;
        }
        
        .product-mini.purchased {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .product-mini.current {
            background: linear-gradient(135deg, var(--warning-500), #d97706);
            color: white;
            animation: highlight 2s infinite;
        }
        
        .product-mini.locked {
            background: #f1f5f9;
            opacity: 0.5;
        }
        
        @keyframes highlight {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .product-mini-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .product-mini-name {
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .enter-market-btn {
            width: 100%;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .enter-market-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        
        .enter-market-btn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .enter-market-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .enter-market-btn:not(:disabled):hover::before {
            left: 100%;
        }
        
        .lock-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .lock-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: shake 2s infinite;
        }
        
        @keyframes shake {
            0%, 90%, 100% { transform: rotate(0deg); }
            93%, 97% { transform: rotate(-3deg); }
            95% { transform: rotate(3deg); }
        }
        
        .lock-message {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .unlock-hint {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        @media (max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }
            
            .markets-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .market-header {
                padding: 1.5rem;
            }
            
            .market-content {
                padding: 1.5rem;
            }
            
            .progress-indicator {
                padding: 0.75rem 1.5rem;
            }
            
            .progress-step {
                width: 1.5rem;
                height: 1.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1 class="main-title">🏪 السوق التدريجي</h1>
            <p class="subtitle">اشترِ من كل سوق لفتح السوق التالي</p>
            
            <div class="progress-indicator">
                @php
                    $unlockedMarkets = is_string($userData->unlocked_markets ?? [1]) 
                        ? json_decode($userData->unlocked_markets, true) 
                        : ($userData->unlocked_markets ?? [1]);
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    <div class="progress-step 
                        @if($i == 5)
                            @if(in_array(5, $unlockedMarkets) || ($userData->current_market_id ?? 1) >= 4) completed @else locked @endif
                        @elseif(in_array($i, $unlockedMarkets))
                            @if($i == $userData->current_market_id) current @else completed @endif
                        @else
                            locked
                        @endif
                    ">
                        @if($i == 5)
                            🏪
                        @else
                            {{ $i }}
                        @endif
                    </div>
                @endfor
            </div>
            
            <!-- عرض الرصيد -->
            <div class="balance-display" style="margin-top: 1rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 1rem; padding: 1rem 2rem; display: inline-block;">
                <div style="color: white; font-size: 1rem; font-weight: 600;">
                    💰 رصيدك الحالي: {{ number_format($userData->balance ?? 50, 2) }} دولار
                </div>
                @if(($userData->balance ?? 50) < 100)
                    <div style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin-top: 0.25rem;">
                        تحتاج 100 دولار للشراء من السوق المفتوح
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="markets-grid">
            @foreach($markets as $market)
                @php
                    $isOpenMarket = $market->id == 5; // السوق المفتوح
                    $unlockedMarkets = is_string($userData->unlocked_markets ?? [1]) 
                        ? json_decode($userData->unlocked_markets, true) 
                        : ($userData->unlocked_markets ?? [1]);
                    $canAccess = $isOpenMarket ? true : in_array($market->id, $unlockedMarkets); // السوق المفتوح متاح دائماً للعرض
                @endphp
                
                <div class="market-card @if($canAccess) unlocked @else locked @endif">
                    @if(!$canAccess)
                        <div class="lock-overlay">
                            <div class="lock-icon">🔒</div>
                            <div class="lock-message">سوق مقفل</div>
                            <div class="unlock-hint">أكمل السوق السابق لفتح هذا السوق</div>
                        </div>
                    @endif
                    
                    <div class="market-status">
                        <div class="status-badge 
                            @if(!$canAccess)
                                locked
                            @elseif($isOpenMarket)
                                @if(($userData->balance ?? 50) >= 100) current @else locked @endif
                            @elseif($market->id == $userData->current_market_id)
                                current
                            @else
                                @php
                                    $allPurchased = true;
                                    foreach($market->products as $product) {
                                        if(!in_array($product->id, $userData->purchased_products ?? [])) {
                                            $allPurchased = false;
                                            break;
                                        }
                                    }
                                @endphp
                                @if($allPurchased) completed @else current @endif
                            @endif
                        ">
                            @if(!$canAccess)
                                <span>🔒</span> مقفل
                            @elseif($isOpenMarket)
                                @if(($userData->balance ?? 50) >= 100)
                                    <span>🛒</span> متاح للشراء
                                @else
                                    <span>👁️</span> للعرض فقط
                                @endif
                            @elseif($market->id == $userData->current_market_id)
                                <span>⚡</span> نشط
                            @else
                                @php
                                    $allPurchased = true;
                                    foreach($market->products as $product) {
                                        if(!in_array($product->id, $userData->purchased_products ?? [])) {
                                            $allPurchased = false;
                                            break;
                                        }
                                    }
                                @endphp
                                @if($allPurchased)
                                    <span>✅</span> مكتمل
                                @else
                                    <span>⚡</span> نشط
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <div class="market-header">
                        <div class="market-icon">{{ $market->icon ?? '🏪' }}</div>
                        <h3 class="market-name">{{ $market->name }}</h3>
                        <p class="market-description">
                            {{ $market->description }}
                            @if($isOpenMarket)
                                <br><small style="color: rgba(255,255,255,0.8);">
                                    مفتوح للعرض دائماً - يتطلب رصيد 100 دولار للشراء
                                </small>
                            @endif
                        </p>
                    </div>
                    
                    <div class="market-content">
                        <div class="products-preview">
                            @foreach($market->products->take(4) as $index => $product)
                                <div class="product-mini 
                                    @if(in_array($product->id, $userData->purchased_products ?? []))
                                        purchased
                                    @elseif($isOpenMarket)
                                        @if(($userData->balance ?? 50) >= 100) current @else locked @endif
                                    @elseif($canAccess)
                                        @if($index == 0 || in_array($market->products[$index-1]->id, $userData->purchased_products ?? []))
                                            current
                                        @else
                                            locked
                                        @endif
                                    @else
                                        locked
                                    @endif
                                ">
                                    <div class="product-mini-icon">
                                        @if(in_array($product->id, $userData->purchased_products ?? []))
                                            ✅
                                        @elseif($isOpenMarket)
                                            @if(($userData->balance ?? 50) >= 100) 💰 @else 👁️ @endif
                                        @elseif($canAccess && ($index == 0 || in_array($market->products[$index-1]->id, $userData->purchased_products ?? [])))
                                            📦
                                        @else
                                            🔒
                                        @endif
                                    </div>
                                    <div class="product-mini-name">{{ Str::limit($product->name, 15) }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($canAccess)
                            <button class="enter-market-btn" onclick="window.location.href='{{ route('progressive.market.show', $market->id) }}'">
                                @if($isOpenMarket)
                                    <span>
                                        @if(($userData->balance ?? 50) >= 100)
                                            💰 دخول السوق المفتوح
                                        @else
                                            👁️ تصفح السوق المفتوح
                                        @endif
                                    </span>
                                @else
                                    <span>دخول السوق</span>
                                @endif
                            </button>
                        @else
                            <button class="enter-market-btn" disabled>
                                <span>🔒 سوق مقفل</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>
