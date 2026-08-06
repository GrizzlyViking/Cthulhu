<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;

/**
 * A case-insensitive LIKE that behaves the same on Postgres (production) and
 * SQLite (tests) — `ilike` is Postgres-only, and SQLite's `like` is only
 * case-insensitive for ASCII, so both sides get lower-cased explicitly.
 */
trait SearchesCaseInsensitively
{
    /**
     * @param array<int, string> $columns
     */
    protected function whereAnyLike(Builder $query, array $columns, string $search): void
    {
        $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], mb_strtolower($search)).'%';

        $query->where(function (Builder $query) use ($columns, $term): void {
            foreach ($columns as $column) {
                $query->orWhereRaw('lower('.$query->getGrammar()->wrap($column).') like ? escape \'\\\'', [$term]);
            }
        });
    }
}
