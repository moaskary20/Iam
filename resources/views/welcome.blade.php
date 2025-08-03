<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تطبيق IAM</title>
    <!-- Updated version with local server layout -->
    
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
            margin: 0;
            padding: 0;
            width: 100%;
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
            width: 100%;
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
            max-width: 100%;
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
        
        .auth-toggle {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .auth-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 1rem;
            padding-bottom: 5rem; /* Space for mobile nav */
            width: 100%;
            box-sizing: border-box;
        }
        
        .container {
            max-width: 100%;
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
        
        /* User Info Row */
        .user-info-row {
            max-width: 100%;
            margin: 32px auto 0;
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 18px;
            margin-bottom: 2.5rem;
            padding: 0 1rem;
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

        /* Enhanced User Info Cards */
        .user-info-card.enhanced {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%);
            border-radius: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08), 0 3px 10px rgba(0,0,0,0.05);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 140px;
            max-height: 140px;
            border: 1px solid rgba(14, 165, 233, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .user-info-card.enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(14, 165, 233, 0.02) 100%);
            pointer-events: none;
        }

        .user-info-card.enhanced:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12), 0 8px 25px rgba(14, 165, 233, 0.08);
            border-color: rgba(14, 165, 233, 0.2);
        }

        .user-info-card.enhanced:hover::before {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.02) 0%, rgba(14, 165, 233, 0.05) 100%);
        }

        .info-icon {
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3px); }
        }

        .info-content {
            flex: 1;
            text-align: center;
            width: 100%;
        }
        }

        .info-label {
            font-size: 0.9rem;
            color: var(--primary-600);
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .info-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            text-shadow: 0 1px 3px rgba(0,0,0,0.08);
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
            
            .slider-user-container {
                padding: 0 1.5rem !important;
            }
            
            .slider-section {
                height: 280px !important;
            }
            
            #currentSlide {
                height: 280px !important;
            }
        }
        
        @media (min-width: 1024px) {
            .content-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .header-content {
                padding: 0 2rem;
            }
            
            .main-content {
                padding: 3rem 2rem;
            }
            
            .slider-user-container {
                padding: 0 2rem !important;
            }
            
            .slider-section {
                height: 350px !important;
            }
            
            #currentSlide {
                height: 350px !important;
            }
            
            .user-info-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                grid-template-rows: 1fr !important;
                gap: 30px !important;
            }
        }

        /* For very large desktop screens */
        @media (min-width: 1280px) {
            .slider-user-container {
                padding: 0 2.5rem !important;
            }
            
            .slider-section {
                height: 380px !important;
            }
            
            #currentSlide {
                height: 380px !important;
            }
            
            .user-info-grid {
                gap: 35px !important;
            }
        }

        /* For extra large screens */
        @media (min-width: 1440px) {
            .user-info-row {
                grid-template-columns: repeat(6,1fr);
                padding: 0 2rem;
            }
            
            .content-grid {
                grid-template-columns: repeat(5, 1fr);
            }
            
            .header-content {
                padding: 0 3rem;
            }
            
            .main-content {
                padding: 3rem 3rem;
            }
            
            .slider-user-container {
                padding: 0 3rem !important;
            }
            
            .slider-section {
                height: 400px !important;
            }
            
            #currentSlide {
                height: 400px !important;
            }
        }

        /* For ultra wide screens */
        @media (min-width: 1920px) {
            .user-info-row {
                grid-template-columns: repeat(8,1fr);
                padding: 0 4rem;
            }
            
            .content-grid {
                grid-template-columns: repeat(6, 1fr);
            }
            
            .header-content {
                padding: 0 4rem;
            }
            
            .main-content {
                padding: 3rem 4rem;
            }
            
            .slider-user-container {
                padding: 0 4rem !important;
            }
            
            .slider-section {
                height: 500px !important;
            }
            
            #currentSlide {
                height: 500px !important;
            }
            
            .user-info-grid {
                grid-template-columns: repeat(6, 1fr) !important;
                grid-template-rows: 1fr !important;
                gap: 45px !important;
            }
        }

        /* For 2K+ screens */
        @media (min-width: 2560px) {
            .slider-user-container {
                padding: 0 6rem !important;
            }
            
            .slider-section {
                height: 600px !important;
            }
            
            #currentSlide {
                height: 600px !important;
            }
            
            .user-info-grid {
                grid-template-columns: repeat(8, 1fr) !important;
                gap: 50px !important;
            }
            
            .header-content {
                padding: 0 6rem;
            }
            
            .main-content {
                padding: 3rem 6rem;
            }
        }

        /* Slider and User Info Grid Responsive */
        @media (max-width: 768px) {
            .user-info-grid {
                gap: 15px !important;
                grid-template-columns: 1fr 1fr !important;
                grid-template-rows: 1fr 1fr !important;
                max-width: 100% !important;
            }
            
            .user-info-card.enhanced {
                padding: 1.2rem 0.8rem;
                min-height: 120px;
                max-height: 120px;
            }
            
            .info-icon {
                font-size: 1.8rem;
                margin-bottom: 0.6rem;
            }
            
            .info-label {
                font-size: 0.8rem;
            }
            
            .info-value {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .user-info-grid {
                gap: 10px !important;
                grid-template-columns: 1fr 1fr !important;
                padding: 0 5px;
            }
            
            .slider-user-container {
                margin: 16px auto 0 !important;
                padding: 0 10px !important;
            }
            
            .user-info-card.enhanced {
                padding: 1rem 0.6rem;
                min-height: 100px;
                max-height: 100px;
            }
            
            .info-icon {
                font-size: 1.6rem;
                margin-bottom: 0.4rem;
            }
            
            .info-content {
                text-align: center;
            }
            
            .info-label {
                font-size: 0.75rem;
                margin-bottom: 0.3rem;
            }
            
            .info-value {
                font-size: 0.9rem;
                line-height: 1.1;
            }
        }
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
            <button class="auth-toggle" onclick="handleAuth()" aria-label="تسجيل الدخول/الخروج">
                @auth
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>تسجيل الخروج</span>
                @else
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m0 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>تسجيل الدخول</span>
                @endauth
            </button>
        </div>
    </header>

    <!-- Slider and User Info Section -->
    @php
        $sliders = \App\Models\Slider::where('active', 1)->orderBy('order')->get();
    @endphp
    
    <div class="slider-user-container" style="max-width:100%;margin:24px 0 0 0;padding:0 2rem;width:100%;box-sizing:border-box;">
        <!-- Slider Section -->
        @if($sliders->count() > 0)
        <div class="slider-section" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.15);position:relative;margin-bottom:20px;width:100%;">
            <!-- Current Slide Display -->
            <div id="currentSlide" style="position:relative;height:300px;width:100%;">
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

        <!-- User Info Cards - Enhanced 2x2 Grid Design -->
        <div class="user-info-grid" style="display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:20px;margin-bottom:2rem;max-width:100%;margin-left:0;margin-right:0;padding:0;">
            @if($user)
                <!-- User Personal Statistics -->
                <div class="user-info-card enhanced">
                    <div class="info-icon">💰</div>
                    <div class="info-content">
                        <div class="info-label">رصيدك الحالي</div>
                        <div class="info-value" data-stat="user_balance">
                            ${{ number_format($statistics['user_balance'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">🔄</div>
                    <div class="info-content">
                        <div class="info-label">معاملاتك</div>
                        <div class="info-value" data-stat="user_transactions">
                            {{ number_format($statistics['user_transactions']) }} معاملة
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📈</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي إيداعاتك</div>
                        <div class="info-value" data-stat="total_deposits">
                            ${{ number_format($statistics['total_deposits'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📉</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي سحوباتك</div>
                        <div class="info-value" data-stat="total_withdrawals">
                            ${{ number_format($statistics['total_withdrawals'], 2) }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest/System Statistics -->
                <div class="user-info-card enhanced">
                    <div class="info-icon">👥</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي المستخدمين</div>
                        <div class="info-value" data-stat="total_users">
                            {{ number_format($statistics['total_users']) }} مستخدم
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">💰</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي الأرصدة</div>
                        <div class="info-value" data-stat="total_balance">
                            ${{ number_format($statistics['total_balance'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">🔄</div>
                    <div class="info-content">
                        <div class="info-label">إجمالي المعاملات</div>
                        <div class="info-value" data-stat="total_transactions">
                            {{ number_format($statistics['total_transactions']) }} معاملة
                        </div>
                    </div>
                </div>
                <div class="user-info-card enhanced">
                    <div class="info-icon">📈</div>
                    <div class="info-content">
                        <div class="info-label">المستخدمون النشطون</div>
                        <div class="info-value" data-stat="active_users">
                            {{ number_format($statistics['active_users']) }} نشط
                        </div>
                    </div>
                </div>
            @endif
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
                    <a href="{{ route('progressive.market') }}" class="card-button" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        ابدأ الآن
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.1s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">إحصائيات المبيعات</h3>
                    <p class="card-description">تابع أداء مبيعاتك وحلل البيانات لتحسين النتائج</p>
                    <a href="{{ route('statistics') }}" class="card-button" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        عرض التقارير
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </a>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.2s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">إدارة المنتجات</h3>
                    <p class="card-description">أضف وحرر منتجاتك بسهولة مع واجهة بسيطة وسريعة</p>
                    <a href="{{ route('progressive.market') }}" class="card-button" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        إدارة المنتجات
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                    </a>
                </div>

                <div class="content-card fade-in" style="animation-delay: 0.3s; background: rgba(255,255,255,0.95); box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                    <h3 class="card-title">الإعدادات</h3>
                    <p class="card-description">خصص تجربتك حسب احتياجاتك الشخصية</p>
                    <a href="{{ route('profile') }}" class="card-button" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        الذهاب للإعدادات
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer"></div>
        </div>
    </main>

    <!-- Mobile Navigation -->
    <x-mobile-nav />

    <script>
        // Auth Toggle Functionality
        function handleAuth() {
            @auth
                // User is logged in, redirect to logout
                if (confirm('هل تريد تسجيل الخروج؟')) {
                    // Create a form to submit logout request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('logout') }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            @else
                // User is not logged in, redirect to login page
                window.location.href = '{{ route('login') }}';
            @endauth
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
        
        // Enhanced Mobile Navigation
        function initializeMobileNav() {
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            const currentPath = window.location.pathname;
            
            mobileNavLinks.forEach(link => {
                const href = link.getAttribute('href');
                
                // Set active state
                if (href === currentPath || (currentPath === '/' && href === '/')) {
                    link.classList.add('active');
                }
                
                // Add ripple effect on click
                link.addEventListener('click', function(e) {
                    createRippleEffect(e, this);
                    
                    // Add haptic feedback for mobile devices
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                    
                    // Smooth transition animation
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
                
                // Add touch feedback
                link.addEventListener('touchstart', function() {
                    this.style.background = 'rgba(14, 165, 233, 0.1)';
                });
                
                link.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.style.background = '';
                    }, 200);
                });
            });
        }
        
        function createRippleEffect(event, element) {
            const ripple = element.querySelector('.mobile-nav-ripple');
            if (!ripple) return;
            
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.opacity = '1';
            ripple.style.transform = 'translate(-50%, -50%) scale(1)';
            
            setTimeout(() => {
                ripple.style.opacity = '0';
                ripple.style.transform = 'translate(-50%, -50%) scale(0)';
            }, 600);
        }
        
        // Mobile navigation badge system
        function updateNavBadges() {
            const badges = {
                wallet: 0, // Could be unread transactions
                statistics: 0, // Could be new insights
                market: 0, // Could be new products
                profile: 0 // Could be incomplete profile sections
            };
            
            // Example: Show badge on wallet if there are recent transactions
            if (badges.wallet > 0) {
                showNavBadge('wallet', badges.wallet);
            }
        }
        
        function showNavBadge(navItem, count) {
            const link = document.querySelector(`[data-nav="${navItem}"]`);
            if (link) {
                const indicator = link.querySelector('.mobile-nav-indicator');
                if (indicator) {
                    indicator.style.opacity = '1';
                    indicator.style.transform = 'scale(1)';
                    if (count > 1) {
                        indicator.setAttribute('data-count', count);
                    }
                }
            }
        }
        
        // Performance optimization for mobile nav
        function optimizeMobileNav() {
            const nav = document.querySelector('.mobile-nav');
            if (!nav) return;
            
            // Use transform3d for better performance
            nav.style.transform = 'translate3d(0, 0, 0)';
            
            // Optimize scroll performance
            let scrollTimeout;
            let lastScrollY = window.scrollY;
            
            window.addEventListener('scroll', () => {
                const currentScrollY = window.scrollY;
                
                // Hide nav when scrolling down, show when scrolling up
                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    nav.style.transform = 'translate3d(0, 100%, 0)';
                } else {
                    nav.style.transform = 'translate3d(0, 0, 0)';
                }
                
                lastScrollY = currentScrollY;
                
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    nav.style.transform = 'translate3d(0, 0, 0)';
                }, 1000);
            }, { passive: true });
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
        
        // Add loading to card buttons (excluding links)
        document.querySelectorAll('.card-button').forEach(button => {
            // Skip if it's a link (has href attribute)
            if (button.tagName.toLowerCase() === 'a') return;
            
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
        
        // Auto-refresh statistics every 30 seconds
        function refreshStatistics() {
            // Show update indicator
            const indicator = document.getElementById('updateIndicator');
            if (indicator) {
                indicator.style.opacity = '1';
            }
            
            fetch('{{ route("api.statistics") }}')
                .then(response => response.json())
                .then(statistics => {
                    // Check if user is authenticated to update appropriate statistics
                    @if($user)
                        // Update user personal statistics
                        updateStatValue('user_balance', '$' + numberFormat(statistics.user_balance, 2));
                        updateStatValue('user_transactions', statistics.user_transactions + ' معاملة');
                        updateStatValue('total_deposits', '$' + numberFormat(statistics.total_deposits, 2));
                        updateStatValue('total_withdrawals', '$' + numberFormat(statistics.total_withdrawals, 2));
                    @else
                        // Update system statistics for guests
                        updateStatValue('total_users', statistics.total_users + ' مستخدم');
                        updateStatValue('total_balance', '$' + numberFormat(statistics.total_balance, 2));
                        updateStatValue('total_transactions', statistics.total_transactions + ' معاملة');
                        updateStatValue('active_users', statistics.active_users + ' نشط');
                    @endif
                    
                    // Update additional statistics (common for both user types)
                    updateStatValue('average_balance', '$' + numberFormat(statistics.average_balance, 2));
                    updateStatValue('total_revenue', '$' + numberFormat(statistics.total_revenue, 2));
                    updateStatValue('today_users', numberFormat(statistics.today_users));
                    updateStatValue('monthly_transactions', numberFormat(statistics.monthly_transactions));
                    updateStatValue('successful_transactions', numberFormat(statistics.successful_transactions));
                    updateStatValue('pending_transactions', numberFormat(statistics.pending_transactions));
                    
                    console.log('Statistics updated successfully');
                    
                    // Hide update indicator after 2 seconds
                    setTimeout(() => {
                        if (indicator) {
                            indicator.style.opacity = '0';
                        }
                    }, 2000);
                })
                .catch(error => {
                    console.log('Statistics update failed:', error);
                    // Hide update indicator on error
                    if (indicator) {
                        indicator.style.opacity = '0';
                    }
                });
        }
        
        function updateStatValue(type, newValue) {
            // Find all elements with the stat value and update them
            const elements = document.querySelectorAll(`[data-stat="${type}"]`);
            elements.forEach(element => {
                if (element.textContent !== newValue) {
                    // Add animation for updated values
                    element.style.transform = 'scale(1.1)';
                    element.style.color = 'var(--success-600)';
                    setTimeout(() => {
                        element.textContent = newValue;
                        element.style.transform = 'scale(1)';
                        element.style.color = 'var(--text-primary)';
                    }, 200);
                }
            });
        }
        
        function numberFormat(number, decimals = 0) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(number);
        }
        
        // Refresh statistics every 30 seconds
        setInterval(refreshStatistics, 30000);
        
        // Add manual refresh button functionality
        function manualRefresh() {
            refreshStatistics();
            showAlert('success', 'تم تحديث الإحصائيات بنجاح!');
        }
        
        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            setActiveNav();
            initializeMobileNav();
            updateNavBadges();
            optimizeMobileNav();
            
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