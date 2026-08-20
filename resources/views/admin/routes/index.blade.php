@extends('layouts.admin')

@section('header', 'Kelola Rute')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Rute Perjalanan</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola data rute awal dan tujuan bus.</p>
        </div>
        <a href="{{ route('admin.routes.create') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md shadow-cyan-500/30 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Rute
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center gap-3 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Gambar</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kota Asal</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kota Tujuan</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Jarak (KM)</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Estimasi Waktu</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($routes as $route)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                @if($route->gambar)
                                    <img src="{{ Storage::url($route->gambar) }}" alt="Gambar Rute" class="w-16 h-12 object-cover rounded-lg border border-slate-200 shadow-sm">
                                @else
                                    <div class="w-16 h-12 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $route->asal }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $route->tujuan }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $route->jarak }} KM</td>
                            <td class="py-4 px-6 text-slate-600">{{ $route->estimasi }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.routes.edit', $route->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus rute ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                Belum ada data rute.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
