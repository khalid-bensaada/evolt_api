<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\StatisticsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::apiResource('/stations', StationController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/mes-reservations', [ReservationController::class, 'mesReservations']);
    Route::post('/reservations/{id}/pay', [ReservationController::class, 'pay']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
});


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::apiResource('/stations', StationController::class);
    Route::get('/statistics', [StatisticsController::class, 'index']);
    Route::get('/dashboard', [StatisticsController::class, 'dashboard']); 
});
