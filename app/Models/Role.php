<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'priority',
        'color'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الصلاحيات
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * إضافة صلاحية للدور
     */
    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    /**
     * إزالة صلاحية من الدور
     */
    public function revokePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }

    /**
     * الحصول على جميع الصلاحيات النشطة
     */
    public function getActivePermissions()
    {
        return $this->permissions()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Scope للأدوار النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للأدوار مرتبة حسب الأولوية
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    /**
     * الحصول على عدد المستخدمين في هذا الدور
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * الحصول على عدد الصلاحيات في هذا الدور
     */
    public function getPermissionsCountAttribute(): int
    {
        return $this->permissions()->where('is_active', true)->count();
    }
}
