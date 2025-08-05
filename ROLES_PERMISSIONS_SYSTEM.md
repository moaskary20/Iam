# نظام User Roles & Permissions في PHP Filament

## 📋 نظرة عامة

تم إنشاء نظام شامل لإدارة الأدوار والصلاحيات في Laravel مع Filament Admin Panel. يوفر النظام:

- ✅ إدارة الأدوار (Roles)
- ✅ إدارة الصلاحيات (Permissions) 
- ✅ ربط المستخدمين بالأدوار والصلاحيات
- ✅ واجهة إدارية متقدمة
- ✅ Middleware للحماية
- ✅ Blade Directives للعرض

## 🗂️ هيكل النظام

### Models
```
app/Models/
├── Role.php              # نموذج الأدوار
├── Permission.php        # نموذج الصلاحيات
└── User.php             # نموذج المستخدمين (محدث)
```

### Database Tables
```
├── roles                 # جدول الأدوار
├── permissions          # جدول الصلاحيات
├── role_permissions     # علاقة الأدوار والصلاحيات
├── user_roles          # علاقة المستخدمين والأدوار
└── user_permissions    # علاقة المستخدمين والصلاحيات المباشرة
```

### Filament Resources
```
app/Filament/Resources/
├── RoleResource.php        # إدارة الأدوار
├── PermissionResource.php  # إدارة الصلاحيات
└── UserResource.php       # إدارة المستخدمين (محدث)
```

### Middleware
```
app/Http/Middleware/
├── CheckPermission.php    # التحقق من الصلاحيات
└── CheckRole.php         # التحقق من الأدوار
```

### Custom Pages
```
app/Filament/Pages/
└── UserPermissionsManager.php  # صفحة إدارة صلاحيات المستخدمين
```

## 🔧 الميزات

### 1. إدارة الأدوار
- إنشاء وتعديل وحذف الأدوار
- تعيين الصلاحيات للأدوار
- تحديد أولويات الأدوار
- تفعيل/إلغاء تفعيل الأدوار
- ألوان مخصصة للأدوار

### 2. إدارة الصلاحيات
- تصنيف الصلاحيات حسب المجموعات
- أولويات الصلاحيات
- تفعيل/إلغاء تفعيل الصلاحيات
- أيقونات للصلاحيات

### 3. إدارة المستخدمين
- تعيين أدوار متعددة للمستخدم
- منح صلاحيات مباشرة للمستخدم
- عرض ملخص الصلاحيات
- فلترة حسب الأدوار والصلاحيات

### 4. الحماية والأمان
- Middleware للتحقق من الصلاحيات
- Middleware للتحقق من الأدوار
- حماية الأدوار والصلاحيات الأساسية من الحذف
- تحكم في الوصول لوحة التحكم

## 🎯 الأدوار الافتراضية

### Super Admin (مدير عام)
- لديه جميع الصلاحيات
- لا يمكن حذفه
- أولوية 1

### Admin (مدير)
- صلاحيات إدارية واسعة
- إدارة المستخدمين والمحتوى
- أولوية 10

### Moderator (مشرف)
- إشراف على المحتوى والمستخدمين
- صلاحيات محدودة
- أولوية 20

### Editor (محرر)
- تحرير المحتوى فقط
- أولوية 30

### User (مستخدم عادي)
- صلاحيات أساسية
- أولوية 100

## 📝 مجموعات الصلاحيات

### System (النظام)
- `super_admin` - مدير عام
- `access_admin` - الوصول للوحة التحكم
- `view_dashboard` - عرض الداشبورد

### Users (المستخدمين)
- `manage_users` - إدارة المستخدمين
- `create_users` - إنشاء مستخدمين
- `edit_users` - تعديل المستخدمين
- `delete_users` - حذف المستخدمين
- `view_users` - عرض المستخدمين

### Roles (الأدوار)
- `manage_roles` - إدارة الأدوار
- `create_roles` - إنشاء أدوار
- `edit_roles` - تعديل الأدوار
- `delete_roles` - حذف الأدوار
- `assign_roles` - تعيين الأدوار

### Permissions (الصلاحيات)
- `manage_permissions` - إدارة الصلاحيات
- `create_permissions` - إنشاء صلاحيات
- `edit_permissions` - تعديل الصلاحيات
- `delete_permissions` - حذف الصلاحيات

### Content (المحتوى)
- `manage_content` - إدارة المحتوى
- `create_content` - إنشاء محتوى
- `edit_content` - تعديل المحتوى
- `delete_content` - حذف المحتوى
- `publish_content` - نشر المحتوى

