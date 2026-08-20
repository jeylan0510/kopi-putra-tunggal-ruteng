<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouteController extends Controller
{
    public function index()
    {
        $routes = BusRoute::latest()->get();
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'jarak' => 'required|numeric|min:0.1',
            'estimasi' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('routes', 'public');
        }

        BusRoute::create($validated);
        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil ditambahkan.');
    }

    public function edit(BusRoute $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, BusRoute $route)
    {
        $validated = $request->validate([
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'jarak' => 'required|numeric|min:0.1',
            'estimasi' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($route->gambar) {
                Storage::disk('public')->delete($route->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('routes', 'public');
        }

        $route->update($validated);
        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroy(BusRoute $route)
    {
        if ($route->gambar) {
            Storage::disk('public')->delete($route->gambar);
        }
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
