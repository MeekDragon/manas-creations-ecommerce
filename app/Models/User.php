<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'mobile',
        'is_admin',
        'is_superadmin',
        'email_verified_at',
        'mobile_verified_at',
        'mobile_otp_code',
        'mobile_otp_expires_at',
        'mobile_otp_sent_at',
        'email_otp_code',
        'email_otp_expires_at',
        'email_otp_sent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mobile_otp_code',
        'mobile_otp_expires_at',
        'mobile_otp_sent_at',
        'email_otp_code',
        'email_otp_expires_at',
        'email_otp_sent_at',
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
            'mobile_verified_at' => 'datetime',
            'mobile_otp_expires_at' => 'datetime',
            'mobile_otp_sent_at' => 'datetime',
            'email_otp_expires_at' => 'datetime',
            'email_otp_sent_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_superadmin' => 'boolean',
        ];
    }

    public function inquiries()
    {
        return $this->hasMany(\App\Models\Inquiry::class);
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function hasVerifiedMobile(): bool
    {
        return $this->mobile_verified_at !== null;
    }

    public function isVerified(): bool
    {
        return $this->hasVerifiedEmail();
    }
}
