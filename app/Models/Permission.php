<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
        'is_active',
        'priority',
        'icon'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    /**
     * العلاقة مع الأدوار
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    /**
     * العلاقة مع المستخدمين (صلاحيات مباشرة)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withTimestamps();
    }

    /**
     * Scope للصلاحيات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للصلاحيات حسب المجموعة
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope للصلاحيات مرتبة حسب الأولوية
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    /**
     * الحصول على جميع المجموعات المتاحة
     */
    public static function getGroups(): array
    {
        return self::distinct('group')
            ->whereNotNull('group')
            ->pluck('group')
            ->toArray();
    }

    /**
     * الحصول على عدد الأدوار التي تحتوي على هذه الصلاحية
     */
    public function getRolesCountAttribute(): int
    {
        return $this->roles()->count();
    }

    /**
     * الحصول على عدد المستخدمين الذين لديهم هذه الصلاحية مباشرة
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * تنسيق اسم الصلاحية
     */
    public function getFormattedNameAttribute(): string
    {
        return ucfirst(str_replace(['_', '-'], ' ', $this->name));
    }
}
