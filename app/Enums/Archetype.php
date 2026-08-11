<?php

namespace App\Enums;

/**
 * What the Keeper is asking for when they press the button: a cultist, a bruiser,
 * a bystander who happened to be standing there, or a friend of the party.
 *
 * The archetype is a shortcut, not a rule — the numbers behind each one live in
 * {@see \App\Misc\ArchetypeTable}, and the occupation is still the Keeper's to
 * choose. Every one of these is a **house invention**: the book has no archetypes,
 * so nothing here is a transcription and all of it may be tuned freely.
 */
enum Archetype: string
{
    case Cultist   = 'cultist';
    case Thug      = 'thug';
    case Bystander = 'bystander';
    case Ally      = 'ally';

    public function label(): string
    {
        return match ($this) {
            self::Cultist   => 'Cultist',
            self::Thug      => 'Thug',
            self::Bystander => 'Bystander',
            self::Ally      => 'Ally',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Cultist   => 'A believer. Knows a little too much, and will do a great deal for whatever it is they worship.',
            self::Thug      => 'Hired muscle. Strong, mean, and armed with whatever was to hand.',
            self::Bystander => 'An ordinary person who happened to be there. No stomach for a fight.',
            self::Ally      => 'A useful acquaintance — someone the party can ask, and who might come along.',
        };
    }

    /**
     * The archetypes as the Keeper's screen wants them: a button each.
     *
     * @return list<array{value: string, label: string, description: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $archetype): array => [
                'value'       => $archetype->value,
                'label'       => $archetype->label(),
                'description' => $archetype->description(),
            ],
            self::cases(),
        );
    }
}
