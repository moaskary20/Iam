#!/bin/bash

# 🚀 سكريبت إعداد السيرفر للرفع على الخادم الخارجي
# يجب تشغيل هذا السكريبت على السيرفر الخارجي

echo "🚀 بدء إعداد السيرفر للرفع..."

# 1. إنشاء symbolic link للـ storage
echo "📁 إنشاء Storage Link..."
php artisan storage:link

# 2. إنشاء المجلدات المطلوبة
echo "📂 إنشاء المجلدات المطلوبة..."
mkdir -p storage/app/public/sliders
mkdir -p storage/app/public/profiles
mkdir -p storage/app/public/products
mkdir -p storage/app/public/temp

# 3. ضبط الصلاحيات للمجلدات
echo "🔐 ضبط صلاحيات المجلدات..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/storage

# 4. تغيير مالك المجلدات
echo "👤 ضبط مالك المجلدات..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chown -R www-data:www-data public/storage

# 5. تنظيف cache
echo "🧹 تنظيف Cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 6. إعادة cache للإعدادات
echo "⚡ إعادة Cache للإعدادات..."
php artisan config:cache

echo "✅ تم إعداد السيرفر بنجاح!"
echo "📋 تأكد من:"
echo "   - أن المجلد public/storage موجود"
echo "   - أن صلاحيات storage صحيحة (775)"
echo "   - أن إعدادات .env صحيحة"
echo ""
echo "🔗 تحقق من الرابط: YOUR_DOMAIN/storage"
