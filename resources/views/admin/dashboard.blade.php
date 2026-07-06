@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem & status training AI')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <h2 class="text-lg md:text-2xl font-bold">🛠️ Panel Admin</h2>
        <p class="text-blue-100 text-sm mt-0.5">Ringkasan sistem & status training AI — {{ now()->format('d M Y') }}</p>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Produk</p>
            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $totalProduk }}</p>
            <p class="text-xs text-slate-400 mt-1">produk terdaftar</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Siap Deteksi</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $produkSiapDeteksi }}</p>
            <p class="text-xs text-slate-400 mt-1">model terlatih</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Akun Owner</p>
            <p class="text-3xl font-bold text-amber-500 mt-1">{{ $totalOwner }}</p>
            <p class="text-xs text-slate-400 mt-1">pengguna aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Deteksi Hari Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-1 font-mono">{{ $deteksiHariIni }}</p>
            <p class="text-xs text-slate-400 mt-1">item terdeteksi</p>
        </div>
    </div>

    {{-- CHART + DETAIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Donut Chart --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Status Training</h3>
            </div>
            <div class="p-5 flex justify-center items-center">
                <div style="width:220px; height:220px;">
                    <canvas id="statusAiChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Rincian Status --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Rincian Status Model</h3>
            </div>
            <div class="p-5 space-y-3">
                @php
                    $statusList = [
                        'belum_dilatih'   => ['label' => 'Belum Dilatih',    'color' => 'bg-red-100 text-red-700',   'dot' => 'bg-red-500'],
                        'proses_training' => ['label' => 'Proses Training',  'color' => 'bg-amber-100 text-amber-700','dot' => 'bg-amber-500'],
                        'siap_deteksi'    => ['label' => 'Siap Deteksi',     'color' => 'bg-green-100 text-green-700','dot' => 'bg-green-500'],
                    ];
                @endphp
                @foreach ($statusList as $key => $s)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="h-3 w-3 rounded-full flex-shrink-0 {{ $s['dot'] }}"></span>
                        <span class="text-sm font-semibold text-slate-700">{{ $s['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold font-mono text-slate-900">{{ $statusAi[$key] ?? 0 }}</span>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $s['color'] }}">produk</span>
                    </div>
                </div>
                @endforeach

                {{-- Progress bar --}}
                @php 
                    // PERBAIKAN: Menggunakan method sum() dari Laravel Collection jika variabel tersedia
                    $total = isset($statusAi) ? $statusAi->sum() : 0; 
                @endphp
                
                @if ($total > 0)
                <div class="mt-2">
                    <div class="flex rounded-full overflow-hidden h-2.5">
                        <div class="bg-red-400 transition-all" style="width:{{ (($statusAi['belum_dilatih'] ?? 0) / $total) * 100 }}%"></div>
                        <div class="bg-amber-400 transition-all" style="width:{{ (($statusAi['proses_training'] ?? 0) / $total) * 100 }}%"></div>
                        <div class="bg-green-500 transition-all" style="width:{{ (($statusAi['siap_deteksi'] ?? 0) / $total) * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5 text-right">Total {{ $total }} produk</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    new Chart(document.getElementById('statusAiChart'), {
        type: 'doughnut',
        data: {
            labels: ['Belum Dilatih', 'Proses Training', 'Siap Deteksi'],
            datasets: [{
                data: [
                    {{ $statusAi['belum_dilatih'] ?? 0 }},
                    {{ $statusAi['proses_training'] ?? 0 }},
                    {{ $statusAi['siap_deteksi'] ?? 0 }},
                ],
                backgroundColor: ['#f87171', '#fbbf24', '#22c55e'],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '72%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, padding: 16, font: { size: 12 } }
                }
            }
        }
    });
</script>
@endpush