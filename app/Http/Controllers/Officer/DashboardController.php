<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard petugas parkir.
     */
    public function index()
    {
        $user = Auth::user();

        // ======================================
        // STATISTIK UTAMA
        // ======================================

        // Jumlah kendaraan yang sedang parkir (status = in)
        $vehiclesParked = ParkingRecord::where('status', 'in')->count();

        // Kendaraan masuk hari ini
        $vehiclesInToday = ParkingRecord::whereDate('entry_time', Carbon::today())->count();

        // Kendaraan keluar hari ini
        $vehiclesOutToday = ParkingRecord::whereDate('exit_time', Carbon::today())->count();

        // Pembayaran pending (kendaraan masih parkir tapi unpaid)
        $paymentPending = ParkingRecord::where('payment_status', 'unpaid')
            ->where('status', 'in')
            ->count();

        // Slot parkir terisi & kosong
        $slotOccupied = ParkingSlot::where('status', 'occupied')->count();
        $slotEmpty    = ParkingSlot::where('status', 'empty')->count();
        $totalSlots   = ParkingSlot::count();

        // ======================================
        // DATA KENDARAAN TERBARU
        // ======================================

        // 5 kendaraan terakhir masuk
        $recentIn = ParkingRecord::where('status', 'in')
            ->orderBy('entry_time', 'desc')
            ->take(5)
            ->get();

        // 5 kendaraan terakhir keluar
        $recentOut = ParkingRecord::where('status', 'out')
            ->orderBy('exit_time', 'desc')
            ->take(5)
            ->get();

        return view('officer.dashboard', compact(
            'user',
            'vehiclesParked',
            'vehiclesInToday',
            'vehiclesOutToday',
            'paymentPending',
            'slotOccupied',
            'slotEmpty',
            'totalSlots',
            'recentIn',
            'recentOut'
        ));
    }
}
