<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ParkingSlotController extends Controller
{
    public function index()
    {
        // Cache hasil selama 2 detik untuk mengurangi query
        return Cache::remember('parking_slots_status', 2, function () {
            // Auto-expire reserved slots yang lewat 3 menit
            ParkingSlot::where('status', 'reserved')
                ->where('last_update', '<', now()->subMinutes(3))
                ->update([
                    'status' => 'empty',
                    'last_update' => now()
                ]);

            $slots = ParkingSlot::select('slot_code', 'status', 'last_update', 'distance_from_entry')
                ->get();

            return $slots->map(function ($slot) {
                $status = $slot->status;

                return [
                    'slot_code'     => $slot->slot_code,
                    'status'        => $status,
                    'status_label'  => match($status) {
                        'occupied' => 'terisi',
                        'empty'    => 'kosong',
                        'reserved' => 'reserved',
                        default    => 'kosong'
                    },
                    'color'         => match($status) {
                        'occupied' => 'red',
                        'empty'    => 'green',
                        'reserved' => 'yellow',
                        default    => 'green'
                    },
                    'last_update'   => $slot->last_update,
                ];
            })->toArray();
        });
    }

    public function updateStatus(Request $request, $slotCode)
    {
        $slot = ParkingSlot::where('slot_code', $slotCode)->first();

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:occupied,empty,reserved'
        ]);

        // PERUBAHAN: Reserved langsung bisa jadi occupied tanpa cek timeout
        if ($slot->status === 'reserved' && $request->status === 'occupied') {
            // Langsung update ke occupied (mobil sudah parkir)
            $slot->update([
                'status' => 'occupied',
                'last_update' => now(),
            ]);
        }
        // Cek timeout hanya untuk status selain occupied
        elseif ($slot->status === 'reserved' && $request->status !== 'occupied') {
            $last = Carbon::parse($slot->last_update);
            if ($last->diffInMinutes(now()) < 3) {
                return response()->json(['message' => 'Slot is reserved'], 400);
            }
            
            $slot->update([
                'status' => $request->status,
                'last_update' => now(),
            ]);
        }
        else {
            $slot->update([
                'status' => $request->status,
                'last_update' => now(),
            ]);
        }

        Cache::forget('parking_slots_status');

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

        $map = [
            'kosong'  => 'empty',
            'terisi'  => 'occupied',
            'unknown' => 'empty',
            'empty'   => 'empty',
            'occupied'=> 'occupied',
            'reserved'=> 'reserved'
        ];

        $updatedSlots = [];
        $now = now();
        $stats = ['kosong' => 0, 'terisi' => 0, 'reserved' => 0];

        foreach ($request->slots as $slotCode => $status) {
            $dbStatus = $map[$status] ?? 'empty';

            $slot = ParkingSlot::where('slot_code', $slotCode)->first();
            if (!$slot) continue;

            // PERUBAHAN: Reserved langsung jadi occupied jika terdeteksi terisi
            if ($slot->status === 'reserved') {
                if ($dbStatus === 'occupied') {
                    // Langsung override: reserved -> occupied (mobil datang)
                    $dbStatus = 'occupied';
                } elseif ($dbStatus === 'empty') {
                    // Cek timeout untuk empty
                    $last = Carbon::parse($slot->last_update);
                    if ($last->diffInMinutes($now) < 3) {
                        $dbStatus = 'reserved'; // Masih reserved
                    } else {
                        $dbStatus = 'empty'; // Timeout, jadi empty
                    }
                }
            }

            $slot->update([
                'status' => $dbStatus,
                'last_update' => $now,
            ]);

            $updatedSlots[$slotCode] = $dbStatus;

            // Count stats
            if ($dbStatus === 'empty') $stats['kosong']++;
            if ($dbStatus === 'occupied') $stats['terisi']++;
            if ($dbStatus === 'reserved') $stats['reserved']++;
        }

        Cache::forget('parking_slots_status');

        return response()->json([
            'success' => true,
            'message' => 'Slots updated',
            'updated' => $updatedSlots,
            'stats' => $stats,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    public function updateFromCamera(Request $request)
    {
        $request->validate([
            'slots' => 'required|array',
        ]);

        $map = [
            'kosong'  => 'empty',
            'terisi'  => 'occupied',
            'reserved'=> 'reserved',
            'unknown' => 'empty'
        ];

        $updatedSlots = [];
        $now = now();

        foreach ($request->slots as $slotCode => $status) {
            $dbStatus = $map[$status] ?? 'empty';
            
            $slot = ParkingSlot::where('slot_code', $slotCode)->first();
            if (!$slot) continue;

            // PERUBAHAN: Reserved langsung jadi occupied jika kamera deteksi terisi
            if ($slot->status === 'reserved' && $dbStatus === 'occupied') {
                // Langsung update ke occupied tanpa cek timeout
                $slot->update([
                    'status' => 'occupied',
                    'last_update' => $now
                ]);
            } else {
                $slot->update([
                    'status' => $dbStatus,
                    'last_update' => $now
                ]);
            }
            
            $updatedSlots[$slotCode] = $slot->status;
        }

        Cache::forget('parking_slots_status');

        return response()->json([
            'message' => 'Slot update success',
            'updated' => $updatedSlots
        ]);
    }

    public function reserveSlot(Request $request, $slotCode)
    {
        $slot = ParkingSlot::where('slot_code', $slotCode)->first();

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        if ($slot->status !== 'empty') {
            return response()->json([
                'message' => 'Slot tidak tersedia',
                'current_status' => $slot->status
            ], 400);
        }

        $slot->update([
            'status' => 'reserved',
            'last_update' => now()
        ]);

        Cache::forget('parking_slots_status');

        return response()->json([
            'message' => 'Slot berhasil dipesan untuk 3 menit',
            'slot' => $slot,
            'expires_at' => now()->addMinutes(3)->toIso8601String()
        ]);
    }

    public function cancelReservation(Request $request, $slotCode)
    {
        $slot = ParkingSlot::where('slot_code', $slotCode)
            ->where('status', 'reserved')
            ->first();

        if (!$slot) {
            return response()->json(['message' => 'No active reservation'], 404);
        }

        $slot->update([
            'status' => 'empty',
            'last_update' => now()
        ]);

        Cache::forget('parking_slots_status');

        return response()->json([
            'message' => 'Reservation cancelled',
            'slot' => $slot
        ]);
    }
}