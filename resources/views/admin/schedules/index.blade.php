@extends('layouts.admin')

@section('header', 'Kelola Jadwal')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Jadwal Keberangkatan</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola jadwal keberangkatan untuk setiap rute dan bus.</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md shadow-cyan-500/30 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center gap-3 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rute</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Bus</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kursi</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $schedule->route->asal }} &rarr; {{ $schedule->route->tujuan }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $schedule->bus->nama_bus }}</div>
                                <div class="text-xs text-slate-500">{{ $schedule->bus->kelas }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($schedule->tanggal)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->jam_tiba)->format('H:i') }}</div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-emerald-600">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="font-bold text-slate-800">{{ $schedule->kursi_tersedia }}</span> / {{ $schedule->bus->kapasitas }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Belum ada data jadwal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
