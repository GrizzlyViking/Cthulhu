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

];
