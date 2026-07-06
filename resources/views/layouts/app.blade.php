<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StockVision') · Warung Madura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Injeksi global pustaka grafik Chart.js untuk menjamin visualisasi data dashboard --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #overlay { transition: opacity 0.3s ease; }
        .glass-panel { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(12px); }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-55 text-slate-900 antialiased selection:bg-blue-500 selection:text-white">

@auth
<div class="min-h-screen flex">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- OVERLAY LATAR (Mobile) --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div id="overlay"
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 hidden opacity-0 lg:hidden transition-all duration-300"
         onclick="closeSidebar()"></div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- SIDEBAR PREMIUM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-68 bg-white border-r border-slate-100 z-40 flex flex-col shadow-xl shadow-slate-100/70
                  -translate-x-full lg:translate-x-0">

        {{-- Logo Section (Ikon Grafis Lensa Kamera Cerdas & Bounding Box YOLO) --}}
        <div class="relative flex items-center gap-3.5 px-6 py-5 border-b border-slate-100 bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-950 overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>
            
            {{-- LOGO GRAPHIC ICON SVG --}}
            <div class="h-10 w-10 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-500/20 flex-shrink-0 ring-2 ring-white/10">
                <svg class="w-5 h-5 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V6a2 2 0 012-2h2M3 16v2a2 2 0 002 2h2M16 4h2a2 2 0 012 2v2M21 16v2a2 2 0 01-2 2h-2" />
                </svg>
            </div>

            <div class="min-w-0">
                <h1 class="text-base font-extrabold text-white tracking-tight leading-tight">StockVision</h1>
                <p class="text-[10px] text-blue-400 font-bold tracking-wider uppercase mt-0.5">Warung Madura v2.0</p>
            </div>
            
            {{-- Tombol Tutup (Hanya Tampil di Mobile) --}}
            <button onclick="closeSidebar()"
                    class="ml-auto lg:hidden text-slate-400 hover:text-white transition p-1.5 hover:bg-white/5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Area Navigasi Utama --}}
        <nav class="flex-1 px-4 py-5 space-y-0.5 overflow-y-auto scrollbar-none">
            @include(auth()->user()->isAdmin() ? 'layouts.admin_sidebar' : 'layouts.owner_sidebar')
        </nav>

        {{-- Profil Pengguna & Aksi Log Out (Panel Berbasis Glassmorphism) --}}
        <div class="p-4 border-t border-slate-100 glass-panel bg-gradient-to-t from-slate-50/80 to-white/50">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-white border border-slate-100 shadow-sm mb-3">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm flex-shrink-0 shadow-md shadow-blue-100">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-slate-800 truncate leading-none mb-1">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md tracking-wide">
                        {{ auth()->user()->isAdmin() ? 'ADMINISTRATOR' : 'OWNER STORE' }}
                    </span>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-100/50 rounded-xl text-sm font-bold transition duration-200 active:scale-[0.98] shadow-sm shadow-rose-50">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- AREA KONTEN UTAMA --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-h-screen lg:ml-68 transition-all duration-300">

        {{-- TOPBAR MINIMALIS --}}
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm shadow-slate-100/40">
            <div class="flex items-center gap-4 px-4 md:px-8 h-16">

                {{-- Hamburger Menu (Tampil di Mobile) --}}
                <button onclick="openSidebar()"
                        class="lg:hidden flex items-center justify-center w-10 h-10 border border-slate-200 bg-white rounded-xl hover:bg-slate-50 transition text-slate-600 shadow-sm active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Dinamis Judul Halaman --}}
                <div class="min-w-0 flex-1">
                    <h2 class="text-base md:text-xl font-black text-slate-800 tracking-tight leading-none">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    @hasSection('page-subtitle')
                    <p class="text-xs font-medium text-slate-400 truncate hidden sm:block mt-1">@yield('page-subtitle')</p>
                    @endif
                </div>

                {{-- Indikator Status Kamera IoT Terintegrasi --}}
                @php $kameraAktif = \App\Support\RaspiStatus::isOnline(); @endphp
                <div class="inline-flex items-center gap-2.5 px-3 py-1.5 bg-white border border-slate-100 rounded-xl shadow-sm flex-shrink-0">
                    <span class="relative flex h-2 w-2">
                        @if ($kameraAktif)
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        @else
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-400"></span>
                        @endif
                    </span>
                    <span class="hidden sm:inline font-bold text-xs tracking-wide
                        {{ $kameraAktif ? 'text-emerald-600' : 'text-rose-500' }}">
                        {{ $kameraAktif ? 'YOLO ONLINE' : 'YOLO OFFLINE' }}
                    </span>
                </div>
            </div>
        </header>

        {{-- CONTAINER UTAMA HALAMAN --}}
        <main class="flex-1 p-4 md:p-8 max-w-[1600px] w-full mx-auto">
            
            {{-- Flash Alert: Status Sukses --}}
            @if (session('status'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3.5 bg-emerald-50 border border-emerald-200/60 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm shadow-emerald-50/50 animate-fadeIn">
                <div class="p-1 bg-emerald-500 rounded-lg text-white">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                {{ session('status') }}
            </div>
            @endif

            {{-- Flash Alert: Status Gagal/Error --}}
            @if (session('error'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3.5 bg-rose-50 border border-rose-200/60 text-rose-800 rounded-2xl text-sm font-semibold shadow-sm shadow-rose-50/50 animate-fadeIn">
                <div class="p-1 bg-rose-500 rounded-lg text-white">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

{{-- Logika JavaScript Handler Sidebar Responsif --}}
<script>
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
</script>

@else
    {{-- Handler Render Layar Otentikasi / Tamu (Login Form) --}}
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>