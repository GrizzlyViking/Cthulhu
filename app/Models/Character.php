<?php

namespace App\Models;

use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Misc\CharacterCreation;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $slug
 * @property int    $strength
 * @property int    $dexterity
 * @property int    $intelligence
 * @property int    $constitution
 * @property int    $appearance
 * @property int    $power
 * @property int    $size
 * @property int    $education
 * @property int    $move_rate
 * @property int    $hit_points
 * @property int    $sanity
 * @property int    $luck
 * @property int    $magic_points
 * @property int    $dodge
 * @property int    $build
 * @property string $damage_bonus
 * @property string $avatar
 * @property bool   $temporary_insanity
 * @property bool   $indefinite_insanity
 * @property bool   $major_wound
 * @property bool   $unconscious
 * @property bool   $dying
 */
class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'occupation',
        'age',
        'gender',
        'residence',
        'birthplace',
        'strength',
        'dexterity',
        'intelligence',
        'constitution',
        'appearance',
        'power',
        'size',
        'education',
        'move_rate',
        'hit_points',
        'sanity',
        'luck',
        'magic_points',
        'dodge',
        'build',
        'damage_bonus',
        'avatar',
        'temporary_insanity',
        'indefinite_insanity',
        'major_wound',
        'unconscious',
        'dying',
        'notes',
        'status',
        'backstory',
        'occupation_id',
        'wizard_step',
        'group_id',
    ];

    /*
     * Games and the group come along because the nav has to sort every visible
     * character into the campaign being played and the ones that are over, and
     * the sheet's era is read off them. Both are eager loads, not a query per
     * character.
     */
    protected $with = ['skills', 'player', 'weapons', 'games', 'group'];

    /** @var list<string> */
    protected $appends = ['in_active_game'];

    protected function casts(): array
    {
        return [
            'temporary_insanity'  => 'boolean',
            'indefinite_insanity' => 'boolean',
            'major_wound'         => 'boolean',
            'unconscious'         => 'boolean',
            'dying'               => 'boolean',
            'strength'            => 'integer',
            'dexterity'           => 'integer',
            'intelligence'        => 'integer',
            'constitution'        => 'integer',
            'appearance'          => 'integer',
            'power'               => 'integer',
            'size'                => 'integer',
            'education'           => 'integer',
            'move_rate'           => 'integer',
            'hit_points'          => 'integer',
            'sanity'              => 'integer',
            'luck'                => 'integer',
            'magic_points'        => 'integer',
            'dodge'               => 'integer',
            'build'               => 'integer',
            'status'              => CharacterStatus::class,
            'backstory'           => 'array',
            'wizard_step'         => 'integer',
        ];
    }

    public function moveRate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $move = CharacterCreation::moveRate($this);

                if ($this->age > 40 && $this->age < 51) {
                    return $move - 1;
                }
                if ($this->age > 50 && $this->age < 61) {
                    return $move - 2;
                }
                if ($this->age > 60 && $this->age < 71) {
                    return $move - 3;
                }
                if ($this->age > 70 && $this->age < 81) {
                    return $move - 4;
                }
                if ($this->age > 80 && $this->age < 91) {
                    return $move - 5;
                }

                return $move;
            }
        );
    }

    /**
     * Players own characters
     */
    #[Scope]
    protected function playersOwn(Builder $query): void
    {
        $query->where('user_id', auth()->id())->orderBy('updated_at', 'desc');
    }

    /**
     * Scope to show other players characters
     */
    #[Scope]
    protected function others(Builder $query): void
    {
        $query->where('user_id', '!=', auth()->id());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function addAllSkills(): self
    {
        Skill::all()
            ->each(function (Skill $skill) {
                $this->skills()->attach($skill, [
                    'order' => $skill->order_by,
                    'value' => $skill->starting_value,
                ]);
            });

        return $this;
    }

    /**
     * @return BelongsToMany<Skill, $this>
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('value', 'experience', 'order', 'show')->orderBy('display_name');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Occupation, $this>
     */
    public function occupationDetails(): BelongsTo
    {
        return $this->belongsTo(Occupation::class, 'occupation_id');
    }

    public function creditRating(): int
    {
        return (int) $this->skills->firstWhere('slug', 'credit_rating')?->pivot->value;
    }

    /**
     * Weapons carry ammunition on the pivot; everything an investigator owns
     * also carries where it is kept, how many there are, and a note.
     *
     * @return MorphToMany<Weapon, $this>
     */
    public function weapons(): MorphToMany
    {
        return $this->morphedByMany(Weapon::class, 'equipable')
            ->withPivot('id', 'ammo', 'ammo_reserve', 'storage_location_id', 'quantity', 'notes');
    }

    /**
     * @return MorphToMany<EquipmentItem, $this>
     */
    public function equipment(): MorphToMany
    {
        return $this->morphedByMany(EquipmentItem::class, 'equipable')
            ->withPivot('id', 'storage_location_id', 'quantity', 'notes');
    }

    public function getDamageBonus(): string
    {
        return CharacterCreation::damageBonus($this);
    }

    /**
     * @return BelongsTo<Group, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The campaigns this investigator is played in. Many-to-many because a
     * game holds a party, and once in a while one investigator turns up in
     * two campaigns.
     *
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withTimestamps();
    }

    /**
     * The game this sheet is being played in: the one its group is running
     * when the investigator is in it, and otherwise the most recent game they
     * belong to, so a sheet retired from an old campaign still reads as that
     * campaign rather than as today's.
     */
    public function currentGame(): ?Game
    {
        $activeGameId = $this->group?->active_game_id;

        if ($activeGameId !== null) {
            $active = $this->games->firstWhere('id', $activeGameId);

            if ($active !== null) {
                return $active;
            }
        }

        return $this->games->sortByDesc('id')->first();
    }

    /**
     * Whether this investigator is in the campaign their group is playing.
     *
     * Appended rather than computed by each caller: it is what the nav sorts
     * on, and what tells a sheet from a finished campaign apart from one still
     * in play. Both relations it reads are eager loaded, so it costs nothing
     * per character.
     *
     * @return Attribute<bool, never>
     */
    protected function inActiveGame(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $activeGameId = $this->group?->active_game_id;

                return $activeGameId !== null && $this->games->contains('id', $activeGameId);
            }
        );
    }

    /**
     * The era this investigator lives in — their game's. Falling back to the
     * group's while they are in none, and to the Twenties while they have no
     * group either, which is the assumption the creation wizard makes.
     */
    public function era(): Era
    {
        return $this->currentGame()?->era ?? $this->group?->era ?? Era::Twenties;
    }
}
