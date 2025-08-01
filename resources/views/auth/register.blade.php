
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
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
        .shape:nth-child(1) { width: 120px; height: 120px; left: 10%; top: 15%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 180px; height: 180px; right: 10%; top: 50%; animation-delay: 3s; }
        .shape:nth-child(3) { width: 90px; height: 90px; left: 70%; top: 20%; animation-delay: 6s; }
        .shape:nth-child(4) { width: 150px; height: 150px; right: 60%; bottom: 15%; animation-delay: 9s; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        .register-card {
            background: rgba(255,255,255,0.97);
            border-radius: 1.2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .register-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #764ba2;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        .register-form {
            width: 100%;
        }
        .form-group {
            margin-bottom: 1.2rem;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #333;
            font-weight: 600;
        }
        input[type="text"], input[type="email"], input[type="tel"], input[type="file"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 1rem;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
            border: 1.5px solid #764ba2;
            outline: none;
        }
        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: 0.8rem 0;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px #764ba222;
        }
        .register-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 4px 16px #764ba244;
        }
        .login-link {
            margin-top: 1.2rem;
            color: #764ba2;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-link:hover {
            color: #0ea5e9;
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="register-card">
        <div class="register-title">إنشاء حساب جديد</div>
        <form class="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="first_name">الاسم الأول*</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">الاسم الأخير*</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور*</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور*</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="country">الدولة</label>
                <input type="text" id="country" name="country">
            </div>
            <div class="form-group">
                <label for="phone">رقم الهاتف مع كود الدولة</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="profile_photo">الصورة الشخصية</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
            </div>
            <div class="form-group">
                <label for="id_image_path">صورة البطاقة</label>
                <input type="file" id="id_image_path" name="id_image_path" accept="image/*">
            </div>
            <div class="form-group">
                <label for="invite_code">كود الدعوة للمشاركة</label>
                <input type="text" id="invite_code" name="invite_code">
            </div>
            <button type="submit" class="register-btn">إنشاء حساب</button>
        </form>
        <a class="login-link" href="{{ route('login') }}">لديك حساب بالفعل؟ تسجيل الدخول</a>
    </div>
</body>
</html>
