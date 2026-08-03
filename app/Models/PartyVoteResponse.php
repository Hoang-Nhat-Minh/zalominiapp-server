<?php
// App\Models\PartyVoteResponse

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyVoteResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'user_id',
        'answer',
        'comment',
    ];

    // Relationships
    public function vote()
    {
        return $this->belongsTo(PartyVote::class, 'vote_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
