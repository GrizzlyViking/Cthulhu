<?php

use App\Enums\RoleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Roles used to live in two places at once: the `users.role` string column and
 * Spatie's `model_has_roles` pivot. The column only ever held one value, and it
 * held the *label* ("Keeper of Arcane Lore") rather than the role name, so it
 * never lined up with the pivot. This folds the column into the pivot — which
 * has always been able to carry several roles per user — and drops it.
 */
return new class() extends Migration
{
    /**
     * Legacy column value => canonical role name. Values are lower-cased before
     * the lookup, and anything unrecognised falls back to player.
     *
     * @var array<string, string>
     */
    private const array LEGACY_ROLE_MAP = [
        'keeper of arcane lore' => 'keeper',
        'keeper'                => 'keeper',
        'admin'                 => 'admin',
        'player'                => 'player',
    ];

    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        $roleIds = $this->ensureRolesExist();

        DB::table('users')->select('id', 'role')->orderBy('id')->chunk(200, function ($users) use ($roleIds): void {
            foreach ($users as $user) {
                $name = self::LEGACY_ROLE_MAP[strtolower((string) $user->role)] ?? RoleEnum::PLAYER->value;

                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id'    => $roleIds[$name],
                    'model_type' => 'App\Models\User',
                    'model_id'   => $user->id,
                ]);
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
        });
    }

    /**
     * Restore the column and repopulate it from the user's most privileged
     * role, so a rollback lands on the same shape the app used to read.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default(RoleEnum::PLAYER->value)->after('name');
        });

        // Most privileged last: each pass overwrites the one before it.
        foreach ([RoleEnum::PLAYER, RoleEnum::KEEPER, RoleEnum::ADMIN] as $role) {
            $userIds = DB::table('model_has_roles')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name', $role->value)
                ->where('model_has_roles.model_type', 'App\Models\User')
                ->pluck('model_has_roles.model_id');

            if ($userIds->isNotEmpty()) {
                DB::table('users')->whereIn('id', $userIds)->update(['role' => $role->value]);
            }
        }
    }

    /**
     * The three canonical roles, created if the seeder has not run yet.
     *
     * @return array<string, int> role name => role id
     */
    private function ensureRolesExist(): array
    {
        $guard = config('auth.defaults.guard', 'web');

        foreach (RoleEnum::values() as $name) {
            DB::table('roles')->insertOrIgnore([
                'name'       => $name,
                'guard_name' => $guard,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('roles')
            ->whereIn('name', RoleEnum::values())
            ->where('guard_name', $guard)
            ->pluck('id', 'name')
            ->all();
    }
};
