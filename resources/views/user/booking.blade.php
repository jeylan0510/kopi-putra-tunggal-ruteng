<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Pemesanan Tiket') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Panggil Komponen Denah Bus 2D -->
            <x-bus-seat-booking :schedule="$schedule" :bookedSeats="$bookedSeats" />
        </div>
    </div>
</x-app-layout>
