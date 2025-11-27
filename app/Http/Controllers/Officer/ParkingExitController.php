<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ParkingExitController extends Controller
{
    /**
     * Tampilkan daftar kendaraan yang sedang parkir
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $records = ParkingRecord::query()
            ->where('status', 'in')
            ->when($search, function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('officer.parking_exit.index', compact('records', 'search'));
    }

    /**
     * ✅ PROSES KENDARAAN KELUAR + KIRIM MQTT KE ESP32
     */
    public function processExit(ParkingRecord $record)
    {
        if ($record->status !== 'in') {
            return back()->with('error', 'Kendaraan sudah keluar sebelumnya.');
        }

        DB::beginTransaction();

        try {
            // Ambil slot parkir
            $slot = $record->parking_slot_id
                ? ParkingSlot::with('area')->find($record->parking_slot_id)
                : null;

            // ===========================
            // ✅ UPDATE DATA PARKIR
            // ===========================
            $record->update([
                'exit_time'       => now(),
                'status'          => 'out',
                'payment_status' => 'paid',
            ]);

            // ===========================
            // ✅ KEMBALIKAN SLOT KE EMPTY
            // ===========================
            if ($slot && $slot->status !== 'empty') {
                $slot->update([
                    'status'      => 'empty',
                    'reserved_at' => null,
                ]);
            }

            // ===========================
            // ✅ KIRIM MQTT KE GATE KELUAR
            // ===========================
            try {
                $mqtt = new MqttService();
                $mqtt->publish('parkir/slot/release', [
                    'ticket_code' => $record->ticket_code,
                    'slot_code'   => $slot?->slot_code,
                    'area'        => $slot?->area?->name,
                    'status'      => 'paid',
                    'command'     => 'open_exit_gate',
                    'time'        => now()->toDateTimeString(),
                ]);
            } catch (\Exception $e) {
                Log::error('MQTT EXIT ERROR: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('officer.parking-exit.index')
                ->with('success', 'Pembayaran berhasil, gate keluar terbuka otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EXIT PROCESS ERROR: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memproses kendaraan keluar.');
        }
    }
}
