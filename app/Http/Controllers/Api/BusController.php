<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar data bus berhasil diambil',
            'data'    => $buses
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bus' => 'required|string|max:255',
            'kelas' => 'required|string',
            'kapasitas' => 'required|integer',
            'nomor_polisi' => 'required|string',
            'fasilitas' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('buses', 'public');
        }

        $bus = Bus::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data bus berhasil ditambahkan',
            'data'    => $bus
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'success' => false,
                'message' => 'Data bus tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data bus',
            'data'    => $bus
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'success' => false,
                'message' => 'Data bus tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_bus' => 'required|string|max:255',
            'kelas' => 'required|string',
            'kapasitas' => 'required|integer',
            'nomor_polisi' => 'required|string',
            'fasilitas' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('gambar')) {
            if ($bus->gambar) {
                Storage::disk('public')->delete($bus->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('buses', 'public');
        }

        $bus->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data bus berhasil diperbarui',
            'data'    => $bus
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json([
                'success' => false,
                'message' => 'Data bus tidak ditemukan'
            ], 404);
        }

        if ($bus->gambar) {
            Storage::disk('public')->delete($bus->gambar);
        }

        $bus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data bus berhasil dihapus'
        ], 200);
    }
}
