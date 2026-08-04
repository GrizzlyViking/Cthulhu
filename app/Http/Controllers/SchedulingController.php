<?php

namespace App\Http\Controllers;

use App\Enums\EventType;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SchedulingController extends Controller
{
    public function calendar(Calendar $calendar)
    {
        return Inertia::render('Scheduling', compact('calendar'));
    }

    public function getMonth(Calendar $calendar, Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year'  => 'required',
        ]);

        return response()->json(['calendar' => $calendar->getDaysOfMonthPadded($request->get('month'), $request->get('year'))]);
    }

    public function createEvents(Calendar $calendar, Request $request)
    {
        $validated = $request->validate([
            'days'        => 'required|array',
            'user_id'     => 'required|exists:users,id',
            'summary'     => 'required|string',
            'type'        => [Rule::enum(EventType::class)],
            'description' => 'nullable|string',
        ]);

        // Events may only be planned for users of the planner's own group.
        abort_unless(
            User::query()->inGroupOf($request->user())->whereKey($validated['user_id'])->exists(),
            403
        );

        collect($validated['days'])->each(function ($day) use ($calendar, $validated) {
            $event              = new Event();
            $event->user_id     = $validated['user_id'];
            $event->summary     = $validated['summary'];
            $event->type        = $validated['type'];
            $event->calendar_id = $calendar->id;
            $event->description = $validated['description'];
            $event->start_at    = Carbon::parse($day['date'])->setTime(17, 00);
            $event->end_at      = Carbon::parse($day['date'])->setTime(22, 00);
            $event->save();
        });

        to_route('calendar', ['calendar' => $calendar->slug]);
    }

    public function removePlanning(Calendar $calendar)
    {
        $calendar->events()->whereIn('type', ['suggestion', 'vote'])->delete();

        return to_route('calendar', $calendar->slug);
    }
}
