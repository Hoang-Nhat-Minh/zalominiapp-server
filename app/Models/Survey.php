<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_tdp',
        'deadline',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline'  => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order', 'asc');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function hasUserResponded(int $userId): bool
    {
        return $this->responses()->where('user_id', $userId)->exists();
    }

    public function getTargetLabelAttribute(): string
    {
        return $this->target_tdp === 'all' || empty($this->target_tdp) ? 'Toàn phường' : $this->target_tdp;
    }
}
