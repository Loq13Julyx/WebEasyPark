<?php

use App\Http\Controllers\Api\ParkingSlotController;
use Illuminate\Support\Facades\Route;


Route::prefix('parking-slots')->group(function () {
    // Get all slots
    Route::get('/', [ParkingSlotController::class, 'index']);
    
    // Update from camera (Python)
    Route::post('/update', [ParkingSlotController::class, 'updateBulk']);
    
    // Single slot update
    Route::post('/{slotCode}', [ParkingSlotController::class, 'updateStatus']);
    
    // RESERVE ROUTES (NEW)
    Route::post('/{slotCode}/reserve', [ParkingSlotController::class, 'reserveSlot']);
    Route::post('/{slotCode}/cancel', [ParkingSlotController::class, 'cancelReservation']);
});