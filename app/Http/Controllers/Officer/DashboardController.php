<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================
        // SLOT PARKIR REALTIME (FIXED)
        // ============================
        $slotTotal = ParkingSlot::count();

        $slotTerisi = ParkingRecord::where('status', 'in')
            ->distinct('parking_slot_id')
            ->count('parking_slot_id');

        $slotKosong = $slotTotal - $slotTerisi;

        // ============================
        // KENDARAAN HARI INI
        // ============================
        $kendaraanMasukHariIni = ParkingRecord::whereDate('entry_time', Carbon::today())
            ->count();

        $kendaraanKeluarHariIni = ParkingRecord::whereDate('exit_time', Carbon::today())
            ->count();

        // ============================
        // KENDARAAN YANG MASIH DI DALAM
        // ============================
        $kendaraanDalamArea = ParkingRecord::where('status', 'in')->count();

        // ============================
        // DAFTAR PARKIR RECORD HARI INI
        // ============================
        $recordsHariIni = ParkingRecord::with(['parkingSlot.area', 'tarif.vehicleType'])
            ->whereDate('entry_time', Carbon::today())
            ->latest('entry_time')
            ->paginate(10);

        return view('officer.dashboard', compact(
            'slotKosong',
            'slotTerisi',
            'slotTotal',
            'kendaraanMasukHariIni',
            'kendaraanKeluarHariIni',
            'kendaraanDalamArea',
            'recordsHariIni'
        ));
    }
}
