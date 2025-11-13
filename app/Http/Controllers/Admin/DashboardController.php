<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total Petugas
        $petugasRole = Role::where('name', 'petugas')->first();
        $totalPetugas = $petugasRole ? $petugasRole->users()->count() : 0;

        // Total Slot Parkir
        $totalSlots = ParkingSlot::count();

        // // Kendaraan Saat Ini (belum keluar)
        // $vehiclesParked = ParkingRecord::whereNull('exit_time')->count();

        // // Kendaraan Keluar Hari Ini
        // $vehiclesExitedToday = ParkingRecord::whereDate('exit_time', now())->count();

        return view('admin.dashboard', compact(
            'user', 
            'totalPetugas', 
            'totalSlots', 
            // 'vehiclesParked', 
            // 'vehiclesExitedToday'
        ));
    }
}
