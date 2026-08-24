@props([
    'schedule',
    'ticketPrice' => 220000,
    'bookedSeats' => [], 
    'formAction' => null,
    'isAdmin' => false
])

<div class="max-w-6xl mx-auto" x-data="busSeatBooking()">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('schedules.search') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 font-medium transition mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Jadwal
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Pilih Kursi & Detail Penumpang</h1>
        <p class="text-slate-500 text-sm mt-1">Silakan pilih nomor kursi yang tersedia dan lengkapi data pemesan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Kolom Kiri: Visualisasi Denah Bus 2D -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div class="flex items-center gap-4">
                    @if($schedule && $schedule->bus->gambar)
                        <img src="{{ Storage::url($schedule->bus->gambar) }}" alt="{{ $schedule->bus->nama_bus }}" class="w-16 h-16 rounded-xl object-cover shadow-sm hidden sm:block">
                    @endif
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg">{{ $schedule ? $schedule->bus->nama_bus : 'Bintang Prima' }} — {{ $schedule ? $schedule->bus->kelas : 'Executive' }} Class</h2>
                        <p class="text-xs text-slate-500">Kapasitas {{ $schedule ? $schedule->bus->kapasitas : 20 }} Kursi &bull; No. Polisi: {{ $schedule ? $schedule->bus->nomor_polisi : '-' }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-semibold text-xs rounded-full border border-emerald-200 text-center">
                    {{ $schedule ? $schedule->route->asal : 'Makassar' }} ➔ <br class="sm:hidden">{{ $schedule ? $schedule->route->tujuan : 'Toraja' }}
                </span>
            </div>

            <!-- Legenda Kursi -->
            <div class="flex flex-wrap items-center justify-around gap-3 p-3 bg-slate-50 rounded-xl mb-6 text-xs font-medium text-slate-600 border border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-5 h-5 bg-white border-2 border-slate-300 rounded-md"></div>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-5 h-5 bg-emerald-600 rounded-md shadow-sm"></div>
                    <span>Dipilih</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-5 h-5 bg-slate-300 rounded-md border border-slate-400 opacity-60"></div>
                    <span>Terisi</span>
                </div>
            </div>

            <!-- Frame Denah Bus 2D -->
            <div class="relative bg-slate-100 border-2 border-slate-300 rounded-3xl p-6 max-w-sm mx-auto shadow-inner">
                <!-- Kemudi Depan -->
                <div class="flex justify-between items-center pb-6 mb-6 border-b-2 border-dashed border-slate-300">
                    <div class="flex items-center space-x-2 text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <span class="text-xs uppercase font-bold tracking-wider">Pintu Depan</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4 2 2 0 000-4zm0 6a2 2 0 100 4 2 2 0 000-4zm0 6a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    </div>
                </div>

                <!-- Grid Baris Kursi -->
                <div class="space-y-4">
                    <template x-for="(row, rowIndex) in seatRows" :key="rowIndex">
                        <div class="flex items-center justify-between">
                            <!-- Sisi Kiri (A & B) -->
                            <div class="flex space-x-3">
                                <template x-for="seat in row.left" :key="seat.number">
                                    <button type="button"
                                        @click="toggleSeat(seat)"
                                        :disabled="seat.status === 'booked'"
                                        :class="{
                                            'bg-white border-2 border-slate-300 text-slate-700 hover:border-emerald-500 hover:text-emerald-600': seat.status === 'available' && !isSelected(seat.number),
                                            'bg-emerald-600 border-2 border-emerald-600 text-white shadow-lg ring-2 ring-emerald-200': isSelected(seat.number),
                                            'bg-slate-300 border-slate-400 text-slate-500 cursor-not-allowed opacity-60': seat.status === 'booked'
                                        }"
                                        class="w-12 h-12 rounded-xl font-bold text-sm flex flex-col items-center justify-center transition-all duration-150 relative shadow-sm">
                                        <span x-text="seat.number"></span>
                                        <div class="w-6 h-1 rounded-full mt-0.5" 
                                             :class="isSelected(seat.number) ? 'bg-emerald-400' : (seat.status === 'booked' ? 'bg-slate-400' : 'bg-slate-200')"></div>
                                    </button>
                                </template>
                            </div>

                            <!-- Gangway / Lorong -->
                            <div class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold rotate-90 select-none">
                                Lorong
                            </div>

                            <!-- Sisi Kanan (C & D) -->
                            <div class="flex space-x-3">
                                <template x-for="seat in row.right" :key="seat.number">
                                    <button type="button"
                                        @click="toggleSeat(seat)"
                                        :disabled="seat.status === 'booked'"
                                        :class="{
                                            'bg-white border-2 border-slate-300 text-slate-700 hover:border-emerald-500 hover:text-emerald-600': seat.status === 'available' && !isSelected(seat.number),
                                            'bg-emerald-600 border-2 border-emerald-600 text-white shadow-lg ring-2 ring-emerald-200': isSelected(seat.number),
                                            'bg-slate-300 border-slate-400 text-slate-500 cursor-not-allowed opacity-60': seat.status === 'booked'
                                        }"
                                        class="w-12 h-12 rounded-xl font-bold text-sm flex flex-col items-center justify-center transition-all duration-150 relative shadow-sm">
                                        <span x-text="seat.number"></span>
                                        <div class="w-6 h-1 rounded-full mt-0.5" 
                                             :class="isSelected(seat.number) ? 'bg-emerald-400' : (seat.status === 'booked' ? 'bg-slate-400' : 'bg-slate-200')"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-8 pt-4 border-t-2 border-dashed border-slate-300 text-center">
                    <span class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Bagian Belakang</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Data Penumpang & Summary -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span>Ringkasan Pesanan</span>
                    <span class="text-xs font-normal text-slate-500" x-text="selectedSeats.length + ' Kursi Dipilih'"></span>
                </h3>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Rute Perjalanan</span>
                        <span class="font-semibold text-slate-800">{{ $schedule ? $schedule->route->asal : 'Makassar' }} ➔ {{ $schedule ? $schedule->route->tujuan : 'Toraja' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Waktu Berangkat</span>
                        <span class="font-semibold text-slate-800">{{ $schedule ? \Carbon\Carbon::parse($schedule->tanggal)->format('d M Y') . ', ' . \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Harga per Kursi</span>
                        <span class="font-semibold text-slate-800" x-text="formatRupiah(ticketPrice)"></span>
                    </div>
                    <div class="flex justify-between text-sm items-center pt-2 border-t border-slate-100">
                        <span class="text-slate-500">Nomor Kursi</span>
                        <div class="flex flex-wrap gap-1 justify-end">
                            <template x-if="selectedSeats.length === 0">
                                <span class="text-slate-400 italic text-xs">Belum memilih kursi</span>
                            </template>
                            <template x-for="seatNum in selectedSeats" :key="seatNum">
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-md" x-text="seatNum"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-900 rounded-xl text-white flex justify-between items-center shadow-sm">
                    <div>
                        <p class="text-xs text-slate-400">Total Pembayaran</p>
                        <p class="text-xl font-bold text-emerald-400" x-text="formatRupiah(totalPrice)"></p>
                    </div>
                    <span class="text-xs bg-slate-800 text-slate-300 px-2.5 py-1 rounded-lg border border-slate-700" x-text="selectedSeats.length + ' x Tiket'"></span>
                </div>
            </div>

            <!-- Form Pemesanan ke Backend -->
            <form action="{{ $formAction ?? route('user.booking.store', $schedule ?? 1) }}" method="POST" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule ?? 1 }}">
                <!-- Input tersembunyi array kursi yang dipilih untuk dikirim ke Laravel -->
                <template x-for="seat in selectedSeats" :key="seat">
                    <input type="hidden" name="nomor_kursi[]" :value="seat">
                </template>

                <h3 class="text-lg font-bold text-slate-900 mb-2 pb-2 border-b border-slate-100">Data Penumpang</h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_penumpang" value="{{ $isAdmin ? '' : (auth()->user()->name ?? '') }}" required placeholder="Sesuai KTP" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">NIK</label>
                    <input type="text" name="nik" required maxlength="16" placeholder="16 digit NIK" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp</label>
                    <input type="tel" name="nomor_hp" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 text-sm outline-none">
                </div>

                <button type="submit" 
                        :disabled="selectedSeats.length === 0"
                        :class="selectedSeats.length > 0 ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xl shadow-emerald-600/40' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="w-full py-4 px-6 rounded-2xl font-extrabold text-lg tracking-wide transition-all duration-200 mt-6 flex items-center justify-center space-x-2">
                    <span>Lanjutkan Booking</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function busSeatBooking() {
        @php
            $kapasitas = $schedule ? $schedule->bus->kapasitas : 20;
            $rows = ceil($kapasitas / 4);
            $seatRows = [];
            $seatCounter = 0;
            for ($i = 1; $i <= $rows; $i++) {
                $left = [];
                $right = [];
                
                if ($seatCounter < $kapasitas) {
                    $left[] = ['number' => $i.'A', 'status' => 'available'];
                    $seatCounter++;
                }
                if ($seatCounter < $kapasitas) {
                    $left[] = ['number' => $i.'B', 'status' => 'available'];
                    $seatCounter++;
                }
                
                if ($seatCounter < $kapasitas) {
                    $right[] = ['number' => $i.'C', 'status' => 'available'];
                    $seatCounter++;
                }
                if ($seatCounter < $kapasitas) {
                    $right[] = ['number' => $i.'D', 'status' => 'available'];
                    $seatCounter++;
                }
                
                $seatRows[] = ['left' => $left, 'right' => $right];
            }
        @endphp

        return {
            ticketPrice: {{ $schedule ? $schedule->harga : ($ticketPrice ?? 220000) }},
            selectedSeats: [],
            bookedList: @json($bookedSeats),
            seatRows: @json($seatRows),
            init() {
                // Tandai kursi yang terisi berdasarkan data database
                this.seatRows.forEach(row => {
                    [...row.left, ...row.right].forEach(seat => {
                        if (this.bookedList.includes(seat.number)) {
                            seat.status = 'booked';
                        }
                    });
                });
            },
            toggleSeat(seat) {
                if (seat.status === 'booked') return;
                const index = this.selectedSeats.indexOf(seat.number);
                if (index > -1) {
                    this.selectedSeats.splice(index, 1);
                } else {
                    this.selectedSeats.push(seat.number);
                }
            },
            isSelected(seatNumber) {
                return this.selectedSeats.includes(seatNumber);
            },
            get totalPrice() {
                return this.selectedSeats.length * this.ticketPrice;
            },
            formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
            }
        }
    }
</script>
