@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Pemesanan Hari Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition cursor-default">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Pemesanan Hari Ini</p>
                <h3 class="text-3xl font-bold text-slate-900">42</h3>
                <p class="text-xs text-emerald-500 font-semibold mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +12% vs Kemarin
                </p>
            </div>
            <div class="w-14 h-14 bg-cyan-100 text-cyan-600 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>

        <!-- Pending Pembayaran -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition cursor-default">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Pending Pembayaran</p>
                <h3 class="text-3xl font-bold text-slate-900">18</h3>
                <p class="text-xs text-amber-500 font-semibold mt-1">Butuh konfirmasi segera</p>
            </div>
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Total Bus Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition cursor-default">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Bus Aktif</p>
                <h3 class="text-3xl font-bold text-slate-900">24</h3>
                <p class="text-xs text-slate-400 font-semibold mt-1">Dari 28 total bus</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
        </div>

        <!-- Total Rute -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition cursor-default">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Rute</p>
                <h3 class="text-3xl font-bold text-slate-900">12</h3>
                <p class="text-xs text-emerald-500 font-semibold mt-1">Seluruh rute beroperasi</p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Section: Tabel Pemesanan Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-lg text-slate-900">Pemesanan Terbaru</h3>
            <a href="#" class="text-sm font-semibold text-cyan-600 hover:text-cyan-700">Lihat Semua Data &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-200">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Booking</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rute & Bus</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Keberangkatan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Harga</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-6 font-bold text-slate-900">#BTX-9921</td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Budi Santoso</div>
                            <div class="text-xs text-slate-500">081234567890</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Makassar → Toraja</div>
                            <div class="text-xs text-slate-500">Manggala Trans (Executive)</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">30 Jul 2026, 19:00</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">Rp 250.000</td>
                        <td class="py-4 px-6">
                            <span class="bg-amber-100 text-amber-700 border border-amber-200 text-xs font-bold px-2.5 py-1 rounded-md">PENDING</span>
                        </td>
                    </tr>
                    
                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-6 font-bold text-slate-900">#BTX-9920</td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Siti Aminah</div>
                            <div class="text-xs text-slate-500">085678912345</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Makassar → Palopo</div>
                            <div class="text-xs text-slate-500">Bintang Timur (Bisnis)</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">29 Jul 2026, 20:00</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">Rp 180.000</td>
                        <td class="py-4 px-6">
                            <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-2.5 py-1 rounded-md">PAID</span>
                        </td>
                    </tr>
                    
                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-6 font-bold text-slate-900">#BTX-9919</td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Ahmad Fauzi</div>
                            <div class="text-xs text-slate-500">081112233445</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-800">Toraja → Makassar</div>
                            <div class="text-xs text-slate-500">Manggala Trans (Executive)</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">28 Jul 2026, 21:00</td>
                        <td class="py-4 px-6 font-semibold text-slate-800">Rp 250.000</td>
                        <td class="py-4 px-6">
                            <span class="bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold px-2.5 py-1 rounded-md">CANCELLED</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
