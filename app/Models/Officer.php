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
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}