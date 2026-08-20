@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Hasil Pencarian -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Hasil Pencarian Jadwal</h1>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <span class="font-semibold text-slate-700">{{ request('asal', 'Makassar') }}</span> 
                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                <span class="font-semibold text-slate-700">{{ request('tujuan', 'Toraja') }}</span>
                <span class="px-2 text-slate-300">|</span>
                <span>{{ request('tanggal', now()->format('d M Y')) }}</span>
            </p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Ubah Pencarian</a>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <div class="w-full lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-24">
                <h3 class="font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Pencarian
                </h3>
                
                <!-- Filter Kelas -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Kelas Bus</h4>
                    <div class="space-y-2">
                        @forelse($availableClasses as $kelas)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="filter-checkbox filter-kelas rounded text-cyan-500 focus:ring-cyan-500 border-slate-300" value="{{ $kelas['kelas'] }}|{{ $kelas['harga'] }}">
                            <span class="text-slate-600 text-sm">{{ $kelas['kelas'] }} (Rp {{ number_format($kelas['harga'], 0, ',', '.') }})</span>
                        </label>
                        @empty
                        <span class="text-slate-500 text-xs">Tidak ada kelas bus</span>
                        @endforelse
                    </div>
                </div>

                <!-- Filter Waktu -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Waktu Keberangkatan</h4>
                    <div class="space-y-2">
                        @forelse($availableTimes as $waktu)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="filter-checkbox filter-waktu rounded text-cyan-500 focus:ring-cyan-500 border-slate-300" value="{{ \Carbon\Carbon::parse($waktu)->format('H:i') }}">
                            <span class="text-slate-600 text-sm">{{ \Carbon\Carbon::parse($waktu)->format('H:i') }}</span>
                        </label>
                        @empty
                        <span class="text-slate-500 text-xs">Tidak ada jadwal</span>
                        @endforelse
                    </div>
                </div>
                
                <button id="reset-filter" class="w-full bg-slate-900 text-white py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Reset Filter</button>
            </div>
        </div>

        <!-- Daftar Jadwal -->
        <div class="w-full lg:w-3/4 space-y-4">
            
            @forelse($schedules as $schedule)
            <!-- Jadwal Card -->
            <div class="schedule-card bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition" data-kelas="{{ $schedule->bus->kelas }}|{{ $schedule->harga }}" data-waktu="{{ \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    
                    <!-- Info Bus -->
                    <div class="flex-1 w-full flex items-start gap-4">
                        @if($schedule->bus->gambar)
                            <img src="{{ Storage::url($schedule->bus->gambar) }}" alt="{{ $schedule->bus->nama_bus }}" class="w-24 h-24 md:w-32 md:h-24 object-cover rounded-xl shadow-sm hidden md:block">
                        @endif
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900">{{ $schedule->bus->nama_bus }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="bg-cyan-100 text-cyan-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $schedule->bus->kelas }}</span>
                                        <span class="text-sm text-slate-500">{{ $schedule->bus->nomor_polisi }}</span>
                                    </div>
                                    @if($schedule->bus->gambar)
                                        <div class="mt-3 md:hidden">
                                            <img src="{{ Storage::url($schedule->bus->gambar) }}" alt="{{ $schedule->bus->nama_bus }}" class="w-full h-32 object-cover rounded-xl shadow-sm">
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-emerald-600">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</div>
                                    <div class="text-sm text-slate-500 mt-1">Sisa {{ $schedule->kursi_tersedia }} Kursi</div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="flex items-center justify-between mt-6">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }}</div>
                                    <div class="text-sm text-slate-500">{{ $schedule->route->asal }}</div>
                                </div>
                                <div class="flex-1 px-4 flex flex-col items-center relative">
                                    <div class="text-xs text-slate-400 mb-1">{{ $schedule->route->estimasi }}</div>
                                    <div class="w-full border-t-2 border-dashed border-slate-300 relative">
                                        <div class="absolute -top-1.5 left-1/2 transform -translate-x-1/2 bg-white px-2">
                                            <svg class="w-4 h-4 text-cyan-500 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($schedule->jam_tiba)->format('H:i') }}</div>
                                    <div class="text-sm text-slate-500">{{ $schedule->route->tujuan }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-auto mt-4 md:mt-0 flex md:flex-col justify-end gap-3 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                        <a href="{{ route('user.booking.create', $schedule->id) }}" class="flex-1 md:flex-none text-center bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md shadow-cyan-500/20">Pilih Tiket</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xl font-bold text-slate-700 mb-2">Jadwal Tidak Ditemukan</h3>
                <p class="text-slate-500">Maaf, kami tidak dapat menemukan jadwal yang sesuai dengan pencarian Anda. Silakan coba mengubah tanggal atau rute pencarian.</p>
                <a href="{{ route('home') }}" class="mt-6 inline-block bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md shadow-cyan-500/20">Kembali ke Beranda</a>
            </div>
            @endforelse

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.filter-checkbox');
        const scheduleCards = document.querySelectorAll('.schedule-card');
        const resetButton = document.getElementById('reset-filter');

        function filterSchedules() {
            const selectedKelas = Array.from(document.querySelectorAll('.filter-kelas:checked')).map(cb => cb.value);
            const selectedWaktu = Array.from(document.querySelectorAll('.filter-waktu:checked')).map(cb => cb.value);

            let visibleCount = 0;

            scheduleCards.forEach(card => {
                const cardKelas = card.getAttribute('data-kelas');
                const cardWaktu = card.getAttribute('data-waktu');

                const matchesKelas = selectedKelas.length === 0 || selectedKelas.includes(cardKelas);
                const matchesWaktu = selectedWaktu.length === 0 || selectedWaktu.includes(cardWaktu);

                if (matchesKelas && matchesWaktu) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Handle empty state visualization if needed, but for now just filter the cards.
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', filterSchedules);
        });

        if (resetButton) {
            resetButton.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
                filterSchedules();
            });
        }
    });
</script>
@endsection
