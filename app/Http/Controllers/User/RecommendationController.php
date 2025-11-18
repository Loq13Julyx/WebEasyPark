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
        $areaId = $request->get('area_id'); // filter area
        $vehicleTypeId = $request->get('vehicle_type_id'); // filter jenis kendaraan

        // Ambil daftar area & jenis kendaraan untuk filter dropdown
        $areas = ParkingArea::orderBy('name')->get();
        $vehicleTypes = VehicleType::orderBy('name')->get();

        /**
         * ====== QUERY REKOMENDASI SLOT ======
         * Filter area dan tipe kendaraan (jika ada)
         * Hanya ambil slot yang kosong
         * Urutkan berdasarkan jarak dari entry point (distance_from_entry)
         */
        $query = ParkingSlot::with('area')
            ->when($areaId, function ($q) use ($areaId) {
                $q->where('area_id', $areaId);
            })
            ->when($vehicleTypeId, function ($q) use ($vehicleTypeId) {
                $q->where('vehicle_type_id', $vehicleTypeId);
            })
            ->where('status', 'empty')
            ->orderByRaw('COALESCE(distance_from_entry, 9999) ASC'); // jarak terkecil dulu

        $recommendedSlots = $query->take(10)->get(); // ambil 10 terdekat

        /**
         * ====== DATA SLOT UNTUK DENAH PARKIR ======
         * Tanpa filter → biar denah lengkap
         */
        $slots = ParkingSlot::with('area')
            ->orderBy('area_id')
            ->orderBy('slot_code')
            ->get();

        /**
         * ====== KIRIM KE VIEW ======
         */
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
