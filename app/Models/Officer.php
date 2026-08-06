<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Officer extends Authenticatable
{
    use Notifiable;

    protected $table = 'officers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'phone',
        'status',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}