<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use App\Models\ParkingArea;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ============================
        // STATISTIK UTAMA
        // ============================

        $slotTerisi = ParkingSlot::where('status', 'occupied')->count();
        $slotKosong = ParkingSlot::where('status', 'empty')->count();
        $totalSlots = ParkingSlot::count();

        $persentaseOccupancy = $totalSlots > 0
            ? round(($slotTerisi / $totalSlots) * 100, 1)
            : 0;

        $kendaraanMasukHariIni = ParkingRecord::whereDate('entry_time', Carbon::today())->count();
        $kendaraanKeluarHariIni = ParkingRecord::whereDate('exit_time', Carbon::today())->count();
        $kendaraanSedangParkir = ParkingRecord::where('status', 'in')->count();

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
        // GRAFIK PENDAPATAN (6 Bulan)
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

        // ============================
        // STATUS AREA PARKIR
        // ============================

        $areaStats = ParkingArea::select(
            'parking_areas.id',
            'parking_areas.name',
            DB::raw('COUNT(parking_slots.id) as total_slots'),
            DB::raw('SUM(CASE WHEN parking_slots.status = "occupied" THEN 1 ELSE 0 END) as occupied'),
            DB::raw('SUM(CASE WHEN parking_slots.status = "empty" THEN 1 ELSE 0 END) as available')
        )
            ->leftJoin('parking_slots', 'parking_slots.area_id', '=', 'parking_areas.id')
            ->where('parking_areas.status', 'active')
            ->groupBy('parking_areas.id', 'parking_areas.name')
            ->get()
            ->map(function ($area) {
                $area->occupancy_percentage = $area->total_slots > 0
                    ? round(($area->occupied / $area->total_slots) * 100, 1)
                    : 0;
                return $area;
            });

        // ============================
        // TRANSAKSI TERBARU
        // ============================

        $lastPayments = ParkingRecord::with(['tarif.vehicleType'])
            ->where('payment_status', 'paid')
            ->orderBy('exit_time', 'desc')
            ->limit(10)
            ->get();

        $pendapatanBulanIni = ParkingRecord::whereMonth('exit_time', Carbon::now()->month)
            ->whereYear('exit_time', Carbon::now()->year)
            ->where('payment_status', 'paid')
            ->join('tarifs', 'tarifs.id', '=', 'parking_records.tarif_id')
            ->sum('tarifs.rate');

        $pembayaranPending = ParkingRecord::where('payment_status', 'unpaid')
            ->where('status', 'in')
            ->count();

        // ============================
        // RATA-RATA DURASI PARKIR
        // ============================

        $avgDuration = ParkingRecord::whereDate('exit_time', Carbon::today())
            ->where('status', 'out')
            ->get()
            ->map(function ($record) {
                return Carbon::parse($record->entry_time)->diffInMinutes($record->exit_time);
            })
            ->avg();

        $avgDurationFormatted = $avgDuration ? round($avgDuration / 60, 1) : 0;

        return view('admin.dashboard', compact(
            'user',
            'slotKosong',
            'totalSlots',
            'kendaraanSedangParkir',
            'pendapatanHariIni',
            'kendaraanKeluarHariIni',
            'pendapatanBulanIni',
            'persentaseOccupancy',
            'kendaraanMasukHariIni',
            'pembayaranPending',
            'monthLabels',
            'monthlyEarnings',
            'areaStats',
            'lastPayments',
            'avgDurationFormatted',
        ));
    }
}
