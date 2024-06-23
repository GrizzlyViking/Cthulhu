<?php

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;

beforeEach(function () {
    if (app()->environment() !== 'testing') {
        dump(app()->environment());
        throw new Exception('Not in testing environment!');
    }
});

test('create event', function () {
    $event = Event::factory()->create();

    expect($event)->toBeInstanceOf(Event::class)
        ->and($event->user)->toBeInstanceOf(User::class)
        ->and($event->calendar)->toBeInstanceOf(Calendar::class)
        ->and($event->start_at)->toBeInstanceOf(\DateTime::class);
});

test('create month with leading and trailing days', function () {
    $calendar = Calendar::factory()->create();
    $month = $calendar->getDaysOfMonthPadded('January', '2022');

    expect($month->first()['date'])->toBe('2021-12-27')
        ->and($month->last()['date'])->toBe('2022-02-06');
});

test('events are found in the correct date', function () {
    $calendar = Calendar::factory()->create();
    $event = App\Models\Event::factory()->create([
        'summary' => 'test event',
        'start_at' => '2024-06-01 2:00:00',
        'end_at' => '2024-06-01 22:00:00'
    ]);
    $calendar->events()->save($event);
    $calendar->refresh();
    $month = $calendar->getDaysOfMonthPadded('June', '2024');

    $events = $month->first(function ($value) {
        return $value['date'] === '2024-06-01';
    })['events'];

    expect($events)->toBeInstanceOf(Collection::class)
        ->and($events->first())->toBeInstanceOf(Event::class)
        ->and($events->first()->summary)->toBe('test event');
});

test('events have been attached to the days from controller', function () {
    $calendar = Calendar::factory()->create();
    $event = App\Models\Event::factory()->create([
        'summary' => 'test event',
        'start_at' => '2024-06-01 2:00:00',
        'end_at' => '2024-06-01 22:00:00'
    ]);
    $calendar->events()->save($event);
    $calendar->refresh();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/calendar/ages-of-madness?month=June&year=2024');

    $response->assertStatus(200);
});
