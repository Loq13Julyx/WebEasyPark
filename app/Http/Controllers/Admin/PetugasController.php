<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    /**
     * Tampilkan daftar petugas.
     */
    public function index(Request $request)
    {
        $query = User::with(['petugas', 'role'])
            ->whereHas('role', fn($q) => $q->where('name', 'petugas'));

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhereHas('petugas', fn($p) => $p->where('nip', 'like', "%$search%"));
            });
        }

        if ($shift = $request->shift) {
            $query->whereHas('petugas', fn($p) => $p->where('shift', $shift));
        }

        if ($status = $request->status) {
            $query->whereHas('petugas', fn($p) => $p->where('status', $status));
        }

        $users = $query->latest()->paginate(perPage: 10);

        return view('admin.petugas.index', compact('users'));
    }

    /**
     * Form tambah petugas baru.
     */
    public function create()
    {
        return view('admin.petugas.create');
    }

    /**
     * Simpan petugas baru (user + detail petugas).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'nip'       => 'required|string|size:18|unique:petugas',
            'phone'     => 'nullable|string|max:15',
            'gender'    => 'nullable|in:L,P',
            'address'   => 'nullable|string',
            'shift'     => 'required|in:pagi,siang,malam',
            'status'    => 'required|in:aktif,nonaktif',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            // Upload foto jika ada
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('uploads/petugas', 'public');
            }

            // Buat akun user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'petugas',
            ]);

            // Buat data detail petugas
            Petugas::create([
                'user_id' => $user->id,
                'nip'     => $request->nip,
                'phone'   => $request->phone,
                'gender'  => $request->gender,
                'address' => $request->address,
                'shift'   => $request->shift,
                'status'  => $request->status,
                'photo'   => $photoPath,
            ]);
        });

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail petugas.
     */
    public function show($id)
    {
        // Menampilkan detail petugas beserta data relasi
        $user = User::with('petugas')->findOrFail($id);
        return view('admin.petugas.show', compact('user'));
    }

    /**
     * Form edit data petugas.
     */
    public function edit($id)
    {
        // Ambil user dengan relasi petugas (biar sama seperti show)
        $user = User::with('petugas')->findOrFail($id);

        return view('admin.petugas.edit', compact('user'));
    }

    /**
     * Update data petugas.
     */
    public function update(Request $request, User $petuga)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,' . $petuga->id,
            'nip'    => 'required|string|size:18|unique:petugas,nip,' . $petuga->petugas->id,
            'phone'  => 'nullable|string|max:15',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string',
            'shift'  => 'required|in:pagi,siang,malam',
            'status' => 'required|in:aktif,nonaktif',
            'photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, $petuga) {
            $photoPath = $petuga->petugas->photo;

            // Jika ada foto baru
            if ($request->hasFile('photo')) {
                // hapus foto lama
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                // upload baru
                $photoPath = $request->file('photo')->store('uploads/petugas', 'public');
            }

            // Update user
            $petuga->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Update detail petugas
            $petuga->petugas->update([
                'nip'    => $request->nip,
                'phone'  => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'shift'  => $request->shift,
                'status' => $request->status,
                'photo'  => $photoPath,
            ]);
        });

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Hapus petugas.
     */
    public function destroy(User $petuga)
    {
        if ($petuga->petugas && $petuga->petugas->photo) {
            $photoPath = $petuga->petugas->photo;
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }

        $petuga->delete(); // cascade delete ke tabel petugas
        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}
