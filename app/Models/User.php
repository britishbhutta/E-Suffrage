<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // role constants (match your migration: 1 = Voter, 2 = Creator)
    public const ROLE_VOTER = 1;
    public const ROLE_CREATOR = 2;
    public const ROLE_CREATOR_MEDIA_AD = 3;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'country_id',
        'google_id',
        'email_verified_at',
        'is_active',
        'email_verification_code',
        'email_verification_sent_at',
        'email_verification_expires_at',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'email_verification_sent_at' => 'datetime',
        'email_verification_expires_at' => 'datetime',
        'role' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id','id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Convenience helpers
    public function isVoter(): bool
    {
        return (int)$this->role === self::ROLE_VOTER;
    }

    public function isCreator(): bool
    {
        return (int)$this->role === self::ROLE_CREATOR;
    }

    public function roleName(): string
    {
        return $this->isCreator() ? 'creator' : 'voter';
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
}
