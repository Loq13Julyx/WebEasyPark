<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ParkingRecordController extends Controller
{
    /**
     * Menampilkan semua data parkir
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => ParkingRecord::all(),
        ]);
    }

    /**
     * Generate ticket dan create parking record (masuk parkir)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // ----------------------------
            // 1. Generate Ticket Code
            // Format: T-YYYYMMDD-XXXX
            // ----------------------------
            $today = Carbon::now()->format('Ymd');

            // hitung nomor urut hari ini
            $countToday = ParkingRecord::whereDate('created_at', Carbon::today())->count();
            $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);

            $ticketCode = "T-{$today}-{$sequence}";

            // ----------------------------
            // 2. Tarif default mobil = ID 2
            // ----------------------------
            $defaultTarifId = 2;

            // ----------------------------
            // 3. Create record
            // ----------------------------
            $parking = ParkingRecord::create([
                'tarif_id'       => $defaultTarifId,
                'ticket_code'    => $ticketCode,
                'entry_time'     => Carbon::now(),
                'payment_status' => 'unpaid',
                'status'         => 'in',     // status kendaraan masuk
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ticket berhasil dibuat',
                'data' => $parking
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan satu record
     */
    public function show($id)
    {
        $data = ParkingRecord::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
