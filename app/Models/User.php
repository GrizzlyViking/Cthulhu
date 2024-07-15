<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string role
 * @property Collection<Message> $messages
 * @property Collection<Character> $characters
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
            'password' => 'hashed',
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
}
