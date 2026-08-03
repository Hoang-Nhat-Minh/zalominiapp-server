<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'zalo_id',
        'avatar',
        'citizen_code',
        'address',
        'role',
        'is_verified',
        'last_login_at',
    ];

    protected $casts = [
        'is_verified'   => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'role_config',
    ];

    // Relationships
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function aiChats()
    {
        return $this->hasMany(AiChat::class);
    }

    public function partyVoteResponses()
    {
        return $this->hasMany(PartyVoteResponse::class);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOfficer(): bool
    {
        return $this->role === 'officer';
    }

    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    public function wardNotifications()
    {
        return $this->belongsToMany(Notification::class)
            ->withPivot('is_read', 'read_at', 'is_acknowledged', 'acknowledged_at')
            ->withTimestamps()
            ->orderByDesc('sent_at');
    }


    public function getRoleConfigAttribute(): array
    {
        return match ($this->role) {
            'citizen' => [
                'label' => 'Công dân',
                'class' => 'citizens-badge-citizen',
            ],
            'officer' => [
                'label' => 'Cán bộ',
                'class' => 'citizens-badge-officer',
            ],
            'admin' => [
                'label' => 'Quản trị viên',
                'class' => 'citizens-badge-admin',
            ],
            default => [
                'label' => 'Không xác định',
                'class' => '',
            ],
        };
    }
}
