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
        $areas = ParkingArea::orderBy('name')->get();
        $vehicleTypes = VehicleType::orderBy('name')->get();

        return view('user.recommendations.index', compact(
            'areas',
            'vehicleTypes'
        ));
    }

    public function loadData(Request $request)
    {
        $areaId = $request->area_id;
        $vehicleTypeId = $request->vehicle_type_id;

        // Rekomendasi
        $recommendedSlots = ParkingSlot::with('area')
            ->when($areaId, fn($q) => $q->where('area_id', $areaId))
            ->when($vehicleTypeId, fn($q) => $q->where('vehicle_type_id', $vehicleTypeId))
            ->where('status', 'empty')
            ->orderByRaw('COALESCE(distance_from_entry, 9999)')
            ->take(10)
            ->get();

        // Semua slot untuk denah
        $slots = ParkingSlot::with('area')
            ->orderBy('area_id')
            ->orderBy('slot_code')
            ->get();

        return response()->json([
            'recommended' => $recommendedSlots,
            'slots'       => $slots,
        ]);
    }
}
