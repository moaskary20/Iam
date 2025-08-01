<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        nav.mobile-nav {
    background-color: white;
}
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
            padding-bottom: 80px; /* مسافة أسفل الصفحة للمنيو السفلي */
        }

        /* Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            left: 70%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            left: 40%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Profile Header */
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            animation: slideDown 0.8s ease-out;
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
            animation: pulse 2s infinite;
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
            animation: slideUp 0.8s ease-out;
            animation-fill-mode: both;
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
            animation: fadeIn 1s ease-out;
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
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Referral Message */
        .referral-message {
            text-align: center;
            margin-top: 30px;
            animation: bounceIn 1s ease-out;
            animation-delay: 0.5s;
            animation-fill-mode: both;
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
            animation: rotate 4s linear infinite;
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
        @media (max-width: 480px) {
            .container {
                padding: 15px;
                max-width: 100%;
            }
            
            .info-card {
                padding: 20px;
                border-radius: 15px;
            }
            
            .user-name {
                font-size: 24px;
            }
            
            .referral-text {
                font-size: 18px;
            }
            
            .dollar-amount {
                font-size: 28px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Delay animations for staggered effect */
        .info-card:nth-child(2) { animation-delay: 0.2s; }
        .info-card:nth-child(3) { animation-delay: 0.4s; }

        /* Additional styles for the profile page */
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
    <style>
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

            // Add floating animation to referral card
            const referralCard = document.querySelector('.referral-card');
            if (referralCard) {
                setInterval(() => {
                    referralCard.style.transform = 'translateY(-5px)';
                    setTimeout(() => {
                        referralCard.style.transform = 'translateY(0)';
                    }, 500);
                }, 3000);
            }
        });

        // Create particle effect
        function createParticles() {
            const canvas = document.createElement('canvas');
            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '-1';
            canvas.style.opacity = '0.3';
            document.body.appendChild(canvas);

            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            const particles = [];
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    vx: (Math.random() - 0.5) * 2,
                    vy: (Math.random() - 0.5) * 2,
                    size: Math.random() * 3 + 1,
                    opacity: Math.random() * 0.5 + 0.2
                });
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                particles.forEach(particle => {
                    particle.x += particle.vx;
                    particle.y += particle.vy;
                    
                    if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                    if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
                    
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
                    ctx.fill();
                });
                
                requestAnimationFrame(animate);
            }
            
            animate();
        }

        // Initialize particles after page load
        window.addEventListener('load', createParticles);
    </script>
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>
</body>


</html>