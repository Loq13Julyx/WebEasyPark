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

        // Ambil input filter
        $search        = $request->input('search');
        $paymentStatus = $request->input('payment_status');
        $status        = $request->input('status');
        $startDate     = $request->input('start_date');
        $endDate       = $request->input('end_date');

        /**
         * FILTER PENCARIAN
         * Bisa cari ticket_code atau plate_number (opsional)
         */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        /**
         * FILTER RANGE TANGGAL MASUK (created_at atau entry_time)
         */
        if ($startDate && $endDate) {
            $query->whereBetween('entry_time', [
                $startDate . " 00:00:00",
                $endDate   . " 23:59:59"
            ]);
        } elseif ($startDate) {
            $query->whereDate('entry_time', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('entry_time', '<=', $endDate);
        }

        /**
         * FILTER STATUS PEMBAYARAN
         */
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        /**
         * FILTER STATUS PARKIR
         */
        if ($status) {
            $query->where('status', $status);
        }

        // Urutkan terbaru
        $records = $query->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('admin.parking_records.index', compact(
            'records',
            'search',
            'paymentStatus',
            'status',
            'startDate',
            'endDate'
        ));
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

    public function print(Request $request)
    {
        $query = ParkingRecord::query();

        // Bisa pakai filter yang sama seperti index
        if ($request->filled('search')) {
            $query->where('ticket_code', 'like', "%{$request->search}%")
                ->orWhere('plate_number', 'like', "%{$request->search}%");
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->orderBy('id', 'DESC')->get();

        return view('admin.parking_records.print', compact('records'));
    }
}
