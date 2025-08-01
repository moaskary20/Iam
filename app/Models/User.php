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
        'invite_code',
        'is_verified',
        'profile_photo',
        'id_image_path',
        'verification_status',
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
            'is_verified' => 'boolean',
        ];
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
