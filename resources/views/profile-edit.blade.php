<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الملف الشخصي</title>
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f8fafc; min-height: 100vh; }
        .edit-container { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px #0001; padding: 32px; }
        .edit-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem; }
        .btn { background: linear-gradient(90deg, #0ea5e9, #0369a1); color: #fff; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; width: 100%; margin-top: 1rem; }
        .btn:hover { background: linear-gradient(90deg, #0369a1, #0ea5e9); }
        .back-link { display: block; text-align: center; margin-top: 1.5rem; color: #0ea5e9; text-decoration: underline; }
    </style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');
</head>
<body>
    <div class="edit-container">
        <div class="edit-title">تعديل الملف الشخصي</div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="form-group">
                <label>الصورة الشخصية الحالية</label>
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="الصورة الشخصية" style="width:80px;height:80px;border-radius:50%;object-fit:cover;display:block;margin-bottom:8px;">
                @else
                    <span style="color:#888">لا يوجد صورة</span>
                @endif
                <input type="file" name="profile_photo" accept="image/*">
            </div>
            <div class="form-group">
                <label>صورة البطاقة الحالية</label>
                @if($user->id_image_path)
                    <img src="{{ asset('storage/' . $user->id_image_path) }}" alt="صورة البطاقة" style="width:100px;height:60px;object-fit:cover;display:block;margin-bottom:8px;">
                @else
                    <span style="color:#888">لا يوجد صورة</span>
                @endif
                <input type="file" name="id_image_path" accept="image/*">
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
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="form-group">
                <label>بلد الإقامة</label>
                <input type="text" name="country" value="{{ old('country', $user->country) }}">
            </div>
            <div class="form-group">
                <label>كلمة المرور الجديدة (اختياري)</label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation">
            </div>
            <button class="btn" type="submit">حفظ التعديلات</button>
        </form>
        <a href="{{ route('profile') }}" class="back-link">العودة للملف الشخصي</a>
    </div>
</body>
</html>
