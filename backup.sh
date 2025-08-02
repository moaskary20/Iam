#!/bin/bash

echo "💾 عمل نسخة احتياطية من قاعدة البيانات..."

# إنشاء مجلد للنسخ الاحتياطية
mkdir -p backups

# تسمية النسخة الاحتياطية بالتاريخ والوقت
BACKUP_NAME="backup_$(date +%Y%m%d_%H%M%S).sqlite"

# نسخ قاعدة البيانات
cp database/database.sqlite "backups/$BACKUP_NAME"

echo "✅ تم إنشاء النسخة الاحتياطية: backups/$BACKUP_NAME"

# إبقاء آخر 5 نسخ احتياطية فقط
ls -t backups/backup_*.sqlite | tail -n +6 | xargs -r rm --

echo "🧹 تم حذف النسخ الاحتياطية القديمة (تم الاحتفاظ بـ 5 نسخ)"

# عرض النسخ الاحتياطية المتاحة
echo "📋 النسخ الاحتياطية المتاحة:"
ls -la backups/backup_*.sqlite 2>/dev/null || echo "لا توجد نسخ احتياطية"