### Finance (المالية)
- `manage_finance` - إدارة المالية
- `view_wallets` - عرض المحافظ
- `manage_transactions` - إدارة المعاملات
- `approve_withdrawals` - موافقة السحب

### Reports (التقارير)
- `view_reports` - عرض التقارير
- `export_reports` - تصدير التقارير

### Settings (الإعدادات)
- `manage_settings` - إدارة الإعدادات
- `backup_system` - النسخ الاحتياطي

### General (عام)
- `view_profile` - عرض الملف الشخصي
- `edit_profile` - تعديل الملف الشخصي
- `change_password` - تغيير كلمة المرور

## 🛠️ طرق الاستخدام

### في Controller
```php
// التحقق من الصلاحية
if (!auth()->user()->hasPermission('manage_users')) {
    abort(403);
}

// التحقق من الدور
if (!auth()->user()->hasRole('admin')) {
    abort(403);
}

// التحقق من Super Admin
if (auth()->user()->isSuperAdmin()) {
    // كود للمدير العام
}
```

### في Routes
```php
// حماية بالصلاحية
Route::get('/admin/users', function() {
    // كود
})->middleware('permission:manage_users');

// حماية بالدور
Route::get('/admin/reports', function() {
    // كود
})->middleware('role:admin,moderator');
```

### في Blade Templates
```blade
{{-- التحقق من الصلاحية --}}
@permission('manage_users')
    <a href="/admin/users">إدارة المستخدمين</a>
@endpermission

{{-- التحقق من الدور --}}
@role('admin')
    <div>محتوى للمديرين فقط</div>
@endrole

{{-- التحقق من Super Admin --}}
@superadmin
    <div>محتوى للمدير العام فقط</div>
@endsuperadmin
```

### في User Model
```php
$user = User::find(1);

// إضافة دور
$user->assignRole('admin');

// إزالة دور
$user->removeRole('editor');

// إضافة صلاحية مباشرة
$user->givePermission('manage_users');

// إزالة صلاحية
$user->revokePermission('delete_users');

// الحصول على جميع الصلاحيات
$permissions = $user->getAllPermissions();

// التحقق من الصلاحية
if ($user->hasPermission('edit_content')) {
    // كود
}
```

## 📊 معلومات قاعدة البيانات

### بيانات المدير العام
```
Email: admin@admin.com
Password: admin123
```

## 🔄 التثبيت والإعداد

1. **تنفيذ Migrations:**
```bash
php artisan migrate
```

2. **تشغيل Seeder:**
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

3. **مسح Cache:**
```bash
php artisan route:clear
php artisan config:clear
```

4. **تشغيل Server:**
```bash
php artisan serve
```

## 🎨 واجهة Filament

### إدارة الأدوار
- عرض جميع الأدوار مع الإحصائيات
- إنشاء أدوار جديدة
- تعديل الأدوار الموجودة
- تعيين الصلاحيات للأدوار
- فلترة وبحث متقدم

### إدارة الصلاحيات
- عرض الصلاحيات مجمعة حسب النوع
- إنشاء صلاحيات جديدة
- تعديل الصلاحيات الموجودة
- فلترة حسب المجموعة والحالة

### إدارة المستخدمين
- عرض المستخدمين مع أدوارهم
- تعيين أدوار متعددة
- منح صلاحيات مباشرة
- فلترة حسب الأدوار والصلاحيات

### صفحة إدارة الصلاحيات
- واجهة سهلة لإدارة صلاحيات مستخدم محدد
- عرض ملخص جميع الصلاحيات
- تجميع الصلاحيات حسب النوع

## 🔐 الأمان

- حماية الأدوار الأساسية من الحذف
- حماية الصلاحيات الأساسية من الحذف
- تحكم في الوصول للوحة التحكم
- Middleware متقدم للحماية
- تشفير كلمات المرور
- التحقق من الهوية قبل أي عملية

## 📱 التوافق

- Laravel 11
- PHP 8.2+
- Filament 3.x
- MySQL/PostgreSQL/SQLite

## 🎯 النتيجة

تم إنشاء نظام شامل ومتقدم لإدارة الأدوار والصلاحيات يوفر:

✅ **سهولة الاستخدام** - واجهة Filament بديهية  
✅ **المرونة** - أدوار وصلاحيات قابلة للتخصيص  
✅ **الأمان** - حماية متقدمة ومتعددة المستويات  
✅ **القابلية للتطوير** - بنية قابلة للتوسع  
✅ **التوثيق الشامل** - دليل مفصل للاستخدام  

النظام جاهز للاستخدام الفوري والتطوير المستقبلي! 🚀
