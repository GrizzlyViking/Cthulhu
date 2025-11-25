<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $summary
 * @property string $description
 * @property EventType $type
 * @property int $calendar_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Calendar $calendar
 * @property User $user
 */
class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => EventType::class,
    ];

    protected $fillable = [
        'uuid',
        'user_id',
        'summary',
        'description',
        'type',
        'calendar_id',
        'start_at',
        'end_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class);
    }
}
