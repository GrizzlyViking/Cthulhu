<?php

use App\Misc\CharacterCreation;
use App\Models\Character;

function makeCharacter(array $attributes): Character
{
    return new Character($attributes);
}

test('hit points are constitution plus size divided by ten, rounded down', function (int $con, int $siz, int $expected) {
    expect(CharacterCreation::hitPoints(makeCharacter(['constitution' => $con, 'size' => $siz])))->toBe($expected);
})->with([
    'Harvey Walters (CON 70, SIZ 80)' => [70, 80, 15],
    'rounds down'                     => [65, 60, 12],
    'low values'                      => [15, 15, 3],
]);

test('damage bonus follows Table I', function (int $str, int $siz, string $expected) {
    expect(CharacterCreation::damageBonus(makeCharacter(['strength' => $str, 'size' => $siz])))->toBe($expected);
})->with([
    'STR+SIZ 64 gives -2'    => [32, 32, '-2'],
    'STR+SIZ 65 gives -1'    => [33, 32, '-1'],
    'STR+SIZ 84 gives -1'    => [42, 42, '-1'],
    'STR+SIZ 85 gives none'  => [43, 42, 'none'],
    'Harvey (STR+SIZ 100)'   => [20, 80, 'none'],
    'STR+SIZ 124 gives none' => [62, 62, 'none'],
    'STR+SIZ 125 gives +1D4' => [63, 62, '+1D4'],
    'STR+SIZ 164 gives +1D4' => [82, 82, '+1D4'],
    'STR+SIZ 165 gives +1D6' => [83, 82, '+1D6'],
    'STR+SIZ 204 gives +1D6' => [102, 102, '+1D6'],
    'STR+SIZ 205 gives +2D6' => [103, 102, '+2D6'],
]);

test('build follows Table I', function (int $str, int $siz, int $expected) {
    expect(CharacterCreation::build(makeCharacter(['strength' => $str, 'size' => $siz])))->toBe($expected);
})->with([
    'STR+SIZ 64 gives -2'  => [32, 32, -2],
    'STR+SIZ 84 gives -1'  => [42, 42, -1],
    'STR+SIZ 100 gives 0'  => [20, 80, 0],
    'STR+SIZ 164 gives +1' => [82, 82, 1],
    'STR+SIZ 204 gives +2' => [102, 102, 2],
    'STR+SIZ 205 gives +3' => [103, 102, 3],
]);

test('move rate depends on STR and DEX relative to SIZ', function (int $str, int $dex, int $siz, int $expected) {
    expect(CharacterCreation::baseMoveRate(makeCharacter(['strength' => $str, 'dexterity' => $dex, 'size' => $siz])))->toBe($expected);
})->with([
    'both under SIZ gives 7'        => [50, 50, 65, 7],
    'both over SIZ gives 9'         => [70, 70, 65, 9],
    'mixed gives 8'                 => [70, 50, 65, 8],
    'all equal gives 8'             => [65, 65, 65, 8],
    'one equal to SIZ gives 8'      => [65, 50, 65, 8],
    'Harvey (STR 20 DEX 55 SIZ 80)' => [20, 55, 80, 7],
]);

test('move rate loses a point per decade from the forties', function (?int $age, int $expected) {
    // STR 70 / DEX 70 against SIZ 65 is a base of 9.
    $character = makeCharacter(['strength' => 70, 'dexterity' => 70, 'size' => 65, 'age' => $age]);

    expect(CharacterCreation::moveRate($character))->toBe($expected);
})->with([
    'no age given'                    => [null, 9],
    '25 is untouched'                 => [25, 9],
    '39 is untouched'                 => [39, 9],
    'forty is already in the forties' => [40, 8],
    '49 is in the forties'            => [49, 8],
    'fifty is in the fifties'         => [50, 7],
    '60s lose three'                  => [65, 6],
    '70s lose four'                   => [70, 5],
    '80s lose five'                   => [89, 4],
    'ninety stays on the eighties'    => [90, 4],
]);

test('derived pools come from the correct characteristics', function () {
    $character = makeCharacter(['power' => 45, 'dexterity' => 55]);

    expect(CharacterCreation::sanity($character))->toBe(45)
        ->and(CharacterCreation::magicPoints($character))->toBe(9)
        ->and(CharacterCreation::dodge($character))->toBe(27);
});

test('half and fifth values round down', function () {
    expect(CharacterCreation::half(84))->toBe(42)
        ->and(CharacterCreation::fifth(84))->toBe(16)
        ->and(CharacterCreation::half(45))->toBe(22)
        ->and(CharacterCreation::fifth(45))->toBe(9);
});
