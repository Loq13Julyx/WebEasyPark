<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SensorController extends Controller
{
    /**
     * Tampilkan daftar semua sensor.
     */
    public function index(Request $request)
    {
        $query = Sensor::with('slot.area');

        // Filter: code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->string('search') . '%');
        }

        // Filter: slot
        if ($request->filled('slot_id')) {
            $query->where('slot_id', $request->integer('slot_id'));
        }

        // Filter: type (ultrasonic/infrared)
        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        // Filter: status (active/inactive)
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $sensors = $query->orderBy('code')->paginate(10)->withQueryString();
        $slots   = ParkingSlot::with('area')->orderBy('slot_code')->get();

        return view('admin.sensors.index', compact('sensors', 'slots'));
    }

    /**
     * Form tambah sensor baru.
     */
    public function create()
    {
        // Generate kode sensor berikutnya SEN-01, SEN-02, dst (berdasarkan urutan numeric)
        $last = Sensor::where('code', 'like', 'SEN-%')
            ->orderByRaw("CAST(SUBSTRING(code, 5) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = $last ? (int) preg_replace('/\D/', '', $last->code) + 1 : 1;
        $nextCode   = 'SEN-' . str_pad((string) $nextNumber, 2, '0', STR_PAD_LEFT);

        $slots = ParkingSlot::with('area')->orderBy('slot_code')->get();

        return view('admin.sensors.create', compact('nextCode', 'slots'));
    }

    /**
     * Simpan sensor baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code'         => ['required','string','max:50','unique:sensors,code'],
            // slot_id boleh null, tapi kalau diisi harus valid & unik (satu slot satu sensor)
            'slot_id'      => ['nullable','exists:parking_slots,id','unique:sensors,slot_id'],
            'type'         => ['required', Rule::in(['ultrasonic','infrared'])],
            'status'       => ['required', Rule::in(['active','inactive'])],
            'threshold_cm' => ['nullable','numeric','min:0','max:1000'],
        ], [
            'slot_id.unique' => 'Slot ini sudah memiliki sensor.',
        ]);

        $sensor = Sensor::create([
            'code'            => strtoupper($data['code']),
            'slot_id'         => $data['slot_id'] ?? null,
            'type'            => $data['type'],
            'status'          => $data['status'],
            'threshold_cm'    => $data['threshold_cm'] ?? 30,
            'api_key'         => Str::uuid()->toString(),   // auto generate
            'last_update'     => now(),
            'last_distance_cm'=> null,
            'last_detected'   => false,
        ]);

        return redirect()->route('admin.sensors.index')
            ->with('success', "Sensor {$sensor->code} berhasil ditambahkan.");
    }

    /**
     * Form edit sensor.
     */
    public function edit(Sensor $sensor)
    {
        $slots = ParkingSlot::with('area')->orderBy('slot_code')->get();
        return view('admin.sensors.edit', compact('sensor', 'slots'));
    }

    /**
     * Update sensor.
     */
    public function update(Request $request, Sensor $sensor)
    {
        $data = $request->validate([
            'code'         => ['required','string','max:50','unique:sensors,code,' . $sensor->id],
            // allow null; unique check abaikan baris sensor saat ini
            'slot_id'      => ['nullable','exists:parking_slots,id','unique:sensors,slot_id,' . $sensor->id],
            'type'         => ['required', Rule::in(['ultrasonic','infrared'])],
            'status'       => ['required', Rule::in(['active','inactive'])],
            'threshold_cm' => ['nullable','numeric','min:0','max:1000'],
        ], [
            'slot_id.unique' => 'Slot ini sudah memiliki sensor.',
        ]);

        $sensor->update([
            'code'         => strtoupper($data['code']),
            'slot_id'      => $data['slot_id'] ?? null,
            'type'         => $data['type'],
            'status'       => $data['status'],
            'threshold_cm' => $data['threshold_cm'] ?? $sensor->threshold_cm,
            'last_update'  => now(),
        ]);

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor berhasil diperbarui.');
    }

    /**
     * Hapus sensor.
     */
    public function destroy(Sensor $sensor)
    {
        $code = $sensor->code;
        $sensor->delete();

        return redirect()->route('admin.sensors.index')
            ->with('success', "Sensor {$code} berhasil dihapus.");
    }

    /**
     * Update cepat status sensor.
     */
    public function updateStatus(Request $request, Sensor $sensor)
    {
        $request->validate([
            'status' => ['required', Rule::in(['active','inactive'])],
        ]);

        $sensor->update(['status' => $request->string('status'), 'last_update' => now()]);

        return back()->with('success', 'Status sensor berhasil diperbarui.');
    }

    /**
     * (Opsional) Rotate/generate API key baru untuk sensor.
     * Pastikan route & tombol di UI hanya untuk admin.
     */
    public function regenerateApiKey(Sensor $sensor)
    {
        $sensor->update([
            'api_key'     => Str::uuid()->toString(),
            'last_update' => now(),
        ]);

        return back()->with('success', "API key untuk {$sensor->code} berhasil diperbarui.");
    }
}
