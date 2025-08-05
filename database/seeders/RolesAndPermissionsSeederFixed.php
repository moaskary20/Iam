<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات
        $permissions = [
            // إدارة النظام
            ['name' => 'super_admin', 'display_name' => 'مدير عام', 'description' => 'صلاحيات المدير العام للنظام', 'group' => 'system', 'priority' => 1, 'icon' => 'heroicon-o-shield-check'],
            ['name' => 'access_admin', 'display_name' => 'الوصول للوحة التحكم', 'description' => 'الوصول للوحة التحكم الإدارية', 'group' => 'system', 'priority' => 2, 'icon' => 'heroicon-o-cog-6-tooth'],
            ['name' => 'view_dashboard', 'display_name' => 'عرض الداشبورد', 'description' => 'عرض لوحة المعلومات الرئيسية', 'group' => 'system', 'priority' => 3, 'icon' => 'heroicon-o-chart-bar'],
            
            // إدارة المستخدمين
            ['name' => 'manage_users', 'display_name' => 'إدارة المستخدمين', 'description' => 'إدارة حسابات المستخدمين', 'group' => 'users', 'priority' => 10, 'icon' => 'heroicon-o-users'],
            ['name' => 'create_users', 'display_name' => 'إنشاء مستخدمين', 'description' => 'إنشاء حسابات مستخدمين جديدة', 'group' => 'users', 'priority' => 11, 'icon' => 'heroicon-o-user-plus'],
            ['name' => 'edit_users', 'display_name' => 'تعديل المستخدمين', 'description' => 'تعديل بيانات المستخدمين', 'group' => 'users', 'priority' => 12, 'icon' => 'heroicon-o-pencil'],
            ['name' => 'delete_users', 'display_name' => 'حذف المستخدمين', 'description' => 'حذف حسابات المستخدمين', 'group' => 'users', 'priority' => 13, 'icon' => 'heroicon-o-trash'],
            ['name' => 'view_users', 'display_name' => 'عرض المستخدمين', 'description' => 'عرض قائمة المستخدمين', 'group' => 'users', 'priority' => 14, 'icon' => 'heroicon-o-eye'],
            
            // إدارة الأدوار والصلاحيات
            ['name' => 'manage_roles', 'display_name' => 'إدارة الأدوار', 'description' => 'إدارة أدوار المستخدمين', 'group' => 'roles', 'priority' => 20, 'icon' => 'heroicon-o-identification'],
            ['name' => 'create_roles', 'display_name' => 'إنشاء أدوار', 'description' => 'إنشاء أدوار جديدة', 'group' => 'roles', 'priority' => 21, 'icon' => 'heroicon-o-plus'],
            ['name' => 'edit_roles', 'display_name' => 'تعديل الأدوار', 'description' => 'تعديل الأدوار الموجودة', 'group' => 'roles', 'priority' => 22, 'icon' => 'heroicon-o-pencil'],
            ['name' => 'delete_roles', 'display_name' => 'حذف الأدوار', 'description' => 'حذف الأدوار', 'group' => 'roles', 'priority' => 23, 'icon' => 'heroicon-o-trash'],
            ['name' => 'assign_roles', 'display_name' => 'تعيين الأدوار', 'description' => 'تعيين الأدوار للمستخدمين', 'group' => 'roles', 'priority' => 24, 'icon' => 'heroicon-o-user-group'],
            
            ['name' => 'manage_permissions', 'display_name' => 'إدارة الصلاحيات', 'description' => 'إدارة صلاحيات النظام', 'group' => 'permissions', 'priority' => 30, 'icon' => 'heroicon-o-key'],
            ['name' => 'create_permissions', 'display_name' => 'إنشاء صلاحيات', 'description' => 'إنشاء صلاحيات جديدة', 'group' => 'permissions', 'priority' => 31, 'icon' => 'heroicon-o-plus-circle'],
            ['name' => 'edit_permissions', 'display_name' => 'تعديل الصلاحيات', 'description' => 'تعديل الصلاحيات الموجودة', 'group' => 'permissions', 'priority' => 32, 'icon' => 'heroicon-o-pencil-square'],
            ['name' => 'delete_permissions', 'display_name' => 'حذف الصلاحيات', 'description' => 'حذف الصلاحيات', 'group' => 'permissions', 'priority' => 33, 'icon' => 'heroicon-o-x-circle'],
            
            // إدارة المحتوى
            ['name' => 'manage_content', 'display_name' => 'إدارة المحتوى', 'description' => 'إدارة محتوى الموقع', 'group' => 'content', 'priority' => 40, 'icon' => 'heroicon-o-document-text'],
            ['name' => 'create_content', 'display_name' => 'إنشاء محتوى', 'description' => 'إنشاء محتوى جديد', 'group' => 'content', 'priority' => 41, 'icon' => 'heroicon-o-plus'],
            ['name' => 'edit_content', 'display_name' => 'تعديل المحتوى', 'description' => 'تعديل المحتوى الموجود', 'group' => 'content', 'priority' => 42, 'icon' => 'heroicon-o-pencil'],
            ['name' => 'delete_content', 'display_name' => 'حذف المحتوى', 'description' => 'حذف المحتوى', 'group' => 'content', 'priority' => 43, 'icon' => 'heroicon-o-trash'],
            ['name' => 'publish_content', 'display_name' => 'نشر المحتوى', 'description' => 'نشر وإلغاء نشر المحتوى', 'group' => 'content', 'priority' => 44, 'icon' => 'heroicon-o-paper-airplane'],
            
            // إدارة المالية
            ['name' => 'manage_finance', 'display_name' => 'إدارة المالية', 'description' => 'إدارة النظام المالي', 'group' => 'finance', 'priority' => 50, 'icon' => 'heroicon-o-banknotes'],
            ['name' => 'view_wallets', 'display_name' => 'عرض المحافظ', 'description' => 'عرض محافظ المستخدمين', 'group' => 'finance', 'priority' => 51, 'icon' => 'heroicon-o-wallet'],
            ['name' => 'manage_transactions', 'display_name' => 'إدارة المعاملات', 'description' => 'إدارة المعاملات المالية', 'group' => 'finance', 'priority' => 52, 'icon' => 'heroicon-o-credit-card'],
            ['name' => 'approve_withdrawals', 'display_name' => 'موافقة السحب', 'description' => 'الموافقة على طلبات السحب', 'group' => 'finance', 'priority' => 53, 'icon' => 'heroicon-o-check-circle'],
            
            // إدارة التقارير
            ['name' => 'view_reports', 'display_name' => 'عرض التقارير', 'description' => 'عرض تقارير النظام', 'group' => 'reports', 'priority' => 60, 'icon' => 'heroicon-o-chart-pie'],
            ['name' => 'export_reports', 'display_name' => 'تصدير التقارير', 'description' => 'تصدير التقارير بصيغ مختلفة', 'group' => 'reports', 'priority' => 61, 'icon' => 'heroicon-o-arrow-down-tray'],
            
            // إدارة الإعدادات
            ['name' => 'manage_settings', 'display_name' => 'إدارة الإعدادات', 'description' => 'إدارة إعدادات النظام', 'group' => 'settings', 'priority' => 70, 'icon' => 'heroicon-o-cog'],
            ['name' => 'backup_system', 'display_name' => 'النسخ الاحتياطي', 'description' => 'إنشاء واستعادة النسخ الاحتياطية', 'group' => 'settings', 'priority' => 71, 'icon' => 'heroicon-o-server'],
            
            // صلاحيات عامة للمستخدمين
            ['name' => 'view_profile', 'display_name' => 'عرض الملف الشخصي', 'description' => 'عرض الملف الشخصي', 'group' => 'general', 'priority' => 80, 'icon' => 'heroicon-o-user'],
            ['name' => 'edit_profile', 'display_name' => 'تعديل الملف الشخصي', 'description' => 'تعديل بيانات الملف الشخصي', 'group' => 'general', 'priority' => 81, 'icon' => 'heroicon-o-user-circle'],
            ['name' => 'change_password', 'display_name' => 'تغيير كلمة المرور', 'description' => 'تغيير كلمة مرور الحساب', 'group' => 'general', 'priority' => 82, 'icon' => 'heroicon-o-lock-closed'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']], 
                $permissionData
            );
        }

        // إنشاء الأدوار
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير عام',
                'description' => 'المدير العام للنظام بصلاحيات كاملة',
                'priority' => 1,
                'color' => '#DC2626',
                'permissions' => ['super_admin'] // سيحصل على جميع الصلاحيات
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'description' => 'مدير النظام بصلاحيات إدارية',
                'priority' => 10,
                'color' => '#7C2D12',
                'permissions' => [
                    'access_admin', 'view_dashboard', 'manage_users', 'create_users', 
                    'edit_users', 'view_users', 'manage_content', 'create_content', 
                    'edit_content', 'delete_content', 'publish_content', 'view_reports',
                    'manage_finance', 'view_wallets', 'manage_transactions'
                ]
            ],
            [
                'name' => 'moderator',
                'display_name' => 'مشرف',
                'description' => 'مشرف المحتوى والمستخدمين',
                'priority' => 20,
                'color' => '#059669',
                'permissions' => [
                    'access_admin', 'view_dashboard', 'view_users', 'edit_users',
                    'manage_content', 'create_content', 'edit_content', 'publish_content',
                    'view_reports'
                ]
            ],
            [
                'name' => 'editor',
                'display_name' => 'محرر',
                'description' => 'محرر المحتوى',
                'priority' => 30,
                'color' => '#2563EB',
                'permissions' => [
                    'access_admin', 'view_dashboard', 'manage_content', 
                    'create_content', 'edit_content', 'view_reports'
                ]
            ],
            [
                'name' => 'user',
                'display_name' => 'مستخدم عادي',
                'description' => 'مستخدم عادي بصلاحيات أساسية',
                'priority' => 100,
                'color' => '#6B7280',
                'permissions' => [
                    'view_profile', 'edit_profile', 'change_password'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']], 
                $roleData
            );

            // ربط الصلاحيات بالدور
            if ($roleData['name'] === 'super_admin') {
                // المدير العام يحصل على جميع الصلاحيات
                $allPermissions = Permission::where('is_active', true)->get();
                $role->permissions()->sync($allPermissions->pluck('id'));
            } else {
                $permissionIds = Permission::whereIn('name', $permissions)
                    ->where('is_active', true)
                    ->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        }

        // تعيين أدوار للمستخدمين الموجودين
        $users = User::all();
        
        // إنشاء مستخدم super admin إذا لم يكن موجود
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now()
        ]);

        // تعيين دور super_admin للمستخدم الأول
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole && !$superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole($superAdminRole);
        }

        // تعيين دور user للمستخدمين الآخرين
        $userRole = Role::where('name', 'user')->first();
        foreach ($users as $user) {
            if ($user->id !== $superAdmin->id && $user->roles()->count() === 0) {
                $user->assignRole($userRole);
            }
        }

        $this->command->info('✅ تم إنشاء الأدوار والصلاحيات بنجاح!');
        $this->command->info('📧 Super Admin: admin@admin.com');
        $this->command->info('🔑 Password: admin123');
    }
}
