@extends('layouts.admin')

@section('header', 'Edit Jadwal')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.schedules.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Jadwal</h2>
            <p class="text-slate-500 text-sm mt-1">Perbarui jadwal keberangkatan bus dan rute.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 max-w-4xl">
        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Rute -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rute Perjalanan</label>
                    <select name="route_id" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" data-estimasi="{{ $route->estimasi }}" {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
                                {{ $route->asal }} &rarr; {{ $route->tujuan }}
                            </option>
                        @endforeach
                    </select>
                    @error('route_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Bus -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Armada Bus</label>
                    <select name="bus_id" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" data-kapasitas="{{ $bus->kapasitas }}" {{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} ({{ $bus->kelas }} - {{ $bus->kapasitas }} Kursi)
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $schedule->tanggal) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jam Berangkat & Tiba -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Berangkat</label>
                        <input type="time" name="jam_berangkat" id="jam_berangkat" value="{{ old('jam_berangkat', $schedule->jam_berangkat) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        @error('jam_berangkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Tiba</label>
                        <input type="time" name="jam_tiba" id="jam_tiba" value="{{ old('jam_tiba', $schedule->jam_tiba) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        @error('jam_tiba') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Tiket (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $schedule->harga) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required min="0">
                    @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kursi Tersedia (Readonly) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Kursi Tersedia</label>
                    <input type="number" name="kursi_tersedia" id="kursi_tersedia" value="{{ old('kursi_tersedia', $schedule->kursi_tersedia) }}" class="w-full border-slate-200 rounded-xl bg-slate-50 text-slate-500 focus:ring-cyan-500 focus:border-cyan-500 cursor-not-allowed" placeholder="Otomatis terisi sesuai bus" readonly>
                </div>

                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md shadow-cyan-500/30 transition">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const routeSelect = document.querySelector('select[name="route_id"]');
            const jamBerangkatInput = document.getElementById('jam_berangkat');
            const jamTibaInput = document.getElementById('jam_tiba');

            function calculateJamTiba() {
                const selectedOption = routeSelect.options[routeSelect.selectedIndex];
                const estimasi = selectedOption ? selectedOption.getAttribute('data-estimasi') : '';
                const jamBerangkat = jamBerangkatInput.value;

                if (estimasi && jamBerangkat) {
                    let hoursToAdd = 0;
                    let minutesToAdd = 0;

                    const hourMatch = estimasi.match(/(\d+)\s*jam/i);
                    if (hourMatch) hoursToAdd = parseInt(hourMatch[1], 10);

                    const minuteMatch = estimasi.match(/(\d+)\s*menit/i);
                    if (minuteMatch) minutesToAdd = parseInt(minuteMatch[1], 10);

                    const [berangkatHour, berangkatMinute] = jamBerangkat.split(':').map(num => parseInt(num, 10));

                    let finalMinute = berangkatMinute + minutesToAdd;
                    let finalHour = berangkatHour + hoursToAdd + Math.floor(finalMinute / 60);
                    
                    finalMinute = finalMinute % 60;
                    finalHour = finalHour % 24;

                    const formattedHour = finalHour.toString().padStart(2, '0');
                    const formattedMinute = finalMinute.toString().padStart(2, '0');

                    jamTibaInput.value = `${formattedHour}:${formattedMinute}`;
                }
            }

            routeSelect.addEventListener('change', calculateJamTiba);
            jamBerangkatInput.addEventListener('input', calculateJamTiba);

            // Note: Since this is edit mode, the booked seats should theoretically subtract from capacity, 
            // but the backend handles the exact calculation on submit. We can just leave the current value 
            // visually as it is unless they change the bus.
            const busSelect = document.querySelector('select[name="bus_id"]');
            const kursiInput = document.getElementById('kursi_tersedia');

            busSelect.addEventListener('change', function() {
                const selectedOption = busSelect.options[busSelect.selectedIndex];
                if (selectedOption && selectedOption.getAttribute('data-kapasitas')) {
                    kursiInput.value = selectedOption.getAttribute('data-kapasitas');
                }
            });
        });
    </script>
@endsection
