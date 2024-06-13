<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeaponController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'welcome'])->name('welcome');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    //Using Resource Controller for Character with excepting some methods
    Route::resource('/character', CharacterController::class);
    Route::put('/character/{character}/attribute/update', [CharacterController::class, 'updateAttribute'])->name('attribute.update');
    Route::get('/character/{character}/rename', [CharacterController::class, 'renameCharacter'])->name('rename.character');
    Route::post('/character/{character}/avatar', [CharacterController::class, 'avatar'])->name('upload.avatar');

    Route::get('/experience/{character}/{skill}/increment', [ExperienceController::class, 'increment'])->name('experience.increment');
    Route::get('/experience/{character}/{skill}/reset', [ExperienceController::class, 'reset'])->name('experience.reset');

    Route::post('/weapon/reload', [WeaponController::class, 'reloadWeapon'])->name('reload.weapon');
    Route::post('/weapon/remove', [WeaponController::class, 'removeWeapon'])->name('remove.weapon');
    Route::post('/weapon/fire', [WeaponController::class, 'fireWeapon'])->name('fire.weapon');
    Route::post('/weapon/equip/{character}', [WeaponController::class, 'addWeapon'])->name('equip.weapon');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/online', [UserController::class, 'online'])->name('users.online');

    Route::resource('skill', SkillController::class)->only(['store']);
    Route::get('/skill/append/{character}', [SkillController::class, 'appendAllMissingSkills'])->name('skill.missing.append');
    Route::post('/skill/roll', [SkillController::class, 'roll'])->name('skill.roll');
    Route::put('/skill/{character}/{skill}/update', [SkillController::class, 'update'])->name('skill.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/message/send', [MessageController::class, 'send'])->name('message.send');
    Route::put('/message/read', [MessageController::class, 'read'])->name('message.read');
});

require __DIR__ . '/auth.php';
