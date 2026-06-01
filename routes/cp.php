<?php

use Illuminate\Support\Facades\Route;
use State\Walls\Http\Controllers\CP\GatesController;

Route::prefix('walls')
    ->name('walls.')
    ->group(function () {
        Route::get('/', [GatesController::class, 'index'])->name('index');
        Route::get('/new', [GatesController::class, 'create'])->name('create');
        Route::post('/', [GatesController::class, 'store'])->name('store');
    });
