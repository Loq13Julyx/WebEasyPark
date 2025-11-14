<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingArea;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class ParkingAreaController extends Controller
{
    public function index(Request $request)
    {
        $query = ParkingArea::withCount('slots'); // hitung total slot otomatis
        $search = $request->input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $areas = $query->latest()->paginate(5);

        return view('admin.parking_areas.index', compact('areas', 'search'));
    }

    public function create()
    {
        $vehicleTypes = VehicleType::all(); // ambil daftar tipe kendaraan
        return view('admin.parking_areas.create', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100|unique:parking_areas',
            'location'        => 'nullable|string|max:255',
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
        ]);

        ParkingArea::create([
            'name'            => $request->name,
            'location'        => $request->location,
            'vehicle_type_id' => $request->vehicle_type_id,
        ]);

        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function show($id)
    {
        $area = ParkingArea::with(['slots.sensor', 'vehicleType'])->findOrFail($id);

        $usedSlots = $area->slots->where('status', 'occupied')->count();
        $emptySlots = $area->slots->where('status', 'empty')->count();
        $totalSlots = $area->slots->count();

        $percentageUsed = $totalSlots > 0 ? round(($usedSlots / $totalSlots) * 100, 2) : 0;

        return view('admin.parking_areas.show', compact(
            'area',
            'usedSlots',
            'emptySlots',
            'totalSlots',
            'percentageUsed'
        ));
    }

    public function edit($id)
    {
        $area = ParkingArea::findOrFail($id);
        $vehicleTypes = VehicleType::all(); // untuk select dropdown
        return view('admin.parking_areas.edit', compact('area', 'vehicleTypes'));
    }

    public function update(Request $request, ParkingArea $parking_area)
    {
        $request->validate([
            'name'            => 'required|string|max:100|unique:parking_areas,name,' . $parking_area->id,
            'location'        => 'nullable|string|max:255',
            'vehicle_type_id' => 'nullable|exists:vehicle_types,id',
        ]);

        $parking_area->update([
            'name'            => $request->name,
            'location'        => $request->location,
            'vehicle_type_id' => $request->vehicle_type_id,
        ]);

        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Data area parkir berhasil diperbarui.');
    }

    public function destroy(ParkingArea $parking_area)
    {
        $parking_area->delete();

        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Area parkir berhasil dihapus.');
    }
}
