# 🚀 دليل رفع مشروع IAM على السيرفر

## 📋 الخطوات الأساسية لأي سيرفر:

### 1️⃣ **رفع المشروع من GitHub:**
```bash
git clone https://github.com/moaskary20/Iam.git
cd Iam
```

### 2️⃣ **تثبيت المتطلبات:**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3️⃣ **إعداد البيئة:**
```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ **تحديث ملف .env للسيرفر:**
```properties
APP_NAME="نظام IAM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en

# قاعدة البيانات (حسب السيرفر)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5️⃣ **إعداد قاعدة البيانات:**
```bash
php artisan migrate --force
php artisan db:seed
```

### 6️⃣ **إنشاء مستخدم mo.askary@gmail.com:**
```bash
php artisan db:seed --class=CreateSpecificUserSeeder
```

### 7️⃣ **إعدادات الأمان:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

---

## 🌐 خيارات الاستضافة:

### أ) **Shared Hosting (cPanel):**
1. رفع الملفات عبر File Manager
2. رفع محتويات `/public` إلى `/public_html`
3. نقل باقي المشروع خارج `/public_html` 
4. تحديث `index.php` في `/public_html`

### ب) **VPS/Cloud Server:**
1. تثبيت PHP 8.1+, Composer, Node.js
2. إعداد Nginx/Apache
3. إعداد SSL certificate
4. تشغيل الأوامر أعلاه

### ج) **Heroku (مجاني للتطوير):**
```bash
# تثبيت Heroku CLI
heroku create iam-app-name
heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show)
git push heroku main
heroku run php artisan migrate --force
heroku run php artisan db:seed
heroku run php artisan db:seed --class=CreateSpecificUserSeeder
```

---

## 👤 بيانات تسجيل الدخول:

**🔐 المدير الأساسي:**
- البريد: `mo.askary@gmail.com`
- كلمة المرور: `newpassword`
- الرصيد: 100 دولار

**📍 رابط لوحة التحكم:**
`https://your-domain.com/admin/login`

---

## ⚙️ إعدادات إضافية:

### 🔒 **الأمان:**
- تأكد من حماية ملف `.env`
- إعداد SSL certificate
- تحديث كلمات المرور الافتراضية

### 📧 **البريد الإلكتروني:**
```properties
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 📁 **الملفات:**
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
```

---

## 🔧 استكشاف الأخطاء:

### ❌ **مشاكل شائعة:**
1. **500 Error:** تحقق من permissions و logs
2. **403 Error:** تحقق من Document Root
3. **Database Error:** تحقق من بيانات الاتصال

### 📝 **فحص الأخطاء:**
```bash
tail -f storage/logs/laravel.log
```

---

## 📞 للدعم:
راسلني في حالة وجود أي مشاكل أثناء الرفع.
