#!/bin/bash

# سكريبت نشر Laravel مع Let's Encrypt + Cloudflare
# استخدم هذا السكريبت بعد تثبيت Certbot وإنشاء شهادة Let's Encrypt

echo "🚀 بدء عملية النشر مع Let's Encrypt..."

# التأكد من وجود دليل المشروع
PROJECT_DIR="/var/www/iam"
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ دليل المشروع غير موجود: $PROJECT_DIR"
    exit 1
fi

cd $PROJECT_DIR

echo "📦 تحديث المشروع من Git..."
git pull origin main

echo "🔧 تثبيت Dependencies..."
composer install --no-dev --optimize-autoloader
npm install --production

echo "⚙️ إعداد ملف البيئة..."
if [ ! -f ".env" ]; then
    cp .env.production .env
    echo "✅ تم نسخ .env.production إلى .env"
else
    echo "⚠️ ملف .env موجود بالفعل"
fi

# إعداد APP_KEY إذا لم يكن موجود
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 إنشاء APP_KEY..."
    php artisan key:generate --force
fi

echo "🗄️ إعداد قاعدة البيانات..."
php artisan migrate --force

echo "🔗 إنشاء storage link..."
php artisan storage:link

echo "🧹 تنظيف الـ Cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ تحسين الأداء..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📁 إعداد الصلاحيات..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

echo "🔒 إعداد Let's Encrypt مع Nginx..."

# إنشاء إعداد Nginx محدث
cat > /etc/nginx/sites-available/iam << 'EOF'
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    # إعادة توجيه كل HTTP إلى HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/iam/public;
    index index.php index.html index.htm;

    # إعدادات Let's Encrypt SSL
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    # إعدادات SSL قوية
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # إعدادات خاصة بـ Cloudflare
    # الحصول على IP الحقيقي من Cloudflare
    set_real_ip_from 103.21.244.0/22;
    set_real_ip_from 103.22.200.0/22;
    set_real_ip_from 103.31.4.0/22;
    set_real_ip_from 104.16.0.0/13;
    set_real_ip_from 104.24.0.0/14;
    set_real_ip_from 108.162.192.0/18;
    set_real_ip_from 131.0.72.0/22;
    set_real_ip_from 141.101.64.0/18;
    set_real_ip_from 162.158.0.0/15;
    set_real_ip_from 172.64.0.0/13;
    set_real_ip_from 173.245.48.0/20;
    set_real_ip_from 188.114.96.0/20;
    set_real_ip_from 190.93.240.0/20;
    set_real_ip_from 197.234.240.0/22;
    set_real_ip_from 198.41.128.0/17;
    set_real_ip_from 2400:cb00::/32;
    set_real_ip_from 2606:4700::/32;
    set_real_ip_from 2803:f800::/32;
    set_real_ip_from 2405:b500::/32;
    set_real_ip_from 2405:8100::/32;
    set_real_ip_from 2a06:98c0::/29;
    set_real_ip_from 2c0f:f248::/32;
    real_ip_header CF-Connecting-IP;

    # إعدادات أمان
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options DENY always;
    add_header X-Content-Type-Options nosniff always;
    add_header X-XSS-Protection "1; mode=block" always;

    # إعدادات Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # إعدادات خاصة بـ Livewire
        fastcgi_param HTTPS on;
        fastcgi_param SERVER_PORT 443;
        fastcgi_param REQUEST_SCHEME https;
    }

    # إعدادات الملفات الثابتة
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        add_header Vary Accept-Encoding;
        access_log off;
    }

    # حماية الملفات الحساسة
    location ~ /\. {
        deny all;
    }
}
EOF

echo "🔄 إعادة تشغيل Nginx..."
nginx -t && systemctl reload nginx

echo "🔄 إعادة تشغيل PHP-FPM..."
systemctl restart php8.2-fpm

echo "🧪 اختبار الإعدادات..."
curl -I https://yourdomain.com/ | head -5

echo "✅ تم النشر بنجاح!"
echo ""
echo "📋 الخطوات التالية:"
echo "1. تحديث yourdomain.com في ملف Nginx إلى النطاق الفعلي"
echo "2. تحديث APP_URL في ملف .env"
echo "3. في Cloudflare، تأكد من إعداد SSL/TLS إلى 'Full (strict)' بدلاً من 'Flexible'"
echo "4. اختبار رفع الملفات في Filament"
echo ""
echo "🔗 روابط مفيدة:"
echo "- إدارة Filament: https://yourdomain.com/admin"
echo "- اختبار SSL: https://www.ssllabs.com/ssltest/"
