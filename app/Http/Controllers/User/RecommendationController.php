<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Menampilkan halaman rekomendasi + layout slot parkir
     */
    public function index(Request $request)
    {
        $areaId = $request->get('area_id');
        $vehicleTypeId = $request->get('vehicle_type_id'); // masih dipakai untuk filter area
        

        /**
         * ====== DATA FILTER ======
         */
        $areas = ParkingArea::where('status', 'active')->get();
        $vehicleTypes = VehicleType::all();


        /**
         * ====== REKOMENDASI SLOT (Paling dekat gerbang) ======
         */
        $query = ParkingSlot::with('area')
            ->whereHas('area', function ($q) use ($areaId, $vehicleTypeId) {
                $q->where('status', 'active');

                // Filter area
                if ($areaId) {
                    $q->where('id', $areaId);
                }

                // Jika area punya type kendaraan tertentu
                if ($vehicleTypeId) {
                    $q->where('vehicle_type_id', $vehicleTypeId);
                }
            })
            ->where('status', 'empty')
            ->orderByRaw('COALESCE(distance_from_entry, 9999) ASC');

        $recommendedSlots = $query->take(10)->get();


        /**
         * ====== DATA SLOT UNTUK DITAMPILKAN PADA DENAH ======
         * Tanpa filter → biar denah lengkap
         */
        $slots = ParkingSlot::with('area')
            ->orderBy('area_id')
            ->orderBy('slot_code')
            ->get();


        /**
         * ========= KIRIM KE VIEW =========
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
