<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================
        // SLOT PARKIR
        // ============================

        $slotKosong = ParkingSlot::where('status', 'empty')->count();
        $totalSlots = ParkingSlot::count();

        // ============================
        // KENDARAAN HARI INI
        // ============================

        $kendaraanMasukHariIni = ParkingRecord::whereDate('entry_time', Carbon::today())->count();
        $kendaraanKeluarHariIni = ParkingRecord::whereDate('exit_time', Carbon::today())->count();

        // ============================
        // PENDAPATAN
        // ============================

        $pendapatanHariIni = ParkingRecord::whereDate('exit_time', Carbon::today())
            ->where('payment_status', 'paid')
            ->join('tarifs', 'tarifs.id', '=', 'parking_records.tarif_id')
            ->sum('tarifs.rate');

        $pendapatanBulanIni = ParkingRecord::whereMonth('exit_time', Carbon::now()->month)
            ->whereYear('exit_time', Carbon::now()->year)
            ->where('payment_status', 'paid')
            ->join('tarifs', 'tarifs.id', '=', 'parking_records.tarif_id')
            ->sum('tarifs.rate');

        // ============================
        // GRAFIK 6 BULAN TERAKHIR
        // ============================

        $monthLabels = [];
        $monthlyEarnings = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M Y');

            $monthlyEarnings[] = ParkingRecord::whereMonth('exit_time', $month->month)
                ->whereYear('exit_time', $month->year)
                ->where('payment_status', 'paid')
                ->join('tarifs', 'tarifs.id', '=', 'parking_records.tarif_id')
                ->sum('tarifs.rate');
        }

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'kendaraanKeluarHariIni',
            'pendapatanBulanIni',
            'kendaraanMasukHariIni',
            'slotKosong',
            'totalSlots',
            'monthLabels',
            'monthlyEarnings'
        ));
    }
}
