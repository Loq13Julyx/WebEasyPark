<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\ParkingRecord;
use Illuminate\Http\Request;

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
            ->when($search, fn($q) => 
                $q->where('ticket_code', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10);

        return view('officer.parking_exit.index', compact('records', 'search'));
    }

    /**
     * Proses kendaraan keluar
     */
    public function processExit(ParkingRecord $record)
    {
        if ($record->status !== 'in') {
            return back()->with('error', 'Kendaraan sudah keluar sebelumnya.');
        }

        // Update exit_time dan status
        $record->update([
            'exit_time'      => now(),
            'status'         => 'out',
            'payment_status' => 'paid', // opsional
        ]);

        return redirect()
            ->route('officer.parking-exit.index')
            ->with('success', 'Kendaraan berhasil keluar.');
    }
}
