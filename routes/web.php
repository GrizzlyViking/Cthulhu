<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterWizardController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeaponController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'welcome'])->name('welcome');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');

    Route::get('/calendar/{calendar}', [SchedulingController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/{calendar}/get_month', [SchedulingController::class, 'getMonth'])->name('calendar.month');
    Route::post('/calendar/{calendar}/events/create', [SchedulingController::class, 'createEvents'])->name('events.create');
    Route::delete('/calendar/{calendar}/events', [SchedulingController::class, 'removePlanning'])->name('planning.events.delete');

    // Character creation wizard (must be registered before the resource so
    // /character/create and /character/wizard are not shadowed by /character/{character})
    Route::get('/character/create', [CharacterWizardController::class, 'create'])->name('character.create');
    Route::post('/character/wizard', [CharacterWizardController::class, 'store'])->name('character.wizard.store');
    Route::put('/character/wizard/{character}/profile', [CharacterWizardController::class, 'profile'])->name('character.wizard.profile');
    Route::put('/character/wizard/{character}/characteristics', [CharacterWizardController::class, 'characteristics'])->name('character.wizard.characteristics');
    Route::put('/character/wizard/{character}/occupation', [CharacterWizardController::class, 'occupation'])->name('character.wizard.occupation');
    Route::put('/character/wizard/{character}/skills', [CharacterWizardController::class, 'skills'])->name('character.wizard.skills');
    Route::put('/character/wizard/{character}/backstory', [CharacterWizardController::class, 'backstory'])->name('character.wizard.backstory');
    Route::put('/character/wizard/{character}/complete', [CharacterWizardController::class, 'complete'])->name('character.wizard.complete');

    // Using Resource Controller for Character with excepting some methods
    Route::resource('/character', CharacterController::class)->except(['create']);
    Route::get('/character/{character}/sheet', [CharacterController::class, 'sheet'])->name('character.sheet');
    Route::put('/character/{character}/attribute/update', [CharacterController::class, 'updateAttribute'])->name('attribute.update');
    Route::put('/character/{character}/backstory', [CharacterController::class, 'updateBackstory'])->name('character.backstory.update');
    Route::get('/character/{character}/rename', [CharacterController::class, 'renameCharacter'])->name('character.rename');
    Route::post('/character/{character}/avatar', [CharacterController::class, 'avatar'])->name('upload.avatar');
    Route::put('/character/{character}/{skill}/update', [CharacterController::class, 'updateSkill'])->name('character.skill.update');
    Route::put('/character/{character}/{skill}/add', [CharacterController::class, 'attachSkill'])->name('character.skill.attach');
    Route::put('/character/{character}/{skill}/remove', [CharacterController::class, 'removeSkill'])->name('character.skill.remove');
    Route::get('/character/{character}/append_skills/', [SkillController::class, 'appendAllMissingSkills'])->name('character.append.missing.skills');

    Route::get('/experience/{character}/{skill}/increment', [ExperienceController::class, 'increment'])->name('experience.increment');
    Route::get('/experience/{character}/{skill}/reset', [ExperienceController::class, 'reset'])->name('experience.reset');

    Route::post('/weapon/equip/{character}', [WeaponController::class, 'addWeapon'])->name('equip.weapon');
    Route::post('/character/{character}/weapon/{equipable}/fire', [WeaponController::class, 'fireWeapon'])->name('fire.weapon');
    Route::post('/character/{character}/weapon/{equipable}/reload', [WeaponController::class, 'reloadWeapon'])->name('reload.weapon');
    Route::put('/character/{character}/weapon/{equipable}/ammo', [WeaponController::class, 'updateAmmo'])->name('weapon.ammo.update');
    Route::delete('/character/{character}/weapon/{equipable}', [WeaponController::class, 'removeWeapon'])->name('remove.weapon');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/online', [UserController::class, 'online'])->name('users.online');
    Route::put('/users/role/{user}', [UserController::class, 'role'])->name('users.role');

    Route::resource('skill', SkillController::class)->only(['store', 'destroy']);
    Route::post('/skill/roll', [SkillController::class, 'roll'])->name('skill.roll');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/message/send', [MessageController::class, 'send'])->name('message.send');
    Route::put('/message/read', [MessageController::class, 'read'])->name('message.read');
    Route::get('/message', [MessageController::class, 'index'])->name('message.index');
});

require __DIR__.'/auth.php';
