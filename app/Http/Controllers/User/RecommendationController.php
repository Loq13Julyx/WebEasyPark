<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $areaId = $request->get('area_id');
        $vehicleTypeId = $request->get('vehicle_type_id');

        // Dropdown filter
        $areas = ParkingArea::orderBy('name')->get();
        $vehicleTypes = VehicleType::orderBy('name')->get();

        /**
         * ===============================
         * REKOMENDASI SLOT KOSONG
         * ===============================
         * Filter area / tipe kendaraan → jika dipilih
         * Hanya ambil slot dengan status 'empty'
         * Urutkan jarak terkecil → terbesar
         */
        
        $recommendedSlots = ParkingSlot::with('area')
            ->when($areaId, fn($q) => $q->where('area_id', $areaId))
            ->when($vehicleTypeId, fn($q) => $q->where('vehicle_type_id', $vehicleTypeId))
            ->where('status', 'empty')
            ->orderByRaw('COALESCE(distance_from_entry, 9999)')
            ->take(10)
            ->get();

        /**
         * ===============================
         * SEMUA SLOT UNTUK DENAH PARKIR
         * ===============================
         * Tidak boleh difilter area/vehicle type di sini
         * Karena denah harus tetap full semua area
         */
        $slots = ParkingSlot::with('area')
            ->orderBy('area_id')
            ->orderBy('slot_code')
            ->get();

        return view('user.recommendations.index', compact(
            'areas',
            'vehicleTypes',
            'areaId',
            'vehicleTypeId',
            'recommendedSlots',
            'slots'
        ));
    }
}
