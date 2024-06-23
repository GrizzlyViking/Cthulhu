<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SkillSeeder::class);
        $this->call(WeaponSeeder::class);
        $this->call(CalendarSeeder::class);

        User::factory()->create([
            'name' => 'Sebastian Edelmann',
            'email' => 'sebastian@schlossberg-edelmann.com',
            'password' => bcrypt('6sun3jD6Mc9Nn2g'),
        ]);
    }
}
