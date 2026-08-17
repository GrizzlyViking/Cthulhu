<?php

namespace App\Models;

use App\Misc\EquipmentTable;
use App\Misc\Money;
use App\Models\Concerns\HasEras;
use Database\Factories\EquipmentItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A line in the equipment catalogue: either the Investigator Handbook's, or
 * something a player typed that the handbook did not have.
 *
 * @property int           $id
 * @property string        $slug
 * @property string        $name
 * @property ?string       $section
 * @property ?string       $cost
 * @property array<string> $eras
 * @property bool          $is_custom
 * @property ?int          $created_by
 * @property ?Carbon       $deleted_at
 * @property-read ?float     $price the cost cell as a number
 * @property-read MorphPivot $pivot the `equipables` row, when read off a character
 */
class EquipmentItem extends Model
{
    /** @use HasFactory<EquipmentItemFactory> */
    use HasEras, HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'section',
        'cost',
        'eras',
        'is_custom',
        'created_by',
    ];

    /** @var list<string> */
    protected $appends = ['price'];

    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
            'eras'      => 'array',
        ];
    }

    /**
     * The price cell read as a number, so the sheet can fill it in when the
     * thing is bought. `cost` stays exactly as the book prints it — "5¢-20¢",
     * "9¢/lb." — and this is the cheap end of it in dollars, or null where the
     * book gives no figure at all.
     */
    protected function price(): Attribute
    {
        return Attribute::get(fn (): ?float => Money::fromCostCell($this->cost));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function characters(): MorphToMany
    {
        return $this->morphToMany(Character::class, 'equipable')
            ->withPivot(['id', 'storage_location_id', 'quantity', 'notes']);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Custom items sort after the book's, so the handbook entry always wins the
     * top of a typeahead over somebody's near-identical addition.
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    public function catalogueOrder(Builder $query): void
    {
        $sections = EquipmentTable::sections();

        $whens = '';
        foreach ($sections as $position => $section) {
            $whens .= " when '".str_replace("'", "''", $section)."' then {$position}";
        }

        $query->orderBy('is_custom')
            ->orderByRaw('(case section'.$whens.' else '.count($sections).' end)')
            ->orderBy('name');
    }

    /**
     * A slug for a player-supplied name, kept unique against everything already
     * in the catalogue — retired rows included, since the column is unique.
     */
    public static function customSlug(string $name): string
    {
        $base = 'custom--'.(Str::slug($name) ?: 'item');
        $slug = $base;

        for ($suffix = 2; static::withTrashed()->where('slug', $slug)->exists(); $suffix++) {
            $slug = $base.'-'.$suffix;
        }

        return $slug;
    }
}
