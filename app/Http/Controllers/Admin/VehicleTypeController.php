<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Tampilkan daftar tipe kendaraan
     */
    public function index(Request $request)
    {
        $query = VehicleType::query();
        $search = $request->input('search');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $vehicleTypes = $query->latest()->paginate(5);

        return view('admin.vehicle_types.index', compact('vehicleTypes', 'search'));
    }

    /**
     * Tampilkan form tambah data
     */
    public function create()
    {
        return view('admin.vehicle_types.create');
    }

    /**
     * Simpan data baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:vehicle_types,name',
        ]);

        VehicleType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data
     */
    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('admin.vehicle_types.edit', compact('vehicleType'));
    }

    /**
     * Update data tipe kendaraan
     */
    public function update(Request $request, VehicleType $vehicle_type)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:vehicle_types,name,' . $vehicle_type->id,
        ]);

        $vehicle_type->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus data tipe kendaraan
     */
    public function destroy(VehicleType $vehicle_type)
    {
        $vehicle_type->delete();

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Tipe kendaraan berhasil dihapus.');
    }
}
