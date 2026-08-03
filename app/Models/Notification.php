<?php
// App\Models\Notification

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'content', 'type', 'sent_at'];

    protected $appends = [
        'type_config',
        'status_config',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function getTypeConfigAttribute(): array
    {
        return match ($this->type) {
            'emergency' => [
                'label' => 'Khẩn cấp',
                'icon' => 'ph-warning-octagon',
            ],
            'government' => [
                'label' => 'Chính quyền',
                'icon' => 'ph-bank',
            ],
            'utility' => [
                'label' => 'Tiện ích công',
                'icon' => 'ph-plugs',
            ],
            'community' => [
                'label' => 'Cộng đồng',
                'icon' => 'ph-users-three',
            ],
            default => [
                'label' => 'Khác',
                'icon' => 'ph-bell',
            ],
        };
    }

    public function getStatusConfigAttribute(): array
    {
        $status = $this->sent_at ? 'sent' : 'draft';

        return match ($status) {
            'draft' => [
                'label' => 'Bản nháp',
                'class' => 'notifications-badge-draft',
            ],
            'sent' => [
                'label' => 'Đã gửi',
                'class' => 'notifications-badge-sent',
            ],
            default => [
                'label' => 'Không xác định',
                'class' => '',
            ],
        };
    }
}