<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حالة التوثيق مطلوبة</title>
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 1rem; box-shadow: 0 4px 24px #0001; padding: 2.5rem 2rem; max-width: 400px; text-align: center; }
        .status { font-size: 1.2rem; color: #764ba2; margin-bottom: 1.2rem; }
        .msg { color: #333; font-size: 1.1rem; margin-bottom: 1.5rem; }
        .btn { background: #764ba2; color: #fff; border: none; border-radius: 0.5rem; padding: 0.7rem 1.5rem; font-size: 1rem; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #667eea; }
    </style>
</head>
<body>
    <div class="card">
        <div class="status">
            @if($status === 'pending')
                🚦 حسابك قيد المراجعة
            @elseif($status === 'rejected')
                ❌ تم رفض توثيق حسابك
            @endif
        </div>
        <div class="msg">
            لا يمكنك تصفح هذه الصفحة حتى يتم توثيق حسابك.<br>
            يرجى التواصل مع الدعم أو انتظار مراجعة الإدارة.
        </div>
        <a href="/" class="btn">العودة للصفحة الرئيسية</a>
    </div>
</body>
</html>
