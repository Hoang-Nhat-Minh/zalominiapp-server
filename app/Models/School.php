<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'address',
        'phone',
        'email',
        'website',
        'latitude',
        'longitude',
        'description',
        'image',
    ];

    protected $appends = [
        'level_label',
        'level_config',
    ];

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            'kindergarten' => 'Mầm non',
            'primary' => 'Tiểu học',
            'secondary' => 'THCS',
            'high_school' => 'THPT',
            'other' => 'Khác/Liên cấp',
            default => 'Không xác định',
        };
    }

    public function getLevelConfigAttribute(): array
    {
        return match ($this->level) {
            'kindergarten' => [
                'label' => 'Mầm non',
                'class' => 'badge-rejected', // Hơi hồng/đỏ nhẹ (có sẵn trong app.scss)
                'dot' => '#EF4444',
            ],
            'primary' => [
                'label' => 'Tiểu học',
                'class' => 'badge-completed', // Xanh lá
                'dot' => '#10B981',
            ],
            'secondary' => [
                'label' => 'THCS',
                'class' => 'badge-received', // Xanh dương
                'dot' => '#3B82F6',
            ],
            'high_school' => [
                'label' => 'THPT',
                'class' => 'badge-processing', // Cam
                'dot' => '#F97316',
            ],
            'other' => [
                'label' => 'Khác/Liên cấp',
                'class' => 'badge-pending', // Tím/Vàng
                'dot' => '#D97706',
            ],
            default => [
                'label' => 'Không xác định',
                'class' => '',
                'dot' => '#94a3b8',
            ],
        };
    }
}
