@extends('layouts.app')

@section('title', 'المحفظة')

@section('head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap');
        
        nav.mobile-nav {
            background-color: white;
        }
        
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
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            right: 15%;
            top: 60%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            left: 60%;
            top: 10%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 120px;
            height: 120px;
            right: 50%;
            bottom: 20%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) translateX(0px) rotate(0deg);
                opacity: 0.3;
            }
            33% { 
                transform: translateY(-30px) translateX(20px) rotate(120deg);
                opacity: 0.6;
            }
            66% { 
                transform: translateY(20px) translateX(-15px) rotate(240deg);
                opacity: 0.4;
            }
        }

        .container {
            max-width: 450px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            animation: slideDown 0.8s ease-out;
        }

        .wallet-title {
            color: white;
            font-size: 32px;
            font-weight: 900;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }

        .wallet-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            font-weight: 400;
        }

        /* Balance Card */
        .balance-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.1) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, rgba(255,255,255,0.1), transparent 30%);
            animation: rotate 6s linear infinite;
        }

        .balance-label {
            color: rgba(255,255,255,0.8);
            font-size: 16px;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .balance-amount {
            color: white;
            font-size: 42px;
            font-weight: 900;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
            animation: countUp 2s ease-out;
        }

        .balance-currency {
            font-size: 24px;
            opacity: 0.9;
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .action-btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 18px;
            padding: 18px 20px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        .action-btn:nth-child(1) {
            animation-delay: 0.2s;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .action-btn:nth-child(2) {
            animation-delay: 0.3s;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }

        .action-btn:active {
            transform: translateY(-1px);
        }

        .btn-icon {
            font-size: 20px;
            margin-left: 8px;
        }

        /* Transaction Sections */
        .transaction-section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .transaction-section:nth-child(4) { animation-delay: 0.4s; }
        .transaction-section:nth-child(5) { animation-delay: 0.6s; }
        .transaction-section:nth-child(6) { animation-delay: 0.8s; }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .section-header:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .section-icon {
            font-size: 24px;
            margin-left: 10px;
        }

        .expand-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .section-content {
            padding: 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s ease;
            opacity: 0;
        }

        .section-content.expanded {
            max-height: 1000px;
            opacity: 1;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-item:hover {
            background: rgba(102, 126, 234, 0.05);
            margin: 0 -15px;
            padding: 15px;
            border-radius: 10px;
        }

        .transaction-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .transaction-amount {
            font-size: 18px;
            font-weight: 700;
        }

        .deposit-amount {
            color: #4caf50;
        }

        .withdraw-amount {
            color: #f44336;
        }

        .transaction-date {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .transaction-time {
            color: #999;
            font-size: 12px;
        }

        .transaction-status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-completed {
            background: #e8f5e8;
            color: #4caf50;
        }

        .status-pending {
            background: #fff3e0;
            color: #ff9800;
        }

        .transaction-description {
            color: #888;
            font-size: 12px;
            font-style: italic;
            margin-top: 3px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .nav-btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
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
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            
            .balance-card {
                padding: 25px 20px;
            }
            
            .balance-amount {
                font-size: 36px;
            }
            
            .wallet-title {
                font-size: 28px;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .section-header {
                padding: 15px;
                font-size: 18px;
            }
            
            .section-content {
                padding: 15px;
            }
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
    </style>
@endsection

@section('content')
    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container" style="padding-bottom: 80px;">
        <!-- Header -->
        <div class="header">
            <h1 class="wallet-title">💰 محفظتي</h1>
            <p class="wallet-subtitle">إدارة أموالك بسهولة وأمان</p>
        </div>

        <!-- Balance Card -->
        <div class="balance-card">
            <div class="balance-label">الرصيد الحالي</div>
            <div class="balance-amount">
                <span class="balance-currency">$</span>
                <span id="balance">{{ number_format($wallet->balance, 2) }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="action-btn" onclick="navigateToDeposit()">
                <span class="btn-icon">💳</span>
                إيداع
            </button>
            <button class="action-btn" onclick="navigateToWithdraw()">
                <span class="btn-icon">💸</span>
                سحب
            </button>
        </div>

        <!-- Deposits Section -->
        <div class="transaction-section">
            <div class="section-header" onclick="toggleSection('deposits')">
                <div>
                    <span class="section-icon">💳</span>
                    الإيداعات
                </div>
                <span class="expand-icon" id="deposits-icon">▼</span>
            </div>
            <div class="section-content" id="deposits-content">
                @forelse($deposits as $deposit)
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-amount deposit-amount">+ ${{ number_format($deposit->amount, 2) }}</div>
                        <div class="transaction-date">{{ $deposit->created_at->format('Y/m/d') }}</div>
                        <div class="transaction-time">{{ $deposit->created_at->format('h:i A') }}</div>
                    </div>
                    <div class="transaction-status status-completed">مكتمل</div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">💳</div>
                    <p>لا توجد عمليات إيداع بعد</p>
                </div>
                @endforelse
                <button class="nav-btn" onclick="navigateToDeposit()">
                    إيداع جديد
                </button>
            </div>
        </div>

        <!-- Withdrawals Section -->
        <div class="transaction-section">
            <div class="section-header" onclick="toggleSection('withdrawals')">
                <div>
                    <span class="section-icon">💸</span>
                    السحوبات
                </div>
                <span class="expand-icon" id="withdrawals-icon">▼</span>
            </div>
            <div class="section-content" id="withdrawals-content">
                @forelse($withdrawals as $withdrawal)
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-amount withdraw-amount">- ${{ number_format($withdrawal->amount, 2) }}</div>
                        <div class="transaction-date">{{ $withdrawal->created_at->format('Y/m/d') }}</div>
                        <div class="transaction-time">{{ $withdrawal->created_at->format('h:i A') }}</div>
                    </div>
                    <div class="transaction-status status-completed">مكتمل</div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">💸</div>
                    <p>لا توجد عمليات سحب بعد</p>
                </div>
                @endforelse
                <button class="nav-btn" onclick="navigateToWithdraw()">
                    سحب جديد
                </button>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="transaction-section">
            <div class="section-header" onclick="toggleSection('history')">
                <div>
                    <span class="section-icon">📊</span>
                    السجل الكامل
                </div>
                <span class="expand-icon" id="history-icon">▼</span>
            </div>
            <div class="section-content" id="history-content">
                @forelse($transactions as $transaction)
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-amount {{ $transaction->type == 'deposit' ? 'deposit-amount' : 'withdraw-amount' }}">
                            {{ $transaction->type == 'deposit' ? '+' : '-' }} ${{ number_format($transaction->amount, 2) }}
                        </div>
                        <div class="transaction-date">
                            {{ $transaction->type == 'deposit' ? 'إيداع' : 'سحب' }} - {{ $transaction->created_at->format('Y/m/d') }}
                        </div>
                        <div class="transaction-time">{{ $transaction->created_at->format('h:i:s A') }}</div>
                        @if($transaction->description)
                        <div class="transaction-description">{{ $transaction->description }}</div>
                        @endif
                    </div>
                    <div class="transaction-status status-completed">مكتمل</div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon">📊</div>
                    <p>لا توجد معاملات بعد</p>
                    <p class="text-sm">ابدأ بإجراء عملية إيداع أو سحب</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Toggle section visibility
        function toggleSection(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const icon = document.getElementById(sectionId + '-icon');
            
            content.classList.toggle('expanded');
            
            if (content.classList.contains('expanded')) {
                icon.style.transform = 'rotate(180deg)';
                
                // Animate transaction items
                const items = content.querySelectorAll('.transaction-item');
                items.forEach((item, index) => {
                    item.style.animation = 'none';
                    setTimeout(() => {
                        item.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s both`;
                    }, 50);
                });
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Navigation functions
        function navigateToDeposit() {
            // Add loading animation
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="loading"></span> جاري التحميل...';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                alert('الانتقال إلى صفحة الإيداع');
                // window.location.href = '/deposit';
            }, 1500);
        }

        function navigateToWithdraw() {
            // Add loading animation
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="loading"></span> جاري التحميل...';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                alert('الانتقال إلى صفحة السحب');
                // window.location.href = '/withdraw';
            }, 1500);
        }

        // Auto-expand first section after page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                toggleSection('deposits');
            }, 1200);

            // Animate balance counter
            animateBalance();
            
            // Add click effects to transaction items
            addClickEffects();
        });

        // Animate balance counter
        function animateBalance() {
            const balanceElement = document.getElementById('balance');
            const targetBalance = {{ $wallet->balance }};
            let currentBalance = 0;
            const increment = targetBalance / 60;
            
            const counter = setInterval(() => {
                currentBalance += increment;
                if (currentBalance >= targetBalance) {
                    currentBalance = targetBalance;
                    clearInterval(counter);
                }
                balanceElement.textContent = currentBalance.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }, 30);
        }

        // Add click effects to transaction items
        function addClickEffects() {
            const transactionItems = document.querySelectorAll('.transaction-item');
            
            transactionItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        }

        // Add scroll reveal animation
        function revealOnScroll() {
            const sections = document.querySelectorAll('.transaction-section');
            
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    section.style.transform = 'translateY(0)';
                    section.style.opacity = '1';
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);
        
        // Initialize
        revealOnScroll();
    </script>
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>
</body>

</html>
