@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-slate-900 overflow-hidden">
        <!-- Abstract Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <polygon fill="currentColor" points="0,100 100,0 100,100"/>
            </svg>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
            <div class="text-center md:text-left md:w-2/3 lg:w-1/2">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    Perjalanan Nyaman, <br>
                    <span class="text-cyan-400">Pemesanan Mudah.</span>
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-xl">
                    Pesan tiket bus antar kota Anda sekarang. Nikmati kemudahan memilih kursi dan metode pembayaran yang aman hanya dalam genggaman.
                </p>
                <div class="flex gap-4 justify-center md:justify-start">
                    <a href="#search-form" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-cyan-500/30 transition transform hover:-translate-y-1">Cari Tiket Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Search Form -->
    <section id="search-form" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-16 z-10">
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-slate-100">
            <form action="{{ route('schedules.search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Asal -->
                    <div class="col-span-1">
                        <label for="asal" class="block text-sm font-medium text-slate-700 mb-1">Kota Asal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="text" name="asal" id="asal" class="pl-10 block w-full rounded-xl border-slate-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 text-sm" placeholder="Contoh: Makassar">
                        </div>
                    </div>
                    <!-- Tujuan -->
                    <div class="col-span-1">
                        <label for="tujuan" class="block text-sm font-medium text-slate-700 mb-1">Kota Tujuan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="text" name="tujuan" id="tujuan" class="pl-10 block w-full rounded-xl border-slate-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 text-sm" placeholder="Contoh: Toraja">
                        </div>
                    </div>
                    <!-- Tanggal -->
                    <div class="col-span-1">
                        <label for="tanggal" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="tanggal" id="tanggal" class="pl-10 block w-full rounded-xl border-slate-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                        </div>
                    </div>
                    <!-- Submit -->
                    <div class="col-span-1">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari Tiket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Rute Populer -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Rute Perjalanan Populer</h2>
                <p class="text-slate-500 mt-2">Jelajahi destinasi favorit pelanggan kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($schedules as $schedule)
                <!-- Dynamic Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition group flex flex-col h-full">
                    <div class="h-48 bg-slate-200 relative overflow-hidden flex-shrink-0">
                        <div class="absolute inset-0 bg-slate-800/20 group-hover:bg-transparent transition z-10"></div>
                        @if($schedule->route->gambar)
                            <img src="{{ Storage::url($schedule->route->gambar) }}" alt="{{ $schedule->route->tujuan }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-3 mb-4 flex-wrap">
                            <span class="font-bold text-lg text-slate-900">{{ $schedule->route->asal }}</span>
                            <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            <span class="font-bold text-lg text-slate-900">{{ $schedule->route->tujuan }}</span>
                        </div>
                        <div class="mb-4">
                            <div class="text-sm font-semibold text-emerald-600 mb-1">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</div>
                            <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($schedule->tanggal)->format('d M Y') }} &bull; {{ \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->jam_tiba)->format('H:i') }}</div>
                        </div>
                        <p class="text-sm text-slate-500 mb-4 flex-grow">{{ $schedule->route->estimasi }} perjalanan dengan {{ $schedule->bus->nama_bus }} ({{ $schedule->bus->kelas }}).</p>
                        <a href="{{ route('schedules.search', ['asal' => $schedule->route->asal, 'tujuan' => $schedule->route->tujuan, 'tanggal' => $schedule->tanggal]) }}" class="text-cyan-600 font-semibold text-sm hover:text-cyan-700 inline-flex items-center gap-1 mt-auto">
                            Pesan Tiket <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 py-12 text-center text-slate-500 bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Belum ada jadwal keberangkatan yang tersedia saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Mengapa Memilih BusTiket?</h2>
                <p class="text-slate-500 mt-2">Komitmen kami untuk pengalaman perjalanan terbaik Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center px-4">
                    <div class="w-16 h-16 bg-cyan-100 text-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kemudahan Pemesanan</h3>
                    <p class="text-slate-500">Pesan tiket kapan saja dan di mana saja langsung dari smartphone Anda tanpa harus antre di loket.</p>
                </div>
                <div class="text-center px-4">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pembayaran Aman</h3>
                    <p class="text-slate-500">Berbagai metode pembayaran transfer bank dengan verifikasi cepat dan terjamin keamanannya.</p>
                </div>
                <div class="text-center px-4">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Armada Nyaman</h3>
                    <p class="text-slate-500">Nikmati perjalanan dengan bus kelas Ekonomi, Bisnis, hingga Executive yang bersih dan terawat.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
