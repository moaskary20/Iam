<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعديل الملف الشخصي - تطبيق IAM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
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
            
            --success-500: #22c55e;
            --warning-500: #f59e0b;
            --error-500: #ef4444;
            --purple-500: #8b5cf6;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
            
            --gradient-primary: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-700) 100%);
            --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--gradient-purple);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .main-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            margin-bottom: 0.5rem;
            animation: slideDown 0.8s ease-out;
        }
        
        .subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.9);
            animation: slideDown 0.8s ease-out 0.2s both;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .profile-card {
            background: var(--bg-primary);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.8s ease-out 0.4s both;
        }
        
        .profile-header {
            background: var(--gradient-primary);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%); }
            100% { transform: translateX(100%) translateY(100%); }
        }
        
        .profile-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .profile-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            position: relative;
            z-index: 1;
        }
        
        .form-container {
            padding: 2rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .form-section {
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .form-section:hover {
            border-color: var(--primary-300);
            box-shadow: var(--shadow-md);
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group:last-child {
            margin-bottom: 0;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        input[type="file"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            font-size: 1rem;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
            background: var(--bg-primary);
        }
        
        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
            transform: translateY(-1px);
        }
        
        input[type="file"] {
            padding: 0.5rem;
            cursor: pointer;
        }
        
        .image-preview {
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .current-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-200);
            box-shadow: var(--shadow-md);
            animation: float 3s ease-in-out infinite;
        }
        
        .current-id-image {
            width: 120px;
            height: 80px;
            border-radius: 0.5rem;
            object-fit: cover;
            border: 2px solid var(--primary-200);
            box-shadow: var(--shadow-md);
        }
        
        .no-image {
            color: var(--text-tertiary);
            font-style: italic;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            border: 2px dashed var(--border-color);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .btn-container {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 150px;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: var(--bg-primary);
            border-color: var(--primary-300);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-600);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: var(--primary-800);
            transform: translateX(-5px);
        }
        
        .success-message {
            background: linear-gradient(135deg, var(--success-500), #16a34a);
            color: white;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.5s ease-out;
        }
        
        .error-message {
            background: linear-gradient(135deg, var(--error-500), #dc2626);
            color: white;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.5s ease-out;
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
        
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }
            
            .main-title {
                font-size: 2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .btn-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1 class="main-title">👤 تعديل الملف الشخصي</h1>
        <p class="subtitle">قم بتحديث معلوماتك الشخصية</p>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-icon">✏️</div>
                <h2 class="profile-title">تحديث البيانات</h2>
            </div>
            
            <div class="form-container">
                @if(session('success'))
                    <div class="success-message">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="error-message">
                        ❌ يرجى التحقق من البيانات المدخلة
                    </div>
                @endif
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="form-grid">
                        <!-- الصور الشخصية -->
                        <div class="form-section">
                            <div class="section-title">
                                📸 الصور الشخصية
                            </div>
                            
                            <div class="form-group">
                                <label>الصورة الشخصية</label>
                                <div class="image-preview">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="الصورة الشخصية" class="current-image">
                                    @else
                                        <div class="no-image">لا يوجد صورة شخصية</div>
                                    @endif
                                </div>
                                <input type="file" name="profile_photo" accept="image/*">
                            </div>
                            
                            <div class="form-group">
                                <label>صورة بطاقة الهوية</label>
                                <div class="image-preview">
                                    @if($user->id_image_path)
                                        <img src="{{ asset('storage/' . $user->id_image_path) }}" alt="صورة البطاقة" class="current-id-image">
                                    @else
                                        <div class="no-image">لا توجد صورة بطاقة</div>
                                    @endif
                                </div>
                                <input type="file" name="id_image_path" accept="image/*">
                            </div>
                        </div>
                        
                        <!-- المعلومات الشخصية -->
                        <div class="form-section">
                            <div class="section-title">
                                👤 المعلومات الشخصية
                            </div>
                            
                            <div class="form-group">
                                <label>الاسم الأول</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>اسم العائلة</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                            
                            <div class="form-group">
                                <label>بلد الإقامة</label>
                                <input type="text" name="country" value="{{ old('country', $user->country) }}">
                            </div>
                        </div>
                        
                        <!-- معلومات الحساب -->
                        <div class="form-section">
                            <div class="section-title">
                                📧 معلومات الحساب
                            </div>
                            
                            <div class="form-group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        
                        <!-- تغيير كلمة المرور -->
                        <div class="form-section">
                            <div class="section-title">
                                🔒 تغيير كلمة المرور
                            </div>
                            
                            <div class="form-group">
                                <label>كلمة المرور الجديدة (اختياري)</label>
                                <input type="password" name="password" placeholder="اتركه فارغاً إذا لم ترد تغيير كلمة المرور">
                            </div>
                            
                            <div class="form-group">
                                <label>تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" placeholder="أعد إدخال كلمة المرور الجديدة">
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-container">
                        <button class="btn" type="submit">
                            <span>💾 حفظ التعديلات</span>
                        </button>
                        <a href="{{ route('profile') }}" class="btn btn-secondary">
                            <span>← العودة للملف الشخصي</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
