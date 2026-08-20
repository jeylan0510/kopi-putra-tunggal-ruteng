<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin BusTiket') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-900 text-slate-300 flex flex-col transition-all duration-300 z-20 shadow-xl">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-slate-800 px-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 w-full justify-center">
                    <svg class="w-8 h-8 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span x-show="sidebarOpen" class="font-bold text-xl text-white tracking-tight whitespace-nowrap overflow-hidden transition-opacity">BusTiket</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-cyan-400' : 'text-slate-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span x-show="sidebarOpen">Overview</span>
                </a>

                <!-- Kelola Bus -->
                <a href="{{ route('admin.buses.index') }}" class="{{ request()->routeIs('admin.buses.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.buses.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span x-show="sidebarOpen">Kelola Bus</span>
                </a>

                <!-- Kelola Rute -->
                <a href="{{ route('admin.routes.index') }}" class="{{ request()->routeIs('admin.routes.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.routes.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span x-show="sidebarOpen">Kelola Rute</span>
                </a>

                <!-- Kelola Jadwal -->
                <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.schedules.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen">Kelola Jadwal</span>
                </a>

                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 px-3 mt-6">Transaksi</div>
                
                <!-- Pesan Tiket Manual -->
                <a href="{{ route('admin.bookings.create') }}" class="{{ request()->routeIs('admin.bookings.create') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.bookings.create') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span x-show="sidebarOpen">Pesan Tiket (Offline)</span>
                </a>

                <!-- Pemesanan -->
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.index') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.bookings.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span x-show="sidebarOpen">Pemesanan</span>
                </a>

                <!-- Pembayaran -->
                <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition group relative">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.payments.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @php
                        $pendingPaymentsCount = \App\Models\Payment::whereIn('status', ['menunggu', 'pending'])->count();
                    @endphp
                    <span x-show="sidebarOpen">Pembayaran</span>
                    @if($pendingPaymentsCount > 0)
                    <span x-show="sidebarOpen" class="absolute right-3 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingPaymentsCount }}</span>
                    @endif
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-slate-400 hover:text-red-400 transition" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen" class="font-medium">Logout Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 rounded p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <h2 class="font-semibold text-lg text-slate-800">
                        @yield('header', 'Admin Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-sm text-right">
                        <div class="font-bold text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</div>
                        <div class="text-slate-500 text-xs">Super Admin</div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">
                        A
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
