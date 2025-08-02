<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>السوق التدريجي - تطبيق IAM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
            --purple-500: #8b5cf6;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
            
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
        }
        
        .header {
            background: var(--gradient-purple);
            padding: 2rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .main-title {
            font-size: 3rem;
            font-weight: 900;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
            animation: slideDown 0.8s ease-out;
        }
        
        .subtitle {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2rem;
            animation: slideDown 0.8s ease-out 0.2s both;
        }
        
        .progress-indicator {
            display: inline-flex;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 1rem 2rem;
            gap: 0.5rem;
            animation: slideDown 0.8s ease-out 0.4s both;
        }
        
        .progress-step {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .progress-step.completed {
            background: var(--success-500);
            color: white;
        }
        
        .progress-step.current {
            background: var(--warning-500);
            color: white;
            animation: pulse 2s infinite;
        }
        
        .progress-step.locked {
            background: rgba(255,255,255,0.3);
            color: rgba(255,255,255,0.7);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .markets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .market-card {
            background: var(--bg-primary);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            transition: all 0.4s ease;
            position: relative;
            animation: slideUp 0.8s ease-out;
            animation-fill-mode: both;
        }
        
        .market-card:nth-child(1) { animation-delay: 0.1s; }
        .market-card:nth-child(2) { animation-delay: 0.2s; }
        .market-card:nth-child(3) { animation-delay: 0.3s; }
        .market-card:nth-child(4) { animation-delay: 0.4s; }
        .market-card:nth-child(5) { animation-delay: 0.5s; }
        
        .market-card.unlocked {
            cursor: pointer;
        }
        
        .market-card.unlocked:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl), 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .market-card.locked {
            opacity: 0.6;
            filter: grayscale(0.8);
        }
        
        .market-card.locked::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 2;
            border-radius: 1.5rem;
        }
        
        .market-header {
            background: var(--gradient-primary);
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
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%); }
            100% { transform: translateX(100%) translateY(100%); }
        }
        
        .market-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .market-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .market-description {
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        
        .market-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 3;
        }
        
        .status-badge {
            background: var(--success-500);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge.locked {
            background: var(--error-500);
        }
        
        .status-badge.current {
            background: var(--warning-500);
            animation: glow 2s infinite;
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.5); }
            50% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.8); }
        }
        
        .market-content {
            padding: 2rem;
        }
        
        .products-preview {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .product-mini {
            background: var(--bg-secondary);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .product-mini.purchased {
            background: linear-gradient(135deg, var(--success-500), #16a34a);
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
                @for($i = 1; $i <= 5; $i++)
                    <div class="progress-step 
                        @if(in_array($i, $userData->unlocked_markets ?? [1]))
                            @if($i == $userData->current_market_id) current @else completed @endif
                        @else
                            locked
                        @endif
                    ">
                        {{ $i }}
                    </div>
                @endfor
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="markets-grid">
            @foreach($markets as $market)
                <div class="market-card @if(in_array($market->id, $userData->unlocked_markets ?? [1])) unlocked @else locked @endif">
                    @if(!in_array($market->id, $userData->unlocked_markets ?? [1]))
                        <div class="lock-overlay">
                            <div class="lock-icon">🔒</div>
                            <div class="lock-message">سوق مقفل</div>
                            <div class="unlock-hint">أكمل السوق السابق لفتح هذا السوق</div>
                        </div>
                    @endif
                    
                    <div class="market-status">
                        <div class="status-badge 
                            @if(!in_array($market->id, $userData->unlocked_markets ?? [1]))
                                locked
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
                            @if(!in_array($market->id, $userData->unlocked_markets ?? [1]))
                                <span>🔒</span> مقفل
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
                        <p class="market-description">{{ $market->description }}</p>
                    </div>
                    
                    <div class="market-content">
                        <div class="products-preview">
                            @foreach($market->products->take(4) as $index => $product)
                                <div class="product-mini 
                                    @if(in_array($product->id, $userData->purchased_products ?? []))
                                        purchased
                                    @elseif(in_array($market->id, $userData->unlocked_markets ?? [1]))
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
                                        @elseif(in_array($market->id, $userData->unlocked_markets ?? [1]) && ($index == 0 || in_array($market->products[$index-1]->id, $userData->purchased_products ?? [])))
                                            📦
                                        @else
                                            🔒
                                        @endif
                                    </div>
                                    <div class="product-mini-name">{{ Str::limit($product->name, 15) }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(in_array($market->id, $userData->unlocked_markets ?? [1]))
                            <button class="enter-market-btn" onclick="window.location.href='{{ route('progressive.market.show', $market->id) }}'">
                                <span>دخول السوق</span>
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
