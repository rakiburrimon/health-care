<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('patient')->group(function () {
        Route::post('register', [PatientController::class, 'registerAsPatient']);
    });

    Route::prefix('appointments')->group(function () {
        Route::post('book', [AppointmentController::class, 'bookAppointment']);
        Route::get('{patientId}', [AppointmentController::class, 'getPatientAppointments']);
        Route::get('doctor/{doctorId}', [AppointmentController::class, 'getDoctorAppointments']);
    });
});
