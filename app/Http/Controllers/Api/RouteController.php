<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = BusRoute::latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar data rute berhasil diambil',
            'data'    => $routes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'jarak' => 'required|numeric',
            'estimasi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $route = BusRoute::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data rute berhasil ditambahkan',
            'data'    => $route
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $route = BusRoute::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Data rute tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data rute',
            'data'    => $route
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $route = BusRoute::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Data rute tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'jarak' => 'required|numeric',
            'estimasi' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $route->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data rute berhasil diperbarui',
            'data'    => $route
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $route = BusRoute::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Data rute tidak ditemukan'
            ], 404);
        }

        $route->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data rute berhasil dihapus'
        ], 200);
    }
}
