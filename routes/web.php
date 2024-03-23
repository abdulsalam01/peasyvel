<?php

use App\Http\Controllers\DailyRecordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

// View Sections.
Route::prefix('view')->group(function() {
    Route::controller(ViewController::class)->group(function () {
        Route::get('/', 'viewHome');
        Route::get('/users', 'viewUser');
        Route::get('/daily_reports', 'viewReport');

        Route::delete('/delete/{id}', 'synchronize');
    });
});

// API Sections.
Route::prefix('api')->group(function () {
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'show');
    });

    Route::prefix('daily_records')->controller(DailyRecordController::class)->group(function () {
        Route::get('/', 'show');
    });
});