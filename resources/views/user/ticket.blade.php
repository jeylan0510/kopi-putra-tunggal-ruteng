<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket BusTiket #BTX-8810</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background-color: white !important; }
            .no-print { display: none !important; }
            .print-border { border: 2px dashed #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-10 font-sans text-slate-800">
    
    <div class="max-w-3xl mx-auto px-4">
        <!-- Control Bar -->
        <div class="no-print flex justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <a href="{{ route('user.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-cyan-600 flex items-center gap-2">
                &larr; Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Tiket (PDF)
            </button>
        </div>

        <!-- Ticket Design -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl print-border border border-slate-200 flex flex-col md:flex-row">
            
            <!-- Left Side: Main Info -->
            <div class="flex-1 p-8 md:p-10 border-b md:border-b-0 md:border-r-2 border-dashed border-slate-300 relative">
                <!-- Watermark -->
                <div class="absolute inset-0 opacity-[0.03] pointer-events-none flex items-center justify-center">
                    <svg class="w-64 h-64" viewBox="0 0 24 24" fill="currentColor"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </div>

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-2xl font-black text-cyan-700 tracking-tight flex items-center gap-2">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            BusTiket
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">Sistem Pemesanan Tiket Interkota</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-200">
                            Lunas / Terkonfirmasi
                        </span>
                        <p class="text-sm font-medium text-slate-500 mt-2">ID: #BTX-8810</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-8 pb-8 border-b border-slate-100">
                    <div class="text-center w-1/3">
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold mb-1">Berangkat</p>
                        <p class="text-2xl font-black text-slate-900">PAREPARE</p>
                        <p class="text-sm font-medium text-slate-600 mt-1">Sabtu, 28 Jul 2026<br>09:00 WITA</p>
                    </div>
                    
                    <div class="flex-1 flex flex-col items-center px-4">
                        <svg class="w-8 h-8 text-cyan-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        <div class="w-full h-px border-t-2 border-dashed border-cyan-200"></div>
                        <p class="text-xs font-semibold text-slate-400 mt-2">Bintang Timur (Bisnis)</p>
                    </div>

                    <div class="text-center w-1/3">
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold mb-1">Tujuan</p>
                        <p class="text-2xl font-black text-slate-900">MAKASSAR</p>
                        <p class="text-sm font-medium text-slate-600 mt-1">Sabtu, 28 Jul 2026<br>14:00 WITA (Est)</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">Nama Penumpang</p>
                        <p class="text-lg font-bold text-slate-900">{{ auth()->user()->name ?? 'Budi Santoso' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-1">Nomor Kursi</p>
                        <p class="text-lg font-bold text-slate-900">05</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: QR Code / Tear-off -->
            <div class="w-full md:w-64 bg-slate-50 p-8 flex flex-col items-center justify-center relative">
                <!-- Half circles for ticket tear-off effect -->
                <div class="hidden md:block absolute -left-4 top-1/4 w-8 h-8 bg-slate-100 rounded-full"></div>
                <div class="hidden md:block absolute -left-4 bottom-1/4 w-8 h-8 bg-slate-100 rounded-full"></div>
                
                <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 mb-6">
                    <!-- Dummy QR Code -->
                    <svg class="w-32 h-32 text-slate-800" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-2 3h2v2h-2v-2zm-3-3h2v2h-2v-2zm3 3h2v2h-2v-2zm-3-3h2v2h-2v-2z"></path>
                    </svg>
                </div>
                
                <p class="text-xs text-center text-slate-500 mb-1">Tunjukkan tiket ini ke petugas saat naik bus.</p>
                <p class="font-mono text-sm font-bold tracking-widest text-slate-900">BTX-8810-{{ $booking ?? 1 }}</p>
            </div>
        </div>
        
    </div>

</body>
</html>
