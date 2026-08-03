<?php
// App\Models\ProfileTimeline

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'status',
        'title',
        'note',
    ];

    // Relationships
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
