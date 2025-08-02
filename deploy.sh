#!/bin/bash

echo "🚀 بدء عملية رفع مشروع IAM على السيرفر..."

# إعداد المتطلبات
echo "📦 تثبيت المتطلبات..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

# إعداد البيئة
echo "⚙️ إعداد البيئة..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "⚠️  يرجى تحديث ملف .env بإعدادات السيرفر"
fi

# توليد مفتاح التطبيق
php artisan key:generate --force

# إعداد قاعدة البيانات
echo "🗄️ إعداد قاعدة البيانات..."
php artisan migrate --force

# تشغيل seeders الأساسية
echo "📋 إنشاء البيانات الأساسية..."
php artisan db:seed --force

# إنشاء المستخدم المطلوب
echo "👤 إنشاء مستخدم mo.askary@gmail.com..."
php artisan db:seed --class=CreateSpecificUserSeeder --force

# تحسين الأداء
echo "⚡ تحسين الأداء..."
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# إعداد الصلاحيات
echo "🔒 إعداد الصلاحيات..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# إنشاء رابط التخزين
php artisan storage:link

echo ""
echo "✅ تم رفع المشروع بنجاح!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔗 رابط لوحة التحكم: https://your-domain.com/admin/login"
echo ""
echo "👤 بيانات تسجيل الدخول:"
echo "   📧 البريد: mo.askary@gmail.com"
echo "   🔑 كلمة المرور: newpassword"
echo "   💰 الرصيد: 100 دولار"
echo ""
echo "📋 المستخدمين الاختباريين:"
echo "   📧 user1@example.com إلى user5@example.com"
echo "   🔑 كلمة المرور: password"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
