# 🚀 دليل حل مشكلة رفع الصور على السيرفر الخارجي

## 📋 المشكلة
خطأ 401 في رفع الصور يعني مشكلة في الصلاحيات أو إعدادات السيرفر.

## 🔧 الحلول السريعة

### 1️⃣ **تشغيل أوامر الإعداد على السيرفر:**

```bash
# انتقل لمجلد المشروع
cd /path/to/your/project

# تشغيل سكريبت الإعداد
chmod +x setup-server.sh
./setup-server.sh

# أو تشغيل الأوامر يدوياً:
php artisan storage:link
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data public
php artisan config:clear
php artisan cache:clear
```

### 2️⃣ **اختبار إعدادات السيرفر:**

اذهب إلى: `https://yourdomain.com/test-server`

### 3️⃣ **التحقق من إعدادات .env:**

```env
APP_URL=https://yourdomain.com
FILESYSTEM_DISK=public
```

### 4️⃣ **إعداد Apache/Nginx:**

#### لـ Apache (.htaccess):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# إعدادات رفع الملفات
php_value upload_max_filesize 50M
php_value post_max_size 50M
php_value max_execution_time 300
```

#### لـ Nginx:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project/public;
    
    client_max_body_size 50M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## 🛠️ **خطوات التشخيص:**

### 1. فحص Storage Link:
```bash
ls -la public/storage
```
يجب أن ترى رابط رمزي إلى `../storage/app/public`

### 2. فحص الصلاحيات:
```bash
ls -la storage/
ls -la storage/app/
ls -la storage/app/public/
```

### 3. اختبار الرفع:
```bash
# إنشاء ملف تجريبي
echo "test" > storage/app/public/test.txt

# التحقق من الوصول
curl https://yourdomain.com/storage/test.txt
```

## 🔍 **مشاكل شائعة وحلولها:**

### المشكلة: Storage link مفقود
```bash
php artisan storage:link
```

### المشكلة: صلاحيات خاطئة
```bash
sudo chmod -R 775 storage
sudo chown -R www-data:www-data storage
```

### المشكلة: إعدادات PHP
```bash
# تحقق من إعدادات PHP
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

### المشكلة: SELinux (على CentOS/RHEL)
```bash
sudo setsebool -P httpd_can_network_connect 1
sudo chcon -R -t httpd_exec_t storage/
```

## 📱 **اختبار سريع عبر المتصفح:**

1. اذهب إلى: `https://yourdomain.com/test-server`
2. اضغط على "تشغيل الاختبار"
3. اتبع التوصيات المعروضة

## 🆘 **إذا استمرت المشكلة:**

1. تحقق من logs السيرفر:
```bash
tail -f storage/logs/laravel.log
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

2. تحقق من إعدادات cPanel/WHM إذا كنت تستخدمهما

3. تواصل مع مزود الاستضافة للتأكد من:
   - دعم PHP Functions المطلوبة
   - عدم وجود قيود على الرفع
   - إعدادات mod_security

## 📞 **الدعم:**
إذا احتجت مساعدة إضافية، أرسل لي:
- رسالة الخطأ كاملة
- نتائج اختبار السيرفر
- نوع الاستضافة المستخدمة
