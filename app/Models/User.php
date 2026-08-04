<?php

namespace App\Models;

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
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string role
 * @property ?int                  $group_id
 * @property ?Carbon               $blocked_at
 * @property ?Group                $group
 * @property Collection<Message>   $messages
 * @property Collection<Character> $characters
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $appends = ['isOnline'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function isBlocked(): bool
    {
        return $this->blocked_at !== null;
    }

    /**
     * Scope to the users visible to the given user: their groupmates when they
     * belong to a group, or just themselves while they are ungrouped.
     */
    #[Scope]
    public function inGroupOf(Builder $query, User $user): void
    {
        if ($user->group_id === null) {
            $query->where('id', $user->id);
        } else {
            $query->where('group_id', $user->group_id);
        }
    }
}
