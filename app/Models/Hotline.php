<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'phone',
        'address',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'police'  => 'Công an / ANTT',
            'medical' => 'Y tế / Cấp cứu',
            'rescue'  => 'PCCC / Cứu hộ',
            'tdp'     => 'Tổ trưởng TDP',
            default   => 'Khác / Tổng đài',
        };
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'police'  => 'badge-police',
            'medical' => 'badge-medical',
            'rescue'  => 'badge-rescue',
            'tdp'     => 'badge-tdp',
            default   => 'badge-other',
        };
    }
}
