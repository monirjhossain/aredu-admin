<?php

namespace App\Models\Messaging;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = ['created_by', 'last_message_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    /**
     * Get the other user in a private conversation.
     */
    public function otherUser($userId = null)
    {
        $userId = $userId ?: Auth::id();
        return $this->participants()->where('users.id', '!=', $userId)->first();
    }

    /**
     * Get unread message count for a specific user.
     */
    public function unreadCountFor($userId)
    {
        $participant = $this->participants()->where('users.id', $userId)->first();

        if (!$participant) {
            return 0;
        }

        $query = $this->messages()->where('sender_id', '!=', $userId);

        if ($participant->pivot->last_read_at) {
            $query->where('created_at', '>', $participant->pivot->last_read_at);
        }

        return $query->count();
    }

    /**
     * Find existing private conversation between two users.
     */
    public static function findBetween($userIdA, $userIdB)
    {
        return static::whereHas('participants', function ($q) use ($userIdA) {
            $q->where('users.id', $userIdA);
        })->whereHas('participants', function ($q) use ($userIdB) {
            $q->where('users.id', $userIdB);
        })->first();
    }
}
