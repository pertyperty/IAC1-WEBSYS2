<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionPerformanceController;

Route::get(
    '/sections/{id}/exams/unoptimized',
    [SectionPerformanceController::class, 'unoptimized']
);

Route::get(
    '/sections/{id}/exams/optimized',
    [SectionPerformanceController::class, 'optimized']
);

Route::get('/', function () {
    return view('welcome');
});
