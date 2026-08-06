<?php

return [

    'admin' => [

        /*
        |----------------------------------------------------------------------
        | Editing the shared reference data
        |----------------------------------------------------------------------
        |
        | The skill list and the armoury are not group data — every group on
        | this server draws from the same two tables, so an admin editing them
        | edits every other group's game as well.
        |
        | While one group runs on this server that is harmless, and being able
        | to add a house-ruled skill or weapon from the browser beats editing a
        | seeder. Turn this off once a second group exists, and reference data
        | goes back to being console-only (SkillSeeder, App\Misc\WeaponTable and
        | the migrations that read from it).
        |
        | With this off, the admin Skills and Weapons pages still list and
        | search; only the create, edit, retire and restore actions disappear —
        | from the UI and from the routes behind it.
        |
        */

        'edit_reference_data' => env('CTHULHU_ADMIN_EDIT_REFERENCE_DATA', true),

    ],

    'sheet' => [

        /*
        |----------------------------------------------------------------------
        | Skills that are always relevant
        |----------------------------------------------------------------------
        |
        | The skills tab defaults to showing only what an investigator has
        | actually put points into — every skill whose value is above the book's
        | starting value. A full sheet is around seventy skills, most of them
        | untouched, which is unreadable on a phone at the table.
        |
        | The slugs below are shown as well, however untouched: they come up in
        | almost every session, and a player should not have to go looking for
        | Dodge when something lunges at them. Add or remove slugs here — the
        | list is read straight into the sheet, nothing else needs changing.
        |
        | Slugs come from the skills table (see SkillSeeder). An unknown slug is
        | simply ignored, so it is safe to list a skill this group has not added.
        |
        */

        'always_relevant_skills' => [
            'dodge',
            'spot-hidden',
            'first_aid',
            'listen',
            'psychology',
        ],

    ],

];
