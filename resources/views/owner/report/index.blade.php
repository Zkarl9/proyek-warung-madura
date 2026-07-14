@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Rekap rekayasa stok masuk dan keluar per periode — Terintegrasi AI')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER LAPORAN CONSOLE --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-2xl p-5 md:p-6 text-white shadow-xl">
        <div class="absolute -right-6 -top-10 w-44 h-44 rounded-full bg-blue-500/10 blur-xl pointer-events-none"></div>
        <div class="absolute right-20 -bottom-12 w-28 h-28 rounded-full bg-indigo-500/10 blur-xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>📊</span> Laporan Log Analitik Stok
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-semibold mt-1">
                    Rentang Waktu: <span class="text-white border-b border-blue-400 font-mono font-black">{{ $dariTanggal->format('d M Y') }}</span> — <span class="text-white border-b border-blue-400 font-mono font-black">{{ $sampaiTanggal->format('d M Y') }}</span>
                </p>
            </div>
            <a href="{{ route('owner.report.cetak', request()->query()) }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-900 font-black px-5 py-2.5 rounded-xl shadow-md transition duration-150 active:scale-95 text-xs uppercase tracking-wider self-start sm:self-auto border border-slate-200">
                🖨️ Cetak Dokumen
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FILTER PARAMETER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 space-y-4">
        
        {{-- Preset Rentang Cepat --}}
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Preset Waktu Cepat</p>
            <div class="flex flex-wrap gap-2">
                @php
                    $presets = [
                        'hari_ini'   => 'Hari Ini',
                        'minggu_ini' => 'Minggu Ini',
                        'bulan_ini'  => 'Bulan Ini',
                        'bulan_lalu' => 'Bulan Lalu',
                    ];
                @endphp
                @foreach ($presets as $key => $label)
                <a href="{{ route('owner.report.index', array_merge(request()->except(['dari','sampai','preset']), ['preset' => $key])) }}"
                   class="px-3.5 py-1.5 text-xs font-black rounded-xl border transition duration-150 shadow-sm
                          {{ request('preset') === $key
                                ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-blue-600 shadow-blue-600/10'
                                : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Form Kustom Penyaringan --}}
        <form id="formFilterLaporan" method="GET" action="{{ route('owner.report.index') }}"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-100">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" id="dariTanggalInput" name="dari" value="{{ $dariTanggal->toDateString() }}"
                       class="w-full px-3.5 py-2.5 border-2 border-slate-200 focus:border-blue-500 rounded-xl focus:outline-none text-sm font-bold text-slate-700 font-mono bg-slate-50/50 transition">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" id="sampaiTanggalInput" name="sampai" value="{{ $sampaiTanggal->toDateString() }}"
                       class="w-full px-3.5 py-2.5 border-2 border-slate-200 focus:border-blue-500 rounded-xl focus:outline-none text-sm font-bold text-slate-700 font-mono bg-slate-50/50 transition">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter Spesifikasi Produk</label>
                <select name="product_id"
                        class="w-full px-3.5 py-2.5 border-2 border-slate-200 focus:border-blue-500 rounded-xl focus:outline-none text-sm font-bold text-slate-700 bg-slate-50/50 transition">
                    <option value="">Semua Komoditas Produk</option>
                    @foreach ($products as $produk)
                        <option value="{{ $produk->id }}" @selected((string) $productId === (string) $produk->id)>
                            {{ $produk->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-black text-xs uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-blue-700/20">
                    🔍 Cari
                </button>
            </div>
        </form>
        <p id="errorTanggal" class="hidden text-xs font-bold text-rose-600 animate-pulse bg-rose-50 border border-rose-100 p-2.5 rounded-xl">⚠ Deviasi Validasi: Matriks tanggal "Dari" dilarang melampaui ambang batas batas tanggal "Sampai".</p>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- RINGKASAN METRIKS UTAMA --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 flex items-center gap-4 transition duration-200 hover:border-emerald-400">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-inner">📥</div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Stok Masuk</p>
                <p class="text-2xl font-black text-emerald-600 font-mono tracking-tight mt-0.5">{{ $totalMasuk }}</p>
                <p class="text-[10px] font-bold text-slate-400 mt-0.5 font-mono">{{ $stockIn->count() }} Transaksi</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 flex items-center gap-4 transition duration-200 hover:border-rose-400">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shadow-inner">📤</div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Stok Keluar</p>
                <p class="text-2xl font-black text-rose-600 font-mono tracking-tight mt-0.5">{{ $totalKeluar }}</p>
                <p class="text-[10px] font-bold text-slate-400 mt-0.5 font-mono">{{ $stockOut->count() }} Transaksi</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- GRAFIK TREN & DIAGRAM PRODUK POPULER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Visualisasi Garis Kontrol Log Tren Harian --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Grafik Mutasi Stok Harian</h3>
            </div>
            <div class="p-5">
                <canvas id="grafikTrenLaporan" height="135"></canvas>
            </div>
        </div>

        {{-- Klasifikasi Komoditas Paling Sering Keluar --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Produk Paling Sering Keluar</h3>
            </div>
            <div class="p-5 h-full max-h-[350px] overflow-y-auto scrollbar-thin">
                @if ($produkTerlaris->isEmpty())
                    <div class="text-center py-12 text-slate-400 font-medium text-sm">
                        <p>Nihil representasi data kelayakan untuk dipetakan.</p>
                    </div>
                @else
                    @php $maksTerlaris = $produkTerlaris->max('total'); @endphp
                    <div class="space-y-4">
                        @foreach ($produkTerlaris as $i => $item)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-extrabold text-slate-800 truncate flex items-center gap-2">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-md bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-black flex items-center justify-center font-mono">{{ $i + 1 }}</span>
                                    {{ $item['nama_produk'] }}
                                </span>
                                <span class="text-xs font-black font-mono text-slate-900 bg-slate-100 px-2 py-0.5 rounded-md ml-2 flex-shrink-0">{{ $item['total'] }} {{ $item['satuan'] }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden p-0.5 ring-1 ring-slate-200/50">
                                <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-orange-500 shadow-sm transition-all duration-500" style="width: {{ $maksTerlaris > 0 ? ($item['total'] / $maksTerlaris) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- RINCIAN LOG MUTASI (MASUK / KELUAR) --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Panel Aliran Stok Masuk --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2 flex-shrink-0">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Log Rincian Pemasukan Barang</h3>
                <span class="ml-auto px-2.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold font-mono rounded-lg">{{ $stockIn->count() }} Baris</span>
            </div>

            <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto scrollbar-thin flex-1">
                @forelse ($stockIn as $item)
                <div class="flex items-center gap-4 px-5 py-3.5 border-l-4 border-transparent hover:border-emerald-500 hover:bg-slate-50 transition duration-150">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">{{ $item->product->nama_produk ?? '—' }}</p>
                        <p class="text-[10px] text-slate-400 font-bold font-mono mt-0.5">📆 {{ $item->created_at->format('d M Y, H:i') }} WIB · <span class="text-slate-500">{{ $item->sumber }}</span></p>
                        @if ($item->keterangan)
                            <p class="text-xs text-slate-500 mt-1 italic bg-slate-50 p-1.5 rounded-lg border border-dashed border-slate-200">"{!! nl2br(e($item->keterangan)) !!}"</p>
                        @endif
                    </div>
                    <span class="flex-shrink-0 px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-black font-mono rounded-lg shadow-sm">
                        +{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-16 text-center text-slate-400 my-auto">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m-7 4h8a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-3.414-3.414A1 1 0 0013.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs font-bold text-slate-500">Nihil data mutasi masuk terdeteksi dalam cakupan rentang.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Panel Aliran Stok Keluar --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2 flex-shrink-0">
                <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Log Rincian Pengeluaran Stok</h3>
                <span class="ml-auto px-2.5 py-0.5 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold font-mono rounded-lg">{{ $stockOut->count() }} Baris</span>
            </div>

            <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto scrollbar-thin flex-1">
                @forelse ($stockOut as $item)
                <div class="flex items-center gap-4 px-5 py-3.5 border-l-4 border-transparent hover:border-rose-500 hover:bg-slate-50 transition duration-150">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">{{ $item->product->nama_produk ?? '—' }}</p>
                        <p class="text-[10px] text-slate-400 font-bold font-mono mt-0.5">
                            📆 {{ $item->created_at->format('d M Y, H:i') }} WIB · 
                            <span class="px-1.5 py-0.2 rounded font-black uppercase text-[9px] {{ $item->tipe === 'otomatis' ? 'bg-violet-50 text-violet-600 border border-violet-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                {{ $item->tipe === 'otomatis' ? '🤖 AI Vision' : '✋ Manual' }}
                            </span>
                        </p>
                        @if ($item->keterangan)
                            <p class="text-xs text-slate-500 mt-1 italic bg-slate-50 p-1.5 rounded-lg border border-dashed border-slate-200">"{!! nl2br(e($item->keterangan)) !!}"</p>
                        @endif
                    </div>
                    <span class="flex-shrink-0 px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-black font-mono rounded-lg shadow-sm">
                        -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-16 text-center text-slate-400 my-auto">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m-7 4h8a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-3.414-3.414A1 1 0 0013.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs font-bold text-slate-500">Nihil data mutasi keluar terdeteksi dalam cakupan rentang.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    // ══════════════════════════════════════════════════
    // INTERSEPSI VALIDASI FORM TANGGAL
    // ══════════════════════════════════════════════════
    const form = document.getElementById('formFilterLaporan');
    const dariInput = document.getElementById('dariTanggalInput');
    const sampaiInput = document.getElementById('sampaiTanggalInput');
    const errorEl = document.getElementById('errorTanggal');

    if (form) {
        form.addEventListener('submit', function (e) {
            if (dariInput.value && sampaiInput.value && dariInput.value > sampaiInput.value) {
                e.preventDefault();
                errorEl.classList.remove('hidden');
            } else {
                errorEl.classList.add('hidden');
            }
        });
    }

    // ══════════════════════════════════════════════════
    // INSTANSIASI CHART JS: TREN MUTASI MUTASI BERKALA
    // ══════════════════════════════════════════════════
    const grafikData = @json($grafikHarian);

    const ctx = document.getElementById('grafikTrenLaporan');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: grafikData.map(d => {
                    const dt = new Date(d.tanggal);
                    return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                }),
                datasets: [
                    {
                        label: 'Pasokan Masuk',
                        data: grafikData.map(d => d.masuk),
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.04)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                    },
                    {
                        label: 'Komoditas Keluar',
                        data: grafikData.map(d => d.keluar),
                        borderColor: '#f43f5e', // rose-500
                        backgroundColor: 'rgba(244, 63, 94, 0.04)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f43f5e',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            boxWidth: 12, 
                            padding: 20, 
                            font: { size: 11, weight: 'bold' } 
                        } 
                    },
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10, weight: '600' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' } }
                    },
                },
            },
        });
    }
})();
</script>
@endpush
