<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // ✅ perbaikan namespace
use App\Http\Controllers\Api\YoloController;

// 🔹 Route tanpa autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::get('/deteksiyolo', [YoloController::class, 'DeteksiYolo']);
Route::get('/sensorultrasonik', [YoloController::class, 'SensorUltrasonik']);

// 🔹 Route dengan autentikasi Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});
