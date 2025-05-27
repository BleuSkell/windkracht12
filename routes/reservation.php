<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsOwner;
use App\Http\Controllers\ReservationController;

Route::get('/reserveringen', [ReservationController::class, 'index'])
    ->middleware('auth')
    ->name('reservations.index');

Route::get('/reserveringen/aanmaken', [ReservationController::class, 'create'])
    ->middleware('auth')
    ->name('reservations.create');

Route::post('/reserveringen', [ReservationController::class, 'store'])
    ->middleware('auth')
    ->name('reservations.store');