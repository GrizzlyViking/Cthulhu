<?php

use App\Models\GameResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Storage::fake('local');
    $this->group = Group::factory()->create();
    $this->game  = $this->group->startGame('The Haunting');
    $this->user  = User::factory()->inGroup($this->group)->create();
    $this->actingAs($this->user);
});

test('any group member uploads an image which another member can open', function () {
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->image('clue.jpg')])->assertCreated();
    $resource = GameResource::firstOrFail();
    expect($resource->game_id)->toBe($this->game->id);
    Storage::disk('local')->assertExists($resource->path);
    expect($resource->toArray())->not->toHaveKey('path');
    $other = User::factory()->inGroup($this->group)->create();
    $this->actingAs($other)->get(route('resources.file', $resource))->assertOk()->assertHeader('content-type', 'image/jpeg')->assertHeader('cache-control', 'no-store, private');
    $this->get(route('resources.file', ['resource' => $resource, 'download' => 1]))->assertDownload('clue.jpg');
});

test('PDF and Word files are accepted as downloads', function ($name, $mime) {
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->create($name, 10, $mime)])->assertCreated();
    $this->get(route('resources.file', GameResource::firstOrFail()))->assertDownload($name);
})->with([
    ['handout.pdf', 'application/pdf'],
    ['handout.doc', 'application/msword'],
    ['handout.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
]);

test('changing campaigns shelves the previous files without losing access', function () {
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->image('old-clue.png')])->assertCreated();
    $new = $this->group->startGame('Orient Express');
    $new->activate();
    $this->user->unsetRelation('group');
    $this->get(route('resources.index'))->assertInertia(fn (Assert $page) => $page->where('game.id', $new->id)->where('game.active', true)->where('resources.total', 0));
    $this->get(route('resources.index', ['game' => $this->game->id]))->assertInertia(fn (Assert $page) => $page->where('game.active', false)->where('resources.total', 1)->where('resources.data.0.name', 'old-clue.png'));
});

test('another group cannot list upload or download the resources', function () {
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->image('clue.jpg')]);
    $resource = GameResource::firstOrFail();
    $outsider = User::factory()->inGroup(Group::factory()->create())->create();
    $this->actingAs($outsider)->get(route('resources.index', ['game' => $this->game->id]))->assertNotFound();
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->image('clue.jpg')])->assertNotFound();
    $this->get(route('resources.file', $resource))->assertNotFound();
});

test('unsafe file types and oversized files are refused', function () {
    foreach ([UploadedFile::fake()->create('script.html', 1, 'text/html'), UploadedFile::fake()->create('drawing.svg', 1, 'image/svg+xml'), UploadedFile::fake()->create('large.pdf', 15361, 'application/pdf')] as $file) {
        $this->postJson(route('resources.store', $this->game), ['file' => $file])->assertUnprocessable()->assertJsonValidationErrors('file');
    }
    expect(GameResource::count())->toBe(0);
});

test('deleting a campaign removes its files', function () {
    $this->postJson(route('resources.store', $this->game), ['file' => UploadedFile::fake()->image('clue.jpg')]);
    $path = GameResource::firstOrFail()->path;
    $this->game->delete();
    Storage::disk('local')->assertMissing($path);
    expect(GameResource::count())->toBe(0);
});

test('an ungrouped player has an empty library and guests must log in', function () {
    $this->actingAs(User::factory()->create())->get(route('resources.index'))->assertInertia(fn (Assert $page) => $page->where('game', null)->has('games', 0));
    auth()->logout();
    $this->get(route('resources.index'))->assertRedirect(route('login'));
});
