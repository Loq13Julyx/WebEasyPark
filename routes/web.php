<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ===============================
// 🔹 ADMIN CONTROLLERS
// ===============================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OfficerController;
use App\Http\Controllers\Admin\ParkingAreaController;
use App\Http\Controllers\Admin\ParkingSlotController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\ParkingRecordController;

// ===============================
// 🔹 PETUGAS CONTROLLERS
// ===============================
use App\Http\Controllers\Officer\ParkingExitController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboardController;

// ===============================
// 🔹 USER CONTROLLERS
// ===============================
use App\Http\Controllers\User\RecommendationController;

// ========================================================================
// 🔹 ADMIN ROUTES
// ========================================================================
Route::middleware(['auth', 'roleWeb:admin'])->prefix('admin')->name('admin.')->group(function () {

    // 🏠 Dashboard Admin
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 👮 CRUD Officer → user role officer
    Route::resource('officers', OfficerController::class);

    // 🅿️ Area Parkir
    Route::resource('parking-areas', ParkingAreaController::class);

    // 🅿️ Slot Parkir
    Route::resource('parking-slots', ParkingSlotController::class);
    Route::patch('parking-slots/{parking_slot}/status', [ParkingSlotController::class, 'updateStatus'])
        ->name('parking-slots.updateStatus');

    // 🚗 Tipe Kendaraan
    Route::resource('vehicle-types', VehicleTypeController::class);

    // 💰 Tarif Parkir
    Route::resource('tarifs', TarifController::class);

    // 🕒 Riwayat Parkir
    Route::get('parking-records/print', [ParkingRecordController::class, 'print'])
        ->name('parking-records.print'); // Pindahkan ke ATAS resource
    Route::resource('parking-records', ParkingRecordController::class);
});

// ========================================================================
// 🔹 PETUGAS ROUTES
// ========================================================================
// Di dalam Route::middleware(['auth', 'roleWeb:officer'])
Route::prefix('officer')->name('officer.')->group(function () {

    Route::get('dashboard', [OfficerDashboardController::class, 'index'])
        ->name('dashboard');

    // ✅ TAMBAHKAN INI
    Route::post('dashboard/load-data', [OfficerDashboardController::class, 'loadData'])
        ->name('dashboard.loadData');

    Route::get('parking-exit', [ParkingExitController::class, 'index'])
        ->name('parking-exit.index');

    Route::post('parking-exit/{record}/process', [ParkingExitController::class, 'processExit'])
        ->name('parking-exit.process');
});

Route::middleware(['auth', 'roleWeb:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('recommendations', [RecommendationController::class, 'index'])
            ->name('recommendations.index');

        Route::post('recommendations/load-data', [RecommendationController::class, 'loadData'])
            ->name('recommendations.loadData');

        // PERBAIKAN: Hapus prefix 'user.' karena sudah ada di group
        Route::post('recommendations/select-slot', [RecommendationController::class, 'selectSlot'])
            ->name('recommendations.selectSlot');  // Bukan 'user.recommendations.selectSlot'
    });

// ========================================================================
// 🔹 PROFILE ROUTES
// ========================================================================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ========================================================================
// 🔐 Auth Routes
// ========================================================================
require __DIR__ . '/auth.php';
