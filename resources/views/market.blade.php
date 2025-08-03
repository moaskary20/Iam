@extends('layouts.app')

@section('title', 'السوق')

@section('content')
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>السوق</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 80px; /* مسافة أسفل الصفحة للمنيو السفلي */
        }

        /* Animated Background */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 120px;
            height: 120px;
            left: 10%;
            top: 15%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 180px;
            height: 180px;
            right: 10%;
            top: 50%;
            animation-delay: 3s;
        }

        .shape:nth-child(3) {
            width: 90px;
            height: 90px;
            left: 70%;
            top: 20%;
            animation-delay: 6s;
        }

        .shape:nth-child(4) {
            width: 150px;
            height: 150px;
            right: 60%;
            bottom: 15%;
            animation-delay: 9s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) translateX(0px) rotate(0deg);
                opacity: 0.3;
            }
            25% { 
                transform: translateY(-40px) translateX(30px) rotate(90deg);
                opacity: 0.6;
            }
            50% { 
                transform: translateY(20px) translateX(-20px) rotate(180deg);
                opacity: 0.4;
            }
            75% { 
                transform: translateY(-20px) translateX(40px) rotate(270deg);
                opacity: 0.5;
            }
        }

        .container {
            max-width: 450px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 35px;
            animation: slideDown 0.8s ease-out;
        }

        .market-title {
            color: white;
            font-size: 36px;
            font-weight: 900;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            margin-bottom: 10px;
            position: relative;
        }

        .market-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            border-radius: 2px;
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0%, 100% { opacity: 0.7; transform: translateX(-50%) scaleX(1); }
            50% { opacity: 1; transform: translateX(-50%) scaleX(1.2); }
        }

        .market-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            font-weight: 400;
        }

        /* Locked Items Section */
        .locked-section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .locked-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: sweep 3s infinite;
        }

        @keyframes sweep {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .locked-items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .locked-item {
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            position: relative;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            animation: slideUp 0.8s ease-out;
            animation-fill-mode: both;
            overflow: hidden;
        }

        .locked-item:nth-child(1) { animation-delay: 0.2s; }
        .locked-item:nth-child(2) { animation-delay: 0.3s; }
        .locked-item:nth-child(3) { animation-delay: 0.4s; }
        .locked-item:nth-child(4) { animation-delay: 0.5s; }

        .locked-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .locked-item:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 40px rgba(255, 107, 107, 0.4);
        }

        .locked-item:hover::before {
            background: rgba(0,0,0,0.2);
        }

        .lock-icon {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            animation: lockShake 2s infinite;
        }

        @keyframes lockShake {
            0%, 90%, 100% { transform: rotate(0deg); }
            93%, 97% { transform: rotate(-2deg); }
            95% { transform: rotate(2deg); }
        }

        .locked-price {
            font-size: 18px;
            font-weight: 700;
            color: white;
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }

        .unlock-text {
            font-size: 12px;
            color: rgba(255,255,255,0.9);
            margin-top: 5px;
            position: relative;
            z-index: 2;
        }

        /* Open Market Section */
        .open-market {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }

        .market-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .market-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .product-card:nth-child(1) { animation-delay: 0.8s; }
        .product-card:nth-child(2) { animation-delay: 0.9s; }
        .product-card:nth-child(3) { animation-delay: 1.0s; }
        .product-card:nth-child(4) { animation-delay: 1.1s; }
        .product-card:nth-child(5) { animation-delay: 1.2s; }
        .product-card:nth-child(6) { animation-delay: 1.3s; }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .product-card:hover::before {
            left: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .product-name {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            text-align: center;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            text-align: center;
            line-height: 1.4;
        }

        .product-price {
            font-size: 20px;
            font-weight: 900;
            color: #4facfe;
            text-align: center;
            margin-bottom: 15px;
        }

        .buy-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .buy-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .buy-btn:hover::before {
            left: 100%;
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .buy-btn:active {
            transform: translateY(0);
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: floatParticle 8s linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Animations */
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            .market-title {
                font-size: 28px;
            }
            .locked-items {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .locked-section, .open-market {
                padding: 20px;
                border-radius: 20px;
            }
            .products-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .lock-icon {
                font-size: 35px;
            }
            .locked-price {
                font-size: 16px;
            }
        }

        /* Loading states */
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
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="market-title">🛒 السوق</h1>
            <p class="market-subtitle">اكتشف أفضل العروض والمنتجات</p>
        </div>

        <!-- Locked Items Section -->
        <div class="locked-section">
            <h2 class="section-title">
                <span>🔐</span>
                العروض المحدودة
            </h2>
            <div class="locked-items">
                <div class="locked-item" onclick="unlockItem(10)">
                    <div class="lock-icon">🔒</div>
                    <div class="locked-price">$10</div>
                    <div class="unlock-text">اضغط للفتح</div>
                </div>
                <div class="locked-item" onclick="unlockItem(20)">
                    <div class="lock-icon">🔒</div>
                    <div class="locked-price">$20</div>
                    <div class="unlock-text">اضغط للفتح</div>
                </div>
                <div class="locked-item" onclick="unlockItem(30)">
                    <div class="lock-icon">🔒</div>
                    <div class="locked-price">$30</div>
                    <div class="unlock-text">اضغط للفتح</div>
                </div>
                <div class="locked-item" onclick="unlockItem(40)">
                    <div class="lock-icon">🔒</div>
                    <div class="locked-price">$40</div>
                    <div class="unlock-text">اضغط للفتح</div>
                </div>
            </div>
        </div>

        <!-- Open Market Section -->
        <div class="open-market">
            <div class="market-header">
                <span style="font-size: 28px;">🏪</span>
                <h3>السوق المفتوح</h3>
            </div>
            
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-icon">📱</div>
                    <div class="product-name">هاتف ذكي</div>
                    <div class="product-description">أحدث التقنيات مع كاميرا عالية الجودة</div>
                    <div class="product-price">$299.99</div>
                    <button class="buy-btn" onclick="buyProduct('هاتف ذكي', 299.99)">شراء الآن</button>
                </div>
                
                <div class="product-card">
                    <div class="product-icon">💻</div>
                    <div class="product-name">لابتوب محمول</div>
                    <div class="product-description">معالج قوي للعمل والألعاب</div>
                    <div class="product-price">$599.99</div>
                    <button class="buy-btn" onclick="buyProduct('لابتوب محمول', 599.99)">شراء الآن</button>
                </div>
                
                <div class="product-card">
                    <div class="product-icon">🎧</div>
                    <div class="product-name">سماعات لاسلكية</div>
                    <div class="product-description">صوت نقي مع إلغاء الضوضاء</div>
                    <div class="product-price">$89.99</div>
                    <button class="buy-btn" onclick="buyProduct('سماعات لاسلكية', 89.99)">شراء الآن</button>
                </div>
                
                <div class="product-card">
                    <div class="product-icon">⌚</div>
                    <div class="product-name">ساعة ذكية</div>
                    <div class="product-description">تتبع الصحة واللياقة البدنية</div>
                    <div class="product-price">$199.99</div>
                    <button class="buy-btn" onclick="buyProduct('ساعة ذكية', 199.99)">شراء الآن</button>
                </div>
                
                <div class="product-card">
                    <div class="product-icon">📷</div>
                    <div class="product-name">كاميرا رقمية</div>
                    <div class="product-description">التقط أجمل اللحظات بدقة عالية</div>
                    <div class="product-price">$449.99</div>
                    <button class="buy-btn" onclick="buyProduct('كاميرا رقمية', 449.99)">شراء الآن</button>
                </div>
                
                <div class="product-card">
                    <div class="product-icon">🎮</div>
                    <div class="product-name">وحدة ألعاب</div>
                    <div class="product-description">تجربة ألعاب لا تُنسى</div>
                    <div class="product-price">$399.99</div>
                    <button class="buy-btn" onclick="buyProduct('وحدة ألعاب', 399.99)">شراء الآن</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Unlock item function
        function unlockItem(price) {
            const item = event.currentTarget;
            const lockIcon = item.querySelector('.lock-icon');
            const priceText = item.querySelector('.locked-price');
            const unlockText = item.querySelector('.unlock-text');
            
            // Add animation
            item.style.transform = 'scale(0.95)';
            setTimeout(() => {
                item.style.transform = 'scale(1)';
            }, 150);
            
            // Change lock to unlock animation
            lockIcon.style.animation = 'none';
            lockIcon.textContent = '🔓';
            unlockText.textContent = 'تم الفتح!';
            
            // Show unlock effect
            setTimeout(() => {
                item.style.background = 'linear-gradient(135deg, #4caf50 0%, #45a049 100%)';
                setTimeout(() => {
                    alert(`تم فتح العرض بقيمة $${price}!`);
                    // Reset after 2 seconds
                    setTimeout(() => {
                        lockIcon.textContent = '🔒';
                        lockIcon.style.animation = 'lockShake 2s infinite';
                        unlockText.textContent = 'اضغط للفتح';
                        item.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #feca57 100%)';
                    }, 2000);
                }, 500);
            }, 300);
        }

        // Buy product function
        function buyProduct(productName, price) {
            const btn = event.target;
            const originalText = btn.textContent;
            
            // Add loading state
            btn.innerHTML = '<span class="loading"></span> جاري المعالجة...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                alert(`تم شراء ${productName} بقيمة $${price} بنجاح!`);
            }, 2000);
        }

        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 5) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Add scroll reveal animation
        function revealOnScroll() {
            const elements = document.querySelectorAll('.product-card, .locked-item');
            
            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    element.style.transform = 'translateY(0)';
                    element.style.opacity = '1';
                }
            });
        }

        // Add hover sound effect (visual feedback)
        function addHoverEffects() {
            const cards = document.querySelectorAll('.product-card, .locked-item');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = this.classList.contains('locked-item') 
                        ? 'translateY(-8px) scale(1.05)' 
                        : 'translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            addHoverEffects();
            
            // Add staggered animation to products
            const products = document.querySelectorAll('.product-card');
            products.forEach((product, index) => {
                product.style.animationDelay = (0.8 + index * 0.1) + 's';
            });
        });

        window.addEventListener('scroll', revealOnScroll);
        revealOnScroll(); // Initial call
    </script>
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>
</body>
</html>
@endsection
