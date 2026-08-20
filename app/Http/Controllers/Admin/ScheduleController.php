<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route'])->latest()->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.schedules.create', compact('buses', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
            'jam_tiba' => 'required',
            'harga' => 'required|numeric|min:0',
        ]);

        $bus = Bus::findOrFail($request->bus_id);
        $validated['kursi_tersedia'] = $bus->kapasitas;

        Schedule::create($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $buses = Bus::all();
        $routes = BusRoute::all();
        return view('admin.schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
            'jam_tiba' => 'required',
            'harga' => 'required|numeric|min:0',
        ]);

        $bus = Bus::findOrFail($request->bus_id);
        
        // Hitung kursi yang sudah dipesan
        $bookedSeatsCount = \App\Models\Booking::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->flatMap(function($booking) {
                $seats = is_string($booking->nomor_kursi) ? json_decode($booking->nomor_kursi, true) ?? explode(',', $booking->nomor_kursi) : $booking->nomor_kursi;
                return is_array($seats) ? $seats : [];
            })
            ->count();

        $validated['kursi_tersedia'] = max(0, $bus->kapasitas - $bookedSeatsCount);

        $schedule->update($validated);
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
