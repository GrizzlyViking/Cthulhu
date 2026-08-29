<?php

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Group;
use App\Models\StorageLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed(Database\Seeders\SkillSeeder::class);

    $this->group = Group::factory()->create();
    $this->user  = User::factory()->create(['group_id' => $this->group->id]);

    $this->actingAs($this->user);
});

/** A completed investigator of this player's, deleted and waiting. */
function deletedSheet(User $player, Group $group): Character
{
    $character = Character::factory()->create([
        'name'     => 'Patrick Setanta O’Connell',
        'user_id'  => $player->id,
        'group_id' => $group->id,
        'status'   => CharacterStatus::Complete,
    ]);

    $character->delete();

    return $character->fresh(['skills']);
}

describe('deleting keeps the sheet', function () {
    test('a deleted investigator is still there, with everything on them', function () {
        $character = deletedSheet($this->user, $this->group);

        $this->delete(route('character.destroy', $character->slug));

        expect(Character::withTrashed()->find($character->id)->trashed())->toBeTrue()
            ->and(DB::table('character_skill')->where('character_id', $character->id)->count())->toBeGreaterThan(0);
    });

    test('the name is free again the moment they are deleted', function () {
        deletedSheet($this->user, $this->group);

        $this->post(route('character.wizard.store'), [
            'name'       => 'Patrick Setanta O’Connell',
            'gender'     => 'Male',
            'age'        => 34,
            'residence'  => 'Arkham',
            'birthplace' => 'Dublin',
        ])->assertSessionHasNoErrors();

        expect(Character::where('name', 'Patrick Setanta O’Connell')->count())->toBe(1);
    });

    test('a live investigator still holds on to their name', function () {
        Character::factory()->create([
            'name'    => 'Patrick Setanta O’Connell',
            'user_id' => $this->user->id,
        ]);

        $this->post(route('character.wizard.store'), [
            'name'       => 'Patrick Setanta O’Connell',
            'gender'     => 'Male',
            'age'        => 34,
            'residence'  => 'Arkham',
            'birthplace' => 'Dublin',
        ])->assertSessionHasErrors('name');
    });

    test('the new investigator gets an address of their own, so the old one keeps theirs', function () {
        $deleted = deletedSheet($this->user, $this->group);

        $this->post(route('character.wizard.store'), [
            'name'       => 'Patrick Setanta O’Connell',
            'gender'     => 'Male',
            'age'        => 34,
            'residence'  => 'Arkham',
            'birthplace' => 'Dublin',
        ]);

        $fresh = Character::where('name', 'Patrick Setanta O’Connell')->sole();

        expect($fresh->slug)->not->toBe($deleted->slug)
            ->and(Character::withTrashed()->find($deleted->id)->slug)->toBe($deleted->slug);
    });

    test('renaming an investigator to the name they already have leaves the slug alone', function () {
        $character = Character::factory()->create(['name' => 'Silas Thorne', 'user_id' => $this->user->id]);

        $this->put(route('character.rename', $character->slug), ['value' => 'Silas Thorne']);

        expect($character->fresh()->slug)->toBe('silas-thorne');
    });
});

describe('the deleted sheet', function () {
    test('opens, so it can be restored from itself', function () {
        $character = deletedSheet($this->user, $this->group);

        $this->get(route('character.show', $character->slug))->assertOk();
    });

    test('is frozen — nothing else about it will resolve', function () {
        $character = deletedSheet($this->user, $this->group);

        $this->put(route('character.rename', $character->slug), ['value' => 'Someone Else'])
            ->assertNotFound();
    });

    test('says so in the props the nav strikes through', function () {
        $character = deletedSheet($this->user, $this->group);

        expect($character->is_deleted)->toBeTrue();
    });
});

describe('restoring', function () {
    test('brings the investigator back whole', function () {
        $character = deletedSheet($this->user, $this->group);
        $skills    = $character->skills->count();

        $this->put(route('character.restore', $character->slug))
            ->assertRedirect(route('character.show', $character->slug));

        $restored = Character::find($character->id);

        expect($restored)->not->toBeNull()
            ->and($restored->skills)->toHaveCount($skills);
    });

    test('is refused to anyone but the player and an admin', function () {
        $character = deletedSheet($this->user, $this->group);

        $this->actingAs(User::factory()->create(['group_id' => $this->group->id]))
            ->put(route('character.restore', $character->slug))
            ->assertForbidden();
    });
});

describe('deleting for good', function () {
    test('takes the skills, the belongings and the campaigns with it', function () {
        $character = deletedSheet($this->user, $this->group);

        $character->equipment()->attach(EquipmentItem::create([
            'slug' => 'a-lantern',
            'name' => 'Kerosene lantern',
        ])->id, ['storage_location_id' => StorageLocation::create([
            'slug' => 'a-pocket',
            'name' => 'Pocket',
        ])->id]);

        $this->delete(route('character.purge', $character->slug))
            ->assertRedirect(route('dashboard'));

        expect(Character::withTrashed()->find($character->id))->toBeNull()
            ->and(DB::table('character_skill')->where('character_id', $character->id)->count())->toBe(0)
            ->and(DB::table('equipables')->where('character_id', $character->id)->count())->toBe(0)
            ->and(DB::table('character_game')->where('character_id', $character->id)->count())->toBe(0);
    });

    test('is refused to anyone but the player and an admin', function () {
        $character = deletedSheet($this->user, $this->group);

        $this->actingAs(User::factory()->create(['group_id' => $this->group->id]))
            ->delete(route('character.purge', $character->slug))
            ->assertForbidden();
    });
});

test('the database sweeps up after a character however they go', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id, 'group_id' => $this->group->id]);

    $character->equipment()->attach(EquipmentItem::create([
        'slug' => 'a-lantern',
        'name' => 'Kerosene lantern',
    ])->id);

    // Not `purge()`, which detaches by hand — this is the raw delete a cascade
    // has to survive, the shape a deleted player account takes.
    DB::table('characters')->where('id', $character->id)->delete();

    expect(DB::table('equipables')->where('character_id', $character->id)->count())->toBe(0)
        ->and(DB::table('character_skill')->where('character_id', $character->id)->count())->toBe(0);
});
