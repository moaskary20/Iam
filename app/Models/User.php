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

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // السماح لجميع المستخدمين بالدخول لـ admin panel
        return true;
    }
}
