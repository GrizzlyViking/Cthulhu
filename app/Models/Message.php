<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $sender_id
 * @property int    $receiver_id
 * @property bool   $read
 * @property string $content
 * @property User   $sender
 * @property User   $receiver
 */
class Message extends Model
{
    use HasFactory;

    protected $with = ['sender', 'receiver'];

    protected $appends = ['sentRelative'];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'read',
        'content',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getSentRelativeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
