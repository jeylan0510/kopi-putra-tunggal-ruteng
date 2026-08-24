@extends('layouts.admin')

@section('header', 'Edit Rute')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.routes.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Rute</h2>
            <p class="text-slate-500 text-sm mt-1">Perbarui detail rute perjalanan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 max-w-3xl">
        <form action="{{ route('admin.routes.update', $route->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Asal -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kota Asal</label>
                    <input type="text" name="asal" value="{{ old('asal', $route->asal) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                    @error('asal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Tujuan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kota Tujuan</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan', $route->tujuan) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                    @error('tujuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jarak -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jarak (KM)</label>
                    <input type="number" step="0.1" name="jarak" value="{{ old('jarak', $route->jarak) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required min="0.1">
                    @error('jarak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Estimasi Waktu -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Estimasi Waktu</label>
                    <input type="text" name="estimasi" value="{{ old('estimasi', $route->estimasi) }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                    @error('estimasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                </div>
            </div>

            <!-- Gambar Rute -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Rute Populer (Opsional)</label>
                @if($route->gambar)
                    <div class="mb-3">
                        <img src="{{ Storage::url($route->gambar) }}" alt="Gambar Rute" class="w-32 h-32 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
                <input type="file" name="gambar" class="w-full border-slate-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100" accept="image/*">
                <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md shadow-cyan-500/30 transition">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
@endsection
