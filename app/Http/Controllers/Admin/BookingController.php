<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return view('admin.bookings.index');
    }

    public function create(Request $request)
    {
        $scheduleId = $request->query('schedule_id');
        
        if (!$scheduleId) {
            $query = \App\Models\Schedule::with(['bus', 'route'])
                ->where('tanggal', '>=', date('Y-m-d'));

            if ($request->filled('route_id')) {
                $query->where('route_id', $request->route_id);
            }

            $schedules = $query->orderBy('tanggal', 'asc')->get();
            $routes = \App\Models\Route::all();
            
            return view('admin.bookings.create', compact('schedules', 'routes'));
        }

        $schedule = \App\Models\Schedule::with(['bus', 'route'])->findOrFail($scheduleId);
        
        $bookedSeats = \App\Models\Booking::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('nomor_kursi')
            ->map(function($seats) {
                if (is_string($seats)) {
                    $decoded = json_decode($seats, true);
                    return is_array($decoded) ? $decoded : explode(',', $seats);
                }
                return is_array($seats) ? $seats : [];
            })
            ->flatten()
            ->toArray();

        return view('admin.bookings.create', compact('schedule', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'nomor_kursi' => 'required|array',
            'nama_penumpang' => 'required|string|max:255',
            'nik' => 'required|numeric',
            'nomor_hp' => 'required|string|max:20',
        ]);

        $scheduleModel = \App\Models\Schedule::findOrFail($request->schedule_id);

        $booking = \App\Models\Booking::create([
            'user_id' => auth()->id(), // Admin is the one booking
            'schedule_id' => $scheduleModel->id,
            'nama_penumpang' => $request->nama_penumpang,
            'nik' => $request->nik,
            'nomor_hp' => $request->nomor_hp,
            'nomor_kursi' => json_encode($request->nomor_kursi),
            'status' => 'berhasil', // Instant success for offline booking
        ]);

        \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'status' => 'berhasil', // Instant success for offline booking
        ]);

        // Kurangi kursi tersedia
        $scheduleModel->decrement('kursi_tersedia', count($request->nomor_kursi));

        return redirect()->route('admin.bookings.index')->with('success', 'Pemesanan tiket offline berhasil dibuat!');
    }
}
