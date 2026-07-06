<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StockVision') · Warung Madura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #sidebar { transition: transform 0.25s ease; }
        #overlay { transition: opacity 0.25s ease; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-900 antialiased">

@auth
<div class="min-h-screen flex">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- OVERLAY (mobile) --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div id="overlay"
         class="fixed inset-0 bg-black/50 z-30 hidden opacity-0 lg:hidden"
         onclick="closeSidebar()"></div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- SIDEBAR --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-64 bg-white border-r border-slate-200 z-40 flex flex-col shadow-xl
                  -translate-x-full lg:translate-x-0">

        {{-- Logo (Disamakan dengan Owner: Biru + Inisial SV) --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="h-9 w-9 bg-white rounded-lg flex items-center justify-center font-bold text-blue-600 text-sm flex-shrink-0">
                SV
            </div>
            <div class="min-w-0">
                <h1 class="text-base font-bold text-white leading-tight">StockVision</h1>
            </div>
            {{-- Tutup sidebar (mobile) --}}
            <button onclick="closeSidebar()"
                    class="ml-auto lg:hidden text-white/70 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
            @include(auth()->user()->isAdmin() ? 'layouts.admin_sidebar' : 'layouts.owner_sidebar')
        </nav>

        {{-- User + Logout --}}
        <div class="p-3 border-t border-slate-200">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->isAdmin() ? 'Administrator' : 'Owner Store' }}
                    </p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MAIN --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 px-4 md:px-6 h-16">

                {{-- Hamburger (mobile) --}}
                <button onclick="openSidebar()"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg hover:bg-slate-100 transition text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Page title --}}
                <div class="min-w-0 flex-1">
                    <h2 class="text-base md:text-lg font-bold text-slate-900 truncate">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    @hasSection('page-subtitle')
                    <p class="text-xs text-slate-400 truncate hidden sm:block">@yield('page-subtitle')</p>
                    @endif
                </div>

               {{-- Status kamera --}}
                @php
                $kameraAktif = \App\Support\RaspiStatus::isOnline();
                @endphp
                <div class="flex items-center gap-1.5 text-xs flex-shrink-0">
                     <span class="h-2 w-2 rounded-full flex-shrink-0
                      {{ $kameraAktif ? 'bg-green-500 animate-pulse' : 'bg-red-400' }}"></span>
                     <span class="hidden sm:inline font-medium
                      {{ $kameraAktif ? 'text-green-600' : 'text-red-500' }}">
                      {{ $kameraAktif ? 'Kamera Aktif' : 'Kamera Offline' }}
                     </span>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 p-4 md:p-6">
            @if (session('status'))
            <div class="mb-4 flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-4 flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

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
        setTimeout(() => overlay.classList.add('hidden'), 250);
    }
</script>

@else
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>