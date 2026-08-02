<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    private const BOTH_ERAS = ['1920s', 'modern'];

    public function run(): void
    {
        foreach ($this->occupations() as $occupation) {
            Occupation::updateOrCreate(['name' => $occupation['name']], $occupation);
        }
    }

    /**
     * @return array<int, array{multiplier: int, options: array<int, string>}>
     */
    private static function formula(string ...$parts): array
    {
        return array_map(function (string $part): array {
            [$options, $multiplier] = explode('*', $part);

            return [
                'multiplier' => (int) $multiplier,
                'options'    => explode('|', $options),
            ];
        }, $parts);
    }

    /**
     * @param  array<int, string>                                                          $options
     * @return array{type: string, count: int, options: array<int, string>, label: string}
     */
    private static function choice(int $count, array $options, string $label): array
    {
        return ['type' => 'choice', 'count' => $count, 'options' => $options, 'label' => $label];
    }

    private static function interpersonal(int $count = 1): array
    {
        $label = $count === 1 ? 'one interpersonal skill' : 'two interpersonal skills';

        return self::choice($count, ['charm', 'fast_talking', 'intimidate', 'persuade'], $label);
    }

    private static function firearms(int $count = 1): array
    {
        return self::choice($count, ['firearms-handgun', 'firearms-rifle', 'firearms-shotgun'], 'a firearms specialisation');
    }

    /**
     * @return array{type: string, count: int, label: string}
     */
    private static function any(int $count, string $label): array
    {
        return ['type' => 'any', 'count' => $count, 'label' => $label];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function occupations(): array
    {
        return [
            [
                'name'                 => 'Antiquarian',
                'description'          => 'A person who delights in the timeless excellence of design and execution, collecting and studying relics of former times. The quintessential Lovecraftian occupation.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 30,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'appraise', 'art_crafts', 'history', 'library_use', 'language_other',
                    self::interpersonal(),
                    'spot-hidden',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Archaeologist',
                'description'          => 'Studies and explores the vestiges of past cultures, from potsherds to lost cities. Prepared for expedition life and painstaking scholarship alike.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 10,
                'credit_rating_max'    => 40,
                'skills'               => [
                    'appraise', 'archeology', 'history', 'language_other', 'library_use', 'spot-hidden', 'mech_repair',
                    self::choice(1, ['navigate', 'science_skill'], 'Navigate or a Science'),
                ],
            ],
            [
                'name'                 => 'Artist',
                'description'          => 'A painter, sculptor or other creative soul; possibly self-taught, possibly renowned. Talent may be matched by temperament.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'power|dexterity*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 50,
                'skills'               => [
                    'art_crafts',
                    self::choice(1, ['history', 'natural_world'], 'History or Natural World'),
                    self::interpersonal(),
                    'language_other', 'psychology', 'spot-hidden',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Athlete',
                'description'          => 'A professional or gifted amateur sportsperson: boxer, baseball player, swimmer. Fitness and fame in varying proportions.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'climb', 'jump', 'fighting-brawl', 'ride',
                    self::interpersonal(),
                    'swim', 'throw',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Author',
                'description'          => 'A writer of novels, stories or verse. Long stretches of solitary research punctuated by flashes of inspiration—and the occasional royalty cheque.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts', 'history', 'library_use',
                    self::choice(1, ['natural_world', 'occult'], 'Natural World or Occult'),
                    'language_other', 'language_own', 'psychology',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Clergy',
                'description'          => 'A minister, priest, rabbi or imam; a shepherd of souls with access to communities, confidences and consecrated ground.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 60,
                'skills'               => [
                    'accounting', 'history', 'library_use', 'listen', 'language_other',
                    self::interpersonal(),
                    'psychology',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Criminal',
                'description'          => 'Pickpocket, burglar, con artist or gang member. Skills honed on the wrong side of the law—useful in places investigators should not be.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|appearance*2'),
                'credit_rating_min'    => 5,
                'credit_rating_max'    => 65,
                'skills'               => [
                    self::interpersonal(),
                    'psychology', 'spot-hidden', 'stealth',
                    self::choice(4, ['appraise', 'disguise', 'fighting-brawl', 'firearms-handgun', 'locksmith', 'mech_repair', 'sleight_of_hand'], 'four specialities of the trade'),
                ],
            ],
            [
                'name'                 => 'Dilettante',
                'description'          => 'Independently wealthy and delightfully unoccupied. Money grants access, education and the leisure to pursue strange interests.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'appearance*2'),
                'credit_rating_min'    => 50,
                'credit_rating_max'    => 99,
                'skills'               => [
                    'art_crafts',
                    self::firearms(),
                    'language_other', 'ride',
                    self::interpersonal(),
                    self::any(3, 'any three other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Doctor of Medicine',
                'description'          => 'A physician, surgeon or specialist. Sworn to heal, trained to observe, and rarely surprised by blood.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 30,
                'credit_rating_max'    => 80,
                'skills'               => [
                    'first_aid', 'medicine', 'language_other', 'psychology', 'science_skill',
                    self::any(2, 'any two other skills as academic or personal specialties'),
                ],
            ],
            [
                'name'                 => 'Drifter',
                'description'          => 'A wanderer by choice or misfortune, moving from town to town. Little money, few ties, and eyes that have seen a great deal.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'appearance|dexterity|strength*2'),
                'credit_rating_min'    => 0,
                'credit_rating_max'    => 5,
                'skills'               => [
                    'climb', 'jump', 'listen', 'navigate',
                    self::interpersonal(),
                    'stealth',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Engineer',
                'description'          => 'A designer and builder of machines, structures and public works. At home with blueprints, boilers and the practical sciences.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 30,
                'credit_rating_max'    => 60,
                'skills'               => [
                    'art_crafts', 'electric_repair', 'library_use', 'mech_repair', 'op_hv_machine', 'science_skill',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Entertainer',
                'description'          => 'Actor, singer, dancer or vaudevillian—alive in the glow of an audience and never short of acquaintances.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'appearance*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'art_crafts', 'disguise',
                    self::interpersonal(2),
                    'listen', 'psychology',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Farmer',
                'description'          => 'A worker of the land, hardy and self-reliant, versed in animals, weather and machinery.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts', 'drive_auto',
                    self::interpersonal(),
                    'mech_repair', 'natural_world', 'op_hv_machine', 'track',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Journalist',
                'description'          => 'A reporter for newspaper, magazine or wire service. Paid to ask awkward questions and to be where things happen.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts', 'history', 'library_use', 'language_own',
                    self::interpersonal(),
                    'psychology',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Lawyer',
                'description'          => 'An advocate and counsellor, fluent in statute and precedent, with clients respectable and otherwise.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 30,
                'credit_rating_max'    => 80,
                'skills'               => [
                    'accounting', 'law', 'library_use',
                    self::interpersonal(2),
                    'psychology',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Librarian',
                'description'          => 'Custodian of the stacks and their secrets. Nobody finds a misfiled tome—or a forbidden one—faster.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 35,
                'skills'               => [
                    'accounting', 'library_use', 'language_other', 'language_own',
                    self::any(4, 'any four other skills as personal specialties or reading interests'),
                ],
            ],
            [
                'name'                 => 'Military Officer',
                'description'          => 'A commissioned leader of soldiers, trained in command, logistics and the management of violence.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 20,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'accounting',
                    self::firearms(),
                    'navigate', 'first_aid',
                    self::interpersonal(2),
                    'psychology', 'survival',
                ],
            ],
            [
                'name'                 => 'Missionary',
                'description'          => 'Carries a message of faith to distant places, often beyond the reach of comfort, medicine or law.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 0,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts', 'first_aid', 'mech_repair', 'medicine', 'natural_world',
                    self::interpersonal(),
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Musician',
                'description'          => 'A performer or composer, from concert hall to smoky speakeasy. Art is a hard master and a poor paymaster.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|appearance*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts',
                    self::interpersonal(),
                    'listen', 'psychology',
                    self::any(4, 'any four other skills'),
                ],
            ],
            [
                'name'                 => 'Nurse',
                'description'          => 'A trained carer of the sick and injured, familiar with hospitals, doctors and the moments between life and death.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'first_aid',
                    self::interpersonal(),
                    'listen', 'medicine', 'psychology', 'science_skill', 'spot-hidden',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Parapsychologist',
                'description'          => 'An investigator of hauntings, mediums and psychic phenomena, armed with cameras, instruments and scepticism—or belief.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'anthropology', 'art_crafts', 'history', 'library_use', 'occult', 'language_other', 'psychology',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Pilot',
                'description'          => 'A flyer of aircraft—stunt pilot, airline or military. Machines, maps and weather are daily business.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity*2'),
                'credit_rating_min'    => 20,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'electric_repair', 'mech_repair', 'navigate', 'op_hv_machine', 'pilot', 'science_skill',
                    self::any(2, 'any two other skills as personal or era specialties'),
                ],
            ],
            [
                'name'                 => 'Police Detective',
                'description'          => 'A plain-clothes investigator of serious crimes. Reads people and crime scenes with equal fluency.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 20,
                'credit_rating_max'    => 50,
                'skills'               => [
                    self::choice(1, ['art_crafts', 'disguise'], 'Art/Craft (Acting) or Disguise'),
                    self::firearms(),
                    'law', 'listen',
                    self::interpersonal(),
                    'psychology', 'spot-hidden',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Police Officer',
                'description'          => 'A uniformed keeper of the peace, on the beat or behind the wheel of a patrol car.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'fighting-brawl',
                    self::firearms(),
                    'first_aid',
                    self::interpersonal(),
                    'law', 'psychology', 'spot-hidden',
                    self::choice(1, ['drive_auto', 'ride'], 'Drive Auto or Ride'),
                ],
            ],
            [
                'name'                 => 'Private Investigator',
                'description'          => 'A licensed snoop for hire: divorce work, missing persons and matters clients prefer kept quiet.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'art_crafts', 'disguise', 'law', 'library_use',
                    self::interpersonal(),
                    'psychology', 'spot-hidden',
                    self::any(1, 'any one other skill as a personal or era specialty'),
                ],
            ],
            [
                'name'                 => 'Professor',
                'description'          => 'An academic of Miskatonic or elsewhere: lectures, publications and access to the university library and its restricted stacks.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*4'),
                'credit_rating_min'    => 20,
                'credit_rating_max'    => 70,
                'skills'               => [
                    'library_use', 'language_other', 'language_own', 'psychology',
                    self::any(4, 'any four other skills as academic specialties'),
                ],
            ],
            [
                'name'                 => 'Soldier',
                'description'          => 'A rank-and-file member of the armed forces, drilled for hardship, discipline and combat.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'dexterity|strength*2'),
                'credit_rating_min'    => 9,
                'credit_rating_max'    => 30,
                'skills'               => [
                    self::choice(1, ['climb', 'swim'], 'Climb or Swim'),
                    'dodge', 'fighting-brawl',
                    self::firearms(),
                    'stealth', 'survival',
                    self::choice(2, ['first_aid', 'mech_repair', 'language_other'], 'two of First Aid, Mechanical Repair or Other Language'),
                ],
            ],
            [
                'name'                 => 'Zealot',
                'description'          => 'A person consumed by a cause—religious, political or social—and driven to act on it.',
                'eras'                 => self::BOTH_ERAS,
                'skill_points_formula' => self::formula('education*2', 'appearance|power*2'),
                'credit_rating_min'    => 0,
                'credit_rating_max'    => 30,
                'skills'               => [
                    'history',
                    self::interpersonal(2),
                    'psychology', 'stealth',
                    self::any(3, 'any three other skills as personal or era specialties'),
                ],
            ],
        ];
    }
}
