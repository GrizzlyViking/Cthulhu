<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Models\Character;
use App\Models\Skill;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('character')->name('character.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/{character}', function (Character $character) {
        return Inertia::render('Character', compact('character'));
    })->name('show');

    Route::get('{character}/raw', function (Character $character) {
        return $character;
    })->name('raw');

    Route::delete('{character}', function (Character $character) {
        $character->delete();
        return to_route('dashboard')->with('success', 'Character deleted.');
    })->name('delete');

    Route::post('/{character}/avatar', [CharacterController::class, 'avatar'])->name('upload.avatar');

    Route::put('/{character}/{skill}/update', [CharacterController::class, 'updateSkill'])->name('skill.update');
    Route::put('/{character}/{skill}/{pivot}/update', [CharacterController::class, 'updatePivot'])->name('pivot.update');
    Route::post('/{character}/weapon/add', [CharacterController::class, 'addWeapon'])->name('weapon.add');
});

Route::get('/create', function () {
    return Inertia::render('Character/Create');
})->middleware(['auth', 'verified'])->name('create');

Route::post('/create/step/first', [CharacterController::class, 'firstStep'])->middleware(['auth', 'verified'])->name('create.step.first');

Route::prefix('skills')->name('skill.')->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [SkillController::class, 'store'])->name('store');
    Route::get('/{character}/{skill}/', [SkillController::class, 'aptitude'])->name('aptitude');
});
Route::get('/append/skills/{character}', [SkillController::class, 'appendAllMissingSkills'])->name('skill.missing.append');

Route::get('rename/{character}', [CharacterController::class, 'renameCharacter'])->name('rename.character');
Route::get('increment/{character}/{skill}', [CharacterController::class, 'incrementExperience'])->name('experience.increment');
Route::get('reset/{character}/{skill}', [CharacterController::class, 'resetExperience'])->name('experience.reset');

Route::put('/{character}/attribute/update', [CharacterController::class, 'updateAttribute'])->name('attribute.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
