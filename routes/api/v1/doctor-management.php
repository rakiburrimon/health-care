<?php

use App\Http\Controllers\Api\V1\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('doctors')->group(function () {
        Route::post('register', [DoctorController::class, 'registerAsDoctor']);
        Route::post('availability', [DoctorController::class, 'setAvailability']);
        Route::get('{id}/availability', [DoctorController::class, 'getAvailabilities']);
    });
});
