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
        'code',
        'type',
        'status',
        'description',
        'received_at',
        'processed_at',
    ];

    protected $casts = [
        'received_at'  => 'datetime',
        'processed_at' => 'datetime',
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
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
