@extends('layouts.admin')

@section('header', 'Pesan Tiket (Offline)')

@section('content')
    @if(!isset($schedule))
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Pilih Jadwal Keberangkatan</h2>
                <p class="text-slate-500 text-sm mt-1">Pilih jadwal bus yang akan dipesan secara manual.</p>
            </div>
            
            <!-- Filter Rute -->
            <form action="{{ route('admin.bookings.create') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
                <select name="route_id" onchange="this.form.submit()" class="border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500 text-sm py-2">
                    <option value="">Semua Rute</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->asal }} &rarr; {{ $route->tujuan }}
                        </option>
                    @endforeach
                </select>
                @if(request('route_id'))
                    <a href="{{ route('admin.bookings.create') }}" class="px-3 py-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition text-sm">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-semibold">
                        <tr>
                            <th class="py-4 px-6">Rute & Waktu</th>
                            <th class="py-4 px-6">Armada Bus</th>
                            <th class="py-4 px-6">Harga & Kapasitas</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($schedules as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $s->route->asal }} &rarr; {{ $s->route->tujuan }}</div>
                                <div class="text-slate-500 text-xs mt-1">{{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }} &bull; {{ \Carbon\Carbon::parse($s->jam_berangkat)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->jam_tiba)->format('H:i') }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-800">{{ $s->bus->nama_bus }}</div>
                                <div class="text-xs text-slate-500">{{ $s->bus->kelas }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-emerald-600">Rp {{ number_format($s->harga, 0, ',', '.') }}</div>
                                <div class="text-xs text-slate-500 mt-1">Tersisa {{ $s->kursi_tersedia }} kursi</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($s->kursi_tersedia > 0)
                                <a href="{{ route('admin.bookings.create', ['schedule_id' => $s->id]) }}" class="inline-block bg-cyan-500 hover:bg-cyan-600 text-white font-semibold px-4 py-2 rounded-lg transition text-xs shadow-sm shadow-cyan-500/30">
                                    Pilih Kursi
                                </a>
                                @else
                                <span class="inline-block bg-slate-100 text-slate-400 font-semibold px-4 py-2 rounded-lg text-xs cursor-not-allowed">Penuh</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Belum ada jadwal keberangkatan yang tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Use the same component as frontend, but with admin specific props -->
        <div class="-m-4 sm:-m-6 lg:-m-8">
            <x-bus-seat-booking 
                :schedule="$schedule" 
                :bookedSeats="$bookedSeats" 
                formAction="{{ route('admin.bookings.store') }}" 
                :isAdmin="true" 
            />
        </div>
    @endif
@endsection
