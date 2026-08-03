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
        'title',
        'description',
        'images',
        'address',
        'status',
        'officer_note',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
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
