<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index(Request $request)
    {
        $query = Gate::query();
        $search = $request->input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $gates = $query->latest()->paginate(5);

        return view('admin.gates.index', compact('gates', 'search'));
    }

    public function create()
    {
        return view('admin.gates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100|unique:gates,name',
            'location' => 'nullable|string|max:150',
            'status'   => 'required|in:open,closed',
        ]);

        Gate::create([
            'name'     => $request->name,
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.gates.index')
            ->with('success', 'Gate berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $gate = Gate::findOrFail($id);
        return view('admin.gates.edit', compact('gate'));
    }

    public function update(Request $request, Gate $gate)
    {
        $request->validate([
            'name'     => 'required|string|max:100|unique:gates,name,' . $gate->id,
            'location' => 'nullable|string|max:150',
            'status'   => 'required|in:open,closed',
        ]);

        $gate->update([
            'name'     => $request->name,
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.gates.index')
            ->with('success', 'Data gate berhasil diperbarui.');
    }

    public function destroy(Gate $gate)
    {
        $gate->delete();

        return redirect()->route('admin.gates.index')
            ->with('success', 'Gate berhasil dihapus.');
    }
}
