<?php

namespace App\Models\Concerns;

use App\Enums\Era;
use App\Misc\EraTable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Reference data that belongs to one or more eras.
 *
 * `eras` is a list of {@see Era} values, never empty: something available
 * throughout carries every era, which is also what a row created without one
 * gets. An empty list would describe a thing no group could ever see, so
 * {@see EraTable::normalise()} turns one back into "all of them".
 *
 * The models using this must cast `eras` to `array` and list it as fillable.
 */
trait HasEras
{
    public static function bootHasEras(): void
    {
        static::saving(function (Model $model): void {
            $model->eras = EraTable::normalise($model->eras);
        });
    }

    /**
     * Limit to what a group playing in this era would have. Passing null is
     * "every era", so a caller with no era in hand need not branch.
     */
    #[Scope]
    public function inEra(Builder $query, ?Era $era): void
    {
        if ($era === null) {
            return;
        }

        $query->whereJsonContains('eras', $era->value);
    }

    public function availableIn(?Era $era): bool
    {
        return $era === null || in_array($era->value, $this->eras ?? [], true);
    }
}
