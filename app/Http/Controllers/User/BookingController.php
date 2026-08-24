<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create($schedule)
    {
        $schedule = \App\Models\Schedule::with(['route', 'bus'])->findOrFail($schedule);
        
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

        return view('user.booking', compact('schedule', 'bookedSeats'));
    }

    public function store(Request $request, $schedule)
    {
        $request->validate([
            'nomor_kursi' => 'required|array',
            'nama_penumpang' => 'required|string|max:255',
            'nik' => 'required|numeric',
            'nomor_hp' => 'required|string|max:20',
        ]);

        $scheduleModel = \App\Models\Schedule::findOrFail($schedule);

        $booking = \App\Models\Booking::create([
            'user_id' => auth()->id(),
            'schedule_id' => $scheduleModel->id,
            'nama_penumpang' => $request->nama_penumpang,
            'nik' => $request->nik,
            'nomor_hp' => $request->nomor_hp,
            'nomor_kursi' => json_encode($request->nomor_kursi),
            'status' => 'menunggu',
        ]);

        \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'status' => 'menunggu',
        ]);

        // Kurangi kursi tersedia
        $scheduleModel->decrement('kursi_tersedia', count($request->nomor_kursi));

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil dipesan. Selesaikan pembayaran Anda.');
    }
    
    public function uploadPayment(Request $request, $booking)
    {
        // Mock payment
        return redirect()->route('user.dashboard')->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }

    public function ticket($booking)
    {
        return view('user.ticket', compact('booking'));
    }
}
