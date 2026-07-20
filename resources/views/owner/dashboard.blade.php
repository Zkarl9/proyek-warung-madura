@extends('layouts.app')
 
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan stok warung hari ini')
 
@section('content')
<div class="space-y-6">
 
    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-3xl font-black tracking-tight flex items-center gap-2">
                    <span>📊</span> Dashboard Pemilik
                </h2>
                <p class="text-slate-300 text-xs md:text-sm mt-1 font-medium bg-slate-800/40 inline-block px-3 py-1 rounded-lg backdrop-blur-sm border border-white/5">
                    📅 {{ now()->translatedFormat('l, d M Y') }}
                </p>
            </div>
        </div>
    </div>
 
    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-500 transition-colors">Total Produk</p>
                <span class="text-lg p-2 bg-slate-50 rounded-xl group-hover:bg-slate-100 transition-colors">📦</span>
            </div>
            <p class="text-2xl md:text-4xl font-black text-slate-800 mt-2 tracking-tight">{{ $totalProduk }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Produk terdaftar aktif</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-500 transition-colors">Barang Habis</p>
                <span class="text-lg p-2 {{ $barangHabis->count() > 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-xl transition-colors">⚠️</span>
            </div>
            <p class="text-2xl md:text-4xl font-black mt-2 tracking-tight {{ $barangHabis->count() > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                {{ $barangHabis->count() }}
            </p>
            <p class="text-xs font-medium text-slate-400 mt-1">Perlu restock segera</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-500 transition-colors">Terjual (7 Hari)</p>
                <span class="text-lg p-2 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">📈</span>
            </div>
            <p class="text-2xl md:text-4xl font-black text-blue-600 mt-2 tracking-tight font-mono">{{ $grafikMingguan->sum('total') }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Item keluar seminggu</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-500 transition-colors">Omzet Hari Ini</p>
                <span class="text-lg p-2 bg-emerald-50 rounded-xl group-hover:bg-emerald-100 transition-colors">💰</span>
            </div>
            <p class="text-xl md:text-3xl font-black text-emerald-600 mt-2 tracking-tight font-mono">Rp{{ number_format($omzetHariIni, 0, ',', '.') }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Dari barang yang terjual</p>
        </div>
    </div>
 
    {{-- STOK TIPIS + GRAFIK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 
        {{-- Stok Tipis (SCROLLABLE) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[380px]">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Peringatan Stok</h3>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">{{ $barangHabis->count() }} Produk</span>
            </div>
            <div class="p-5 overflow-y-auto flex-1 scrollbar-thin">
                @if ($barangHabis->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-xl">✅</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Semua Stok Aman!</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tidak ada produk kritis.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($barangHabis as $produk)
                        <div class="flex items-center justify-between gap-2 py-1.5 border-b border-slate-50 last:border-0">
                            <span class="text-sm font-semibold text-slate-700 truncate" title="{{ $produk->nama_produk }}">
                                {{ $produk->nama_produk }}
                            </span>
                            <span class="text-xs px-2.5 py-0.5 rounded-lg font-mono font-bold flex-shrink-0 shadow-sm bg-rose-50 text-rose-600 border border-rose-100">
                                🔴 Tidak Ada
                            </span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
 
        {{-- Grafik Mingguan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[380px]">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center gap-2 bg-slate-50/50 flex-shrink-0">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Tren Barang Keluar (7 Hari Terakhir)</h3>
            </div>
            <div class="p-5 flex-1 flex items-center justify-center">
                <div class="w-full h-full min-h-[260px]">
                    <canvas id="grafikMingguan"></canvas>
                </div>
            </div>
        </div>
    </div>
 
    {{-- BARANG TERLARIS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-4 flex items-center gap-2 bg-slate-50/50">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">🏆 Produk Terlaris</h3>
        </div>
        <div class="p-5">
            @if ($barangTerlaris->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-slate-400">Belum ada rekaman data penjualan terlaris.</p>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php $maks = $barangTerlaris->max('total_terjual'); @endphp
                @foreach ($barangTerlaris as $index => $item)
                <div class="flex items-center gap-3.5 p-3 rounded-xl border border-slate-50 bg-slate-50/30 hover:bg-slate-50 hover:border-slate-100 transition duration-200">
                    <span class="flex-shrink-0 w-7 h-7 rounded-xl font-bold flex items-center justify-center text-sm shadow-sm
                        {{ $index == 0 ? 'bg-amber-100 text-amber-700 ring-2 ring-amber-200' : ($index == 1 ? 'bg-slate-200 text-slate-700' : ($index == 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-500')) }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1 gap-2">
                            <span class="text-sm font-bold text-slate-700 truncate">{{ $item->product->nama_produk ?? '—' }}</span>
                            <span class="text-sm font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">{{ $item->total_terjual }}x</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-500"
                                 style="width: {{ $maks > 0 ? ($item->total_terjual / $maks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
 
    {{-- LOG AKTIVITAS (SCROLLABLE) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[420px]">
        <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Log Pergerakan Stok</h3>
            </div>
            <span class="text-xs font-semibold text-slate-400">Terbaru</span>
        </div>
        <div class="divide-y divide-slate-100/80 overflow-y-auto flex-1 scrollbar-thin">
            @forelse ($recentSales as $sale)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/70 transition-all duration-150">
                <div class="min-w-0 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold flex-shrink-0 text-sm
                        {{ $sale->tipe === 'otomatis' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $sale->tipe === 'otomatis' ? '🤖' : '✋' }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $sale->product->nama_produk ?? '—' }}</p>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $sale->created_at->format('d M Y • H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-3 flex-shrink-0">
                    <span class="text-sm font-mono font-extrabold text-rose-500 bg-rose-50/60 px-2 py-0.5 rounded-md border border-rose-100">-{{ $sale->jumlah }}</span>
                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg shadow-sm tracking-wide border
                        {{ $sale->tipe === 'otomatis' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                        {{ $sale->tipe === 'otomatis' ? 'DETEKSI YOLO' : 'MANUAL' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-12 text-center text-sm text-slate-400 my-auto">
                <div class="text-2xl mb-2">📥</div>
                <p class="font-medium text-slate-500">Belum ada aktivitas baru</p>
                <p class="text-xs text-slate-400 mt-0.5">Semua data pergerakan stok akan tampil di sini.</p>
            </div>
            @endforelse
        </div>
    </div>
 
</div>
 
{{-- Kustomisasi Scrollbar Minimalis --}}
<style>
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
</style>
@endsection
 
@push('scripts')
<script>
    // 💡 PENYELESAIAN ERROR DI SINI: Kode inisialisasi Chart dibungkus DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        const canvasElement = document.getElementById('grafikMingguan');
        if (canvasElement) {
            const ctx = canvasElement.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');
 
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($grafikMingguan->pluck('tanggal')->map(fn ($t) => \Carbon\Carbon::parse($t)->translatedFormat('d M'))) !!},
                    datasets: [{
                        label: 'Barang Keluar',
                        data: {!! json_encode($grafikMingguan->pluck('total')) !!},
                        borderColor: '#2563eb',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        tension: 0.38,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#2563eb',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#2563eb',
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            padding: 10,
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { color: '#94a3b8', font: { weight: 500 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { weight: 500 } }
                        },
                    }
                }
            });
        }
    });
</script>
@endpush
