<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تطبيق IAM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Modern Colors */
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
            
            /* Success Colors */
            --success-50: #f0fdf4;
            --success-100: #dcfce7;
            --success-200: #bbf7d0;
            --success-300: #86efac;
            --success-400: #4ade80;
            --success-500: #22c55e;
            --success-600: #16a34a;
            --success-700: #15803d;
            --success-800: #166534;
            --success-900: #14532d;
            
            /* Warning Colors */
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-200: #fde68a;
            --warning-300: #fcd34d;
            --warning-400: #fbbf24;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-700: #b45309;
            --warning-800: #92400e;
            --warning-900: #78350f;
            
            /* Error Colors */
            --error-50: #fef2f2;
            --error-100: #fee2e2;
            --error-200: #fecaca;
            --error-300: #fca5a5;
            --error-400: #f87171;
            --error-500: #ef4444;
            --error-600: #dc2626;
            --error-700: #b91c1c;
            --error-800: #991b1b;
            --error-900: #7f1d1d;
            
            /* Neutral Colors */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --gradient-success: linear-gradient(135deg, var(--success-500) 0%, var(--success-700) 100%);
            --gradient-sunset: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-ocean: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Dark Mode Variables */
        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-tertiary: #94a3b8;
            --border-color: #334155;
        }
        
        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: var(--text-primary);
            line-height: 1.6;
            transition: all var(--transition-fast);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        /* Background Animation */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.12;
            pointer-events: none;
        }
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.13);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
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
        
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .logo {
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 2px 16px #764ba2cc, 0 1px 0 #fff8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 1px;
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
        
        .theme-toggle {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 1rem;
            padding-bottom: 5rem; /* Space for mobile nav */
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Desktop Navigation */
        .desktop-nav {
            display: none;
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }
        
        .nav-list {
            display: flex;
            gap: 1rem;
            list-style: none;
        }
        
        .nav-item {
            flex: 1;
        }
        
        .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            text-decoration: none;
            color: var(--text-secondary);
            border-radius: 0.5rem;
            transition: all var(--transition-fast);
            background: transparent;
            border: 1px solid transparent;
        }
        
        .nav-link:hover, .nav-link.active {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .nav-icon {
            width: 1.5rem;
            height: 1.5rem;
        }
        
        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100vw;
            background: var(--bg-primary);
            border-top: 1px solid var(--border-color);
            padding: 0.5rem 0;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .mobile-nav-list {
            display: flex;
            justify-content: space-around;
            list-style: none;
        }
        
        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem;
            text-decoration: none;
            color: var(--text-tertiary);
            transition: all var(--transition-fast);
            border-radius: 0.5rem;
            min-width: 4rem;
        }
        
        .mobile-nav-link:hover, .mobile-nav-link.active {
            color: var(--primary-600);
            background: var(--primary-50);
            transform: scale(1.05);
        }
        
        .mobile-nav-icon {
            width: 1.25rem;
            height: 1.25rem;
        }
        
        .mobile-nav-text {
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* User Info Row */
        .user-info-row {
            max-width: 900px;
            margin: 32px auto 0;
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 18px;
            margin-bottom: 2.5rem;
        }
        .user-info-card {
            background: var(--bg-secondary);
            border-radius: 1rem;
            box-shadow: 0 2px 12px #0001;
            padding: 1.2rem 0.7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 90px;
        }
        @media (max-width: 900px) {
            .user-info-row {
                grid-template-columns: repeat(2,1fr);
            }
        }
        @media (max-width: 600px) {
            .user-info-row {
                grid-template-columns: repeat(2,1fr);
            }
        }
        @media (max-width: 400px) {
            .user-info-row {
                grid-template-columns: 1fr;
            }
        }
        /* Content Cards */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .content-card {
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }
        
        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }
        
        .content-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .card-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        .card-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-button:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        /* Responsive Design */
        @media (min-width: 640px) {
            .content-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 768px) {
            .main-content {
                padding-bottom: 2rem;
            }
            
            .mobile-nav {
                display: none;
            }
            
            .desktop-nav {
                display: block;
            }
        }
        
        @media (min-width: 1024px) {
            .content-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .header-content {
                padding: 0 2rem;
            }
            
            .main-content {
                padding: 3rem 2rem;
            }
        }

        /* Slider and User Info Grid Responsive */
        @media (max-width: 768px) {
            .user-info-grid {
                gap: 15px !important;
            }
        }
        
        @media (max-width: 480px) {
            .user-info-grid {
                gap: 10px !important;
            }
            
            .slider-user-container {
                margin: 16px auto 0 !important;
                padding: 0 10px !important;
            }
            
            .user-info-card {
                padding: 12px !important;
                font-size: 0.9rem !important;
            }
            
            .user-info-card > div:first-child {
                font-size: 0.85rem !important;
            }
            
            .user-info-card > div:last-child {
                font-size: 1rem !important;
            }
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Loading Animation */
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
        
        /* Success/Error Messages */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background: var(--success-50);
            color: var(--success-800);
            border: 1px solid var(--success-200);
        }
        
        .alert-error {
            background: var(--error-50);
            color: var(--error-800);
            border: 1px solid var(--error-200);
        }
        
        .alert-warning {
            background: var(--warning-50);
            color: var(--warning-800);
            border: 1px solid var(--warning-200);
        }
    </style>
</head>
<body data-theme="light">
    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <div class="logo-icon">🚀</div>
                <span>تطبيق IAM </span>
            </a>
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="تبديل الوضع">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Slider and User Info Section -->
    @php
        $sliders = \App\Models\Slider::where('active', 1)->orderBy('order')->get();
    @endphp
    
    <div class="slider-user-container" style="max-width:1200px;margin:24px auto 0;">
        <!-- Slider Section -->
        @if($sliders->count() > 0)
        <div class="slider-section" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.15);position:relative;margin-bottom:20px;">
            <!-- Current Slide Display -->
            <div id="currentSlide" style="position:relative;height:220px;width:100%;">
                <!-- Images will be loaded here by JavaScript -->
            </div>
            
            <!-- Navigation Arrows -->
            <!-- Right Arrow (Next) -->
            <div style="position:absolute;top:50%;left:15px;transform:translateY(-50%);z-index:100;">
                <button id="nextBtn" onclick="nextSlide()" style="background:rgba(255,255,255,0.95);border:none;border-radius:50%;width:60px;height:60px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 20px rgba(0,0,0,0.25);transition:all 0.3s ease;">
                    <svg width="28" height="28" fill="none" stroke="#333" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            
            <!-- Left Arrow (Previous) -->
            <div style="position:absolute;top:50%;right:15px;transform:translateY(-50%);z-index:100;">
                <button id="prevBtn" onclick="prevSlide()" style="background:rgba(255,255,255,0.95);border:none;border-radius:50%;width:60px;height:60px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 20px rgba(0,0,0,0.25);transition:all 0.3s ease;">
                    <svg width="28" height="28" fill="none" stroke="#333" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>
            
            <!-- Dots -->
            <div id="sliderDots" style="position:absolute;bottom:20px;left:0;right:0;display:flex;justify-content:center;gap:12px;z-index:100;">
                <!-- Dots will be generated by JavaScript -->
            </div>
        </div>

        <script>
            // Slider data from PHP
            const slidersData = @json($sliders->toArray());
            let currentIndex = 0;
            let autoInterval;
            
            console.log('Sliders data:', slidersData);
            
            function initSlider() {
                const currentSlideDiv = document.getElementById('currentSlide');
                const dotsContainer = document.getElementById('sliderDots');
                
                if (!currentSlideDiv || !slidersData || slidersData.length === 0) {
                    console.error('Slider initialization failed - missing elements or data');
                    return;
                }
                
                // Generate dots
                dotsContainer.innerHTML = '';
                slidersData.forEach((slider, index) => {
                    const dot = document.createElement('span');
                    dot.style.cssText = `
                        width: 12px;
                        height: 12px;
                        border-radius: 50%;
                        background: ${index === 0 ? '#0ea5e9' : 'rgba(255,255,255,0.5)'};
                        border: 2px solid #fff;
                        display: inline-block;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        ${index === 0 ? 'transform: scale(1.3); box-shadow: 0 2px 8px rgba(14,165,233,0.5);' : ''}
                    `;
                    dot.onclick = () => goToSlide(index);
                    dotsContainer.appendChild(dot);
                });
                
                // Show first slide
                showSlide(0);
                
                // Start auto-play
                startAutoPlay();
                
                console.log('Slider initialized successfully');
            }
            
            function showSlide(index) {
                const currentSlideDiv = document.getElementById('currentSlide');
                const dots = document.getElementById('sliderDots').children;
                
                if (!slidersData[index]) return;
                
                const slider = slidersData[index];
                console.log('Showing slide:', index, slider);
                
                // Update slide content
                currentSlideDiv.innerHTML = `
                    <a href="${slider.link || '#'}" target="_blank" style="display:block;width:100%;height:100%;">
                        <img src="/storage/${slider.image}" alt="${slider.title}" 
                             style="width:100%;height:100%;object-fit:cover;display:block;border-radius:16px;"
                             onerror="console.error('Failed to load image:', this.src)"
                             onload="console.log('Image loaded:', this.src)">
                    </a>
                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.8));color:white;padding:20px;border-radius:0 0 16px 16px;">
                        <h3 style="margin:0;font-size:20px;font-weight:bold;">${slider.title}</h3>
                        ${slider.description ? `<p style="margin:5px 0 0;font-size:14px;opacity:0.9;">${slider.description}</p>` : ''}
                    </div>
                `;
                
                // Update dots
                Array.from(dots).forEach((dot, i) => {
                    if (i === index) {
                        dot.style.background = '#0ea5e9';
                        dot.style.transform = 'scale(1.3)';
                        dot.style.boxShadow = '0 2px 8px rgba(14,165,233,0.5)';
                    } else {
                        dot.style.background = 'rgba(255,255,255,0.5)';
                        dot.style.transform = 'scale(1)';
                        dot.style.boxShadow = 'none';
                    }
                });
                
                currentIndex = index;
            }
            
            function nextSlide() {
                const nextIndex = (currentIndex + 1) % slidersData.length;
                console.log('Next slide:', nextIndex);
                showSlide(nextIndex);
                resetAutoPlay();
            }
            
            function prevSlide() {
                const prevIndex = (currentIndex - 1 + slidersData.length) % slidersData.length;
                console.log('Previous slide:', prevIndex);
                showSlide(prevIndex);
                resetAutoPlay();
            }
            
            function goToSlide(index) {
                console.log('Go to slide:', index);
                showSlide(index);
                resetAutoPlay();
            }
            
            function startAutoPlay() {
                if (slidersData.length > 1) {
                    autoInterval = setInterval(nextSlide, 4000);
                    console.log('Auto-play started');
                }
            }
            
            function stopAutoPlay() {
                if (autoInterval) {
                    clearInterval(autoInterval);
                    autoInterval = null;
                    console.log('Auto-play stopped');
                }
            }
            
            function resetAutoPlay() {
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            }
            
            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, initializing slider...');
                setTimeout(initSlider, 100);
                
                // Add hover effects to arrow buttons
                const nextBtn = document.getElementById('nextBtn');
                const prevBtn = document.getElementById('prevBtn');
                
                if (nextBtn) {
                    nextBtn.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-50%) scale(1.1)';
                        this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.35)';
                        this.style.background = 'rgba(14,165,233,0.95)';
                        this.querySelector('svg').style.stroke = '#fff';
                    });
                    
                    nextBtn.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(-50%) scale(1)';
                        this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.25)';
                        this.style.background = 'rgba(255,255,255,0.95)';
                        this.querySelector('svg').style.stroke = '#333';
                    });
                    
                    nextBtn.addEventListener('mousedown', function() {
                        this.style.transform = 'translateY(-50%) scale(0.95)';
                    });
                    
                    nextBtn.addEventListener('mouseup', function() {
                        this.style.transform = 'translateY(-50%) scale(1.1)';
                    });
                }
                
                if (prevBtn) {
                    prevBtn.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-50%) scale(1.1)';
                        this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.35)';
                        this.style.background = 'rgba(14,165,233,0.95)';
                        this.querySelector('svg').style.stroke = '#fff';
                    });
                    
                    prevBtn.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(-50%) scale(1)';
                        this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.25)';
                        this.style.background = 'rgba(255,255,255,0.95)';
                        this.querySelector('svg').style.stroke = '#333';
                    });
                    
                    prevBtn.addEventListener('mousedown', function() {
                        this.style.transform = 'translateY(-50%) scale(0.95)';
                    });
                    
                    prevBtn.addEventListener('mouseup', function() {
                        this.style.transform = 'translateY(-50%) scale(1.1)';
                    });
                }
            });
        </script>
        @else
        <div class="slider-section" style="padding:40px;text-align:center;background:#f8f9fa;border-radius:16px;margin-bottom:20px;">
            <p style="color:#666;font-size:16px;">لا توجد صور في السليدر حالياً</p>
        </div>
        @endif

        <!-- User Info Cards - 2x2 Grid Below Slider -->
        <div class="user-info-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="user-info-card">
                <div style="font-size:1rem;color:var(--primary-600);font-weight:600;margin-bottom:0.3rem;">الاسم</div>
                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">محمد أحمد</div>
            </div>
            <div class="user-info-card">
                <div style="font-size:1rem;color:var(--primary-600);font-weight:600;margin-bottom:0.3rem;">رصيد الحساب</div>
                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">2500 ج.م</div>
            </div>
            <div class="user-info-card">
                <div style="font-size:1rem;color:var(--primary-600);font-weight:600;margin-bottom:0.3rem;">رقم العضوية</div>
                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">#10234</div>
            </div>
            <div class="user-info-card">
                <div style="font-size:1rem;color:var(--primary-600);font-weight:600;margin-bottom:0.3rem;">السوق الحالي</div>
                <div style="font-size:1.1rem;font-weight:700;color:var(--text-primary);">القاهرة</div>
            </div>
        </div>
    </div>

    <!-- Separator -->
    <div style="height: 40px;"></div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container" style="backdrop-filter: blur(0.5px);">
            <!-- Desktop Navigation -->
            <x-desktop-nav />

            <!-- Content Area -->
            <div class="content-grid">
            <div class="content-card fade-in" style="background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">مرحباً بك في التطبيق</h3>
                    <p class="card-description">استكشف جميع الميزات المتاحة واستمتع بتجربة فريدة ومتطورة</p>
                    <button class="card-button" onclick="showAlert('success', 'تم تسجيل الدخول بنجاح!')">
                        ابدأ الآن
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.1s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">إحصائيات المبيعات</h3>
                    <p class="card-description">تابع أداء مبيعاتك وحلل البيانات لتحسين النتائج</p>
                    <button class="card-button" onclick="showAlert('warning', 'يرجى تحديث البيانات')">
                        عرض التقارير
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.2s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">إدارة المنتجات</h3>
                    <p class="card-description">أضف وحرر منتجاتك بسهولة مع واجهة بسيطة وسريعة</p>
                    <button class="card-button" onclick="showAlert('error', 'عذراً، هذه الميزة غير متاحة حالياً')">
                        إدارة المنتجات
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.3s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">الإعدادات</h3>
                    <p class="card-description">خصص تجربتك حسب احتياجاتك الشخصية</p>
                    <button class="card-button">
                        الذهاب للإعدادات
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer"></div>
        </div>
    </main>

    <!-- Mobile Navigation -->
    <x-mobile-nav />

    <script>
        // Theme Toggle Functionality
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Add animation effect
            body.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                body.style.transition = '';
            }, 300);
        }
        
        // Load saved theme
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
        }
        
        // Alert System
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const alertElement = document.createElement('div');
            alertElement.id = alertId;
            alertElement.className = `alert alert-${type} fade-in`;
            
            const icons = {
                success: `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`,
                error: `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`,
                warning: `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>`
            };
            
            alertElement.innerHTML = `
                ${icons[type]}
                <span>${message}</span>
                <button onclick="closeAlert('${alertId}')" style="margin-right: auto; background: none; border: none; color: inherit; cursor: pointer; padding: 0.25rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            alertContainer.appendChild(alertElement);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }
        
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.transform = 'translateX(100%)';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }
        
        // Navigation Active State
        function setActiveNav() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath || (currentPath === '/' && href === '/')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }
        
        // Smooth scroll for anchor links (ignore href="#" or empty)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#' || href.length === 1) return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Loading state for buttons
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = `<span class="loading"></span> جاري التحميل...`;
            button.disabled = true;
            
            // Simulate loading
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }
        
        // Add loading to all card buttons
        document.querySelectorAll('.card-button').forEach(button => {
            const originalClick = button.onclick;
            button.addEventListener('click', function(e) {
                if (!this.disabled) {
                    showLoading(this);
                    if (originalClick) {
                        setTimeout(() => {
                            originalClick.call(this, e);
                        }, 1000);
                    }
                }
            });
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe all content cards
        document.querySelectorAll('.content-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
        
        // PWA Support disabled for now
        // Service Worker registration removed to prevent 404 errors
        
        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            setActiveNav();
            
            // Add some demo data
            setTimeout(() => {
                showAlert('success', 'مرحباً بك! تم تحميل البيانات بنجاح');
            }, 1000);
        });
        
        // Handle online/offline status
        window.addEventListener('online', () => {
            showAlert('success', 'تم الاتصال بالإنترنت');
        });
        
        window.addEventListener('offline', () => {
            showAlert('warning', 'لا يوجد اتصال بالإنترنت');
        });
        
        // Touch gestures for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - could navigate to next page
                    console.log('Swiped left');
                } else {
                    // Swipe right - could navigate to previous page
                    console.log('Swiped right');
                }
            }
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Toggle theme with Ctrl/Cmd + Shift + T
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                toggleTheme();
            }
            
            // Focus search with Ctrl/Cmd + K
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                // Focus search input if exists
                const searchInput = document.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
        
        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
                }, 0);
            });
        }
    </script>
</body>
</html>