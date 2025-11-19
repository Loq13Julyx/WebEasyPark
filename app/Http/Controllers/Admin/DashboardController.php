<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Slot Terisi
        $slotTerisi = ParkingSlot::where('status', 'occupied')->count();

        // Slot Kosong
        $slotKosong = ParkingSlot::where('status', 'empty')->count();

        // Total Slot
        $totalSlots = ParkingSlot::count();

        // Total Pembayaran
        $totalPembayaran = ParkingRecord::where('payment_status', 'paid')
            ->join('tarifs', 'tarifs.id', '=', 'parking_records.tarif_id')
            ->sum('tarifs.rate');

        // ============================
        // Grafik Keuntungan per Bulan
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
        // Last 3 Payments
        // ============================

        $lastPayments = ParkingRecord::where('payment_status', 'paid')
            ->orderBy('exit_time', 'desc')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'slotTerisi',
            'slotKosong',
            'totalSlots',
            'totalPembayaran',
            'monthLabels',
            'monthlyEarnings',
            'lastPayments'
        ));
    }
}
