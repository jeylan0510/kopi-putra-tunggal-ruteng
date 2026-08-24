<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with(['booking.user', 'booking.schedule.route'])->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function destroy(\App\Models\Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Data pembayaran berhasil dihapus');
    }
}
