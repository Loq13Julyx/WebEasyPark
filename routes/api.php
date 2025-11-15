<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\YoloController;
use App\Http\Controllers\API\ParkingRecordController; 
use App\Http\Controllers\Api\ParkingSlotController;   // ← tambahan controller slot

/**
 * =========================================================
 *  ROUTE PUBLIC (tanpa autentikasi)
 * =========================================================
 */

// Login
Route::post('/login', [AuthController::class, 'login']);

// YOLO Camera & Sensors
Route::get('/deteksiyolo', [YoloController::class, 'DeteksiYolo']);
Route::get('/sensorultrasonik', [YoloController::class, 'SensorUltrasonik']);

// Parking Records (Publik)
Route::get('/parking-records', [ParkingRecordController::class, 'index']);
Route::post('/parking-records', [ParkingRecordController::class, 'store']); // masuk parkir
Route::get('/parking-records/{id}', [ParkingRecordController::class, 'show']);

/**
 * =========================================================
 *  PARKING SLOT API (Publik untuk kamera YOLO)
 * =========================================================
 */

// Mendapatkan data slot (mirip foto deteksi)
Route::get('/parking-slots', [ParkingSlotController::class, 'index']);

// Update status slot per slot_code (A1, A2, B1, dll)
Route::put('/parking-slots/{slot_code}/status', [ParkingSlotController::class, 'updateStatus']);


/**
 * =========================================================
 *  ROUTE PROTECTED (HARUS LOGIN SANCTUM)
 * =========================================================
 */
Route::middleware('auth:sanctum')->group(function () {

    // Logout & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

});
