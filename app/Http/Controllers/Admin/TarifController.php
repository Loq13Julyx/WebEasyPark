<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    /**
     * Tampilkan daftar semua tarif parkir.
     */
    public function index(Request $request)
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();

        $query = Tarif::with('vehicleType');

        // Filter berdasarkan jenis kendaraan
        if ($request->filled('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        // Filter berdasarkan nominal tarif
        if ($request->filled('search')) {
            $query->where('rate', 'like', '%' . $request->search . '%');
        }

        $tarifs = $query->orderBy('vehicle_type_id')->paginate(10);

        return view('admin.tarifs.index', compact('tarifs', 'vehicleTypes'));
    }

    /**
     * Form tambah tarif baru.
     */
    public function create()
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        return view('admin.tarifs.create', compact('vehicleTypes'));
    }

    /**
     * Simpan tarif baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id|unique:tarifs,vehicle_type_id',
            'rate'            => 'required|numeric|min:0',
        ]);

        Tarif::create([
            'vehicle_type_id' => $request->vehicle_type_id,
            'rate'            => $request->rate,
        ]);

        return redirect()->route('admin.tarifs.index')
            ->with('success', 'Tarif parkir berhasil ditambahkan.');
    }

    /**
     * Form edit tarif.
     */
    public function edit(Tarif $tarif)
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        return view('admin.tarifs.edit', compact('tarif', 'vehicleTypes'));
    }

    /**
     * Update tarif.
     */
    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id|unique:tarifs,vehicle_type_id,' . $tarif->id,
            'rate'            => 'required|numeric|min:0',
        ]);

        $tarif->update([
            'vehicle_type_id' => $request->vehicle_type_id,
            'rate'            => $request->rate,
        ]);

        return redirect()->route('admin.tarifs.index')
            ->with('success', 'Data tarif parkir berhasil diperbarui.');
    }

    /**
     * Hapus tarif.
     */
    public function destroy(Tarif $tarif)
    {
        $tarif->delete();

        return redirect()->route('admin.tarifs.index')
            ->with('success', 'Tarif parkir berhasil dihapus.');
    }
}
