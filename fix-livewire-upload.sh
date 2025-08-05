#!/bin/bash

# 🔧 سكريبت إصلاح مشكلة رفع Livewire على السيرفر الخارجي

echo "🚀 بدء إصلاح مشكلة رفع Livewire..."

# 1. تنظيف cache
echo "🧹 تنظيف Cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan livewire:publish --config

# 2. إنشاء مجلد livewire-tmp
echo "📂 إنشاء مجلدات Livewire..."
mkdir -p storage/app/public/livewire-tmp
mkdir -p storage/app/public/temp

# 3. ضبط صلاحيات مجلدات Livewire
echo "🔐 ضبط صلاحيات Livewire..."
chmod -R 775 storage/app/public/livewire-tmp
chmod -R 775 storage/app/public/temp
chown -R www-data:www-data storage/app/public/livewire-tmp
chown -R www-data:www-data storage/app/public/temp

# 4. التأكد من storage link
echo "🔗 فحص Storage Link..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ تم إنشاء Storage Link"
else
    echo "✅ Storage Link موجود"
fi

# 5. إعادة cache للإعدادات
echo "⚡ إعادة Cache..."
php artisan config:cache

# 6. اختبار الإعدادات
echo "🔍 اختبار الإعدادات..."
php artisan tinker --execute="
echo 'Storage disk: ' . config('filesystems.default') . PHP_EOL;
echo 'Livewire disk: ' . config('livewire.temporary_file_upload.disk') . PHP_EOL;
echo 'Upload directory: ' . storage_path('app/public/livewire-tmp') . PHP_EOL;
echo 'Directory writable: ' . (is_writable(storage_path('app/public/livewire-tmp')) ? 'Yes' : 'No') . PHP_EOL;
"

echo ""
echo "✅ تم إصلاح إعدادات Livewire!"
echo "📋 للتأكد من الإصلاح:"
echo "   - اذهب إلى /test-server"
echo "   - جرب رفع صورة في Filament"
echo "   - تحقق من logs: storage/logs/laravel.log"
