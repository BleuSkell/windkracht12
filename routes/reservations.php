<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsOwner;
use App\Http\Controllers\ReservationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/reserveringen', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reserveren', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reserveren', [ReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/reserveringen/{reservation}/betaling', [ReservationController::class, 'updatePaymentStatus'])
        ->name('reservations.update-payment');
});