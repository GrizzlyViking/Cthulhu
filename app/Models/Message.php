<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $subject
 * @property string $message
 * @property boolean $read
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property User $sender
 * @property User $receiver
 */
class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
        'read',
    ];

    use HasFactory;

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
