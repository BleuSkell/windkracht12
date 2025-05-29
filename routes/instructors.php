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

    Route::post('/instructeur/klanten/{customer}/reserveringen/{reservation}/cancel-weather', [InstructorController::class, 'cancelLessonWeather'])
        ->name('instructor.reservations.cancel-weather');
    
    Route::post('/instructeur/klanten/{customer}/reserveringen/{reservation}/cancel-sick', [InstructorController::class, 'cancelLessonSick'])
        ->name('instructor.reservations.cancel-sick');
        
    Route::get('/instructeur/rooster/dag', [InstructorController::class, 'scheduleDay'])
        ->name('instructor.schedule.day');

    Route::get('/instructeur/rooster/week', [InstructorController::class, 'scheduleWeek'])
        ->name('instructor.schedule.week');
        
    Route::get('/instructeur/rooster/maand', [InstructorController::class, 'scheduleMonth'])
        ->name('instructor.schedule.month');

    Route::post('/instructeur/klanten/{customer}/reserveringen/{reservation}/cancel-approve', [InstructorController::class, 'approveCancellation'])
        ->name('instructor.reservations.cancel-approve');
        
    Route::post('/instructeur/klanten/{customer}/reserveringen/{reservation}/cancel-reject', [InstructorController::class, 'rejectCancellation'])
        ->name('instructor.reservations.cancel-reject');

    Route::get('/instructeur/annuleringen', [InstructorController::class, 'cancellationRequests'])
        ->name('instructor.cancellation-requests');
});