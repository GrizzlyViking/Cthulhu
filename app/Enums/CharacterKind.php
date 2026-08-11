<?php

namespace App\Enums;

/**
 * What a row in `characters` is.
 *
 * An investigator belongs to a player and is built in the wizard. A non-player
 * character belongs to the Keeper who conjured it up, is generated whole, and is
 * visible to nobody else.
 *
 * A monster would be a third kind: same figures to lose, generated the same way
 * from a table of stat blocks rather than from occupations. Nothing outside this
 * enum needs to change for that — see the Keeper's cast in CLAUDE.md.
 */
enum CharacterKind: string
{
    case Investigator = 'investigator';
    case NonPlayer    = 'npc';
}
