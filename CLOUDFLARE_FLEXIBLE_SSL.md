# 🌩️ دليل Cloudflare Flexible SSL مع Laravel

## ⚠️ ما هو Flexible SSL؟

**Cloudflare Flexible SSL** يعني:
- **المستخدم ← Cloudflare:** HTTPS ✅ (مشفر)
- **Cloudflare ← السيرفر:** HTTP ❌ (غير مشفر)

هذا يتطلب إعدادات خاصة لأن Laravel يستلم طلبات HTTP لكن المستخدمين يصلون عبر HTTPS.

## 🛠️ التعديلات المطلوبة

### 1️⃣ **TrustProxies Middleware** (تم تعديله)

```php
// التحقق من X-Forwarded-Proto من Cloudflare
if ($forwardedProto === 'https') {
    $_SERVER['HTTPS'] = 'on';  // إجبار Laravel على اكتشاف HTTPS
    $_SERVER['SERVER_PORT'] = 443;
    $_SERVER['REQUEST_SCHEME'] = 'https';
}

// إعداد خاص لـ Flexible SSL
if ($request->header('CF-Ray') && !$request->isSecure()) {
    // إذا كان من Cloudflare ولم يكتشف HTTPS، أجبره
    $_SERVER['HTTPS'] = 'on';
}
```

### 2️⃣ **AppServiceProvider** (تم تعديله)

```php
// إجبار HTTPS للـ URLs المولدة
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    URL::forceScheme('https');
}
```

### 3️⃣ **إعدادات .env**

```env
# ✅ صحيح للـ Flexible SSL
APP_URL=https://yourdomain.com
FORCE_HTTPS=true

# ⚠️ مهم: لا تستخدم SECURE cookies مع Flexible
SESSION_SECURE_COOKIE=false
CSRF_COOKIE_SECURE=false
SESSION_SAME_SITE=lax
CSRF_COOKIE_SAME_SITE=lax

TRUST_PROXIES=*
CLOUDFLARE_SSL_MODE=flexible
```

## 🔧 إعدادات Cloudflare Dashboard

### SSL/TLS Settings:
```
Encryption Mode: Flexible
Always Use HTTPS: ON
Automatic HTTPS Rewrites: ON
```

### Page Rules (اختياري):
```
Pattern: http://yourdomain.com/*
Setting: Always Use HTTPS
```

## ⚡ مشاكل شائعة وحلولها

### 1. **Redirect Loop**
```
المشكلة: اللوب اللانهائي بين HTTP و HTTPS
الحل: تأكد من أن Always Use HTTPS مفعل في Cloudflare فقط
```

### 2. **Mixed Content Warnings**
```
المشكلة: بعض الـ assets تحمل عبر HTTP
الحل: استخدم URL::forceScheme('https') في AppServiceProvider
```

### 3. **Session/CSRF Issues**
```
المشكلة: مشاكل في الجلسات والـ CSRF
الحل: SESSION_SECURE_COOKIE=false مع Flexible SSL
```

### 4. **Livewire Upload Issues**
```
المشكلة: خطأ 401 في رفع الملفات
الحل: middleware: ['web'] فقط، بدون 'auth'
```

## 🎯 اختبار الإعداد

### 1. اختبار HTTPS Detection:
```bash
# زيارة صفحة الاختبار
https://yourdomain.com/test-cloudflare
```

### 2. فحص Headers:
```php
// في Controller
dd([
    'is_secure' => request()->isSecure(),
    'scheme' => request()->getScheme(),
    'x_forwarded_proto' => request()->header('X-Forwarded-Proto'),
    'cf_visitor' => request()->header('CF-Visitor'),
]);
```

### 3. اختبار URLs:
```php
// تأكد من أن هذه تعطي HTTPS URLs
echo url('/');           // https://yourdomain.com
echo asset('css/app.css'); // https://yourdomain.com/css/app.css
echo route('home');      // https://yourdomain.com/
```

## 🛡️ الأمان مع Flexible SSL

### ⚠️ تحذيرات مهمة:
- الاتصال بين Cloudflare والسيرفر **غير مشفر**
- لا تنقل بيانات حساسة بدون Full SSL
- استخدم Full SSL للمواقع التجارية

### ✅ توصيات:
- ترقية لـ **Full SSL** أو **Full (Strict)** عند الإمكان
- استخدم شهادة SSL مجانية (Let's Encrypt)
- فعل **HSTS** في Cloudflare

## 🚀 الترقية لـ Full SSL

### 1. احصل على شهادة SSL:
```bash
# مع Let's Encrypt
certbot --nginx -d yourdomain.com
```

### 2. غير الإعدادات في Cloudflare:
```
SSL/TLS → Overview → Full أو Full (Strict)
```

### 3. عدل .env:
```env
# بعد الترقية لـ Full SSL
SESSION_SECURE_COOKIE=true
CSRF_COOKIE_SECURE=true
SESSION_SAME_SITE=none
CSRF_COOKIE_SAME_SITE=none
```

## 📋 خلاصة Flexible SSL

### ✅ الايجابيات:
- سهل الإعداد
- لا يحتاج شهادة SSL على السيرفر
- مجاني

### ❌ السلبيات:
- الاتصال بين Cloudflare والسيرفر غير مشفر
- مشاكل في Session cookies
- أقل أماناً

### 🎯 النصيحة:
استخدم **Flexible SSL** للبداية، ثم ارتقي لـ **Full SSL** لاحقاً للحصول على أمان أفضل.

## 📞 اختبار سريع:

1. اذهب لـ: `/test-cloudflare`
2. تأكد من:
   - `is_secure: true`
   - `scheme: https`
   - `x_forwarded_proto: https`
3. جرب رفع صورة في Filament
