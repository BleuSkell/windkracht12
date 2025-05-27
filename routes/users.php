<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsOwner;
use App\Http\Controllers\UserController;

Route::get('/gebruikers', [UserController::class, 'index'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.index');

Route::get('/gebruikers/{user}', [UserController::class, 'edit'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.edit');

Route::put('/gebruikers/{user}', [UserController::class, 'update'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.update');