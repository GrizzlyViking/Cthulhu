<?php

namespace Database\Seeders;

use App\Models\Calendar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Calendar::create([
            'name' => 'Ages of Madness',
            'slug' => Str::slug('Ages of Madness'),
        ]);
    }
}
