<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $market->name }} - تطبيق IAM</title>
    
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
        }
        
        .header {
            background: var(--gradient-purple);
            padding: 1.5rem 0;
            box-shadow: var(--shadow-lg);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .market-info {
            text-align: center;
            color: white;
        }
        
        .market-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }
        
        .market-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            background: var(--bg-primary);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            transition: all 0.4s ease;
            position: relative;
            animation: slideUp 0.8s ease-out;
            animation-fill-mode: both;
        }
        
        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        
        .product-card.available {
            cursor: pointer;
        }
        
        .product-card.available:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl), 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .product-card.purchased {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 2px solid var(--success-500);
        }
        
        .product-card.insufficient-balance {
            opacity: 0.8;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 2px solid var(--warning-500);
        }
        
        .product-card.locked {
            opacity: 0.4;
            filter: grayscale(1) blur(3px);
            pointer-events: none;
            position: relative;
        }
        
        .product-card.locked::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(0,0,0,0.1),
                rgba(0,0,0,0.1) 10px,
                transparent 10px,
                transparent 20px
            );
            z-index: 1;
            border-radius: 1.5rem;
        }
        
        .product-card.locked .product-content {
            position: relative;
            z-index: 0;
        }
        
        .product-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 3;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }
        
        .status-badge.purchased {
            background: var(--success-500);
        }
        
        .status-badge.available {
            background: var(--warning-500);
            animation: glow 2s infinite;
        }
        
        .status-badge.insufficient-balance {
            background: var(--warning-500);
            animation: pulse 2s infinite;
        }
        
        .status-badge.locked {
            background: var(--error-500);
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.5); }
            50% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.8); }
        }
        
        .product-image {
            height: 200px;
            background: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card.available:hover .product-image img {
            transform: scale(1.1);
        }
        
        .no-image {
            font-size: 4rem;
            color: var(--text-tertiary);
        }
        
        .product-content {
            padding: 2rem;
        }
        
        .product-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .product-description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .pricing-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .price-item {
            text-align: center;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 0.75rem;
        }
        
        .price-label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            margin-bottom: 0.25rem;
        }
        
        .price-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-600);
        }
        
        .commission-info {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .commission-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .commission-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        
        .purchase-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .purchase-btn.available {
            background: var(--gradient-primary);
            color: white;
        }
        
        .purchase-btn.available:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .purchase-btn.purchased {
            background: var(--success-500);
            color: white;
            cursor: default;
        }
        
        .purchase-btn.insufficient-balance {
            background: var(--warning-500);
            color: white;
            cursor: not-allowed;
        }
        
        .purchase-btn.locked {
            background: #9ca3af;
            color: white;
            cursor: not-allowed;
        }
        
        .purchase-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .purchase-btn.available:hover::before {
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
            padding: 1.5rem;
            border-radius: 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .lock-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            animation: shake 2s infinite;
        }
        
        @keyframes shake {
            0%, 90%, 100% { transform: rotate(0deg); }
            93%, 97% { transform: rotate(-3deg); }
            95% { transform: rotate(3deg); }
        }
        
        .lock-message {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .unlock-hint {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
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
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .market-title {
                font-size: 1.5rem;
            }
            
            .products-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .product-content {
                padding: 1.5rem;
            }
            
            .pricing-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('progressive.market') }}" class="back-btn">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للأسواق
            </a>
            
            <div class="market-info">
                <h1 class="market-title">{{ $market->icon ?? '🏪' }} {{ $market->name }}</h1>
                <p class="market-subtitle">
                    {{ $market->description }}
                    @if($market->id == 5)
                        <br>
                        <span style="font-size: 0.9rem; background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 1rem; margin-top: 0.5rem; display: inline-block;">
                            💰 رصيدك: {{ number_format($userData->balance ?? 50, 2) }} دولار
                            @if(($userData->balance ?? 50) < 100)
                                | ⚠️ يتطلب 100 دولار للشراء
                            @endif
                        </span>
                    @endif
                </p>
            </div>
            
            <div style="width: 120px;"></div> <!-- Spacer for centering -->
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="products-container">
            @foreach($products as $index => $product)
                @php
                    $isPurchased = in_array($product->id, $userData->purchased_products ?? []);
                    $isOpenMarket = $market->id == 5; // السوق المفتوح
                    
                    if ($isOpenMarket) {
                        // في السوق المفتوح: جميع المنتجات مرئية، لكن الشراء يتطلب رصيد 100 دولار
                        $isAvailable = ($userData->balance ?? 50) >= 100 && !$isPurchased;
                        $isLocked = false; // لا توجد منتجات مقفلة في السوق المفتوح
                        $canView = true; // يمكن رؤية كل شيء
                        $insufficientBalance = ($userData->balance ?? 50) < 100 && !$isPurchased;
                    } else {
                        // في الأسواق العادية: النظام التدريجي العادي
                        $isAvailable = $index == 0 || in_array($products[$index-1]->id, $userData->purchased_products ?? []);
                        $isLocked = !$isPurchased && !$isAvailable;
                        $canView = !$isLocked;
                        $insufficientBalance = false;
                    }
                @endphp
                
                <div class="product-card 
                    @if($isPurchased) purchased 
                    @elseif($isAvailable) available 
                    @elseif($insufficientBalance) insufficient-balance
                    @else locked 
                    @endif
                ">
                    @if($isLocked)
                        <div class="lock-overlay">
                            <div class="lock-icon">🔒</div>
                            <div class="lock-message">منتج مقفل</div>
                            <div class="unlock-hint">اشترِ المنتج السابق أولاً</div>
                        </div>
                    @elseif($insufficientBalance)
                        <div class="lock-overlay" style="background: rgba(245, 158, 11, 0.9);">
                            <div class="lock-icon" style="animation: none;">💰</div>
                            <div class="lock-message">رصيد غير كافي</div>
                            <div class="unlock-hint">تحتاج {{ 100 - ($userData->balance ?? 50) }} دولار إضافي</div>
                        </div>
                    @endif
                    
                    <div class="product-status">
                        <div class="status-badge 
                            @if($isPurchased) purchased 
                            @elseif($isAvailable) available 
                            @elseif($insufficientBalance) insufficient-balance
                            @else locked 
                            @endif
                        ">
                            @if($isPurchased)
                                <span>✅</span> تم الشراء
                            @elseif($isAvailable)
                                <span>⚡</span> متاح الآن
                            @elseif($insufficientBalance)
                                <span>💰</span> رصيد غير كافي
                            @else
                                <span>🔒</span> مقفل
                            @endif
                        </div>
                    </div>
                    
                    <div class="product-image">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                        @else
                            <div class="no-image">📦</div>
                        @endif
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->description }}</p>
                        
                        <div class="pricing-section">
                            <div class="price-item">
                                <div class="price-label">سعر الشراء</div>
                                <div class="price-value">{{ number_format($product->purchase_price, 2) }} @if($isOpenMarket) دولار @else ج.م @endif</div>
                            </div>
                            <div class="price-item">
                                <div class="price-label">سعر البيع المتوقع</div>
                                <div class="price-value">{{ number_format($product->expected_selling_price, 2) }} @if($isOpenMarket) دولار @else ج.م @endif</div>
                            </div>
                        </div>
                        
                        <div class="commission-info">
                            <div class="commission-title">تفاصيل العمولات</div>
                            <div class="commission-details">
                                <span>عمولة النظام: {{ $product->system_commission }}%</span>
                                <span>عمولة التسويق: {{ $product->marketing_commission }}%</span>
                            </div>
                        </div>
                        
                        @if($isPurchased)
                            <button class="purchase-btn purchased">
                                <span>✅ تم الشراء بالفعل</span>
                            </button>
                        @elseif($isAvailable)
                            <button class="purchase-btn available" onclick="showSellMethodPopup({{ $product->id }}, {{ $product->purchase_price }}, '{{ $isOpenMarket ? "دولار" : "ج.م" }}')">
                                <span>شراء بـ {{ number_format($product->purchase_price, 2) }} @if($isOpenMarket) دولار @else ج.م @endif</span>
                            </button>
                        @elseif($insufficientBalance)
                            <button class="purchase-btn insufficient-balance">
                                <span>💰 رصيد غير كافي ({{ number_format($product->purchase_price, 2) }} دولار)</span>
                            </button>
                        @else
                            <button class="purchase-btn locked">
                                <span>🔒 غير متاح</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <script>
        function purchaseProduct(productId, button) {
            const originalText = button.innerHTML;
            const productCard = button.closest('.product-card');
            
            // Add purchase animation
            productCard.style.transform = 'scale(0.95)';
            button.innerHTML = '<span class="loading"></span> جاري المعالجة...';
            button.disabled = true;
            
            // Create success particles effect
            function createSuccessParticles() {
                const rect = productCard.getBoundingClientRect();
                for (let i = 0; i < 15; i++) {
                    const particle = document.createElement('div');
                    particle.style.cssText = `
                        position: fixed;
                        width: 8px;
                        height: 8px;
                        background: #22c55e;
                        border-radius: 50%;
                        left: ${rect.left + rect.width/2}px;
                        top: ${rect.top + rect.height/2}px;
                        z-index: 9999;
                        pointer-events: none;
                        animation: explode 1.5s ease-out forwards;
                    `;
                    
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes explode {
                            0% {
                                transform: translate(0, 0) scale(1);
                                opacity: 1;
                            }
                            100% {
                                transform: translate(${(Math.random() - 0.5) * 200}px, ${(Math.random() - 0.5) * 200}px) scale(0);
                                opacity: 0;
                            }
                        }
                    `;
                    
                    document.head.appendChild(style);
                    document.body.appendChild(particle);
                    
                    setTimeout(() => {
                        particle.remove();
                        style.remove();
                    }, 1500);
                }
            }
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (!token) {
                console.error('CSRF token not found');
                productCard.style.transform = 'scale(1)';
                button.innerHTML = originalText;
                button.disabled = false;
                alert('خطأ في الأمان - يرجى إعادة تحميل الصفحة');
                return;
            }
            
            fetch(`/progressive-market/purchase/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success animation
                    createSuccessParticles();
                    productCard.style.transform = 'scale(1)';
                    productCard.style.background = 'linear-gradient(135deg, #f0fdf4, #dcfce7)';
                    productCard.style.border = '2px solid #22c55e';
                    
                    button.innerHTML = '<span>✅ تم الشراء بنجاح</span>';
                    button.className = 'purchase-btn purchased';
                    
                    // Update status badge
                    const statusBadge = productCard.querySelector('.status-badge');
                    statusBadge.innerHTML = '<span>✅</span> تم الشراء';
                    statusBadge.className = 'status-badge purchased';
                    
                    // Show success message with animation
                    const successMessage = document.createElement('div');
                    successMessage.style.cssText = `
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background: linear-gradient(135deg, #22c55e, #16a34a);
                        color: white;
                        padding: 2rem;
                        border-radius: 1rem;
                        font-size: 1.2rem;
                        font-weight: 600;
                        z-index: 10000;
                        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                        animation: successPop 0.5s ease-out;
                    `;
                    
                    const successStyle = document.createElement('style');
                    successStyle.textContent = `
                        @keyframes successPop {
                            0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
                            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
                        }
                    `;
                    
                    document.head.appendChild(successStyle);
                    successMessage.textContent = data.message;
                    document.body.appendChild(successMessage);
                    
                    setTimeout(() => {
                        successMessage.style.animation = 'successPop 0.5s ease-out reverse';
                        setTimeout(() => {
                            successMessage.remove();
                            successStyle.remove();
                            // Reload page to update UI
                            window.location.reload();
                        }, 500);
                    }, 2000);
                    
                } else {
                    throw new Error(data.message || 'فشل في شراء المنتج');
                }
            })
            .catch(error => {
                console.error('Purchase error:', error);
                productCard.style.transform = 'scale(1)';
                button.innerHTML = originalText;
                button.disabled = false;
                
                let errorMessage = 'حدث خطأ أثناء الشراء';
                if (error.message.includes('404')) {
                    errorMessage = 'المنتج غير موجود';
                } else if (error.message.includes('500')) {
                    errorMessage = 'خطأ في الخادم - يرجى المحاولة لاحقاً';
                } else if (error.message.includes('419')) {
                    errorMessage = 'انتهت صلاحية الجلسة - يرجى إعادة تحميل الصفحة';
                }
                
                alert(errorMessage);
            });
        }
        
        // Add shimmer effect to available products
        document.addEventListener('DOMContentLoaded', function() {
            const availableCards = document.querySelectorAll('.product-card.available');
            
            availableCards.forEach((card, index) => {
                // Add subtle breathing animation
                card.style.animationDelay = `${index * 0.5}s`;
                card.style.animation = 'breathe 3s ease-in-out infinite';
            });
            
            // Add breathing animation style
            const breatheStyle = document.createElement('style');
            breatheStyle.textContent = `
                @keyframes breathe {
                    0%, 100% { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
                    50% { box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.06), 0 0 30px rgba(245, 158, 11, 0.2); }
                }
            `;
            document.head.appendChild(breatheStyle);
        });

        // Sell Method Popup Functions
        function showSellMethodPopup(productId, productPrice, currency) {
            currentProductId = productId;
            currentProductPrice = productPrice;
            currentCurrency = currency;
            
            const marketingFee = productPrice * 0.1;
            const systemCommission = productPrice * 0.05;
            const totalFees = marketingFee + systemCommission;
            
            document.getElementById('method-shipping-price').textContent = `${productPrice.toFixed(2)} ${currency}`;
            document.getElementById('method-ai-price').textContent = `${totalFees.toFixed(2)} ${currency}`;
            document.getElementById('method-social-price').textContent = `${totalFees.toFixed(2)} ${currency}`;
            
            document.getElementById('sellMethodPopup').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeSellMethodPopup() {
            document.getElementById('sellMethodPopup').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function selectSellMethod(method) {
            if (method === 'shipping') {
                closeSellMethodPopup();
                showShippingPopup();
            } else if (method === 'ai') {
                closeSellMethodPopup();
                processPurchase(currentProductId, 'ai');
            } else if (method === 'social') {
                closeSellMethodPopup();
                processPurchase(currentProductId, 'social');
            }
        }
        
        function showShippingPopup() {
            document.getElementById('shippingPopup').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeShippingPopup() {
            document.getElementById('shippingPopup').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function processShippingOrder() {
            const phone = document.getElementById('phone').value;
            const country = document.getElementById('country').value;
            const address = document.getElementById('address').value;
            const paymentMethod = document.getElementById('payment-method').value;
            
            if (!phone || !country || !address || !paymentMethod) {
                alert('يرجى ملء جميع الحقول');
                return;
            }
            
            closeShippingPopup();
            processPurchase(currentProductId, 'shipping', {
                phone, country, address, paymentMethod
            });
        }
        
        let currentProductId = null;
        let currentProductPrice = null;
        let currentCurrency = null;
        
        function processPurchase(productId, sellMethod, shippingData = null) {
            const formData = new FormData();
            formData.append('sell_method', sellMethod);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            if (shippingData) {
                Object.keys(shippingData).forEach(key => {
                    formData.append(key, shippingData[key]);
                });
            }
            
            fetch(`/progressive-market/purchase/${productId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.share_link) {
                        showShareLinkPopup(data.share_link);
                    } else {
                        alert(data.message);
                        location.reload();
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء المعالجة');
            });
        }
        
        function showShareLinkPopup(shareLink) {
            document.getElementById('shareLink').value = shareLink;
            document.getElementById('shareLinkPopup').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeShareLinkPopup() {
            document.getElementById('shareLinkPopup').style.display = 'none';
            document.body.style.overflow = 'auto';
            location.reload();
        }
        
        function copyShareLink() {
            const shareLink = document.getElementById('shareLink');
            shareLink.select();
            document.execCommand('copy');
            alert('تم نسخ الرابط!');
        }
        
        function shareToWhatsApp() {
            const shareLink = document.getElementById('shareLink').value;
            const message = `🛍️ منتج رائع للبيع!\n\nتفضل بزيارة الرابط:\n${shareLink}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }
        
        function shareToFacebook() {
            const shareLink = document.getElementById('shareLink').value;
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareLink)}`, '_blank');
        }
        
        function shareToTwitter() {
            const shareLink = document.getElementById('shareLink').value;
            const message = `🛍️ منتج رائع للبيع! تفضل بزيارة الرابط:`;
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(message)}&url=${encodeURIComponent(shareLink)}`, '_blank');
        }
    </script>

    <!-- Sell Method Selection Popup -->
    <div id="sellMethodPopup" class="popup-overlay">
        <div class="popup-content sell-method-popup">
            <div class="popup-header">
                <h3>🛍️ اختر طريقة البيع</h3>
                <button class="close-btn" onclick="closeSellMethodPopup()">×</button>
            </div>
            
            <div class="sell-methods">
                <div class="sell-method-card" onclick="selectSellMethod('shipping')">
                    <div class="method-icon">🚚</div>
                    <h4>الشحن للمنزل</h4>
                    <p>شراء المنتج وشحنه إلى عنوانك</p>
                    <div class="method-price">
                        <span id="method-shipping-price">0.00 ج.م</span>
                    </div>
                </div>
                
                <div class="sell-method-card" onclick="selectSellMethod('ai')">
                    <div class="method-icon">🤖</div>
                    <h4>البيع بالذكاء الاصطناعي</h4>
                    <p>رسوم التسويق والعمولة فقط</p>
                    <div class="method-price">
                        <span id="method-ai-price">0.00 ج.م</span>
                        <small>(رسوم فقط)</small>
                    </div>
                </div>
                
                <div class="sell-method-card" onclick="selectSellMethod('social')">
                    <div class="method-icon">📱</div>
                    <h4>البيع عبر السوشيال ميديا</h4>
                    <p>احصل على رابط للمشاركة</p>
                    <div class="method-price">
                        <span id="method-social-price">0.00 ج.م</span>
                        <small>(رسوم فقط)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Details Popup -->
    <div id="shippingPopup" class="popup-overlay">
        <div class="popup-content shipping-popup">
            <div class="popup-header">
                <h3>🚚 تفاصيل الشحن</h3>
                <button class="close-btn" onclick="closeShippingPopup()">×</button>
            </div>
            
            <form class="shipping-form">
                <div class="form-group">
                    <label>📱 رقم الهاتف</label>
                    <input type="tel" id="phone" placeholder="أدخل رقم هاتفك" required>
                </div>
                
                <div class="form-group">
                    <label>🌍 البلد</label>
                    <select id="country" required>
                        <option value="">اختر البلد</option>
                        <option value="مصر">مصر</option>
                        <option value="السعودية">السعودية</option>
                        <option value="الإمارات">الإمارات</option>
                        <option value="الكويت">الكويت</option>
                        <option value="قطر">قطر</option>
                        <option value="البحرين">البحرين</option>
                        <option value="عمان">عمان</option>
                        <option value="الأردن">الأردن</option>
                        <option value="لبنان">لبنان</option>
                        <option value="المغرب">المغرب</option>
                        <option value="تونس">تونس</option>
                        <option value="الجزائر">الجزائر</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>📍 العنوان</label>
                    <textarea id="address" placeholder="أدخل عنوانك التفصيلي" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>💳 طريقة الدفع</label>
                    <select id="payment-method" required>
                        <option value="">اختر طريقة الدفع</option>
                        <option value="credit-card">بطاقة ائتمان</option>
                        <option value="cash-on-delivery">الدفع عند الاستلام</option>
                        <option value="bank-transfer">حوالة بنكية</option>
                        <option value="mobile-wallet">محفظة إلكترونية</option>
                    </select>
                </div>
                
                <button type="button" class="confirm-order-btn" onclick="processShippingOrder()">
                    ✅ تأكيد الطلب
                </button>
            </form>
        </div>
    </div>

    <!-- Share Link Popup -->
    <div id="shareLinkPopup" class="popup-overlay">
        <div class="popup-content share-popup">
            <div class="popup-header">
                <h3>📱 رابط المشاركة</h3>
                <button class="close-btn" onclick="closeShareLinkPopup()">×</button>
            </div>
            
            <div class="share-content">
                <p>تم إنشاء رابط المشاركة بنجاح! شارك هذا الرابط للبدء في البيع:</p>
                
                <div class="link-container">
                    <input type="text" id="shareLink" readonly>
                    <button onclick="copyShareLink()" class="copy-btn">📋 نسخ</button>
                </div>
                
                <div class="share-buttons">
                    <button onclick="shareToWhatsApp()" class="share-whatsapp">
                        💬 واتساب
                    </button>
                    <button onclick="shareToFacebook()" class="share-facebook">
                        📘 فيسبوك
                    </button>
                    <button onclick="shareToTwitter()" class="share-twitter">
                        🐦 تويتر
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        
        .popup-content {
            background: white;
            border-radius: 1rem;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: popupSlide 0.3s ease-out;
        }
        
        @keyframes popupSlide {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .popup-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 1.25rem;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0.5rem;
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-btn:hover {
            background: #f3f4f6;
        }
        
        .sell-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
        }
        
        .sell-method-card {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .sell-method-card:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
        }
        
        .method-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .sell-method-card h4 {
            margin: 0.5rem 0;
            color: #1f2937;
            font-size: 1rem;
        }
        
        .sell-method-card p {
            color: #6b7280;
            font-size: 0.8rem;
            margin: 0.5rem 0;
        }
        
        .method-price {
            margin-top: 1rem;
            font-weight: 600;
            color: #059669;
        }
        
        .method-price small {
            display: block;
            color: #6b7280;
            font-weight: normal;
            font-size: 0.7rem;
        }
        
        .shipping-form {
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .confirm-order-btn {
            width: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .confirm-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        
        .share-content {
            padding: 1.5rem;
        }
        
        .share-content p {
            margin-bottom: 1rem;
            color: #6b7280;
            text-align: center;
        }
        
        .link-container {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .link-container input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-family: 'Cairo', sans-serif;
            background: #f9fafb;
        }
        
        .copy-btn {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .copy-btn:hover {
            background: #2563eb;
        }
        
        .share-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        
        .share-buttons button {
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            font-weight: 600;
        }
        
        .share-whatsapp {
            background: #25d366;
            color: white;
        }
        
        .share-facebook {
            background: #1877f2;
            color: white;
        }
        
        .share-twitter {
            background: #1da1f2;
            color: white;
        }
        
        .share-buttons button:hover {
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .sell-methods {
                grid-template-columns: 1fr;
            }
            
            .share-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
