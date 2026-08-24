<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tiket Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ showModal: false, selectedTicket: null }">
            
            @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-start justify-between shadow-sm">
                <div class="flex gap-3 items-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            <!-- Statistik Ringkas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-cyan-100 text-cyan-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 font-medium">Total Tiket</div>
                        <div class="text-2xl font-bold text-slate-900">2</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-emerald-100 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 font-medium">Dikonfirmasi</div>
                        <div class="text-2xl font-bold text-slate-900">1</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
                    <div class="p-4 bg-amber-100 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 font-medium">Menunggu Pembayaran</div>
                        <div class="text-2xl font-bold text-slate-900">1</div>
                    </div>
                </div>
            </div>

            <!-- List Tiket -->
            <div class="space-y-6">
                <!-- Tiket Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-200">PENDING</span>
                                <span class="text-sm text-slate-500 font-medium">Booking ID: #BTX-9921</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Makassar <span class="text-slate-400 mx-2">→</span> Toraja</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Berangkat</div>
                                    <div class="font-semibold text-slate-800">Senin, 30 Jul 2026<br>19:00</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Armada</div>
                                    <div class="font-semibold text-slate-800">Manggala Trans<br><span class="text-sm text-cyan-600">Executive</span></div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Penumpang</div>
                                    <div class="font-semibold text-slate-800">{{ auth()->user()->name ?? 'Budi Santoso' }}<br>Kursi: 12</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Total Bayar</div>
                                    <div class="font-bold text-lg text-emerald-600">Rp 250.000</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:border-l border-slate-200 md:pl-8 flex flex-col justify-center items-center md:items-end gap-3 min-w-[200px]">
                            <p class="text-sm text-slate-500 text-center md:text-right">Segera lakukan pembayaran sebelum tiket dibatalkan.</p>
                            <button @click="showModal = true; selectedTicket = '#BTX-9921'" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2.5 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Bukti
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tiket Dikonfirmasi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100 opacity-80">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200">DIKONFIRMASI</span>
                                <span class="text-sm text-slate-500 font-medium">Booking ID: #BTX-8810</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Parepare <span class="text-slate-400 mx-2">→</span> Makassar</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Berangkat</div>
                                    <div class="font-semibold text-slate-800">Sabtu, 28 Jul 2026<br>09:00</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Armada</div>
                                    <div class="font-semibold text-slate-800">Bintang Timur<br><span class="text-sm text-cyan-600">Bisnis</span></div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Penumpang</div>
                                    <div class="font-semibold text-slate-800">{{ auth()->user()->name ?? 'Budi Santoso' }}<br>Kursi: 5</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Total Bayar</div>
                                    <div class="font-bold text-lg text-emerald-600">Rp 150.000</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:border-l border-slate-200 md:pl-8 flex flex-col justify-center items-center md:items-end min-w-[200px]">
                            <a href="{{ route('user.ticket.show', 1) }}" target="_blank" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                E-Ticket (PDF)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Upload Bukti Transfer -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <form action="{{ route('user.payment.upload', 1) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-xl leading-6 font-bold text-slate-900 mb-4" id="modal-title">
                                            Upload Bukti Pembayaran
                                        </h3>
                                        
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
                                            <p class="text-sm text-slate-600 mb-2">Silakan transfer sesuai tagihan ke rekening berikut:</p>
                                            <div class="font-mono font-bold text-lg text-slate-900">BCA - 1234 567 890</div>
                                            <div class="text-sm font-semibold text-slate-700">a.n. PT BusTiket Nusantara</div>
                                        </div>

                                        <div class="mb-4" x-data="{ fileName: '' }">
                                            <label class="block text-sm font-medium text-slate-700 mb-2">File Bukti Transfer (JPG/PNG)</label>
                                            <label for="file-upload" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-cyan-500 hover:bg-cyan-50 transition cursor-pointer bg-slate-50">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-slate-600 justify-center">
                                                        <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-cyan-600 hover:text-cyan-500">
                                                            Pilih File
                                                        </span>
                                                        <input id="file-upload" name="file-upload" type="file" class="sr-only" @change="fileName = $event.target.files[0].name">
                                                    </div>
                                                    <p class="text-xs text-slate-500" x-show="!fileName">PNG, JPG, up to 2MB</p>
                                                    <p class="text-xs font-bold text-cyan-600 mt-2" x-show="fileName" x-text="'File dipilih: ' + fileName" style="display: none;"></p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-cyan-600 text-base font-medium text-white hover:bg-cyan-700 sm:ml-3 sm:w-auto sm:text-sm">
                                    Kirim Bukti
                                </button>
                                <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
