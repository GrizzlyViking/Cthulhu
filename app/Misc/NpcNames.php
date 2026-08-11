<?php

namespace App\Misc;

use App\Enums\Era;
use App\Enums\Gender;

/**
 * Names for the Keeper's cast.
 *
 * Nothing here is from the book — it is a **house list**, and a short one on
 * purpose: a name the Keeper can say out loud without stalling, era-appropriate
 * enough that a 1925 cultist is not called Tyler. Faker would do the job, but it
 * has no idea which decade it is in.
 *
 * A repeat is harmless: the slug is made unique when the character is saved.
 */
class NpcNames
{
    /** @var array<string, list<string>> */
    private const array FIRST_NAMES = [
        '1920s.male' => [
            'Albert', 'Arthur', 'Clarence', 'Edgar', 'Elias', 'Ernest', 'Frank', 'Harold',
            'Herbert', 'Horace', 'Hugh', 'Leonard', 'Lester', 'Mortimer', 'Nathaniel',
            'Oswald', 'Percival', 'Ralph', 'Silas', 'Stanley', 'Theodore', 'Walter', 'Wilbur',
        ],
        '1920s.female' => [
            'Adelaide', 'Agnes', 'Beatrice', 'Cordelia', 'Dorothy', 'Edith', 'Eleanor',
            'Ethel', 'Florence', 'Gertrude', 'Harriet', 'Hazel', 'Ida', 'Josephine', 'Lavinia',
            'Mabel', 'Mildred', 'Myrtle', 'Opal', 'Prudence', 'Rosalind', 'Vera', 'Winifred',
        ],
        'modern.male' => [
            'Aaron', 'Brandon', 'Calvin', 'Damian', 'Dean', 'Derek', 'Eli', 'Gavin', 'Ian',
            'Jared', 'Julian', 'Kyle', 'Marcus', 'Nathan', 'Owen', 'Reece', 'Simon', 'Travis',
            'Trevor', 'Tyler', 'Victor', 'Wesley', 'Zachary',
        ],
        'modern.female' => [
            'Alexis', 'Bethany', 'Carmen', 'Chelsea', 'Danielle', 'Erin', 'Gemma', 'Heather',
            'Imogen', 'Jasmine', 'Kirsten', 'Lauren', 'Megan', 'Naomi', 'Nicole', 'Paige',
            'Renee', 'Shannon', 'Sienna', 'Tanya', 'Vanessa', 'Whitney', 'Yasmin',
        ],
    ];

    /**
     * Surnames both eras share — New England enough for the setting, and none of
     * them Lovecraft's own, so no player recognises a name and guesses the plot.
     *
     * @var list<string>
     */
    private const array SURNAMES = [
        'Ashcroft', 'Barlow', 'Bexley', 'Bramwell', 'Caldwell', 'Carrow', 'Chesney',
        'Cranmer', 'Denby', 'Dunhill', 'Eastwick', 'Fenn', 'Garrick', 'Halloway',
        'Hatcher', 'Kinsley', 'Larkin', 'Mabry', 'Marsden', 'Norwood', 'Orwell',
        'Pemberton', 'Quill', 'Rutherford', 'Sedgwick', 'Sable', 'Thorne', 'Vance',
        'Wexley', 'Whitlock', 'Yarrow', 'Ashby', 'Brandt', 'Coleridge', 'Drury',
        'Fairweather', 'Godwin', 'Hollis', 'Ingram', 'Leland', 'Mercer', 'Pike',
        'Rushton', 'Sowerby', 'Tarrant', 'Underhill', 'Vosper', 'Wraith',
    ];

    /**
     * A full name for somebody of this era and gender. The lists carry two
     * genders; anybody else draws from the same list as a man, which the Keeper
     * can rename in a second.
     */
    public static function random(Era $era, Gender $gender): string
    {
        $key = $era->value.'.'.($gender === Gender::Female ? 'female' : 'male');

        return self::pick(self::FIRST_NAMES[$key]).' '.self::pick(self::SURNAMES);
    }

    /**
     * @param list<string> $values
     */
    private static function pick(array $values): string
    {
        return $values[random_int(0, count($values) - 1)];
    }
}
