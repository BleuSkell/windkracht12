<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsOwner;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;

Route::get('/gebruikers', [UserController::class, 'index'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.index');

Route::get('/gebruikers/{user}', [UserController::class, 'edit'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.edit');

Route::put('/gebruikers/{user}', [UserController::class, 'update'])
    ->middleware(['auth', IsOwner::class])
    ->name('users.update');

// Owner routes
Route::middleware(['auth', IsOwner::class])->group(function () {
    Route::get('/klanten', [OwnerController::class, 'customerIndex'])->name('owner.customers.index');
    Route::get('/klanten/create', [OwnerController::class, 'customerCreate'])->name('owner.customers.create');
    Route::post('/klanten', [OwnerController::class, 'customerStore'])->name('owner.customers.store');
    Route::get('/klanten/{customer}/edit', [OwnerController::class, 'customerEdit'])->name('owner.customers.edit');
    Route::put('/klanten/{customer}', [OwnerController::class, 'customerUpdate'])->name('owner.customers.update');
    Route::delete('/klanten/{customer}', [OwnerController::class, 'customerDestroy'])->name('owner.customers.destroy');

    // Instructor management routes
    Route::get('/instructeurs', [OwnerController::class, 'instructorIndex'])->name('owner.instructors.index');
    Route::get('/instructeurs/create', [OwnerController::class, 'instructorCreate'])->name('owner.instructors.create');
    Route::post('/instructeurs', [OwnerController::class, 'instructorStore'])->name('owner.instructors.store');
    Route::get('/instructeurs/{instructor}/edit', [OwnerController::class, 'instructorEdit'])->name('owner.instructors.edit');
    Route::put('/instructeurs/{instructor}', [OwnerController::class, 'instructorUpdate'])->name('owner.instructors.update');
    Route::delete('/instructeurs/{instructor}', [OwnerController::class, 'instructorDestroy'])->name('owner.instructors.destroy');

    // Lesson cancellation routes
    Route::post('/klanten/{customer}/reserveringen/{reservation}/cancel-weather', [OwnerController::class, 'cancelLessonWeather'])
        ->name('owner.reservations.cancel-weather');
    Route::post('/klanten/{customer}/reserveringen/{reservation}/cancel-sick', [OwnerController::class, 'cancelLessonSick'])
        ->name('owner.reservations.cancel-sick');

    // Customer lessons route
    Route::get('/klanten/{customer}/lessen', [OwnerController::class, 'customerLessons'])
        ->name('owner.customers.lessons');

    // Instructor schedule routes
    Route::get('/instructeurs/{instructor}/rooster/dag', [OwnerController::class, 'instructorScheduleDay'])
        ->name('owner.instructors.schedule.day');
    Route::get('/instructeurs/{instructor}/rooster/week', [OwnerController::class, 'instructorScheduleWeek'])
        ->name('owner.instructors.schedule.week');
    Route::get('/instructeurs/{instructor}/rooster/maand', [OwnerController::class, 'instructorScheduleMonth'])
        ->name('owner.instructors.schedule.month');

    // Unpaid invoices route
    Route::get('/betalingen', [OwnerController::class, 'unpaidInvoices'])->name('owner.unpaid-invoices');

    // Reservation confirmation route
    Route::post('/reserveringen/{reservation}/confirm', [OwnerController::class, 'confirmReservation'])
        ->name('owner.reservations.confirm');
});