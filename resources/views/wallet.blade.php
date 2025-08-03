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
                window.location.href = '/deposit';
            }, 800);
        }

        function navigateToWithdraw() {
            showWithdrawalPopup();
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
        
        // Withdrawal popup functions
        function showWithdrawalPopup() {
            document.getElementById('withdrawalPopup').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeWithdrawalPopup() {
            document.getElementById('withdrawalPopup').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function processWithdrawal() {
            const amount = parseFloat(document.getElementById('withdrawal-amount').value);
            const fullName = document.getElementById('withdrawal-fullname').value;
            const phone = document.getElementById('withdrawal-phone').value;
            const paypalEmail = document.getElementById('withdrawal-paypal-email').value;

            // Validation
            if (!amount || amount < 10) {
                alert('يرجى إدخال مبلغ صحيح (الحد الأدنى 10 دولار)');
                return;
            }

            if (!fullName || fullName.length < 3) {
                alert('يرجى إدخال الاسم الكامل');
                return;
            }

            if (!phone || phone.length < 10) {
                alert('يرجى إدخال رقم هاتف صحيح');
                return;
            }

            if (!paypalEmail || !paypalEmail.includes('@')) {
                alert('يرجى إدخال إيميل باي بال صحيح');
                return;
            }

            const maxBalance = {{ auth()->user()->balance ?? 0 }};
            if (amount > maxBalance) {
                alert(`المبلغ يتجاوز رصيدك المتاح ($${maxBalance.toFixed(2)})`);
                return;
            }

            // Confirm withdrawal
            if (!confirm(`هل أنت متأكد من سحب $${amount.toFixed(2)}؟\nسيتم معالجة الطلب خلال 48 ساعة.`)) {
                return;
            }

            // Disable button and show loading
            const btn = document.querySelector('.withdrawal-submit-btn');
            btn.disabled = true;
            btn.textContent = 'جاري المعالجة...';

            // Submit withdrawal request
            const formData = new FormData();
            formData.append('amount', amount);
            formData.append('full_name', fullName);
            formData.append('phone', phone);
            formData.append('paypal_email', paypalEmail);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/withdrawal/request', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeWithdrawalPopup();
                    location.reload(); // Refresh to show updated balance
                } else {
                    alert(data.message || 'حدث خطأ أثناء المعالجة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'تأكيد طلب السحب';
            });
        }
    </script>
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>

    <!-- Withdrawal Popup -->
    <div id="withdrawalPopup" class="popup-overlay">
        <div class="popup-content withdrawal-popup">
            <div class="popup-header">
                <h3>💰 سحب الأموال عبر باي بال</h3>
                <button class="close-btn" onclick="closeWithdrawalPopup()">×</button>
            </div>
            
            <div class="withdrawal-form">
                <div class="form-group">
                    <label>💸 المبلغ المراد سحبه (بالدولار)</label>
                    <input type="number" id="withdrawal-amount" placeholder="0.00" min="10" max="{{ auth()->user()->balance ?? 0 }}" step="0.01" required>
                    <small>الحد الأدنى: $10 - الحد الأقصى: ${{ number_format(auth()->user()->balance ?? 0, 2) }}</small>
                </div>
                
                <div class="form-group">
                    <label>👤 الاسم الكامل</label>
                    <input type="text" id="withdrawal-fullname" placeholder="الاسم كما هو مسجل في باي بال" value="{{ auth()->user()->name ?? '' }}" required>
                </div>
                
                <div class="form-group">
                    <label>📞 رقم الهاتف</label>
                    <input type="tel" id="withdrawal-phone" placeholder="+966 50 123 4567" required>
                </div>
                
                <div class="form-group">
                    <label>📧 إيميل باي بال</label>
                    <input type="email" id="withdrawal-paypal-email" placeholder="your-email@example.com" value="{{ auth()->user()->email ?? '' }}" required>
                </div>
                
                <div class="withdrawal-notice">
                    <div class="notice-header">⚠️ تنويه مهم</div>
                    <p>• سيتم معالجة طلب السحب خلال <strong>48 ساعة</strong> من تقديم الطلب</p>
                    <p>• تأكد من صحة بيانات باي بال المدخلة</p>
                    <p>• لا يمكن إلغاء الطلب بعد التقديم</p>
                    <p>• سيتم خصم المبلغ من رصيدك فوراً</p>
                </div>
                
                <button type="button" class="withdrawal-submit-btn" onclick="processWithdrawal()">
                    تأكيد طلب السحب
                </button>
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
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .popup-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .withdrawal-form {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group small {
            color: #666;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .withdrawal-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
        }

        .notice-header {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }

        .withdrawal-notice p {
            margin: 5px 0;
            color: #856404;
            font-size: 0.9rem;
        }

        .withdrawal-submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .withdrawal-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .withdrawal-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .popup-content {
                width: 95%;
                margin: 20px;
            }
            
            .withdrawal-form {
                padding: 20px;
            }
        }
    </style>
</body>

</html>
