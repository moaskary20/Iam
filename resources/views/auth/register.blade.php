
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - IAM Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --secondary-gradient: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow-primary: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --success-color: #48bb78;
            --error-color: #f56565;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }
        /* Animated Background Elements */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 12s ease-in-out infinite;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .shape:nth-child(1) { 
            width: 200px; 
            height: 200px; 
            left: 5%; 
            top: 10%; 
            animation-delay: 0s; 
        }
        .shape:nth-child(2) { 
            width: 150px; 
            height: 150px; 
            right: 15%; 
            top: 60%; 
            animation-delay: 4s; 
        }
        .shape:nth-child(3) { 
            width: 100px; 
            height: 100px; 
            left: 75%; 
            top: 25%; 
            animation-delay: 8s; 
        }
        .shape:nth-child(4) { 
            width: 180px; 
            height: 180px; 
            right: 70%; 
            bottom: 20%; 
            animation-delay: 12s; 
        }
        .shape:nth-child(5) { 
            width: 120px; 
            height: 120px; 
            left: 20%; 
            bottom: 30%; 
            animation-delay: 2s; 
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
                opacity: 0.1;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg) scale(1.1); 
                opacity: 0.2;
            }
        }

        /* Main Container */
        .register-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
        }

        /* Glassmorphism Card */
        .register-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-primary);
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.1) 50%, 
                transparent 100%);
            animation: shimmer 3s infinite;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }
        /* Header Section */
        .register-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
        }

        .register-logo {
            width: 80px;
            height: 80px;
            background: var(--secondary-gradient);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(118, 75, 162, 0.3);
            animation: pulse 2s infinite;
        }

        .register-logo i {
            font-size: 35px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .register-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .register-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            margin-bottom: 0;
        }

        /* Form Styling */
        .register-form {
            width: 100%;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            z-index: 2;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        input[type="tel"], 
        input[type="file"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            font-size: 16px;
            color: white;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
        }

        input[type="file"] {
            padding: 15px;
            cursor: pointer;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        input:focus {
            outline: none;
            border: 2px solid rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        input:focus + .input-icon {
            color: white;
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: var(--transition);
            border-radius: 2px;
        }

        .strength-weak { background: var(--error-color); width: 33%; }
        .strength-medium { background: #f6ad55; width: 66%; }
        .strength-strong { background: var(--success-color); width: 100%; }
        /* Submit Button */
        .register-btn {
            width: 100%;
            background: var(--secondary-gradient);
            color: white;
            border: none;
            padding: 18px 0;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 25px;
            margin-bottom: 20px;
            transition: var(--transition);
            box-shadow: 0 8px 25px rgba(118, 75, 162, 0.4);
            position: relative;
            overflow: hidden;
            font-family: 'Cairo', sans-serif;
        }

        .register-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.2) 50%, 
                transparent 100%);
            transition: var(--transition);
        }

        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(118, 75, 162, 0.6);
        }

        .register-btn:hover::before {
            left: 100%;
        }

        .register-btn:active {
            transform: translateY(-1px);
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer Links */
        .register-footer {
            text-align: center;
            margin-top: 25px;
        }

        .login-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            font-size: 16px;
        }

        .login-link:hover {
            color: white;
            text-shadow: 0 2px 8px rgba(255, 255, 255, 0.5);
        }

        /* Error/Success Messages */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(245, 101, 101, 0.2);
            border-color: rgba(245, 101, 101, 0.4);
            color: #feb2b2;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.2);
            border-color: rgba(72, 187, 120, 0.4);
            color: #9ae6b4;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .register-container {
                max-width: 100%;
                margin: 0 10px;
            }

            .register-card {
                padding: 30px 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .register-title {
                font-size: 24px;
            }

            .register-subtitle {
                font-size: 14px;
            }

            input[type="text"], 
            input[type="email"], 
            input[type="password"], 
            input[type="tel"], 
            input[type="file"] {
                padding: 12px 12px 12px 40px;
                font-size: 14px;
            }

            .register-btn {
                padding: 15px 0;
                font-size: 16px;
            }
        }

        @media (max-width: 400px) {
            body {
                padding: 10px;
            }

            .register-card {
                padding: 25px 20px;
            }

            .bg-shapes {
                display: none;
            }
        }

        /* Dark theme adjustments */
        @media (prefers-color-scheme: dark) {
            :root {
                --text-primary: #e2e8f0;
                --text-secondary: #cbd5e0;
            }
        }

        /* Animation delays for form elements */
        .form-group:nth-child(1) { animation: slideInLeft 0.6s ease-out 0.1s both; }
        .form-group:nth-child(2) { animation: slideInRight 0.6s ease-out 0.2s both; }
        .form-group:nth-child(3) { animation: slideInLeft 0.6s ease-out 0.3s both; }
        .form-group:nth-child(4) { animation: slideInRight 0.6s ease-out 0.4s both; }
        .form-group:nth-child(5) { animation: slideInLeft 0.6s ease-out 0.5s both; }

        @keyframes slideInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="register-title">إنشاء حساب جديد</h1>
                <p class="register-subtitle">انضم إلى مجتمع IAM Shop واستمتع بتجربة تسوق مميزة</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form class="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">الاسم الأول*</label>
                        <div class="input-container">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="first_name" name="first_name" placeholder="أدخل اسمك الأول" required value="{{ old('first_name') }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">الاسم الأخير*</label>
                        <div class="input-container">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="last_name" name="last_name" placeholder="أدخل اسمك الأخير" required value="{{ old('last_name') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" placeholder="example@domain.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">كلمة المرور*</label>
                        <div class="input-container">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="أدخل كلمة مرور قوية" required>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور*</label>
                        <div class="input-container">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="country">الدولة</label>
                        <div class="input-container">
                            <i class="fas fa-globe input-icon"></i>
                            <input type="text" id="country" name="country" placeholder="اختر دولتك" value="{{ old('country') }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <div class="input-container">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="phone" name="phone" placeholder="+966 50 123 4567" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="profile_photo">الصورة الشخصية</label>
                        <div class="input-container">
                            <i class="fas fa-camera input-icon"></i>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_image_path">صورة البطاقة</label>
                        <div class="input-container">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="file" id="id_image_path" name="id_image_path" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="invite_code">كود الدعوة (اختياري)</label>
                    <div class="input-container">
                        <i class="fas fa-gift input-icon"></i>
                        <input type="text" id="invite_code" name="invite_code" placeholder="أدخل كود الدعوة إن وجد" value="{{ old('invite_code') }}">
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus" style="margin-left: 8px;"></i>
                    إنشاء حساب جديد
                </button>
            </form>

            <div class="register-footer">
                <a class="login-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt" style="margin-left: 5px;"></i>
                    لديك حساب بالفعل؟ تسجيل الدخول
                </a>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check password criteria
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength bar
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Form validation and enhancement
        document.querySelector('.register-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('كلمات المرور غير متطابقة');
                return;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
                return;
            }

            // Add loading state to button
            const submitBtn = this.querySelector('.register-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i> جاري إنشاء الحساب...';
            submitBtn.disabled = true;
        });

        // Enhanced input animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
