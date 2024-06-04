<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\KeeperOfSecretsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/', [KeeperOfSecretsController::class, 'welcome'])->name('welcome');
    Route::get('/dashboard', [KeeperOfSecretsController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::resource('character', CharacterController::class)->except(['create', 'store', 'edit', 'update']);

    //Using Resource Controller for Character with excepting some methods
    Route::get('/create', [CharacterController::class, 'create'])->name('create');
    Route::post('/create/step/first', [CharacterController::class, 'firstStep'])->name('create.step.first');
    Route::put('/{character}/attribute/update', [CharacterController::class, 'updateAttribute'])->name('attribute.update');
    Route::get('rename/{character}', [CharacterController::class, 'renameCharacter'])->name('rename.character');
    Route::get('increment/{character}/{skill}', [CharacterController::class, 'incrementExperience'])->name('experience.increment');
    Route::get('reset/{character}/{skill}', [CharacterController::class, 'resetExperience'])->name('experience.reset');

    Route::post('/weapon/reload', [CharacterController::class, 'reloadWeapon'])->name('reload.weapon');
    Route::post('/weapon/remove', [CharacterController::class, 'removeWeapon'])->name('remove.weapon');
    Route::post('/weapon/fire', [CharacterController::class, 'fireWeapon'])->name('fire.weapon');

    Route::resource('skills', SkillController::class)->only(['store']);
    Route::get('/append/skills/{character}', [SkillController::class, 'appendAllMissingSkills'])->name('skill.missing.append');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
