<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = User::where('role_id', 2)->get();

        return view('admin.officers.index', compact('officers'));
    }

    public function create()
    {
        return view('admin.officers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // officer
        ]);

        return redirect()->route('admin.officers.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $officer = User::where('role_id', 2)->findOrFail($id);

        return view('admin.officers.edit', compact('officer'));
    }

    public function update(Request $request, $id)
    {
        $officer = User::where('role_id', 2)->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $officer->id,
        ]);

        $officer->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.officers.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $officer = User::where('role_id', 2)->findOrFail($id);
        $officer->delete();

        return redirect()->route('admin.officers.index')
            ->with('success', 'Petugas berhasil dihapus.');
    }
}
