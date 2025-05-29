<?php

use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsInstructor;

Route::middleware(['auth', IsInstructor::class])->group(function () {
    Route::get('/instructeur/klanten', [InstructorController::class, 'customerIndex'])
        ->name('instructor.customers.index');

    Route::get('/instructeur/klanten/create', [InstructorController::class, 'customerCreate'])
        ->name('instructor.customers.create');

    Route::post('/instructeur/klanten', [InstructorController::class, 'customerStore'])
        ->name('instructor.customers.store');

    Route::get('/instructeur/klanten/{customer}/edit', [InstructorController::class, 'customerEdit'])
        ->name('instructor.customers.edit');

    Route::get('/instructeur/klanten/{customer}/lessen', [InstructorController::class, 'customerLessons'])
        ->name('instructor.customers.lessons');

    Route::get('/instructeur/klanten/{customer}/reserveringen/{reservation}/edit', [InstructorController::class, 'reservationEdit'])
        ->name('instructor.customers.reservations.edit');

    Route::put('/instructeur/klanten/{customer}/reserveringen/{reservation}', [InstructorController::class, 'reservationUpdate'])
        ->name('instructor.customers.reservations.update');

    Route::put('/instructeur/klanten/{customer}/lessen', [InstructorController::class, 'updateCustomerLessons'])
        ->name('instructor.customers.lessons.update');

    Route::put('/instructeur/klanten/{customer}', [InstructorController::class, 'customerUpdate'])
        ->name('instructor.customers.update');

    Route::delete('/instructeur/klanten/{customer}', [InstructorController::class, 'customerDestroy'])
        ->name('instructor.customers.destroy');
});