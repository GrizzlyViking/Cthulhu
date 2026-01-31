<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillWithDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'slug'           => 'accounting',
                'display_name'   => 'Accounting',
                'starting_value' => 5,
                'description'    => 'Understanding of financial records and the ability to find discrepancies or hidden information in business ledgers.',
            ],
            [
                'slug'           => 'anthropology',
                'display_name'   => 'Anthropology',
                'starting_value' => 1,
                'description'    => 'The study of human cultures and behavior, allowing an investigator to understand and predict the actions of different groups.',
            ],
            [
                'slug'           => 'appraise',
                'display_name'   => 'Appraise',
                'starting_value' => 5,
                'description'    => 'The ability to estimate the value and authenticity of antiques, jewelry, and other items.',
            ],
            [
                'slug'           => 'archeology',
                'display_name'   => 'Archeology',
                'starting_value' => 1,
                'description'    => 'The study of ancient civilizations through their physical remains.',
            ],
            [
                'slug'           => 'art_crafts',
                'display_name'   => 'Art & Crafts',
                'starting_value' => 5,
                'description'    => 'Proficiency in a particular artistic or technical craft, such as painting, photography, or carpentry.',
            ],
            [
                'slug'           => 'charm',
                'display_name'   => 'Charm',
                'starting_value' => 15,
                'description'    => 'Using personality and likability to influence others.',
            ],
            [
                'slug'           => 'climb',
                'display_name'   => 'Climb',
                'starting_value' => 20,
                'description'    => 'The ability to scale vertical surfaces, from trees to building walls.',
            ],
            [
                'slug'           => 'credit_rating',
                'display_name'   => 'Credit Rating',
                'starting_value' => 0,
                'description'    => 'A measure of an investigator\'s social standing and financial resources.',
            ],
            [
                'slug'           => 'cthulhu_mythos',
                'display_name'   => 'Cthulhu Mythos',
                'starting_value' => 0,
                'description'    => 'Knowledge of the terrible truths of the universe, the Great Old Ones, and the Outer Gods. Increases as sanity decreases.',
            ],
            [
                'slug'           => 'disguise',
                'display_name'   => 'Disguise',
                'starting_value' => 5,
                'description'    => 'The art of changing one\'s appearance to avoid recognition or impersonate someone else.',
            ],
            [
                'slug'           => 'dodge',
                'display_name'   => 'Dodge',
                'starting_value' => 0,
                'description'    => 'The ability to instinctively avoid incoming attacks or physical hazards.',
            ],
            [
                'slug'           => 'drive_auto',
                'display_name'   => 'Drive Auto',
                'starting_value' => 20,
                'description'    => 'Skill in operating automobiles and other common motor vehicles.',
            ],
            [
                'slug'           => 'electric_repair',
                'display_name'   => 'Electric Repair',
                'starting_value' => 10,
                'description'    => 'The ability to repair electrical devices and systems.',
            ],
            [
                'slug'           => 'fast_talking',
                'display_name'   => 'Fast Talking',
                'starting_value' => 20,
                'description'    => 'The use of rapid-fire speech and verbal trickery to confuse or persuade others.',
            ],
            [
                'slug'           => 'fighting',
                'display_name'   => 'Fighting',
                'starting_value' => 25,
                'description'    => 'General proficiency in hand-to-hand combat.',
            ],
            [
                'slug'           => 'firearms_handgun',
                'display_name'   => 'Firearms Handgun',
                'starting_value' => 20,
                'description'    => 'The ability to use pistols and revolvers effectively.',
            ],
            [
                'slug'           => 'firearms-rifle',
                'display_name'   => 'Firearms (Rifle)',
                'starting_value' => 20,
                'description'    => 'The ability to use rifles in combat.',
            ],
            [
                'slug'           => 'firearms-shotgun',
                'display_name'   => 'Firearms (Shotgun)',
                'starting_value' => 20,
                'description'    => 'The ability to use shotguns effectively.',
            ],
            [
                'slug'           => 'firearms-bow',
                'display_name'   => 'Firearms (Bow)',
                'starting_value' => 20,
                'description'    => 'The ability to use bows and crossbows.',
            ],
            [
                'slug'           => 'fighting-whip',
                'display_name'   => 'Fighting (Whip)',
                'starting_value' => 20,
                'description'    => 'Specialized training in using a whip as a weapon.',
            ],
            [
                'slug'           => 'fighting-brawl',
                'display_name'   => 'Fighting (Brawl)',
                'starting_value' => 20,
                'description'    => 'Unarmed combat, including punches, kicks, and grappling.',
            ],
            [
                'slug'           => 'fighting-mg',
                'display_name'   => 'Fighting (MG)',
                'starting_value' => 20,
                'description'    => 'Proficiency in using machine guns.',
            ],
            [
                'slug'           => 'fighting-axe',
                'display_name'   => 'Fighting (Axe)',
                'starting_value' => 20,
                'description'    => 'The ability to use axes and similar heavy bladed weapons.',
            ],
            [
                'slug'           => 'fighting-garrote',
                'display_name'   => 'Fighting (Garrote)',
                'starting_value' => 20,
                'description'    => 'Specialized skill in using a garrote for strangulation.',
            ],
            [
                'slug'           => 'fighting-smg',
                'display_name'   => 'Firearms (SMG)',
                'starting_value' => 20,
                'description'    => 'Proficiency in using submachine guns.',
            ],
            [
                'slug'           => 'fighting-flail',
                'display_name'   => 'Fighting (Flail)',
                'starting_value' => 20,
                'description'    => 'The ability to use flails and other articulated weapons.',
            ],
            [
                'slug'           => 'fighting-spear',
                'display_name'   => 'Fighting (Spear)',
                'starting_value' => 20,
                'description'    => 'The ability to use spears and other polearms.',
            ],
            [
                'slug'           => 'first_aid',
                'display_name'   => 'First Aid',
                'starting_value' => 30,
                'description'    => 'Emergency medical treatment to stabilize injuries.',
            ],
            [
                'slug'           => 'history',
                'display_name'   => 'History',
                'starting_value' => 5,
                'description'    => 'Knowledge of historical events, dates, and figures.',
            ],
            [
                'slug'           => 'intimidate',
                'display_name'   => 'Intimidate',
                'starting_value' => 15,
                'description'    => 'Using threats and physical presence to force others to comply.',
            ],
            [
                'slug'           => 'jump',
                'display_name'   => 'Jump',
                'starting_value' => 20,
                'description'    => 'The ability to leap across obstacles or down from heights.',
            ],
            [
                'slug'           => 'language_other',
                'display_name'   => 'Language Other',
                'starting_value' => 1,
                'description'    => 'Knowledge of a language other than the investigator\'s native tongue.',
            ],
            [
                'slug'           => 'language_own',
                'display_name'   => 'Language Own',
                'starting_value' => 0,
                'description'    => 'Proficiency in the investigator\'s native language.',
            ],
            [
                'slug'           => 'law',
                'display_name'   => 'Law',
                'starting_value' => 5,
                'description'    => 'Knowledge of legal systems, precedents, and procedures.',
            ],
            [
                'slug'           => 'library_use',
                'display_name'   => 'Library Use',
                'starting_value' => 20,
                'description'    => 'The ability to find specific information in libraries, archives, and other collections of records.',
            ],
            [
                'slug'           => 'listen',
                'display_name'   => 'Listen',
                'starting_value' => 20,
                'description'    => 'The ability to hear faint or distant sounds and interpret them correctly.',
            ],
            [
                'slug'           => 'locksmith',
                'display_name'   => 'Locksmith',
                'starting_value' => 1,
                'description'    => 'The ability to pick locks and bypass mechanical security systems.',
            ],
            [
                'slug'           => 'mech_repair',
                'display_name'   => 'Mechanical Repair',
                'starting_value' => 10,
                'description'    => 'The ability to repair mechanical devices and engines.',
            ],
            [
                'slug'           => 'medicine',
                'display_name'   => 'Medicine',
                'starting_value' => 1,
                'description'    => 'Professional medical knowledge for diagnosing and treating illnesses and injuries.',
            ],
            [
                'slug'           => 'natural_world',
                'display_name'   => 'Natural World',
                'starting_value' => 1,
                'description'    => 'Knowledge of flora, fauna, and environmental conditions.',
            ],
            [
                'slug'           => 'navigate',
                'display_name'   => 'Navigate',
                'starting_value' => 10,
                'description'    => 'The ability to find one\'s way using maps, compasses, or the stars.',
            ],
            [
                'slug'           => 'occult',
                'display_name'   => 'Occult',
                'starting_value' => 10,
                'description'    => 'Knowledge of folklore, magic, and secret traditions.',
            ],
            [
                'slug'           => 'op_hv_machine',
                'display_name'   => 'Operate Heavy Machine',
                'starting_value' => 1,
                'description'    => 'The ability to operate cranes, excavators, and other heavy equipment.',
            ],
            [
                'slug'           => 'persuade',
                'display_name'   => 'Persuade',
                'starting_value' => 10,
                'description'    => 'Using logical arguments and reasoning to influence others.',
            ],
            [
                'slug'           => 'pilot',
                'display_name'   => 'Pilot',
                'starting_value' => 1,
                'description'    => 'The ability to operate aircraft or sea vessels.',
            ],
            [
                'slug'           => 'psychology',
                'display_name'   => 'Psychology',
                'starting_value' => 10,
                'description'    => 'The ability to understand human motives and detect when someone is lying or acting strangely.',
            ],
            [
                'slug'           => 'psychoanalysis',
                'display_name'   => 'Psychoanalysis',
                'starting_value' => 1,
                'description'    => 'The ability to treat mental disorders and emotional trauma.',
            ],
            [
                'slug'           => 'ride',
                'display_name'   => 'Ride',
                'starting_value' => 5,
                'description'    => 'The ability to ride horses or other animals.',
            ],
            [
                'slug'           => 'science_skill',
                'display_name'   => 'Science Skill',
                'starting_value' => 1,
                'description'    => 'Knowledge of a specific scientific discipline, such as biology, chemistry, or physics.',
            ],
            [
                'slug'           => 'sleight_of_hand',
                'display_name'   => 'Sleight of Hand',
                'starting_value' => 10,
                'description'    => 'The ability to perform manual trickery, such as palmings and picking pockets.',
            ],
            [
                'slug'           => 'spot-hidden',
                'display_name'   => 'Spot Hidden',
                'starting_value' => 25,
                'description'    => 'The ability to find concealed objects or notice subtle details.',
            ],
            [
                'slug'           => 'stealth',
                'display_name'   => 'Stealth',
                'starting_value' => 20,
                'description'    => 'The ability to move quietly and remain unseen.',
            ],
            [
                'slug'           => 'survival',
                'display_name'   => 'Survival',
                'starting_value' => 10,
                'description'    => 'The ability to survive in hostile environments, such as wilderness or extreme weather.',
            ],
            [
                'slug'           => 'swim',
                'display_name'   => 'Swim',
                'starting_value' => 10,
                'description'    => 'The ability to move through water and avoid drowning.',
            ],
            [
                'slug'           => 'throw',
                'display_name'   => 'Throw',
                'starting_value' => 20,
                'description'    => 'The ability to throw objects with accuracy and force.',
            ],
            [
                'slug'           => 'track',
                'display_name'   => 'Track',
                'starting_value' => 10,
                'description'    => 'The ability to follow trails left by people or animals.',
            ],
        ];

        foreach ($skills as $index => $skillData) {
            Skill::updateOrCreate(
                ['slug' => $skillData['slug']],
                array_merge($skillData, ['order_by' => $index])
            );
        }
    }
}
