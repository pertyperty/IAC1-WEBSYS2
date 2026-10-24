<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SchoolController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [
        AuthController::class, 'login'
    ]);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [
            AuthController::class, 'logout'
        ]);

        Route::get('/students', [
            SchoolController::class, 'students'
        ]);

        Route::get('/assignments', [
            SchoolController::class, 'assignments'
        ]);

        Route::get('/students/{id}/grades', [
            SchoolController::class, 'grades'
        ]);
    });
});
