<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>الملف الشخصي</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            padding-bottom: 100px; /* مسافة أسفل الصفحة للمنيو السفلي */
            margin: 0;
            width: 100%;
            max-width: 100vw;
        }

        /* Simple Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.05;
            background: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.2) 0%, transparent 50%),
                        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }        /* Profile Header */
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            /* animation: slideDown 0.8s ease-out; */
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

        .profile-avatar {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .avatar-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            padding: 5px;
            position: relative;
            /* animation: pulse 2s infinite; */
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(255, 107, 107, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #666;
        }

        .user-name {
            font-size: 28px;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }

        /* Card Styles */
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            /* animation: slideUp 0.8s ease-out; */
            /* animation-fill-mode: both; */
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
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

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item:hover {
            background: rgba(102, 126, 234, 0.05);
            margin: 0 -15px;
            padding: 15px;
            border-radius: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-value {
            color: #666;
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            /* animation: fadeIn 1s ease-out; */
        }

        .status-pending {
            background: linear-gradient(45deg, #ffd93d, #ff8f00);
            color: white;
        }

        .status-approved {
            background: linear-gradient(45deg, #4caf50, #45a049);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
        }

        .status-default {
            background: #f0f0f0;
            color: #666;
        }

        /* Invite Code */
        .invite-code {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
        }

        .invite-code::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            /* animation: shine 3s infinite; */
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Referral Message */
        .referral-message {
            text-align: center;
            margin-top: 30px;
            /* animation: bounceIn 1s ease-out; */
            /* animation-delay: 0.5s; */
            /* animation-fill-mode: both; */
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .referral-card {
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            color: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .referral-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, rgba(255,255,255,0.1), transparent 30%);
            /* animation: rotate 4s linear infinite; */
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .referral-text {
            font-size: 20px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .dollar-amount {
            font-size: 32px;
            font-weight: 900;
            display: block;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Icons */
        .icon {
            width: 20px;
            height: 20px;
            opacity: 0.7;
        }

        /* Responsive */
        /* Media Queries for Mobile Devices */

        /* Tablets and Small Laptops */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                max-width: 100%;
            }

            .page-title {
                font-size: 32px;
                margin-bottom: 20px;
                text-align: center;
            }

            .profile-card {
                margin: 0 0 20px 0;
                padding: 20px;
                border-radius: 15px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .profile-avatar {
                margin-bottom: 15px;
            }

            .profile-info h2 {
                font-size: 24px;
                margin-bottom: 5px;
            }

            .wallet-info {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin-top: 20px;
            }

            .info-item {
                padding: 12px 0;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-item:hover {
                margin: 0 -10px;
                padding: 12px 10px;
            }

            .info-label {
                font-size: 14px;
                color: #666;
            }

            .info-value {
                font-size: 16px;
                font-weight: 600;
                color: #333;
            }
        }

        /* Mobile Phones (Portrait) */
        @media (max-width: 480px) {
            body {
                padding-bottom: 120px;
                font-size: 14px;
            }

            .container {
                padding: 10px;
            }

            .page-title {
                font-size: 28px;
                margin-bottom: 15px;
                padding: 0 10px;
            }

            .profile-card {
                margin: 0 0 15px 0;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .profile-header {
                gap: 10px;
                padding-bottom: 15px;
            }

            .profile-avatar img {
                width: 80px;
                height: 80px;
                border: 3px solid #fff;
            }

            .profile-info h2 {
                font-size: 20px;
                margin-bottom: 3px;
                line-height: 1.2;
            }

            .profile-info p {
                font-size: 14px;
                margin: 2px 0;
            }

            .wallet-info {
                grid-template-columns: 1fr;
                gap: 10px;
                margin-top: 15px;
            }

            .wallet-item {
                padding: 12px;
                text-align: center;
                border-radius: 10px;
            }

            .wallet-item .amount {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 5px;
            }

            .wallet-item .label {
                font-size: 12px;
                opacity: 0.8;
            }

            .info-item {
                padding: 10px 0;
                border-bottom: 1px solid rgba(0,0,0,0.08);
            }

            .info-item:hover {
                margin: 0 -8px;
                padding: 10px 8px;
                border-radius: 8px;
            }

            .info-label {
                font-size: 13px;
                font-weight: 500;
            }

            .info-value {
                font-size: 15px;
                font-weight: 600;
                margin-top: 3px;
            }

            .status-badge {
                padding: 4px 8px;
                font-size: 12px;
                border-radius: 15px;
            }

            /* Action buttons if any */
            .btn {
                padding: 10px 20px;
                font-size: 14px;
                border-radius: 8px;
                width: 100%;
                margin: 5px 0;
            }
        }

        /* Small Mobile Phones */
        @media (max-width: 360px) {
            .container {
                padding: 8px;
            }

            .page-title {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .profile-card {
                padding: 12px;
                margin: 0 0 12px 0;
            }

            .profile-avatar img {
                width: 70px;
                height: 70px;
                border: 2px solid #fff;
            }

            .profile-info h2 {
                font-size: 18px;
            }

            .profile-info p {
                font-size: 13px;
            }

            .wallet-item {
                padding: 10px;
            }

            .wallet-item .amount {
                font-size: 18px;
            }

            .wallet-item .label {
                font-size: 11px;
            }

            .info-item {
                padding: 8px 0;
            }

            .info-label {
                font-size: 12px;
            }

            .info-value {
                font-size: 14px;
            }

            .status-badge {
                padding: 3px 6px;
                font-size: 11px;
            }
        }

        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            body {
                padding-bottom: 80px;
            }

            .profile-header {
                flex-direction: row;
                text-align: left;
                align-items: center;
                gap: 20px;
            }

            .profile-avatar {
                margin-bottom: 0;
            }

            .wallet-info {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        /* Additional Mobile Optimizations */
        @media (max-width: 480px) {
            /* Prevent horizontal scroll */
            * {
                box-sizing: border-box;
            }

            html, body {
                overflow-x: hidden;
                width: 100%;
                max-width: 100vw;
            }

            /* Improve touch targets */
            .info-item {
                min-height: 44px;
                touch-action: manipulation;
            }

            /* Better spacing for cards */
            .card {
                margin: 8px 0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            /* Optimize text for mobile */
            body {
                font-size: 16px; /* Prevent zoom on iOS */
                line-height: 1.4;
            }

            /* Improve button spacing */
            .btn + .btn {
                margin-top: 10px;
            }

            /* Better form elements if any */
            input, select, textarea {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 12px;
                border-radius: 8px;
            }
        }

        /* Ultra small screens */
        @media (max-width: 320px) {
            .container {
                padding: 5px;
            }

            .profile-card {
                padding: 10px;
                border-radius: 8px;
            }

            .page-title {
                font-size: 20px;
                padding: 0 5px;
            }

            .profile-avatar img {
                width: 60px;
                height: 60px;
            }

            .profile-info h2 {
                font-size: 16px;
            }

            .wallet-item .amount {
                font-size: 16px;
            }

            .info-label {
                font-size: 11px;
            }

            .info-value {
                font-size: 13px;
            }
        }

        /* Original mobile CSS (keeping existing) */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            .card {
                padding: 15px;
                margin: 10px 0;
            }
            h1 {
                font-size: 24px;
            }
        }        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Delay animations for staggered effect */
        /* .info-card:nth-child(2) { animation-delay: 0.2s; } */
        /* .info-card:nth-child(3) { animation-delay: 0.4s; } */

        /* Hide mobile nav on desktop */
        @media (min-width: 768px) {
            .d-block.d-md-none {
                display: none !important;
            }
        }
    </style>
</head>
<body>


    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar" style="position: relative;">
                <div class="avatar-container">
                    @if(!empty($user->profile_photo))
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="صورة المستخدم" class="avatar-img">
                    @else
                        <div class="avatar-img">👤</div>
                    @endif
                </div>
                <!-- Edit Icon Button -->
                <a href="{{ route('profile.edit') }}" title="تعديل البيانات" style="position: absolute; left: 0; bottom: 0; background: #fff; border-radius: 50%; box-shadow: 0 2px 8px #0002; padding: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6" />
                        <rect x="3" y="17" width="18" height="4" rx="2" fill="#eee" stroke="#ccc"/>
                        <rect x="7" y="13" width="10" height="4" rx="2" fill="#eee" stroke="#ccc"/>
                    </svg>
                </a>
            </div>
            <h2 class="user-name">{{ $user->first_name }} {{ $user->last_name }}</h2>
        </div>

        <!-- User Details Card -->
        <div class="info-card">
            <div class="info-item">
                <div class="info-label">
                    <span>👤</span>
                    <strong>الاسم الأول:</strong>
                </div>
                <div class="info-value">{{ $user->first_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>👥</span>
                    <strong>اسم العائلة:</strong>
                </div>
                <div class="info-value">{{ $user->last_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>📧</span>
                    <strong>البريد الإلكتروني:</strong>
                </div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>📱</span>
                    <strong>رقم الهاتف:</strong>
                </div>
                <div class="info-value">{{ $user->phone ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>🌍</span>
                    <strong>بلد الإقامة:</strong>
                </div>
                <div class="info-value">{{ $user->country ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>✅</span>
                    <strong>حالة التوثيق:</strong>
                </div>
                <div class="info-value">
                    @if($user->verification_status === 'pending')
                        <span class="status-badge status-pending">قيد المراجعة</span>
                    @elseif($user->verification_status === 'approved')
                        <span class="status-badge status-approved">تم التوثيق</span>
                    @elseif($user->verification_status === 'rejected')
                        <span class="status-badge status-rejected">مرفوض</span>
                    @else
                        <span class="status-badge status-default">-</span>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <span>🎟️</span>
                    <strong>كود الدعوة:</strong>
                </div>
                <div class="info-value">
                    <span class="invite-code">{{ $user->invite_code ?? '---' }}</span>
                </div>
            </div>
        </div>

        <!-- Referral Message -->
        <div class="referral-message">
            <div class="referral-card">
                <div class="referral-text">
                    اكسب
                    <span class="dollar-amount">$10</span>
                    على كل مستخدم تقوم بدعوته
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate info items on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Add hover effects to info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add click effect to invite code
            const inviteCode = document.querySelector('.invite-code');
            if (inviteCode) {
                inviteCode.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                    
                    // Show copied message (if needed)
                    const originalText = this.textContent;
                    this.textContent = 'تم النسخ!';
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 1000);
                });
            }
        });

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('فشل في نسخ النص: ', err);
            }
            
            document.body.removeChild(textArea);
        }
    </script>
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>
</body>
</html>