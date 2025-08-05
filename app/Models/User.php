<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    public function walletTransactions()
    {
        return $this->hasManyThrough(
            \App\Models\WalletTransaction::class,
            \App\Models\Wallet::class,
            'user_id', // Foreign key on wallets table...
            'wallet_id', // Foreign key on wallet_transactions table...
            'id', // Local key on users table...
            'id' // Local key on wallets table...
        );
    }
    
    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }
    
    // إضافة دوال للحصول على بيانات المحفظة
    public function getBalanceAttribute()
    {
        return $this->wallet ? $this->wallet->balance : 0;
    }
    
    public function getTotalEarningsAttribute()
    {
        return $this->walletTransactions()
            ->where('type', 'deposit')
            ->sum('amount');
    }
    
    public function getMarketStatusAttribute()
    {
        return 'مفتوح'; // يمكن تخصيصها حسب النظام
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->invite_code)) {
                do {
                    $code = strtoupper(substr(bin2hex(random_bytes(6)), 0, 8));
                } while (self::where('invite_code', $code)->exists());
                $user->invite_code = $code;
            }
            
            // تعيين name تلقائياً إذا لم يكن موجود
            if (empty($user->name)) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                if (empty($user->name)) {
                    $user->name = $user->email; // كحل احتياطي
                }
            }
        });
        
        static::updating(function ($user) {
            // تحديث name عند تحديث first_name أو last_name
            if ($user->isDirty(['first_name', 'last_name']) && empty($user->name)) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                if (empty($user->name)) {
                    $user->name = $user->email;
                }
            }
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'country',
        'password',
        'email_verified_at',
        'invite_code',
        'is_verified',
        'profile_photo',
        'id_image_path',
        'verification_status',
        'current_market_id',
        'current_product_index',
        'purchased_products',
        'unlocked_markets',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'purchased_products' => 'array',
            'unlocked_markets' => 'array',
        ];
    }

    /**
     * Get the user's current market progress
     */
    public function getCurrentMarket()
    {
        return \App\Models\Market::find($this->current_market_id);
    }

    /**
     * Check if user can access a market
     */
    public function canAccessMarket($marketId)
    {
        return in_array($marketId, $this->unlocked_markets ?? [1]);
    }

    /**
     * Check if user has purchased a product
     */
    public function hasPurchased($productId)
    {
        return in_array($productId, $this->purchased_products ?? []);
    }

    /**
     * Purchase a product and unlock next
     */
    public function purchaseProduct($productId)
    {
        $purchasedProducts = $this->purchased_products ?? [];
        
        if (!in_array($productId, $purchasedProducts)) {
            $purchasedProducts[] = $productId;
            $this->purchased_products = $purchasedProducts;
            
            // Get current market products
            $currentMarket = $this->getCurrentMarket();
            $marketProducts = $currentMarket->products()->orderBy('id')->get();
            
            // Check if all products in current market are purchased
            $allPurchased = true;
            foreach ($marketProducts as $product) {
                if (!in_array($product->id, $purchasedProducts)) {
                    $allPurchased = false;
                    break;
                }
            }
            
            // If all products purchased, unlock next market
            if ($allPurchased) {
                $unlockedMarkets = $this->unlocked_markets ?? [1];
                $nextMarketId = $this->current_market_id + 1;
                
                if ($nextMarketId <= 5 && !in_array($nextMarketId, $unlockedMarkets)) {
                    $unlockedMarkets[] = $nextMarketId;
                    $this->unlocked_markets = $unlockedMarkets;
                    $this->current_market_id = $nextMarketId;
                    $this->current_product_index = 0;
                }
            } else {
                // Move to next product in current market
                $this->current_product_index++;
            }
            
            $this->save();
        }
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // ========================================
    // Roles & Permissions Relations
    // ========================================

    /**
     * العلاقة مع الأدوار
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الصلاحيات المباشرة
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    /**
     * التحقق من وجود دور معين
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * التحقق من وجود أي من الأدوار المحددة
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * التحقق من وجود جميع الأدوار المحددة
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->where('is_active', true)
            ->count() === count($roles);
    }

    /**
     * إضافة دور للمستخدم
     */
    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && !$this->hasRole($role->name)) {
            $this->roles()->attach($role);
        }
    }

    /**
     * إزالة دور من المستخدم
     */
    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role);
        }
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission(string $permission): bool
    {
        // فحص الصلاحيات المباشرة
        $directPermission = $this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists();

        if ($directPermission) {
            return true;
        }

        // فحص الصلاحيات عبر الأدوار
        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission)
                    ->where('is_active', true);
            })
            ->exists();
    }

    /**
     * التحقق من وجود أي من الصلاحيات المحددة
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * إضافة صلاحية مباشرة للمستخدم
     */
    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasDirectPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    /**
     * إزالة صلاحية مباشرة من المستخدم
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
     * التحقق من وجود صلاحية مباشرة (بدون الأدوار)
     */
    public function hasDirectPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * الحصول على جميع الصلاحيات (مباشرة + عبر الأدوار)
     */
    public function getAllPermissions()
    {
        $directPermissions = $this->permissions()
            ->where('is_active', true)
            ->get();

        $rolePermissions = Permission::whereHas('roles', function ($query) {
            $query->whereIn('id', $this->roles()->where('is_active', true)->pluck('id'));
        })->where('is_active', true)->get();

        return $directPermissions->merge($rolePermissions)->unique('id');
    }

    /**
     * التحقق من كونه Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasPermission('super_admin');
    }

    /**
     * التحقق من كونه Admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin');
    }

    /**
     * الحصول على أعلى دور للمستخدم
     */
    public function getHighestRole()
    {
        return $this->roles()
            ->where('is_active', true)
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // السماح لجميع المستخدمين المفعلين بالوصول للوحة التحكم
        // يمكن تخصيص هذا لاحقاً حسب الحاجة
        return $this->is_verified && $this->email_verified_at !== null;
    }
}
