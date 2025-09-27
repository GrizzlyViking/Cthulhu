<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['slug' => 'accounting', 'display_name' => 'Accounting', 'starting_value' => 5],
            ['slug' => 'anthropology', 'display_name' => 'Anthropology', 'starting_value' => 1],
            ['slug' => 'appraise', 'display_name' => 'Appraise', 'starting_value' => 5],
            ['slug' => 'archeology', 'display_name' => 'Archeology', 'starting_value' => 1],
            ['slug' => 'art_crafts', 'display_name' => 'Art & Crafts', 'starting_value' => 5],
            ['slug' => 'charm', 'display_name' => 'Charm', 'starting_value' => 15],
            ['slug' => 'climb', 'display_name' => 'Climb', 'starting_value' => 20],
            ['slug' => 'credit_rating', 'display_name' => 'Credit_rating', 'starting_value' => 0],
            ['slug' => 'cthulhu_mythos', 'display_name' => 'Cthulhu_mythos', 'starting_value' => 0],
            ['slug' => 'disguise', 'display_name' => 'Disguise', 'starting_value' => 5],
            ['slug' => 'dodge', 'display_name' => 'Dodge', 'starting_value' => 0],
            ['slug' => 'drive_auto', 'display_name' => 'Drive Auto', 'starting_value' => 20],
            ['slug' => 'electric_repair', 'display_name' => 'Electric Repair', 'starting_value' => 10],
            ['slug' => 'fast_talking', 'display_name' => 'Fast Talking', 'starting_value' => 20],
            ['slug' => 'fighting', 'display_name' => 'Fighting', 'starting_value' => 25],
            ['slug' => 'firearms_handgun', 'display_name' => 'Firearms Handgun', 'starting_value' => 20],
            ['slug' => 'firearms-rifle', 'display_name' => 'Firearms (Rifle)', 'starting_value' => 20],

            ['slug' => 'firearms-shotgun', 'display_name' => 'Firearms (Shotgun)', 'starting_value' => 20],
            ['slug' => 'firearms-bow', 'display_name' => 'Firearms (Bow)', 'starting_value' => 20],
            ['slug' => 'fighting-whip', 'display_name' => 'Fighting (Whip)', 'starting_value' => 20],
            ['slug' => 'fighting-brawl', 'display_name' => 'Fighting (Brawl)', 'starting_value' => 20],
            ['slug' => 'fighting-mg', 'display_name' => 'Fighting (MG)', 'starting_value' => 20],
            ['slug' => 'fighting-axe', 'display_name' => 'Fighting (Axe)', 'starting_value' => 20],
            ['slug' => 'fighting-garrote', 'display_name' => 'Fighting (Garrote)', 'starting_value' => 20],
            ['slug' => 'fighting-smg', 'display_name' => 'Firearms (SMG)', 'starting_value' => 20],
            ['slug' => 'fighting-flail', 'display_name' => 'Fighting (Flail)', 'starting_value' => 20],
            ['slug' => 'fighting-spear', 'display_name' => 'Fighting (Spear)', 'starting_value' => 20],

            ['slug' => 'first_aid', 'display_name' => 'First_aid', 'starting_value' => 30],
            ['slug' => 'history', 'display_name' => 'History', 'starting_value' => 5],
            ['slug' => 'intimidate', 'display_name' => 'Intimidate', 'starting_value' => 15],
            ['slug' => 'jump', 'display_name' => 'Jump', 'starting_value' => 20],
            ['slug' => 'language_other', 'display_name' => 'Language other', 'starting_value' => 1],
            ['slug' => 'language_own', 'display_name' => 'Language own', 'starting_value' => 0],
            ['slug' => 'law', 'display_name' => 'Law', 'starting_value' => 5],
            ['slug' => 'library_use', 'display_name' => 'Library_use', 'starting_value' => 20],
            ['slug' => 'listen', 'display_name' => 'Listen', 'starting_value' => 20],
            ['slug' => 'locksmith', 'display_name' => 'Locksmith', 'starting_value' => 1],
            ['slug' => 'mech_repair', 'display_name' => 'Mech. Repair', 'starting_value' => 1],
            ['slug' => 'medicine', 'display_name' => 'Medicine', 'starting_value' => 1],
            ['slug' => 'natural_world', 'display_name' => 'Natural_world', 'starting_value' => 1],
            ['slug' => 'navigate', 'display_name' => 'Navigate', 'starting_value' => 10],
            ['slug' => 'occult', 'display_name' => 'Occult', 'starting_value' => 10],
            ['slug' => 'op_hv_machine', 'display_name' => 'Operate heavy machine', 'starting_value' => 1],
            ['slug' => 'persuade', 'display_name' => 'Persuade', 'starting_value' => 10],
            ['slug' => 'pilot', 'display_name' => 'Pilot', 'starting_value' => 1],
            ['slug' => 'psychology', 'display_name' => 'Psychology', 'starting_value' => 10],
            ['slug' => 'psychoanalysis', 'display_name' => 'Psychoanalysis', 'starting_value' => 1],
            ['slug' => 'ride', 'display_name' => 'Ride', 'starting_value' => 5],
            ['slug' => 'science_skill', 'display_name' => 'Science Skill', 'starting_value' => 1],
            ['slug' => 'sleight_of_hand', 'display_name' => 'Sleight of Hand', 'starting_value' => 10],
            ['slug' => 'spot-hidden', 'display_name' => 'Spot Hidden', 'starting_value' => 25],
            ['slug' => 'stealth', 'display_name' => 'Stealth', 'starting_value' => 20],
            ['slug' => 'survival', 'display_name' => 'Survival', 'starting_value' => 10],
            ['slug' => 'swim', 'display_name' => 'Swim', 'starting_value' => 10],
            ['slug' => 'throw', 'display_name' => 'Throw', 'starting_value' => 20],
            ['slug' => 'track', 'display_name' => 'Track', 'starting_value' => 10],
        ];

        collect($skills)->map(function ($item, $index) {
            $item['order_by'] = $index;

            Skill::create($item);
        });
    }
}
