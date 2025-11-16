<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    public function index()
    {
        $slots = ParkingSlot::with('area')->get();

        $data = $slots->map(function ($slot) {
            $status = $slot->status;

            return [
                'slot_code'     => $slot->slot_code,
                'area'          => $slot->area->name ?? null,
                'status'        => $status,
                'status_label'  => match($status) {
                    'occupied' => 'terisi',
                    'empty'    => 'kosong',
                    'inactive' => 'unknown',
                    default    => 'unknown'
                },
                'color'         => match($status) {
                    'occupied' => 'red',
                    'empty'    => 'green',
                    'inactive' => 'gray',
                    default    => 'gray'
                },
                'distance_from_entry' => $slot->distance_from_entry,
                'last_update'   => $slot->last_update,
            ];
        });

        return response()->json($data);
    }

    public function updateStatus(Request $request, $slotCode)
    {
        $slot = ParkingSlot::where('slot_code', $slotCode)->first();

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:occupied,empty,inactive'
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

    public function updateBulk(Request $request)
    {
        $request->validate([
            'slots' => 'required|array'
        ]);

        // Mapping status dari front-end / kamera
        $map = [
            'kosong'  => 'empty',
            'terisi'  => 'occupied',
            'unknown' => 'inactive',
            'empty'   => 'empty',
            'occupied'=> 'occupied',
            'inactive'=> 'inactive'
        ];

        $updatedSlots = [];

        foreach ($request->slots as $slotCode => $status) {

            $slot = ParkingSlot::where('slot_code', $slotCode)->first();
            if (!$slot) continue;

            $dbStatus = $map[$status] ?? 'inactive';

            $slot->update([
                'status' => $dbStatus,
                'last_update' => now(),
            ]);

            $updatedSlots[$slotCode] = $dbStatus;
        }

        return response()->json([
            'message' => 'All slots updated successfully',
            'updated' => $updatedSlots
        ]);
    }

    // Fungsi khusus update dari kamera
    public function updateFromCamera(Request $request)
    {
        $request->validate([
            'slots' => 'required|array',
        ]);

        $map = [
            'kosong'  => 'empty',
            'terisi'  => 'occupied',
            'unknown' => 'inactive'
        ];

        $updatedSlots = [];

        foreach ($request->slots as $slotCode => $status) {

            $dbStatus = $map[$status] ?? 'inactive';

            ParkingSlot::where('slot_code', $slotCode)
                ->update([
                    'status' => $dbStatus,
                    'last_update' => now(),
                ]);

            $updatedSlots[$slotCode] = $dbStatus;
        }

        return response()->json([
            'message' => 'Slot update success',
            'updated' => $updatedSlots
        ]);
    }
}
