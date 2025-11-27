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
        // ✅ TAMBAH RELASI parkingSlot
        $query = ParkingRecord::with(['tarif', 'parkingSlot']);

        // Ambil input filter
        $search        = $request->input('search');
        $paymentStatus = $request->input('payment_status');
        $status        = $request->input('status');
        $startDate     = $request->input('start_date');
        $endDate       = $request->input('end_date');

        /**
         * Filter pencarian
         */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%");
            });
        }

        /**
         * Filter tanggal masuk
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
         * Filter status pembayaran
         */
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        /**
         * Filter status (in/out)
         */
        if ($status) {
            $query->where('status', $status);
        }

        // Pagination
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
     * Halaman detail data parkir.
     */
    public function show($id)
    {
        // ✅ TAMBAH RELASI parkingSlot
        $record = ParkingRecord::with(['tarif', 'parkingSlot'])
            ->findOrFail($id);

        return view('admin.parking_records.show', compact('record'));
    }

    /**
     * Print data parkir berdasarkan filter.
     */
    public function print(Request $request)
    {
        // ✅ TAMBAH RELASI parkingSlot
        $query = ParkingRecord::with(['tarif', 'parkingSlot']);

        // Filter tanggal masuk
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_time', [
                $request->start_date . " 00:00:00",
                $request->end_date   . " 23:59:59"
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('entry_time', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('entry_time', '<=', $request->end_date);
        }

        // Filter status parkir
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $records = $query->orderBy('entry_time', 'DESC')->get();

        // Data untuk info filter di print
        $filters = [
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ];

        return view('admin.parking_records.print', compact('records', 'filters'));
    }
}