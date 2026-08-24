<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::latest()->get();
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bus' => 'required|string|max:255',
            'nomor_polisi' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:1',
            'kelas' => 'required|in:Ekonomi,Bisnis,Executive',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('buses', 'public');
        }

        Bus::create($validated);
        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil ditambahkan.');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'nama_bus' => 'required|string|max:255',
            'nomor_polisi' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:1',
            'kelas' => 'required|in:Ekonomi,Bisnis,Executive',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($bus->gambar) {
                Storage::disk('public')->delete($bus->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('buses', 'public');
        }

        $bus->update($validated);
        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil diperbarui.');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->gambar) {
            Storage::disk('public')->delete($bus->gambar);
        }
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil dihapus.');
    }
}
