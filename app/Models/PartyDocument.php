<?php
// App\Models\PartyDocument

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'file_path',
        'category',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    protected $appends = [
        'category_config',
        'status_config',
        'file_info',
        'file_icon',
    ];

    public function getCategoryConfigAttribute(): array
    {
        return match ($this->category) {
            'meeting' => [
                'label' => 'Họp chi bộ',
                'class' => 'documents-tag-meeting',
            ],
            'directive' => [
                'label' => 'Chỉ thị',
                'class' => 'documents-tag-directive',
            ],
            'resolution' => [
                'label' => 'Nghị quyết',
                'class' => 'documents-tag-resolution',
            ],
            'report' => [
                'label' => 'Báo cáo',
                'class' => 'documents-tag-report',
            ],
            'form' => [
                'label' => 'Biểu mẫu',
                'class' => 'documents-tag-form',
            ],
            'guide' => [
                'label' => 'Hướng dẫn',
                'class' => 'documents-tag-guide',
            ],
            default => [
                'label' => 'Khác',
                'class' => '',
            ],
        };
    }

    public function getStatusConfigAttribute(): array
    {
        return match ($this->status) {
            'draft' => [
                'label' => 'Bản nháp',
                'class' => 'documents-badge-draft',
            ],
            'published' => [
                'label' => 'Đã xuất bản',
                'class' => 'documents-badge-published',
            ],
            'archived' => [
                'label' => 'Lưu trữ',
                'class' => 'documents-badge-archived',
            ],
            default => [
                'label' => 'Không xác định',
                'class' => '',
            ],
        };
    }

    public function getFileInfoAttribute(): object
    {
        $name = basename($this->file_path);
        $format = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        return (object)[
            'name' => $name,
            'format' => $format,
            'size' => 'N/A'
        ];
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->file_info->format) {
            'pdf' => 'ph-file-pdf',
            'doc', 'docx' => 'ph-file-doc',
            'xls', 'xlsx' => 'ph-file-xls',
            'ppt', 'pptx' => 'ph-file-ppt',
            default => 'ph-file',
        };
    }
}
