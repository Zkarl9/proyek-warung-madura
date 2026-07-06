@extends('layouts.app')

@section('title', 'Kamera Live')
@section('page-title', 'Kamera Live')
@section('page-subtitle', 'Streaming deteksi YOLOv8 dari Raspberry Pi 5')

@section('content')
<div class="space-y-4">

    {{-- NOTIFIKASI --}}
    @if(session('status'))
    <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg md:text-2xl font-bold">📷 Kamera Live</h2>
                <p class="text-blue-100 text-sm mt-0.5">Raspberry Pi 5 — Camera Module V3</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                <span class="text-sm font-semibold">LIVE</span>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Status Perangkat</p>
            <div class="mt-2">
                @if ($statusPerangkat === 'online')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span> Online
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Offline
                </span>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Deteksi Hari Ini</p>
            <p class="text-3xl font-bold text-slate-900 mt-1 font-mono">{{ $deteksiHariIni }}</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">FPS Rata-rata</p>
            <p class="text-3xl font-bold text-slate-900 mt-1 font-mono">3–7</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Model AI</p>
            <p class="text-3xl font-bold text-slate-900 mt-1 font-mono">v8s</p>
        </div>
    </div>

    {{-- STREAM + AKTIVITAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Stream + Kontrol --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Video Stream --}}
            <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
                <div class="relative bg-slate-900">
                    <span class="absolute top-3 left-3 z-10 inline-flex items-center gap-1.5 px-2.5 py-1 bg-black/60 text-white text-xs font-bold rounded-full">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span> REC
                    </span>
                    <img src="http://192.168.0.193:5000/stream.mjpg"
                         alt="Stream kamera deteksi"
                         class="w-full aspect-video object-contain bg-slate-900"
                         onerror="this.style.display='none'; document.getElementById('stream-error').style.display='flex';">
                    <div id="stream-error"
                         class="hidden w-full aspect-video bg-slate-900 items-center justify-center flex-col gap-2 text-slate-400">
                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                        </svg>
                        <p class="text-sm">Stream tidak tersedia — periksa koneksi Raspberry Pi</p>
                    </div>
                </div>
            </div>

            {{-- Kontrol --}}
            <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Kontrol Perangkat</p>
                <div class="flex flex-wrap gap-3">
                    {{-- Start/Stop Deteksi --}}
                    <form action="{{ route('owner.camera.start') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Jalankan Deteksi AI
                        </button>
                    </form>
                    <form action="{{ route('owner.camera.stop') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            Hentikan Deteksi AI
                        </button>
                    </form>

                    {{-- BARU: Ambil Dataset Kamera --}}
                    <button type="button" id="btn-ambil-dataset"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Ambil Dataset (50 Foto)
                    </button>
                </div>

                {{-- BARU: Progress dataset --}}
                <div id="dataset-progress-wrap" class="hidden mt-4">
                    <div class="flex items-center justify-between mb-1">
                        <span id="dataset-progress-text" class="text-xs font-medium text-slate-600">Mengambil foto...</span>
                        <span id="dataset-progress-pct" class="text-xs font-mono text-slate-500">0%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div id="dataset-progress-bar" class="h-full bg-indigo-600 transition-all duration-300" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- Deteksi Real-time per Produk --}}
            <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Deteksi Saat Ini</h3>
                    <span id="deteksi-count" class="text-xs text-slate-400">0 produk</span>
                </div>
                <div id="deteksi-list" class="divide-y divide-slate-100">
                    <div class="px-5 py-10 text-center text-sm text-slate-400">
                        Menunggu data deteksi...
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Aktivitas Terbaru</h3>
                <span class="text-xs text-slate-400">{{ $aktivitasTerbaru->count() }} item</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($aktivitasTerbaru as $deteksi)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $deteksi->product->nama_produk ?? '—' }}</p>
                        <p class="text-xs text-slate-400">{{ $deteksi->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="flex-shrink-0 ml-2 text-sm font-mono font-bold text-red-500">
                        -{{ $deteksi->jumlah }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-sm text-slate-400">
                    Belum ada deteksi otomatis tercatat.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- LEGENDA --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Legenda Deteksi</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600">Stok aman</span>
                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">✓ Aman</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600">Stok menipis</span>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">⚠ Stok Menipis</span>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const FLASK_BASE_URL       = 'http://192.168.0.193:5000';
    const FLASK_DETECTIONS_URL = `${FLASK_BASE_URL}/detections`;
    const FLASK_CAPTURE_URL    = `${FLASK_BASE_URL}/capture-dataset`;
    const FLASK_DATASET_STATUS_URL = `${FLASK_BASE_URL}/dataset-status`;

    const listEl  = document.getElementById('deteksi-list');
    const countEl = document.getElementById('deteksi-count');

    function badgeClass(status) {
        return status === 'Aman'
            ? 'bg-green-100 text-green-700'
            : 'bg-amber-100 text-amber-700';
    }

    function badgeIcon(status) {
        return status === 'Aman' ? '✓' : '⚠';
    }

    function render(detections) {
        countEl.textContent = `${detections.length} produk`;

        if (!detections.length) {
            listEl.innerHTML = `
                <div class="px-5 py-10 text-center text-sm text-slate-400">
                    Tidak ada produk terdeteksi saat ini.
                </div>`;
            return;
        }

        listEl.innerHTML = detections.map(d => `
            <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">${d.product_label}</p>
                    <p class="text-xs text-slate-400">${d.count} pcs terdeteksi</p>
                </div>
                <span class="flex-shrink-0 ml-2 px-2.5 py-1 text-xs font-bold rounded-full ${badgeClass(d.status)}">
                    ${badgeIcon(d.status)} ${d.status}
                </span>
            </div>
        `).join('');
    }

    async function fetchDetections() {
        try {
            const res  = await fetch(FLASK_DETECTIONS_URL, { cache: 'no-store' });
            const data = await res.json();
            render(data.detections || []);
        } catch (err) {
            listEl.innerHTML = `
                <div class="px-5 py-10 text-center text-sm text-red-400">
                    Gagal ambil data deteksi dari Raspberry Pi.
                </div>`;
        }
    }

    fetchDetections();
    setInterval(fetchDetections, 3000); // polling tiap 3 detik

    // ================= BARU: Ambil Dataset =================
    const btnAmbilDataset   = document.getElementById('btn-ambil-dataset');
    const progressWrap      = document.getElementById('dataset-progress-wrap');
    const progressBar       = document.getElementById('dataset-progress-bar');
    const progressPct       = document.getElementById('dataset-progress-pct');
    const progressText      = document.getElementById('dataset-progress-text');

    let datasetPollInterval = null;

    async function pollDatasetStatus() {
        try {
            const res  = await fetch(FLASK_DATASET_STATUS_URL, { cache: 'no-store' });
            const data = await res.json();

            const pct = data.total > 0 ? Math.round((data.progress / data.total) * 100) : 0;
            progressBar.style.width = `${pct}%`;
            progressPct.textContent = `${pct}%`;
            progressText.textContent = data.message || '...';

            if (!data.running) {
                clearInterval(datasetPollInterval);
                btnAmbilDataset.disabled = false;
                btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
                setTimeout(() => progressWrap.classList.add('hidden'), 3000);
            }
        } catch (err) {
            progressText.textContent = 'Gagal cek status dataset dari Raspberry Pi.';
        }
    }

    btnAmbilDataset.addEventListener('click', async () => {
        btnAmbilDataset.disabled = true;
        btnAmbilDataset.classList.add('opacity-50', 'cursor-not-allowed');
        progressWrap.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressPct.textContent = '0%';
        progressText.textContent = 'Memulai pengambilan foto...';

        try {
            const res  = await fetch(FLASK_CAPTURE_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ jumlah: 50 }),
            });
            const data = await res.json();

            if (!data.ok) {
                progressText.textContent = data.message || 'Gagal memulai pengambilan dataset.';
                btnAmbilDataset.disabled = false;
                btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            datasetPollInterval = setInterval(pollDatasetStatus, 1000);
        } catch (err) {
            progressText.textContent = 'Gagal terhubung ke Raspberry Pi.';
            btnAmbilDataset.disabled = false;
            btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
})();
</script>
@endpush