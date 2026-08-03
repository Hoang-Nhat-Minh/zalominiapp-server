<?php
// App\Models\PartyVote

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function responses()
    {
        return $this->hasMany(PartyVoteResponse::class, 'vote_id');
    }

    // Helpers
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function hasVoted(int $userId): bool
    {
        return $this->responses()->where('user_id', $userId)->exists();
    }
}
