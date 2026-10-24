<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionPerformanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssignmentFileController;

Route::get(
    '/sections/{id}/exams/unoptimized',
    [SectionPerformanceController::class, 'unoptimized']
);

Route::get(
    '/sections/{id}/exams/optimized',
    [SectionPerformanceController::class, 'optimized']
);

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::post(
    '/assignments/{assignment}/instructions',
    [AssignmentFileController::class, 'upload']
)->name('assignments.instructions.upload');