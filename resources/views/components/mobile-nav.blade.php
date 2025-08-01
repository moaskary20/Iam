<!-- Mobile Navigation Component -->
<nav class="mobile-nav">
    <ul class="mobile-nav-list">
        <li>
            <a href="/" class="mobile-nav-link">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="mobile-nav-text">الرئيسية</span>
            </a>
        </li>
        <li>
            <a href="{{ route('wallet') }}" class="mobile-nav-link">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span class="mobile-nav-text">المحفظة</span>
            </a>
        </li>
        <li>
            <a href="{{ route('market') }}" class="mobile-nav-link">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="mobile-nav-text">السوق</span>
            </a>
        </li>
        <li>
            <a href="/profile" class="mobile-nav-link">
                <svg class="mobile-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="mobile-nav-text">الملف الشخصي</span>
            </a>
        </li>
    </ul>
</nav>
