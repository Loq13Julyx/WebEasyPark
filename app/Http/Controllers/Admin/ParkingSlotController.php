<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    /**
     * Tampilkan daftar slot parkir dengan filter area & pencarian.
     */
    public function index(Request $request)
    {
        $areas = ParkingArea::orderBy('name')->get();

        $query = ParkingSlot::with('area');

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('search')) {
            $query->where('slot_code', 'like', '%' . $request->search . '%');
        }

        $slots = $query->orderBy('slot_code')->paginate(10);

        return view('admin.parking_slots.index', compact('slots', 'areas'));
    }

    /**
     * Form tambah slot parkir baru.
     */
    public function create()
    {
        $areas = ParkingArea::orderBy('name')->get();
        return view('admin.parking_slots.create', compact('areas'));
    }

    /**
     * Simpan slot parkir baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id'             => 'required|exists:parking_areas,id',
            'slot_code'           => 'required|string|max:10|unique:parking_slots,slot_code',
            'status'              => 'required|in:empty,occupied,inactive',
            'distance_from_entry' => 'nullable|numeric|min:0',
            'route_direction'     => 'nullable|string|max:500',
        ]);

        ParkingSlot::create([
            'area_id'             => $request->area_id,
            'slot_code'           => strtoupper($request->slot_code),
            'status'              => $request->status,
            'distance_from_entry' => $request->distance_from_entry,
            'route_direction'     => $request->route_direction,
        ]);

        return redirect()->route('admin.parking-slots.index')
            ->with('success', 'Slot parkir berhasil ditambahkan.');
    }

    /**
     * Form edit slot parkir.
     */
    public function edit(ParkingSlot $parking_slot)
    {
        $areas = ParkingArea::orderBy('name')->get();
        return view('admin.parking_slots.edit', compact('parking_slot', 'areas'));
    }

    /**
     * Update slot parkir.
     */
    public function update(Request $request, ParkingSlot $parking_slot)
    {
        $request->validate([
            'area_id'             => 'required|exists:parking_areas,id',
            'slot_code'           => 'required|string|max:10|unique:parking_slots,slot_code,' . $parking_slot->id,
            'status'              => 'required|in:empty,occupied,inactive',
            'distance_from_entry' => 'nullable|numeric|min:0',
            'route_direction'     => 'nullable|string|max:500',
        ]);

        $parking_slot->update([
            'area_id'             => $request->area_id,
            'slot_code'           => strtoupper($request->slot_code),
            'status'              => $request->status,
            'distance_from_entry' => $request->distance_from_entry,
            'route_direction'     => $request->route_direction,
        ]);

        return redirect()->route('admin.parking-slots.index')
            ->with('success', 'Data slot parkir berhasil diperbarui.');
    }

    /**
     * Hapus slot parkir.
     */
    public function destroy(ParkingSlot $parking_slot)
    {
        $parking_slot->delete();

        return redirect()->route('admin.parking-slots.index')
            ->with('success', 'Slot parkir berhasil dihapus.');
    }

    /**
     * Update status slot (quick update).
     */
    public function updateStatus(Request $request, ParkingSlot $parking_slot)
    {
        $request->validate([
            'status' => 'required|in:empty,occupied,inactive',
        ]);

        $parking_slot->update(['status' => $request->status]);

        return back()->with('success', 'Status slot berhasil diperbarui.');
    }
}
