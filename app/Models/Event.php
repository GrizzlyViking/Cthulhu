<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $summary
 * @property string $description
 * @property int $calendar_id
 * @property DateTime $start_at
 * @property DateTime $end_at
 *
 * @property Calendar $calendar
 * @property User $user
 */
class Event extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
