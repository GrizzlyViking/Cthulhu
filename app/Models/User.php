<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int                   $id
 * @property string                $name
 * @property string                $email
 * @property string                $password
 * @property ?int                  $group_id
 * @property ?Carbon               $blocked_at
 * @property ?Group                $group
 * @property Collection<Character> $characters
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $appends = ['isOnline', 'role_names'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_id',
        'blocked_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // The frontend reads `role_names`; the pivot models behind it are noise.
        'roles',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'blocked_at'        => 'datetime',
        ];
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function getIsOnlineAttribute(): bool
    {
        return DB::table('sessions')->where('user_id', $this->id)->exists();
    }

    /**
     * @return BelongsTo<Group, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /**
     * Roles are cumulative — a user may hold any combination of player, keeper
     * and admin — so these ask "does this user have this hat on", never "is
     * this user exactly this".
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN->value);
    }

    public function isKeeper(): bool
    {
        return $this->hasRole(RoleEnum::KEEPER->value);
    }

    public function isPlayer(): bool
    {
        return $this->hasRole(RoleEnum::PLAYER->value);
    }

    /**
     * The user's role names, for sharing with the frontend. Eager-load `roles`
     * when serialising a list, or this costs a query per user.
     *
     * @return array<int, string>
     */
    public function roleNames(): array
    {
        return $this->getRoleNames()->all();
    }

    /**
     * @return array<int, string>
     */
    public function getRoleNamesAttribute(): array
    {
        return $this->roleNames();
    }

    /**
     * Scope to the users visible to the given user: their groupmates when they
     * belong to a group, or just themselves while they are ungrouped.
     */
    #[Scope]
    protected function inGroupOf(Builder $query, User $user): void
    {
        if ($user->group_id === null) {
            $query->where('id', $user->id);
        } else {
            $query->where('group_id', $user->group_id);
        }
    }
}
