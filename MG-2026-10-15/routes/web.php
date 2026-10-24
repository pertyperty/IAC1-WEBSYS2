<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssignmentFileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SectionPerformanceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard and logout
    Route::get('/dashboard', [
        AuthController::class,
        'dashboard'
    ])->name('dashboard');

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');

    // Assignment pages
    Route::get('/assignments', [
        AssignmentFileController::class,
        'index'
    ])->name('assignments.index');

    Route::get('/assignments/create', [
        AssignmentFileController::class,
        'create'
    ])->name('assignments.create');

    Route::post('/assignments', [
        AssignmentFileController::class,
        'store'
    ])->name('assignments.store');

    Route::get('/assignments/{assignment}', [
        AssignmentFileController::class,
        'show'
    ])->name('assignments.show');

    Route::post('/assignments/{assignment}/instructions', [
        AssignmentFileController::class,
        'upload'
    ])->name('assignments.instructions.upload');

    // Submission pages
    Route::get('/submissions', [
        SubmissionController::class,
        'index'
    ])->name('submissions.index');

    Route::post('/assignments/{assignment}/submit', [
        SubmissionController::class,
        'store'
    ])->name('submissions.store');

    Route::get('/submissions/{submission}/download', [
        SubmissionController::class,
        'download'
    ])->name('submissions.download');

    // Part 1 performance demonstration
    Route::get('/sections/{id}/exams/unoptimized', [
        SectionPerformanceController::class,
        'unoptimized'
    ]);

    Route::get('/sections/{id}/exams/optimized', [
        SectionPerformanceController::class,
        'optimized'
    ]);
});
