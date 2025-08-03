
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - IAM Shop</title>
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
        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
        }

        /* Glassmorphism Card */
        .login-card {
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

        .login-card::before {
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
        .login-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
        }

        .login-logo {
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

        .login-logo i {
            font-size: 35px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .login-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            margin-bottom: 0;
        }

        /* Form Styling */
        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 10px;
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

        input[type="email"], 
        input[type="password"] {
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

        /* Show/Hide Password */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 16px;
            z-index: 2;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: white;
        }

        /* Remember Me Checkbox */
        .remember-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .custom-checkbox {
            position: relative;
            width: 20px;
            height: 20px;
        }

        .custom-checkbox input {
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            transition: var(--transition);
        }

        .custom-checkbox input:checked ~ .checkmark {
            background: var(--secondary-gradient);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .checkmark::after {
            content: "";
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox input:checked ~ .checkmark::after {
            display: block;
        }

        .remember-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin: 0;
        }

        .forgot-password {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: white;
            text-shadow: 0 2px 8px rgba(255, 255, 255, 0.5);
        }
        /* Submit Button */
        .login-btn {
            width: 100%;
            background: var(--secondary-gradient);
            color: white;
            border: none;
            padding: 18px 0;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 25px;
            transition: var(--transition);
            box-shadow: 0 8px 25px rgba(118, 75, 162, 0.4);
            position: relative;
            overflow: hidden;
            font-family: 'Cairo', sans-serif;
        }

        .login-btn::before {
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

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(118, 75, 162, 0.6);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer Links */
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }

        .register-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            font-size: 16px;
        }

        .register-link:hover {
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
        @media (max-width: 500px) {
            .login-container {
                max-width: 100%;
                margin: 0 10px;
            }

            .login-card {
                padding: 30px 25px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-subtitle {
                font-size: 14px;
            }

            input[type="email"], 
            input[type="password"] {
                padding: 12px 12px 12px 40px;
                font-size: 14px;
            }

            .login-btn {
                padding: 15px 0;
                font-size: 16px;
            }
        }

        @media (max-width: 400px) {
            body {
                padding: 10px;
            }

            .login-card {
                padding: 25px 20px;
            }

            .bg-shapes {
                display: none;
            }
        }

        /* Animation delays for form elements */
        .form-group:nth-child(1) { animation: slideInLeft 0.6s ease-out 0.1s both; }
        .form-group:nth-child(2) { animation: slideInRight 0.6s ease-out 0.2s both; }
        .remember-section { animation: slideInLeft 0.6s ease-out 0.3s both; }
        .login-btn { animation: slideInUp 0.6s ease-out 0.4s both; }

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

        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
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

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h1 class="login-title">تسجيل الدخول</h1>
                <p class="login-subtitle">أهلاً بك مرة أخرى في IAM Shop</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="أدخل بريدك الإلكتروني"
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="أدخل كلمة المرور"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-section">
                    <div class="checkbox-container">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkmark"></span>
                        </label>
                        <label for="remember" class="remember-label">تذكرني</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i>
                    تسجيل الدخول
                </button>
            </form>

            <div class="login-footer">
                <a class="register-link" href="{{ route('register') }}">
                    <i class="fas fa-user-plus" style="margin-left: 5px;"></i>
                    ليس لديك حساب؟ إنشاء حساب جديد
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation and enhancement
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
                return;
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('يرجى إدخال بريد إلكتروني صحيح');
                return;
            }

            // Add loading state to button
            const submitBtn = this.querySelector('.login-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i> جاري تسجيل الدخول...';
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

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    </script>
</body>
</html>
