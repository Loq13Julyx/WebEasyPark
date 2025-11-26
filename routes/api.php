<?php

use App\Http\Controllers\Api\GateController;
use App\Http\Controllers\Api\ParkingSlotController;
use Illuminate\Support\Facades\Route;

/**
 * ================================
 * PARKING SLOT API
 * ================================
 */
Route::prefix('parking-slots')->group(function () {
    Route::get('/', [ParkingSlotController::class, 'index']);
    Route::put('/{slot_code}/status', [ParkingSlotController::class, 'updateStatus']);
    Route::post('/update', [ParkingSlotController::class, 'updateBulk']);
});
