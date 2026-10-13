<?php

use Azuriom\Plugin\Birthday\Controllers\Admin\PlayerController;
use Azuriom\Plugin\Birthday\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:birthday.admin')->group(function () {
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/{user}', [PlayerController::class, 'show'])->name('players.show');
    Route::post('/players/{user}', [PlayerController::class, 'update'])->name('players.update');

    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');
});
