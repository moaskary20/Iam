<!-- Load Mobile Navigation Styles -->
<link rel="stylesheet" href="{{ asset('css/mobile-nav.css') }}">

<!-- Professional Mobile Navigation Component -->
<nav class="mobile-nav">
    <!-- Top Border Indicator -->
    <div class="mobile-nav-border"></div>
    
    <!-- Navigation Background with Gradient -->
    <div class="mobile-nav-background"></div>
    
    <!-- Navigation Content -->
    <div class="mobile-nav-content">
        <ul class="mobile-nav-list">
            <li class="mobile-nav-item">
                <a href="/" class="mobile-nav-link" data-nav="home" aria-label="الصفحة الرئيسية">
                    <div class="mobile-nav-icon-container">
                        <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <div class="mobile-nav-indicator" aria-hidden="true"></div>
                    </div>
                    <span class="mobile-nav-text">الرئيسية</span>
                    <div class="mobile-nav-ripple" aria-hidden="true"></div>
                </a>
            </li>
            
            <li class="mobile-nav-item">
                <a href="{{ route('wallet') }}" class="mobile-nav-link" data-nav="wallet" aria-label="المحفظة المالية">
                    <div class="mobile-nav-icon-container">
                        <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <div class="mobile-nav-indicator" aria-hidden="true"></div>
                    </div>
                    <span class="mobile-nav-text">المحفظة</span>
                    <div class="mobile-nav-ripple" aria-hidden="true"></div>
                </a>
            </li>
            
            <li class="mobile-nav-item mobile-nav-center">
                <a href="{{ route('market') }}" class="mobile-nav-link mobile-nav-primary" data-nav="market" aria-label="السوق الإلكتروني">
                    <div class="mobile-nav-icon-container">
                        <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <div class="mobile-nav-glow" aria-hidden="true"></div>
                    </div>
                    <span class="mobile-nav-text">السوق</span>
                    <div class="mobile-nav-ripple" aria-hidden="true"></div>
                </a>
            </li>
            
            <li class="mobile-nav-item">
                <a href="{{ route('statistics') }}" class="mobile-nav-link" data-nav="statistics" aria-label="الإحصائيات والتقارير">
                    <div class="mobile-nav-icon-container">
                        <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="mobile-nav-indicator" aria-hidden="true"></div>
                    </div>
                    <span class="mobile-nav-text">الإحصائيات</span>
                    <div class="mobile-nav-ripple" aria-hidden="true"></div>
                </a>
            </li>
            
            <li class="mobile-nav-item">
                <a href="{{ route('profile') }}" class="mobile-nav-link" data-nav="profile" aria-label="الملف الشخصي">
                    <div class="mobile-nav-icon-container">
                        <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="mobile-nav-indicator" aria-hidden="true"></div>
                    </div>
                    <span class="mobile-nav-text">الملف الشخصي</span>
                    <div class="mobile-nav-ripple" aria-hidden="true"></div>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Navigation Border -->
    <div class="mobile-nav-border" aria-hidden="true"></div>
</nav>

<!-- Load Mobile Navigation JavaScript -->
<script src="{{ asset('js/mobile-nav.js') }}"></script>
<script src="{{ asset('js/mobile-nav-advanced.js') }}"></script>
<script src="{{ asset('js/mobile-nav-active.js') }}"></script>
