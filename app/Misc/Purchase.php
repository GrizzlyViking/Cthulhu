<?php

namespace App\Misc;

use App\Enums\Purse;
use App\Models\Character;
use Illuminate\Validation\Rule;

/**
 * Paying for something, wherever it is being bought.
 *
 * Weapons and equipment are added by two different controllers but bought the
 * same way, so the rules and the arithmetic live here rather than twice over.
 *
 * The price arrives filled in from the catalogue and is then the player's: they
 * may have haggled it down, been given the thing, or found it in a dead man's
 * coat. Anything from nothing upwards is accepted, and a purse of `nothing`
 * means no money changed hands at all.
 */
class Purchase
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'price'     => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'paid_from' => ['nullable', Rule::enum(Purse::class)],
        ];
    }

    /**
     * Take the price out of the chosen purse, and say what was spent — or
     * nothing at all, which is what a Keeper handing somebody a revolver
     * mid-scene should cost.
     *
     * @param array<string, mixed> $validated
     */
    public static function settle(Character $character, array $validated): ?string
    {
        $price = (float) ($validated['price'] ?? 0);
        $purse = Purse::tryFrom((string) ($validated['paid_from'] ?? '')) ?? Purse::Nothing;

        if ($price <= 0.0 || $purse === Purse::Nothing) {
            return null;
        }

        $character->pay($price, $purse);

        return Money::format($price).' taken from '.$purse->value.'.';
    }
}
