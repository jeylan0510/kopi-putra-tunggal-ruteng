@extends('layouts.admin')

@section('header', 'Konfirmasi Pembayaran')

@section('content')
<div x-data="{ showModal: false, currentPayment: {} }">
    <!-- Filter & Search -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex gap-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            @php
                $pendingCount = \App\Models\Payment::whereIn('status', ['menunggu', 'pending'])->count();
            @endphp
            <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium whitespace-nowrap shadow-sm">Semua</button>
            <button class="px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 rounded-lg text-sm font-medium whitespace-nowrap">Menunggu {{ $pendingCount > 0 ? "($pendingCount)" : "" }}</button>
            <button class="px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 rounded-lg text-sm font-medium whitespace-nowrap">Berhasil</button>
            <button class="px-4 py-2 bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 rounded-lg text-sm font-medium whitespace-nowrap">Ditolak</button>
        </div>
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="pl-10 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 text-sm" placeholder="Cari ID Booking...">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Booking</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nominal Tagihan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pembayaran</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payments as $payment)
                    @php
                        $seats = is_string($payment->booking->nomor_kursi) ? (json_decode($payment->booking->nomor_kursi, true) ?? explode(',', $payment->booking->nomor_kursi)) : $payment->booking->nomor_kursi;
                        $totalHarga = count($seats) * $payment->booking->schedule->harga;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-6 text-slate-600">{{ $payment->created_at->format('d M Y') }}<br><span class="text-xs text-slate-400">{{ $payment->created_at->format('H:i A') }}</span></td>
                        <td class="py-4 px-6 font-bold text-slate-900">#BTX-{{ $payment->booking_id }}</td>
                        <td class="py-4 px-6 font-medium text-slate-700">{{ $payment->booking->user->name ?? $payment->booking->nama_penumpang }}</td>
                        <td class="py-4 px-6 font-bold text-emerald-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            @if($payment->status == 'menunggu' || $payment->status == 'pending')
                            <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-md border border-amber-200 flex items-center gap-1 w-max">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Menunggu
                            </span>
                            @elseif($payment->status == 'berhasil' || $payment->status == 'success')
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-md border border-emerald-200 flex items-center gap-1 w-max">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Berhasil
                            </span>
                            @else
                            <span class="bg-rose-100 text-rose-700 text-xs font-bold px-2.5 py-1 rounded-md border border-rose-200 flex items-center gap-1 w-max">
                                Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="showModal = true; currentPayment = {id: '{{ $payment->booking_id }}', nama: '{{ $payment->booking->user->name ?? $payment->booking->nama_penumpang }}', nominal: 'Rp {{ number_format($totalHarga, 0, ',', '.') }}', status: '{{ ucfirst($payment->status) }}'}" class="bg-cyan-50 text-cyan-600 hover:bg-cyan-100 font-semibold px-3 py-1.5 rounded-lg transition text-xs border border-cyan-200">
                                    Cek Bukti
                                </button>
                                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold px-3 py-1.5 rounded-lg transition text-xs border border-rose-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">Belum ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Konfirmasi -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div class="bg-white px-6 pt-6 pb-2">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                        <h3 class="text-xl leading-6 font-bold text-slate-900">Detail Pembayaran</h3>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <!-- Bukti Transfer -->
                        <div class="bg-slate-100 rounded-xl p-2 border border-slate-200 flex flex-col items-center justify-center min-h-[300px]">
                            <!-- Mockup Gambar -->
                            <div class="text-center text-slate-400 p-8">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm font-medium">Gambar Bukti Transfer.jpg</p>
                                <button class="mt-4 bg-white border border-slate-300 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm">Buka Gambar Penuh</button>
                            </div>
                        </div>

                        <!-- Data Booking -->
                        <div class="space-y-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="text-xs text-slate-500 mb-1">ID Booking</div>
                                <div class="font-bold text-lg text-slate-900" x-text="'#' + currentPayment.id"></div>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="text-xs text-slate-500 mb-1">Pelanggan</div>
                                <div class="font-bold text-slate-900" x-text="currentPayment.nama"></div>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="text-xs text-slate-500 mb-1">Nominal Tagihan</div>
                                <div class="font-bold text-2xl text-emerald-600" x-text="currentPayment.nominal"></div>
                            </div>
                            
                            <div x-show="currentPayment.status === 'Menunggu'" class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                                <p class="text-sm text-amber-800">Mohon periksa mutasi bank Anda dan cocokkan nominal transfer dengan tagihan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div x-show="currentPayment.status === 'Menunggu'" class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                    <form action="#" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tolak Pembayaran
                        </button>
                    </form>
                    <form action="#" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-emerald-500/20 transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Konfirmasi Berhasil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
