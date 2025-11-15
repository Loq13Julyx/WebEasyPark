<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    /**
     * Menampilkan semua slot parkir (mirip foto)
     */
    public function index()
    {
        $slots = ParkingSlot::with('area')->get();

        // Format respons seperti tampilan deteksi kamera
        $data = $slots->map(function ($slot) {

            // Mapping warna + label
            $status = $slot->status; // "occupied" / "empty"

            return [
                'slot_code'     => $slot->slot_code,
                'area'          => $slot->area->name ?? null,
                'status'        => $status,
                'status_label'  => $status === 'occupied' ? 'terisi' : 'kosong',
                'color'         => $status === 'occupied' ? 'red' : 'green',
                'distance_from_entry' => $slot->distance_from_entry,
                'last_update'   => $slot->last_update,
            ];
        });

        return response()->json($data);
    }

    /**
     * Update status slot (dipakai kamera prototyping)
     */
    public function updateStatus(Request $request, $slotCode)
    {
        $slot = ParkingSlot::where('slot_code', $slotCode)->first();

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:occupied,empty'
        ]);

        $slot->update([
            'status' => $request->status,
            'last_update' => now(),
        ]);

        return response()->json([
            'message' => 'Status updated',
            'slot' => $slot
        ]);
    }
}
