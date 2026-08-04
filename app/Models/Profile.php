<?php
// App\Models\Profile

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'officer_id',
        'title',
        'code',
        'type',
        'household_type',
        'income_per_capita',
        'housing_status',
        'household_code',
        'event_type',
        'department',
        'status',
        'description',
        'received_at',
        'processed_at',
    ];

    protected $casts = [
        'received_at'       => 'datetime',
        'processed_at'      => 'datetime',
        'income_per_capita' => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function timelines()
    {
        return $this->hasMany(ProfileTimeline::class)->orderBy('created_at', 'asc');
    }

    // Helpers
    public function getHouseholdTypeLabelAttribute(): string
    {
        return match ($this->household_type) {
            'poor'      => 'Hộ nghèo',
            'near_poor' => 'Hộ cận nghèo',
            'policy'    => 'Gia đình chính sách',
            default     => 'Hộ thường',
        };
    }

    public function getEventTypeLabelAttribute(): string
    {
        return match ($this->event_type) {
            'birth'    => 'Khai sinh / Nhập khẩu',
            'death'    => 'Khai tử / Xóa đăng ký',
            'move_in'  => 'Đăng ký thường trú/tạm trú',
            'move_out' => 'Tạm vắng / Chuyển đi',
            'split'    => 'Tách hộ khẩu',
            default    => 'Thủ tục hành chính',
        };
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
