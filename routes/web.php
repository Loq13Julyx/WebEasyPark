<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ===============================
// 🔹 ADMIN CONTROLLERS
// ===============================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
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
use App\Http\Controllers\User\ParkingTicketController;

// ===============================
// 🔸 DEFAULT REDIRECT
// ===============================
// Route::get('/', fn() => redirect()->route('login'));


// ========================================================================
// 🔹 ADMIN ROUTES
// ========================================================================
Route::middleware(['auth', 'roleWeb:admin'])->group(function () {

    // 🏠 Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // 👥 Data Petugas
    Route::resource('admin/petugas', PetugasController::class)
        ->names('admin.petugas');

    Route::get('admin/petugas/{user}/detail', [PetugasController::class, 'createDetail'])
        ->name('admin.petugas.detail.create');

    Route::post('admin/petugas/{user}/detail', [PetugasController::class, 'storeDetail'])
        ->name('admin.petugas.detail.store');

    // 🅿️ Area Parkir
    Route::resource('admin/parking-areas', ParkingAreaController::class)
        ->names('admin.parking-areas');

    // 🅿️ Slot Parkir
    Route::resource('admin/parking-slots', ParkingSlotController::class)
        ->names('admin.parking-slots');

    Route::patch(
        'admin/parking-slots/{parking_slot}/status',
        [ParkingSlotController::class, 'updateStatus']
    )
        ->name('admin.parking-slots.updateStatus');

    // 🚗 Tipe Kendaraan
    Route::resource('admin/vehicle-types', VehicleTypeController::class)
        ->names('admin.vehicle-types');

    // 💰 Tarif Parkir
    Route::resource('admin/tarifs', TarifController::class)
        ->names('admin.tarifs');

    // 🕒 Riwayat & Data Parkir (FULL CRUD)
    Route::resource('admin/parking-records', ParkingRecordController::class)
        ->names('admin.parking-records');
    Route::get('/admin/parking-records/print', [ParkingRecordController::class, 'print'])->name('admin.parking-records.print');
});


// ========================================================================
// 🔹 PETUGAS ROUTES
// ========================================================================
Route::prefix('officer')
    ->middleware(['auth', 'roleWeb:officer'])
    ->name('officer.')
    ->group(function () {

        Route::get('dashboard', [OfficerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('parking-exit', [ParkingExitController::class, 'index'])
            ->name('parking-exit.index');

        Route::post('parking-exit/{record}/process', [ParkingExitController::class, 'processExit'])
            ->name('parking-exit.process');
    });


// ========================================================================
// 🔹 USER ROUTES
// ========================================================================
Route::middleware(['auth', 'roleWeb:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/recommendations', [RecommendationController::class, 'index'])
            ->name('recommendations.index');

        Route::post('/recommendations/select-slot/{id}', [RecommendationController::class, 'selectSlot'])
            ->name('recommendations.selectSlot');
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
