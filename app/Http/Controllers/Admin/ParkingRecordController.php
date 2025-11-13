<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingRecord;

class ParkingRecordController extends Controller
{
    /**
     * Menampilkan daftar semua data parkir.
     * Bisa difilter berdasarkan status atau tanggal.
     */
    public function index(Request $request)
    {
        $query = ParkingRecord::with(['parkingSlot', 'vehicleType', 'tarif']);

        // Filter opsional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('entry_time', $request->date);
        }

        $records = $query->orderByDesc('entry_time')->paginate(10);

        return view('admin.parking_records.index', compact('records'));
    }
}
