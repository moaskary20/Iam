# 🌩️ دليل إعداد Cloudflare مع Laravel

## 📋 نظرة عامة

تم إنشاء **TrustProxies middleware** للتعامل مع Cloudflare proxies وضمان اكتشاف HTTPS بشكل صحيح.

## 🛠️ المكونات المضافة

### 1️⃣ **TrustProxies Middleware**
- يدعم جميع IP ranges الخاصة بـ Cloudflare
- يجبر HTTPS scheme باستخدام X-Forwarded-Proto
- يضيف معلومات Cloudflare للـ request
- يتعامل مع CF-Visitor header

### 2️⃣ **CloudflareTestController**
- اختبار شامل لإعدادات Cloudflare
- كشف HTTPS detection
- عرض Real visitor IP
- توصيات للإعداد

### 3️⃣ **صفحة اختبار تفاعلية**
- واجهة سهلة لاختبار الإعدادات
- عرض JSON مفصل
- نسخ البيانات للحافظة

## 🚀 التثبيت والإعداد

### 1. إعدادات .env

```env
# تأكد من استخدام HTTPS
APP_URL=https://yourdomain.com
FORCE_HTTPS=true
ASSET_URL=https://yourdomain.com

# إعدادات Session آمنة
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none

# إعدادات CSRF
CSRF_COOKIE_SECURE=true
CSRF_COOKIE_SAME_SITE=none

# Trust Proxies
TRUST_PROXIES=*
CLOUDFLARE_ENABLED=true
```

### 2. إعدادات Cloudflare Dashboard

#### SSL/TLS Settings:
- **Encryption Mode:** Full (Strict) أو Full
- **Always Use HTTPS:** ON
- **HTTP Strict Transport Security:** ON

#### Network Settings:
- **HTTP/2:** ON
- **HTTP/3:** ON (اختياري)
- **0-RTT Connection Resumption:** ON

#### Page Rules (اختياري):
```
https://yourdomain.com/*
Settings: Always Use HTTPS
```

## 🔍 الاختبار

### 1. اختبار أساسي:
```bash
# زيارة صفحة الاختبار
https://yourdomain.com/test-cloudflare
```

### 2. اختبار API:
```bash
curl -H "Accept: application/json" https://yourdomain.com/test-cloudflare-api
```

### 3. فحص Headers يدوياً:
```bash
curl -I https://yourdomain.com
```

## 📊 Headers المهمة

### Cloudflare Headers:
- `CF-Ray`: معرف طلب Cloudflare
- `CF-Connecting-IP`: IP الزائر الحقيقي
- `CF-IPCountry`: بلد الزائر
- `CF-Visitor`: معلومات الاتصال (HTTP/HTTPS)

### Forwarded Headers:
- `X-Forwarded-For`: سلسلة IPs
- `X-Forwarded-Proto`: البروتوكول (http/https)
- `X-Forwarded-Host`: اسم المضيف
- `X-Forwarded-Port`: رقم المنفذ

## 🛡️ الأمان

### IP Whitelisting:
```php
// في TrustProxies middleware
protected $proxies = [
    // Cloudflare IPs محدثة تلقائياً
    '103.21.244.0/22',
    '104.16.0.0/13',
    // ... المزيد
];
```

### Headers Validation:
```php
// التحقق من صحة CF headers
if ($request->header('CF-Ray') && $request->header('CF-Connecting-IP')) {
    // طلب صالح من Cloudflare
}
```

## 🔧 استكشاف الأخطاء

### مشكلة: Laravel لا يكتشف HTTPS

**الحل:**
```php
// في TrustProxies middleware
protected function forceHttpsScheme(Request $request): void
{
    if ($request->header('X-Forwarded-Proto') === 'https') {
        $request->server->set('HTTPS', 'on');
    }
}
```

### مشكلة: Session/CSRF مع HTTPS

**الحل:**
```env
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
CSRF_COOKIE_SECURE=true
```

### مشكلة: Redirect Loops

**الحل:**
- تأكد من أن Cloudflare SSL mode = Full (Strict)
- تأكد من أن السيرفر يدعم HTTPS
- فحص Page Rules في Cloudflare

## 📈 المراقبة

### Log Headers:
```php
// في middleware
Log::info('Cloudflare Request', [
    'cf_ray' => $request->header('CF-Ray'),
    'real_ip' => $request->header('CF-Connecting-IP'),
    'country' => $request->header('CF-IPCountry'),
]);
```

### Performance Monitoring:
```php
// استخدام CF-Ray للتتبع
$cfRay = $request->header('CF-Ray');
Log::channel('performance')->info("Request {$cfRay} completed");
```

## 🎯 أفضل الممارسات

1. **استخدم Full (Strict) SSL mode** في Cloudflare
2. **فعل HSTS** لمنع downgrade attacks
3. **استخدم CF-Connecting-IP** كـ real IP
4. **اختبر بانتظام** مع `/test-cloudflare`
5. **راقب CF-Ray** في logs للتتبع

## 📞 الدعم

### لوحة تحكم الاختبار:
- `/test-cloudflare` - اختبار تفاعلي
- `/test-server` - اختبار السيرفر العام

### المساعدة:
إذا واجهت مشاكل، أرسل:
- نتائج `/test-cloudflare-api`
- إعدادات Cloudflare Dashboard
- محتوى `storage/logs/laravel.log`
