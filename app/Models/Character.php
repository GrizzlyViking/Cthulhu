<?php

namespace App\Models;

use App\Enums\Archetype;
use App\Enums\CharacterKind;
use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Enums\Purse;
use App\Misc\CharacterCreation;
use App\Misc\Wealth;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int           $id
 * @property string        $slug
 * @property int           $strength
 * @property int           $dexterity
 * @property int           $intelligence
 * @property int           $constitution
 * @property int           $appearance
 * @property int           $power
 * @property int           $size
 * @property int           $education
 * @property int           $move_rate
 * @property int           $hit_points
 * @property int           $sanity
 * @property int           $luck
 * @property ?float        $cash
 * @property ?float        $assets
 * @property int           $magic_points
 * @property int           $dodge
 * @property int           $build
 * @property string        $damage_bonus
 * @property ?string       $avatar              the stored path, which may outlive the file
 * @property bool          $temporary_insanity
 * @property bool          $indefinite_insanity
 * @property bool          $major_wound
 * @property bool          $unconscious
 * @property bool          $dying
 * @property CharacterKind $kind
 * @property ?Archetype    $archetype
 * @property ?int          $keeper_id
 * @property ?int          $user_id
 * @property ?int          $group_id
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
        'cash',
        'assets',
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
        'kind',
        'archetype',
        'keeper_id',
    ];

    /*
     * Games and the group come along because the nav has to sort every visible
     * character into the campaign being played and the ones that are over, and
     * the sheet's era is read off them. Both are eager loads, not a query per
     * character.
     */
    protected $with = ['skills', 'player', 'weapons', 'games', 'group'];

    /** @var list<string> */
    protected $appends = ['in_active_game', 'wealth'];

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
            'cash'                => 'float',
            'assets'              => 'float',
            'magic_points'        => 'integer',
            'dodge'               => 'integer',
            'build'               => 'integer',
            'status'              => CharacterStatus::class,
            'kind'                => CharacterKind::class,
            'archetype'           => Archetype::class,
            'backstory'           => 'array',
            'wizard_step'         => 'integer',
        ];
    }

    /**
     * Read from the characteristics rather than the column, so editing STR,
     * DEX, SIZ or age on the sheet moves it straight away.
     */
    public function moveRate(): Attribute
    {
        return Attribute::make(
            get: fn (): int => CharacterCreation::moveRate($this)
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

    /**
     * Sheets a player owns and plays. Every list a player is shown asks for this:
     * the Keeper's own cast lives in the same table and is nobody else's business.
     */
    #[Scope]
    protected function investigators(Builder $query): void
    {
        $query->where('kind', CharacterKind::Investigator);
    }

    /**
     * The cast one Keeper has conjured up — theirs alone, whichever games they
     * are in.
     */
    #[Scope]
    protected function castOf(Builder $query, User $keeper): void
    {
        $query->where('kind', CharacterKind::NonPlayer)->where('keeper_id', $keeper->id);
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
     * The Keeper this sheet belongs to, on a sheet nobody plays. Null on an
     * investigator, who has a `player` instead.
     *
     * @return BelongsTo<User, $this>
     */
    public function keeper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }

    /**
     * Whether this is one of a Keeper's own, rather than somebody's investigator.
     */
    public function isNpc(): bool
    {
        return $this->kind === CharacterKind::NonPlayer;
    }

    /**
     * Delete a sheet and everything hanging off it, for good — no soft delete, no
     * pivot rows left behind.
     *
     * What the Keeper's cast needs: a cultist conjured up for one scene should
     * leave nothing at all when the scene ends, and `equipables` has no cascade to
     * do it for us. A player's investigator is retired with an ordinary
     * `delete()` instead, so it can come back.
     */
    public function purge(): void
    {
        $this->skills()->detach();
        $this->weapons()->detach();
        $this->equipment()->detach();
        $this->games()->detach();

        $this->forceDelete();
    }

    /**
     * A slug nobody has taken, retired sheets included — the column is unique and
     * the Keeper's cast is generated from a short list of names, so two Silas
     * Thornes on one server are a matter of time.
     */
    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'character';
        $slug = $base;

        for ($suffix = 2; static::withTrashed()->where('slug', $slug)->exists(); $suffix++) {
            $slug = $base.'-'.$suffix;
        }

        return $slug;
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
     * What the investigator has to spend, and what they are worth besides.
     *
     * Cash and assets follow the Credit Rating band out of Table II until
     * something is actually bought or a figure typed over — so a sheet fresh
     * out of the wizard needs no bookkeeping at all, and raising Credit Rating
     * raises the money with it. The first purchase settles both columns and
     * they stop following the band; `settled` is which of the two is happening,
     * and is what lets the sheet say so.
     *
     * The living standard and the spending level always come from the band:
     * they are a rating, not a balance, and no amount of spending changes what
     * an investigator can buy without counting.
     *
     * @return Attribute<array{living_standard: string, description: string, spending_level: float, cash: float, assets: float, settled: bool}, never>
     */
    protected function wealth(): Attribute
    {
        return Attribute::make(
            get: function (): array {
                $band = Wealth::for($this->creditRating(), $this->era());

                return [
                    'living_standard' => $band['living_standard'],
                    'description'     => $band['description'],
                    'spending_level'  => $band['spending_level'],
                    'cash'            => $this->cash ?? $band['cash'],
                    'assets'          => $this->assets ?? $band['assets'],
                    'settled'         => $this->cash !== null || $this->assets !== null,
                ];
            }
        );
    }

    /**
     * Take the price of something out of one of the two purses.
     *
     * Both columns are written, not just the one being spent from: the moment a
     * player starts counting their money, the other figure has to stop drifting
     * with their Credit Rating or the sheet would contradict itself.
     *
     * A balance is allowed to go past zero. The player chose the price and the
     * purse, and an investigator who has overspent is a fact of play, not an
     * error to refuse.
     */
    public function pay(float $amount, Purse $purse): void
    {
        $column = $purse->column();

        if ($column === null || $amount <= 0.0) {
            return;
        }

        $wealth = $this->wealth;

        $this->update([
            'cash'   => round($column === 'cash' ? $wealth['cash'] - $amount : $wealth['cash'], 2),
            'assets' => round($column === 'assets' ? $wealth['assets'] - $amount : $wealth['assets'], 2),
        ]);
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
