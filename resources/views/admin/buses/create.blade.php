@extends('layouts.admin')

@section('header', 'Tambah Bus')

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.buses.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tambah Bus Baru</h2>
            <p class="text-slate-500 text-sm mt-1">Masukkan detail armada bus.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 max-w-3xl">
        <form action="{{ route('admin.buses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Bus -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bus</label>
                    <input type="text" name="nama_bus" value="{{ old('nama_bus') }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" placeholder="Contoh: Manggala Trans" required>
                    @error('nama_bus') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Nomor Polisi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Polisi</label>
                    <input type="text" name="nomor_polisi" value="{{ old('nomor_polisi') }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" placeholder="Contoh: DD 1234 XY" required>
                    @error('nomor_polisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kapasitas Kursi</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" placeholder="Contoh: 32" required min="1">
                    @error('kapasitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas Bus</label>
                    <select name="kelas" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        <option value="">-- Pilih Kelas --</option>
                        <option value="Ekonomi" {{ old('kelas') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="Bisnis" {{ old('kelas') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="Executive" {{ old('kelas') == 'Executive' ? 'selected' : '' }}>Executive</option>
                    </select>
                    @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full border-slate-200 rounded-xl focus:ring-cyan-500 focus:border-cyan-500" required>
                        <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Gambar -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Bus (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full border-slate-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md shadow-cyan-500/30 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
