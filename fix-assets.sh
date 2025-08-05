#!/bin/bash

# سكريپت إصلاح مشاكل Assets على السيرفر

echo "🔧 إصلاح مشاكل Assets على السيرفر..."

PROJECT_DIR="/var/www/iam"
cd $PROJECT_DIR

echo "1️⃣ تنظيف وإعادة بناء Assets..."

# تنظيف cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# إعادة بناء assets
npm install
npm run build

echo "2️⃣ إصلاح مشاكل Livewire Assets..."

# التأكد من وجود livewire assets
php artisan livewire:publish --config
php artisan livewire:publish --assets

echo "3️⃣ إعداد صلاحيات الملفات..."

# إعداد صلاحيات صحيحة
chown -R www-data:www-data $PROJECT_DIR/public
chmod -R 755 $PROJECT_DIR/public
chmod -R 755 $PROJECT_DIR/storage
chmod -R 755 $PROJECT_DIR/bootstrap/cache

echo "4️⃣ إنشاء Storage Link..."

# إعادة إنشاء storage link
php artisan storage:link --force

echo "5️⃣ تحسين Nginx للـ Assets..."

# إعداد Nginx لخدمة الملفات الثابتة بشكل صحيح
cat > /etc/nginx/sites-available/iam-assets << 'EOF'
# إعدادات إضافية للـ Assets
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|map)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    add_header Vary Accept-Encoding;
    access_log off;
    
    # إعداد خاص لملفات Livewire
    location ~* livewire.*\.js$ {
        expires 1d;
        add_header Cache-Control "public";
        add_header X-Content-Type-Options nosniff;
        add_header X-Frame-Options DENY;
    }
    
    # منع حجب AdBlocker
    location ~* (livewire|alpine|app)\.(js|css)$ {
        expires 1d;
        add_header Cache-Control "public";
        add_header X-Robots-Tag "noindex, nofollow";
    }
}

# إعداد خاص لـ Livewire upload
location ~ ^/livewire/(livewire\.js|livewire\.min\.js) {
    expires 1d;
    add_header Cache-Control "public";
    add_header Access-Control-Allow-Origin "*";
    add_header X-Content-Type-Options nosniff;
}
EOF

echo "Deploying asset fixes..."

# إنشاء مجلدات الـ assets إذا لم تكن موجودة
mkdir -p public/js
mkdir -p public/css

# نسخ الملفات الآمنة
echo "Copying safe assets..."
cp public/js/safe-loader.js public/js/safe-loader.js.bak 2>/dev/null || true
cp public/css/safe-styles.css public/css/safe-styles.css.bak 2>/dev/null || true
cp public/livewire-fallback.html public/livewire-fallback.html.bak 2>/dev/null || true

# إنشاء symlinks للـ fallback
echo "Creating fallback symlinks..."
ln -sf /var/www/iam/public/js/safe-loader.js /var/www/iam/public/js/app.js 2>/dev/null || true
ln -sf /var/www/iam/public/css/safe-styles.css /var/www/iam/public/css/app.css 2>/dev/null || true

# تحديث Nginx configuration
echo "Updating Nginx configuration..."
sudo cp nginx-config-updated.conf /etc/nginx/sites-available/iam
sudo nginx -t && sudo systemctl reload nginx

# تطبيق Laravel changes
echo "Applying Laravel changes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إعطاء صلاحيات للملفات
echo "Setting file permissions..."
chmod 644 public/js/*.js
chmod 644 public/css/*.css
chmod 644 public/*.html
chown www-data:www-data public/js/*.js
chown www-data:www-data public/css/*.css
chown www-data:www-data public/*.html

echo "Asset fixes deployed successfully!"
echo "Safe assets created:"
echo "- public/js/safe-loader.js (AdBlocker-resistant)"
echo "- public/css/safe-styles.css (Core styles)"
echo "- public/livewire-fallback.html (Livewire backup)"
echo "Please check your application now."
