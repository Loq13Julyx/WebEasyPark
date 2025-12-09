<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    // ============================
    // 🔹 AJAX LOAD DATA
    // ============================
    public function loadData(Request $request)
    {
        try {
            // SLOT PARKIR REALTIME
            $slotTotal = ParkingSlot::count();
            $slotTerisi = ParkingRecord::where('status', 'in')
                ->distinct('parking_slot_id')
                ->count('parking_slot_id');
            $slotKosong = $slotTotal - $slotTerisi;

            // KENDARAAN HARI INI - pastikan menggunakan timezone yang benar
            $today = Carbon::today()->startOfDay();
            $kendaraanMasukHariIni = ParkingRecord::whereDate('entry_time', $today)->count();
            $kendaraanKeluarHariIni = ParkingRecord::whereDate('exit_time', $today)->count();

            // DAFTAR PARKIR RECORD HARI INI (dengan pagination)
            $page = $request->get('page', 1);
            $recordsHariIni = ParkingRecord::with(['parkingSlot.area', 'tarif.vehicleType'])
                ->whereDate('entry_time', $today)
                ->latest('entry_time')
                ->paginate(10, ['*'], 'page', $page);

            // Format data untuk AJAX response
            $formattedRecords = $recordsHariIni->map(function ($record, $index) use ($recordsHariIni) {
                return [
                    'no' => $recordsHariIni->firstItem() + $index,
                    'ticket_code' => $record->ticket_code,
                    'vehicle_type' => $record->tarif && $record->tarif->vehicleType
                        ? $record->tarif->vehicleType->name
                        : '-',
                    'vehicle_icon' => $record->tarif && $record->tarif->vehicleType && $record->tarif->vehicleType->name == 'Motor'
                        ? 'bicycle'
                        : 'car-front',
                    'slot_code' => $record->parkingSlot ? $record->parkingSlot->slot_code : '-',
                    'area_name' => $record->parkingSlot && $record->parkingSlot->area
                        ? $record->parkingSlot->area->name
                        : '-',
                    'entry_time' => $record->entry_time
                        ? Carbon::parse($record->entry_time)->format('H:i')
                        : '-',
                    'exit_time' => $record->exit_time
                        ? Carbon::parse($record->exit_time)->format('H:i')
                        : '-',
                    'status' => $record->status,
                    'payment_status' => $record->payment_status,
                ];
            });

            return response()->json([
                'success' => true,
                'slotKosong' => $slotKosong,
                'slotTerisi' => $slotTerisi,
                'slotTotal' => $slotTotal,
                'kendaraanMasukHariIni' => $kendaraanMasukHariIni,
                'kendaraanKeluarHariIni' => $kendaraanKeluarHariIni,
                'records' => $formattedRecords,
                'pagination' => [
                    'current_page' => $recordsHariIni->currentPage(),
                    'last_page' => $recordsHariIni->lastPage(),
                    'per_page' => $recordsHariIni->perPage(),
                    'total' => $recordsHariIni->total(),
                    'from' => $recordsHariIni->firstItem(),
                    'to' => $recordsHariIni->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard Load Data Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }
}
