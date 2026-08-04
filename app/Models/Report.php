<?php
// App\Models\Report

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'assigned_department',
        'title',
        'description',
        'images',
        'address',
        'status',
        'officer_note',
        'resolved_at',
    ];

    protected $casts = [
        'images'      => 'array',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'environment'       => 'Môi trường',
            'urban_order'       => 'Trật tự đô thị',
            'traffic'           => 'Giao thông',
            'infrastructure'    => 'Hạ tầng',
            'electricity_water' => 'Điện lực - Cấp thoát nước',
            default             => 'An ninh trật tự',
        };
    }

    public function getAssignedDepartmentLabelAttribute(): string
    {
        return $this->assigned_department ?: match ($this->category) {
            'environment'       => 'Bộ phận Tài nguyên & Môi trường',
            'urban_order'       => 'Công an & Trật tự đô thị',
            'traffic'           => 'Đội Cảnh sát giao thông & Đô thị',
            'infrastructure'    => 'Bộ phận Quản lý Hạ tầng',
            'electricity_water' => 'Bộ phận Điện lực - Cấp thoát nước',
            default             => 'Tổ công tác chuyên trách',
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
