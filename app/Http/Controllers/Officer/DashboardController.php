<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard petugas.
     */
    public function index()
    {
        $user = Auth::user(); // Ambil data user yang login

        // Total Slot Parkir
        $totalSlots = ParkingSlot::count();

        // Kendaraan Saat Ini (belum keluar)
        $vehiclesParked = ParkingRecord::whereNull('exit_time')->count();

        // Kendaraan Keluar Hari Ini
        $vehiclesExitedToday = ParkingRecord::whereDate('exit_time', now())->count();

        return view('officer.dashboard', compact(
            'user', 
            'totalSlots', 
            'vehiclesParked', 
            'vehiclesExitedToday'
        ));
    }
}
