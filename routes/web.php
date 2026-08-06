<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterWizardController;
use App\Http\Controllers\EquipmentController;
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

    // Equipment. `{equipable}` is a row in the pivot, so these also move and
    // annotate weapons — everything an investigator owns sits in one table.
    Route::get('/equipment/search', [EquipmentController::class, 'search'])->name('equipment.search');
    Route::post('/character/{character}/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::put('/character/{character}/equipment/{equipable}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/character/{character}/equipment/{equipable}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::post('/character/{character}/storage-locations', [EquipmentController::class, 'storeLocation'])->name('storage.location.store');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/online', [UserController::class, 'online'])->name('users.online');

    Route::resource('skill', SkillController::class)->only(['store', 'destroy']);
    Route::post('/skill/roll', [SkillController::class, 'roll'])->name('skill.roll');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/message/send', [MessageController::class, 'send'])->name('message.send');
    Route::put('/message/read', [MessageController::class, 'read'])->name('message.read');
    Route::get('/message', [MessageController::class, 'index'])->name('message.index');
});

/*
 * The admin section. Admins manage their own group and nothing else — anything
 * that has to reach across groups stays with the artisan commands.
 */
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('index');

    Route::get('/group', [Admin\GroupController::class, 'edit'])->name('group.edit');
    Route::put('/group', [Admin\GroupController::class, 'update'])->name('group.update');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/roles', [Admin\UserController::class, 'updateRoles'])->name('users.roles.update');
    Route::put('/users/{user}/block', [Admin\UserController::class, 'block'])->name('users.block');
    Route::delete('/users/{user}/block', [Admin\UserController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/invitations', [Admin\InvitationController::class, 'store'])->name('invitations.store');
    Route::delete('/invitations/{invitation}', [Admin\InvitationController::class, 'destroy'])->name('invitations.destroy');

    Route::get('/skills', [Admin\SkillController::class, 'index'])->name('skills.index');
    Route::get('/weapons', [Admin\WeaponController::class, 'index'])->name('weapons.index');
    Route::get('/equipment', [Admin\EquipmentController::class, 'index'])->name('equipment.index');

    /*
     * Skills and the armoury are shared by every group on the server, so the
     * writes sit behind a toggle — see config/cthulhu.php. Reading them above
     * is always allowed.
     */
    Route::middleware('reference-data')->group(function () {
        Route::post('/skills', [Admin\SkillController::class, 'store'])->name('skills.store');
        Route::put('/skills/{skill}', [Admin\SkillController::class, 'update'])->name('skills.update');
        Route::delete('/skills/{skill}', [Admin\SkillController::class, 'destroy'])->name('skills.destroy');
        Route::put('/skills/{slug}/restore', [Admin\SkillController::class, 'restore'])->name('skills.restore');

        Route::post('/weapons', [Admin\WeaponController::class, 'store'])->name('weapons.store');
        Route::put('/weapons/{weapon}', [Admin\WeaponController::class, 'update'])->name('weapons.update');
        Route::delete('/weapons/{weapon}', [Admin\WeaponController::class, 'destroy'])->name('weapons.destroy');
        Route::put('/weapons/{id}/restore', [Admin\WeaponController::class, 'restore'])->name('weapons.restore');

        Route::post('/equipment', [Admin\EquipmentController::class, 'store'])->name('equipment.store');
        Route::put('/equipment/{equipment}', [Admin\EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/{equipment}', [Admin\EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::put('/equipment/{slug}/restore', [Admin\EquipmentController::class, 'restore'])->name('equipment.restore');

        Route::post('/storage-locations', [Admin\EquipmentController::class, 'storeLocation'])->name('locations.store');
        Route::put('/storage-locations/{location}', [Admin\EquipmentController::class, 'updateLocation'])->name('locations.update');
        Route::delete('/storage-locations/{location}', [Admin\EquipmentController::class, 'destroyLocation'])->name('locations.destroy');
    });
});

require __DIR__.'/auth.php';
