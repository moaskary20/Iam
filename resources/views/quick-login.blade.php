<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول سريع - اختبار رفع الملفات</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">🚀 تسجيل دخول سريع</h1>
            <p class="text-gray-600">للوصول لصفحة اختبار رفع الملفات</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني:</label>
                <input type="email" name="email" required 
                       value="test@example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور:</label>
                <input type="password" name="password" required 
                       value="password123"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                🔐 تسجيل الدخول
            </button>
        </form>

        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2">📋 بيانات اختبار جاهزة:</h3>
            <div class="text-sm text-blue-700 space-y-1">
                <p><strong>مستخدم عادي:</strong></p>
                <p>📧 test@example.com</p>
                <p>🔑 password123</p>
                
                <p class="mt-3"><strong>مستخدم مدير:</strong></p>
                <p>📧 admin@example.com</p>
                <p>🔑 admin123</p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('upload.test.page') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                🎯 الذهاب لصفحة اختبار رفع الملفات مباشرة
            </a>
        </div>

        @if(auth()->check())
            <div class="mt-4 p-4 bg-green-50 rounded-lg text-center">
                <p class="text-green-800">✅ أنت مسجل دخول باسم: <strong>{{ auth()->user()->name }}</strong></p>
                <a href="{{ route('upload.test.page') }}" 
                   class="inline-block mt-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    🚀 الذهاب لصفحة الاختبار
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block mt-2 mr-2">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        🚪 تسجيل خروج
                    </button>
                </form>
            </div>
        @endif
    </div>

    <script>
        // تلقائياً تسجيل الدخول إذا لم يكن مسجل
        @if(!auth()->check())
            setTimeout(function() {
                document.querySelector('form').submit();
            }, 2000);
        @endif
    </script>
</body>
</html>
