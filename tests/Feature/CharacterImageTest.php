<?php

use App\Misc\CharacterImage;
use App\Models\Character;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('player');
    $this->character = Character::factory()->create(['user_id' => $this->user->id]);
});

test('a picture larger than the sheet shows it is scaled down rather than refused', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('grandmother.jpg', 4000, 5000),
        ])
        ->assertRedirect(route('character.show', $this->character->slug))
        ->assertSessionHasNoErrors();

    $path = $this->character->fresh()->avatar;

    expect($path)->not->toBeNull();

    [$width, $height] = getimagesizefromstring(Storage::disk('public')->get($path));

    // Scaled down to fit inside the portrait box, keeping the 4:5 it arrived in.
    expect($width)->toBe(900)
        ->and($height)->toBe(1125)
        ->and($height)->toBeLessThanOrEqual(CharacterImage::BOXES['portrait'][1]);
});

test('a picture already small enough is not enlarged to fill the box', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('thumb.jpg', 300, 400),
        ])->assertSessionHasNoErrors();

    [$width, $height] = getimagesizefromstring(
        Storage::disk('public')->get($this->character->fresh()->avatar)
    );

    expect($width)->toBe(300)->and($height)->toBe(400);
});

test('the backdrop and the likeness are two different pictures', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('face.jpg', 600, 800),
        ])->assertSessionHasNoErrors();

    $portrait = $this->character->fresh()->avatar;

    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'banner']), [
            'image' => UploadedFile::fake()->image('street.jpg', 3000, 1000),
        ])->assertSessionHasNoErrors();

    $character = $this->character->fresh();

    // Uploading one leaves the other where it was.
    expect($character->avatar)->toBe($portrait)
        ->and($character->banner)->not->toBeNull()
        ->and($character->banner)->not->toBe($portrait);

    Storage::disk('public')->assertExists($portrait);
    Storage::disk('public')->assertExists($character->banner);

    [$width] = getimagesizefromstring(Storage::disk('public')->get($character->banner));

    expect($width)->toBe(CharacterImage::BOXES['banner'][0]);
});

test('replacing a picture takes the one it replaced off the disk', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('first.jpg', 600, 800),
        ])->assertSessionHasNoErrors();

    $first = $this->character->fresh()->avatar;

    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('second.jpg', 600, 800),
        ])->assertSessionHasNoErrors();

    $second = $this->character->fresh()->avatar;

    expect($second)->not->toBe($first);
    Storage::disk('public')->assertMissing($first);
    Storage::disk('public')->assertExists($second);
});

test('something that is not a picture is refused in words a player can act on', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->create('notes.pdf', 40, 'application/pdf'),
        ])
        ->assertSessionHasErrors(['image' => 'That file is not a picture. JPEG, PNG, GIF, WebP and AVIF all work.']);

    expect($this->character->fresh()->avatar)->toBeNull();
});

test('only a shape the sheet has can be uploaded to', function () {
    $this->actingAs($this->user)
        ->post('/character/'.$this->character->slug.'/image/tattoo', [
            'image' => UploadedFile::fake()->image('face.jpg', 600, 800),
        ])
        ->assertNotFound();
});

test('a player cannot put a picture on somebody else s investigator', function () {
    $stranger = User::factory()->create();
    $stranger->assignRole('player');

    $this->actingAs($stranger)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('face.jpg', 600, 800),
        ])
        ->assertForbidden();

    expect($this->character->fresh()->avatar)->toBeNull();
});

test('a deleted sheet takes no new pictures', function () {
    $this->character->delete();

    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character->slug, 'shape' => 'portrait']), [
            'image' => UploadedFile::fake()->image('face.jpg', 600, 800),
        ])
        ->assertNotFound();
});

test('the stored path is remembered but the missing file behind it is not offered', function () {
    expect(CharacterImage::url('characters/nobody/portrait-gone.jpg'))->toBeNull()
        ->and(CharacterImage::url(null))->toBeNull();
});

test('the backdrop can be handed back to the house default', function () {
    $this->actingAs($this->user)
        ->post(route('character.image', ['character' => $this->character, 'shape' => 'banner']), [
            'image' => UploadedFile::fake()->image('street.jpg', 2000, 800),
        ])->assertSessionHasNoErrors();

    $uploaded = $this->character->fresh()->banner;

    $this->actingAs($this->user)
        ->delete(route('character.image.destroy', ['character' => $this->character, 'shape' => 'banner']))
        ->assertRedirect(route('character.show', $this->character->slug));

    // Nothing in the column is what puts the house picture back on the sheet.
    expect($this->character->fresh()->banner)->toBeNull();
    Storage::disk('public')->assertMissing($uploaded);
});

test('the likeness can be taken off, leaving the other picture alone', function () {
    foreach (['portrait' => 'face.jpg', 'banner' => 'street.jpg'] as $shape => $name) {
        $this->actingAs($this->user)
            ->post(route('character.image', ['character' => $this->character, 'shape' => $shape]), [
                'image' => UploadedFile::fake()->image($name, 800, 600),
            ])->assertSessionHasNoErrors();
    }

    $banner = $this->character->fresh()->banner;

    $this->actingAs($this->user)
        ->delete(route('character.image.destroy', ['character' => $this->character, 'shape' => 'portrait']))
        ->assertSessionHasNoErrors();

    $character = $this->character->fresh();

    expect($character->avatar)->toBeNull()
        ->and($character->banner)->toBe($banner);

    Storage::disk('public')->assertExists($banner);
});

test('a player cannot take a picture off somebody else s investigator', function () {
    $stranger = User::factory()->create();
    $stranger->assignRole('player');

    $this->character->update(['avatar' => 'characters/x/portrait-kept.jpg']);

    $this->actingAs($stranger)
        ->delete(route('character.image.destroy', ['character' => $this->character, 'shape' => 'portrait']))
        ->assertForbidden();

    expect($this->character->fresh()->avatar)->toBe('characters/x/portrait-kept.jpg');
});
