<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingRecord;
use Illuminate\Http\Request;

class ParkingRecordController extends Controller
{
    /**
     * Menampilkan semua data parkir.
     */
    public function index(Request $request)
    {
        $query = ParkingRecord::query();

        // Ambil input pencarian
        $search = $request->input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Urutkan terbaru dulu
        $records = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();

        return view('admin.parking_records.index', compact('records', 'search'));
    }

    /**
     * Halaman detail data parkir (tanpa relasi).
     */
    public function show($id)
    {
        $record = ParkingRecord::findOrFail($id);

        return view('admin.parking_records.show', compact('record'));
    }

    /**
     * Halaman form edit data parkir (tanpa relasi).
     */
    public function edit($id)
    {
        $record = ParkingRecord::findOrFail($id);

        return view('admin.parking_records.edit', compact('record'));
    }

    /**
     * Update data parkir (tanpa relasi).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
            'exit_time'     => 'nullable|date',
        ]);

        $record = ParkingRecord::findOrFail($id);

        // LOGIKA OTOMATIS
        if ($request->payment_status === 'unpaid') {
            $status = 'in'; // belum bayar berarti masih parkir
            $exit_time = null; // otomatis hapus waktu keluar
        } else {
            $status = 'out'; // bayar berarti keluar
            $exit_time = $request->exit_time ?? now();
        }

        $record->update([
            'payment_status' => $request->payment_status,
            'status'         => $status,
            'exit_time'      => $exit_time,
        ]);

        return redirect()
            ->route('admin.parking-records.index')
            ->with('success', 'Data parkir berhasil diperbarui.');
    }

    /**
     * Hapus data parkir.
     */
    public function destroy($id)
    {
        $record = ParkingRecord::findOrFail($id);
        $record->delete();

        return redirect()
            ->route('admin.parking-records.index')
            ->with('success', 'Data parkir berhasil dihapus.');
    }
}
