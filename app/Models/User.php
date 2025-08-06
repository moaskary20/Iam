<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

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
        return 'مفتوح';
    }

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
            
            if (empty($user->name)) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                if (empty($user->name)) {
                    $user->name = $user->email;
                }
            }
        });
        
        static::updating(function ($user) {
            if ($user->isDirty(['first_name', 'last_name']) && empty($user->name)) {
                $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                if (empty($user->name)) {
                    $user->name = $user->email;
                }
            }
        });
    }

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'purchased_products' => 'array',
            'unlocked_markets' => 'array',
        ];
    }

    public function getUnlockedMarketsAttribute($value)
    {
        if ($value === null) {
            return [1];
        }
        return json_decode($value, true) ?: [1];
    }

    public function setUnlockedMarketsAttribute($value)
    {
        $this->attributes['unlocked_markets'] = json_encode($value);
    }

    public function getPurchasedProductsAttribute($value)
    {
        if ($value === null) {
            return [];
        }
        return json_decode($value, true) ?: [];
    }

    public function setPurchasedProductsAttribute($value)
    {
        $this->attributes['purchased_products'] = json_encode($value);
    }

    public function getCurrentMarket()
    {
        return \App\Models\Market::find($this->current_market_id);
    }

    public function canAccessMarket($marketId)
    {
        return in_array($marketId, $this->unlocked_markets ?? [1]);
    }

    public function hasPurchased($productId)
    {
        return in_array($productId, $this->purchased_products ?? []);
    }

    public function purchaseProduct($productId)
    {
        $purchasedProducts = $this->purchased_products ?? [];
        
        if (!in_array($productId, $purchasedProducts)) {
            $purchasedProducts[] = $productId;
            $this->purchased_products = $purchasedProducts;
            
            $currentMarket = $this->getCurrentMarket();
            $marketProducts = $currentMarket->products()->orderBy('id')->get();
            
            $allPurchased = true;
            foreach ($marketProducts as $product) {
                if (!in_array($product->id, $purchasedProducts)) {
                    $allPurchased = false;
                    break;
                }
            }
            
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
                $this->current_product_index++;
            }
            
            $this->save();
        }
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->where('is_active', true)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->where('is_active', true)
            ->exists();
    }

    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->where('is_active', true)
            ->count() === count($roles);
    }

    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && !$this->hasRole($role->name)) {
            $this->roles()->attach($role);
        }
    }

    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role);
        }
    }

    public function hasPermission(string $permission): bool
    {
        $directPermission = $this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists();

        if ($directPermission) {
            return true;
        }

        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission)
                    ->where('is_active', true);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasDirectPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    public function revokePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }

    public function hasDirectPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->where('is_active', true)
            ->exists();
    }

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

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasPermission('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin');
    }

    public function getHighestRole()
    {
        return $this->roles()
            ->where('is_active', true)
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * السماح لجميع المستخدمين بالدخول للوحة التحكم
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
