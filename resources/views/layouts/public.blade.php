<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pemesanan Tiket Bus') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <span class="font-bold text-xl tracking-tight">BusTiket</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center sm:gap-6">
                    <a href="{{ route('home') }}" class="hover:text-cyan-400 transition">Beranda</a>
                    <a href="{{ route('schedules.search') }}" class="hover:text-cyan-400 transition">Cari Jadwal</a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-400 transition">Admin Panel</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="hover:text-cyan-400 transition">Tiket Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg text-sm font-semibold transition text-white">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-cyan-400 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition shadow-md shadow-cyan-500/30">Register</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-300 hover:text-white hover:bg-slate-700 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-800">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-base font-medium text-white hover:bg-slate-700">Beranda</a>
                <a href="{{ route('schedules.search') }}" class="block px-4 py-2 text-base font-medium text-white hover:bg-slate-700">Cari Jadwal</a>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-white hover:bg-slate-700">Admin Panel</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-base font-medium text-white hover:bg-slate-700">Tiket Saya</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-400 hover:bg-slate-700">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-white hover:bg-slate-700">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-cyan-400 hover:bg-slate-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span class="font-bold text-xl tracking-tight text-white">BusTiket</span>
                </a>
                <p class="text-sm">Platform pemesanan tiket bus antar kota terpercaya, nyaman, dan aman. Kami menghubungkan Anda ke berbagai destinasi dengan armada terbaik.</p>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Navigasi Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-cyan-400 transition">Beranda</a></li>
                    <li><a href="{{ route('schedules.search') }}" class="hover:text-cyan-400 transition">Cari Tiket</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-cyan-400 transition">Login</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Hubungi Kami</h3>
                <ul class="space-y-2 text-sm">
                    <li>WhatsApp: +62 812-3456-7890</li>
                    <li>Email: support@bustiket.com</li>
                    <li>Alamat: Jl. Terminal Baru No. 1, Kota Makassar</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-8 border-t border-slate-800 text-center text-sm">
            &copy; {{ date('Y') }} BusTiket. All rights reserved.
        </div>
    </footer>

</body>
</html>
