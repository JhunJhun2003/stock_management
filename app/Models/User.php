<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_HOME = 'home';
    public const ROLE_SHOP = 'shop';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || ($this->role === null && (bool) $this->is_admin);
    }

    public function isHome(): bool
    {
        return $this->role === self::ROLE_HOME;
    }

    public function isShop(): bool
    {
        return $this->role === self::ROLE_SHOP;
    }

    public function isSeller(): bool
    {
        return !$this->isAdmin();
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_HOME => 'Home',
            self::ROLE_SHOP => 'Shop',
            default => 'Admin',
        };
    }


    public function getFullNameAttribute()
    {
        return $this->name;
    }
}