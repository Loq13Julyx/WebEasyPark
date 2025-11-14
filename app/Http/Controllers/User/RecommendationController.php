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
     * Tampilkan rekomendasi slot parkir
     */
    public function index(Request $request)
    {
        $areaId = $request->get('area_id');
        $vehicleTypeId = $request->get('vehicle_type_id');

        $areas = ParkingArea::where('status', 'active')->get();
        $vehicleTypes = VehicleType::all();

        $query = ParkingSlot::with(['area'])
            ->whereHas('area', function ($q) use ($areaId, $vehicleTypeId) {
                $q->where('status', 'active');

                if ($areaId) {
                    $q->where('id', $areaId);
                }

                if ($vehicleTypeId) {
                    $q->where('vehicle_type_id', $vehicleTypeId);
                }
            })
            ->where('status', 'empty')               
            ->orderByRaw('COALESCE(distance_from_entry, 9999) ASC');

        $recommendedSlots = $query->take(10)->get();

        return view('user.recommendations.index', compact(
            'recommendedSlots',
            'areas',
            'vehicleTypes',
            'areaId',
            'vehicleTypeId'
        ));
    }
}
