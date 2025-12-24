<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int               $id
 * @property string            $name
 * @property string            $slug
 * @property Collection<Event> $events
 */
class Calendar extends Model
{
    use HasFactory;

    protected $with = ['events'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getDaysOfMonthPadded($month, $year): \Illuminate\Support\Collection
    {
        $pointOfReference = CarbonImmutable::parse("{$year}-{$month}-01");
        $startOfCalendar  = $pointOfReference->startOfMonth()->startOfWeek();
        $endOfCalendar    = $pointOfReference->endOfMonth()->endOfWeek();

        $datesOfCalendar = $this->generateDatesOfCalendar($startOfCalendar, $endOfCalendar);

        return $this->getEventsPerDates($datesOfCalendar, $pointOfReference);
    }

    /**
     * The generateDatesOfCalendar method generates the list of dates between the start and end of the calendar range.
     */
    private function generateDatesOfCalendar(CarbonImmutable $startOfCalendar, CarbonImmutable $endOfCalendar): array
    {
        $walker = $startOfCalendar->toMutable();
        $walker->setTime(12, 00);
        $dates = [];

        while ($walker <= $endOfCalendar) {
            $dates[] = $walker->format('Y-m-d');
            $walker->addDay();
        }

        return $dates;
    }

    /**
     * The getEventsPerDates function maps over these dates and gets the events for each day.
     */
    private function getEventsPerDates(array $dates, CarbonImmutable $pointOfReference): \Illuminate\Support\Collection
    {
        return collect($dates)->map(function ($date) use ($pointOfReference) {
            return [
                'date'           => $date,
                'isCurrentMonth' => $pointOfReference->isSameMonth($date),
                'events'         => $this->getEventsOfDay($date),
            ];
        });
    }

    /**
     * the getEventsOfDay method retrieves the events for a specific day
     */
    private function getEventsOfDay(string $date): Collection
    {
        return $this->events()->whereDate('start_at', '=', $date)->get();
    }
}
