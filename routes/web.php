<?php

use App\Http\Controllers\CharacterController;
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

    // Using Resource Controller for Character with excepting some methods
    Route::resource('/character', CharacterController::class);
    Route::put('/character/{character}/attribute/update', [CharacterController::class, 'updateAttribute'])->name('attribute.update');
    Route::get('/character/{character}/rename', [CharacterController::class, 'renameCharacter'])->name('character.rename');
    Route::post('/character/{character}/avatar', [CharacterController::class, 'avatar'])->name('upload.avatar');
    Route::put('/character/{character}/{skill}/update', [CharacterController::class, 'updateSkill'])->name('character.skill.update');
    Route::put('/character/{character}/{skill}/add', [CharacterController::class, 'attachSkill'])->name('character.skill.attach');
    Route::put('/character/{character}/{skill}/remove', [CharacterController::class, 'removeSkill'])->name('character.skill.remove');
    Route::get('/character/{character}/append_skills/', [SkillController::class, 'appendAllMissingSkills'])->name('character.append.missing.skills');

    Route::get('/experience/{character}/{skill}/increment', [ExperienceController::class, 'increment'])->name('experience.increment');
    Route::get('/experience/{character}/{skill}/reset', [ExperienceController::class, 'reset'])->name('experience.reset');

    Route::post('/weapon/reload', [WeaponController::class, 'reloadWeapon'])->name('reload.weapon');
    Route::post('/weapon/remove', [WeaponController::class, 'removeWeapon'])->name('remove.weapon');
    Route::post('/weapon/fire', [WeaponController::class, 'fireWeapon'])->name('fire.weapon');
    Route::post('/weapon/equip/{character}', [WeaponController::class, 'addWeapon'])->name('equip.weapon');

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
