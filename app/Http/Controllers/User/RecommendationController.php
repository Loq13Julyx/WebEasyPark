<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;
use App\Models\VehicleType;
use App\Models\ParkingRecord;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $areas = ParkingArea::orderBy('name')->get();
        $vehicleTypes = VehicleType::orderBy('name')->get();
        return view('user.recommendations.index', compact('areas', 'vehicleTypes'));
    }

    public function loadData()
    {
        $slots = ParkingSlot::with(['area.vehicleType.tarifs'])
            ->orderBy('area_id')
            ->orderBy('slot_code')
            ->get()
            ->map(function ($slot) {
                // Ambil tarif dari vehicle type
                $tarif = $slot->area->vehicleType->tarifs->first();

                return [
                    'id' => $slot->id,
                    'slot_code' => $slot->slot_code,
                    'status' => $slot->status, // empty, reserved, occupied
                    'distance_from_entry' => (int) $slot->distance_from_entry,
                    'last_update' => $slot->last_update,
                    'area_id' => $slot->area_id,
                    'area_name' => $slot->area->name ?? '-',
                    'area_location' => $slot->area->location ?? '-',
                    'vehicle_type' => $slot->area->vehicleType->name ?? '-',
                    'vehicle_type_id' => $slot->area->vehicle_type_id ?? null,
                    'tarif_rate' => $tarif->rate ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }

    public function selectSlot(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:parking_slots,id',
            'tarif_id' => 'required|exists:tarifs,id'
        ]);

        DB::beginTransaction();

        try {
            $slot = ParkingSlot::with('area')->findOrFail($request->slot_id);

            // Validasi: hanya slot kosong yang bisa dipilih
            if ($slot->status !== 'empty') {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot sudah terisi atau terpesan!'
                ], 400);
            }

            $tarif = Tarif::findOrFail($request->tarif_id);

            // Validasi: tarif harus sesuai dengan vehicle type area
            if ($slot->area->vehicle_type_id && $tarif->vehicle_type_id !== $slot->area->vehicle_type_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarif tidak sesuai dengan tipe kendaraan area parkir!'
                ], 400);
            }

            // Generate ticket code
            $ticketCode = 'TKT-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));

            // Buat parking record
            ParkingRecord::create([
                'tarif_id'         => $request->tarif_id,
                'parking_slot_id'  => $slot->id, // SIMPAN SLOT ID
                'ticket_code'      => $ticketCode,
                'entry_time'       => now(),
                'payment_status'   => 'unpaid',
                'status'           => 'in'
            ]);

            // Update status slot menjadi RESERVED
            $slot->update(['status' => 'reserved']);

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Slot berhasil dipesan!',
                'ticket_code'   => $ticketCode,
                'slot_code'     => $slot->slot_code,
                'area_name'     => $slot->area->name,
                'area_location' => $slot->area->location,
                'vehicle_type'  => $slot->area->vehicleType->name,
                'tarif_rate'    => $tarif->rate,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Select Slot Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }
}