<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;
use App\Models\VehicleType;
use App\Models\ParkingRecord;
use App\Models\Tarif;
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

        $query = ParkingSlot::with(['area.vehicleType'])
            ->whereHas('area', function ($q) use ($areaId, $vehicleTypeId) {
                $q->where('status', 'active');
                if ($areaId) $q->where('id', $areaId);
                if ($vehicleTypeId) $q->where('vehicle_type_id', $vehicleTypeId);
            })
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

    /**
     * Pilih slot parkir dan redirect ke tiket
     */
    public function selectSlot(Request $request, $id)
    {
        $slot = ParkingSlot::findOrFail($id);

        if ($slot->status !== 'empty') {
            return back()->with('error', 'Slot sudah terisi, silakan pilih slot lain.');
        }

        // Tandai slot sudah dipilih
        $slot->status = 'occupied';
        $slot->save();

        // Ambil tarif sesuai tipe kendaraan
        $tarif = Tarif::where('vehicle_type_id', $slot->area->vehicle_type_id)->first();

        // Catat transaksi parkir tanpa total_payment
        $record = ParkingRecord::create([
            'parking_slot_id' => $slot->id,
            'vehicle_type_id' => $slot->area->vehicle_type_id,
            'tarif_id' => $tarif ? $tarif->id : null,
            'ticket_code' => 'TCK-' . strtoupper(uniqid()),
            'entry_time' => now(),
            'status' => 'in',
            'payment_status' => 'unpaid',
        ]);

        // Redirect ke halaman tiket
        return redirect()->route('user.parking-ticket.show', $record->id)
            ->with('success', 'Slot berhasil dipilih! Berikut tiket parkir Anda.');
    }
}
