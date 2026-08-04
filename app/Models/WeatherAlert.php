<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'level',
        'content',
        'area',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at'  => 'datetime',
        'end_at'    => 'datetime',
    ];

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            'info'    => 'Thông tin thời tiết',
            'warning' => 'Cảnh báo thời tiết',
            'danger'  => 'Cực đoan - Nguy hiểm khẩn cấp',
            default   => 'Thông báo',
        };
    }

    public function getLevelBgAttribute(): string
    {
        return match ($this->level) {
            'info'    => '#EBF1FF',
            'warning' => '#FFFBEB',
            'danger'  => '#FEF2F2',
            default   => '#F3F4F6',
        };
    }

    public function getLevelColorAttribute(): string
    {
        return match ($this->level) {
            'info'    => '#0057FF',
            'warning' => '#D97706',
            'danger'  => '#EF4444',
            default   => '#374151',
        };
    }
}
