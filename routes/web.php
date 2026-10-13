<?php

use Azuriom\Plugin\Birthday\Controllers\BirthdayController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BirthdayController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/', [BirthdayController::class, 'store'])->name('store');
    Route::post('/claim', [BirthdayController::class, 'claim'])->name('claim');
});
