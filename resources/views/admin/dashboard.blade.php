@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Monitoring sistem dan status training model AI')

@section('content')
<div class="space-y-6">

    {{-- HEADER STATS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Total Produk', 'value' => $totalProduk, 'color' => 'text-slate-900', 'icon' => '📦'],
                ['label' => 'Siap Deteksi', 'value' => $produkSiapDeteksi, 'color' => 'text-green-600', 'icon' => '🎯'],
                ['label' => 'Akun Owner', 'value' => $totalOwner, 'color' => 'text-amber-500', 'icon' => '👤'],
                ['label' => 'Deteksi Hari Ini', 'value' => $deteksiHariIni, 'color' => 'text-blue-600', 'icon' => '⚡'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $s['label'] }}</span>
                <span class="text-lg">{{ $s['icon'] }}</span>
            </div>
            <p class="text-3xl font-black {{ $s['color'] }} font-mono">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CHART SECTION --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest mb-6">Distribusi Training AI</h3>
            <div class="relative h-64 flex justify-center items-center">
                <canvas id="statusAiChart"></canvas>
            </div>
        </div>

        {{-- LIST STATUS --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Rincian Status Model</h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">Real-time</span>
            </div>
            
            <div class="p-6 space-y-4">
                @php
                    $statusList = [
                        'belum_dilatih'   => ['label' => 'Belum Dilatih',   'color' => 'bg-red-500',   'text' => 'text-red-700'],
                        'proses_training' => ['label' => 'Proses Training', 'color' => 'bg-amber-500', 'text' => 'text-amber-700'],
                        'siap_deteksi'    => ['label' => 'Siap Deteksi',    'color' => 'bg-green-500', 'text' => 'text-green-700'],
                    ];
                @endphp

                @foreach ($statusList as $key => $s)
                <div class="group flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-white hover:border-slate-200 border border-transparent transition-all">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 rounded-full {{ $s['color'] }}"></span>
                        <span class="text-sm font-bold text-slate-700">{{ $s['label'] }}</span>
                    </div>
                    <span class="text-lg font-black text-slate-900">{{ $statusAi[$key] ?? 0 }}</span>
                </div>
                @endforeach

                {{-- Progress Bar yang lebih estetis --}}
                @php $total = ($statusAi['belum_dilatih'] ?? 0) + ($statusAi['proses_training'] ?? 0) + ($statusAi['siap_deteksi'] ?? 0); @endphp
                @if ($total > 0)
                <div class="pt-4">
                    <div class="flex w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="bg-red-500" style="width:{{ (($statusAi['belum_dilatih'] ?? 0) / $total) * 100 }}%"></div>
                        <div class="bg-amber-500" style="width:{{ (($statusAi['proses_training'] ?? 0) / $total) * 100 }}%"></div>
                        <div class="bg-green-500" style="width:{{ (($statusAi['siap_deteksi'] ?? 0) / $total) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('statusAiChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Dilatih', 'Proses Training', 'Siap Deteksi'],
            datasets: [{
                data: [{{ $statusAi['belum_dilatih'] ?? 0 }}, {{ $statusAi['proses_training'] ?? 0 }}, {{ $statusAi['siap_deteksi'] ?? 0 }}],
                backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
        }
    });
</script>
@endphp