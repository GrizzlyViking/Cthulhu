<?php

use App\Enums\NotesVisibility;
use App\Models\Character;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->group     = Group::factory()->create();
    $this->owner     = User::factory()->inGroup($this->group)->create();
    $this->character = Character::factory()->create(['user_id' => $this->owner->id, 'group_id' => $this->group->id, 'notes' => '<p>Secret journal marker</p>']);
});

test('visibility is enforced before notes reach any response', function ($visibility, $role, $visible) {
    $this->character->update(['notes_visibility' => $visibility]);
    $viewer = $role === 'owner' ? $this->owner : User::factory()->inGroup($this->group)->create();
    if ($role !== 'owner') {
        $viewer->assignRole($role);
    }
    $response = $this->actingAs($viewer)->get(route('character.show', $this->character));
    $response->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('notepad.canView', $visible)
        ->where('notepad.content', $visible ? '<p>Secret journal marker</p>' : null)
        ->missing('character.notes')
    );
    if (! $visible) {
        $response->assertDontSee('Secret journal marker');
        $this->get(route('dashboard'))->assertDontSee('Secret journal marker');
        $this->get(route('character.sheet', $this->character))->assertDontSee('Secret journal marker');
    }
})->with([
    ['everyone', 'owner', true], ['everyone', 'player', true], ['everyone', 'keeper', true], ['everyone', 'admin', true],
    ['keeper', 'owner', true], ['keeper', 'player', false], ['keeper', 'keeper', true], ['keeper', 'admin', false],
    ['private', 'owner', true], ['private', 'player', false], ['private', 'keeper', false], ['private', 'admin', false],
]);

test('the player saves notes and visibility together', function () {
    $this->actingAs($this->owner)->put(route('character.notes.update', $this->character), [
        'notes' => '<p>My secret</p>', 'notes_visibility' => 'private',
    ])->assertRedirect();
    expect($this->character->refresh()->notes)->toBe('<p>My secret</p>')
        ->and($this->character->notes_visibility)->toBe(NotesVisibility::Private);
});

test('a keeper cannot read or overwrite private notes through either save route', function ($route) {
    $keeper = User::factory()->inGroup($this->group)->create();
    $keeper->assignRole('keeper');
    $this->character->update(['notes_visibility' => 'private']);
    $this->actingAs($keeper)->put(route($route, $this->character), ['notes' => 'Overwritten'])->assertForbidden();
    expect($this->character->refresh()->notes)->toBe('<p>Secret journal marker</p>');
})->with(['character.notes.update', 'character.update']);

test('keepers can edit shared notes but only the player can change visibility', function () {
    $keeper = User::factory()->inGroup($this->group)->create();
    $keeper->assignRole('keeper');
    $this->actingAs($keeper)->put(route('character.notes.update', $this->character), ['notes' => 'Shared clue'])->assertRedirect();
    $this->put(route('character.notes.update', $this->character), ['notes' => 'Another clue', 'notes_visibility' => 'private'])->assertForbidden();
    expect($this->character->refresh()->notes)->toBe('Shared clue');
});

test('unknown visibility and the general visibility write are refused', function () {
    $this->actingAs($this->owner)->put(route('character.notes.update', $this->character), ['notes' => 'Clue', 'notes_visibility' => 'invalid'])->assertSessionHasErrors('notes_visibility');
    $this->put(route('character.update', $this->character), ['notes_visibility' => 'private'])->assertSessionHasErrors('notes_visibility');
});

test('a groupmate cannot edit notes even when allowed to read them', function () {
    $viewer = User::factory()->inGroup($this->group)->create();
    $this->actingAs($viewer)->put(route('character.notes.update', $this->character), ['notes' => 'Changed'])->assertForbidden();
});

test('outside groups and guests cannot open the notepad', function () {
    $outsider = User::factory()->create();
    $outsider->assignRole('keeper');
    $this->actingAs($outsider)->get(route('character.show', $this->character))->assertForbidden();
    $this->put(route('character.notes.update', $this->character), ['notes' => 'Changed'])->assertForbidden();
    auth()->logout();
    $this->get(route('character.show', $this->character))->assertRedirect(route('login'));
});

test('deleted sheets retain their privacy and reject note changes', function () {
    $this->character->update(['notes_visibility' => 'private']);
    $this->character->delete();
    $this->actingAs($this->owner)->get(route('character.show', $this->character))->assertInertia(fn (Assert $page) => $page->where('notepad.canView', true)->where('notepad.canEdit', false)->where('notepad.canSetVisibility', false));
    $this->put(route('character.notes.update', $this->character), ['notes' => 'Changed'])->assertNotFound();
});
