@extends('layouts.app')

@section('title', 'Kamera Live')
@section('page-title', 'Kamera Live')
@section('page-subtitle', 'Streaming deteksi YOLOv8 dari Raspberry Pi 5')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- NOTIFIKASI SYSTEM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if(session('status'))
    <div class="flex items-center gap-3 px-4 py-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm animate-fadeIn">
        <div class="p-1 bg-emerald-500 rounded-lg text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold shadow-sm animate-fadeIn">
        <div class="p-1 bg-rose-500 rounded-lg text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        {{ session('error') }}
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER CONSOLE --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>📷</span> Modul Kamera Live Vision
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">Stasiun Perangkat: Raspberry Pi 5 — Camera Module V3</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-500/20 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-black tracking-widest">
                <span class="h-2 w-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>LIVE FEED</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- GRID INDIKATOR TELEMETRI --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 group transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Perangkat</p>
            <div class="mt-2.5">
                @if ($statusPerangkat === 'online')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-lg shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span> ONLINE
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black rounded-lg shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-rose-500"></span> OFFLINE
                </span>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deteksi Hari Ini</p>
            <p class="text-2xl md:text-3xl font-black text-slate-800 mt-1.5 font-mono tracking-tight">{{ $deteksiHariIni }} <span class="text-xs font-sans text-slate-400 font-bold">item</span></p>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">FPS Rata-rata</p>
            <p class="text-2xl md:text-3xl font-black text-blue-600 mt-1.5 font-mono tracking-tight">3–7 <span class="text-xs font-sans text-slate-400 font-bold">Hz</span></p>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Arsitektur AI</p>
            <p class="text-2xl md:text-3xl font-black text-purple-600 mt-1.5 font-mono tracking-tight">YOLOv8s</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- LAYOUT UTAMA: STREAMING & CONSOLE LOGS --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- AREA STREAM DAN PANEL KONTROL (KOLOM KIRI & TENGAH) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Monitor Video Streaming --}}
            <div class="bg-slate-950 rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden relative group/stream">
                <div class="relative bg-slate-900 w-full aspect-video flex items-center justify-center">
                    <span class="absolute top-4 left-4 Regal-Z z-10 inline-flex items-center gap-1.5 px-3 py-1 bg-black/70 text-white text-[11px] font-black rounded-lg backdrop-blur-md border border-white/10">
                        <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span> STREAMING REC
                    </span>
                    
                    <img src="http://192.168.0.193:5000/stream.mjpg"
                         alt="Stream kamera deteksi"
                         class="w-full h-full object-contain bg-slate-950"
                         onerror="this.style.display='none'; document.getElementById('stream-error').style.setProperty('display', 'flex', 'important');">
                    
                    <div id="stream-error"
                         class="hidden absolute inset-0 bg-slate-950 flex items-center justify-center flex-col gap-3 text-slate-400 p-6 text-center">
                        <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-rose-500 border border-slate-800 shadow-inner">
                            <svg class="w-6 h-6 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-200">Koneksi Kamera Terputus</p>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs leading-relaxed">Stream tidak tersedia. Silakan periksa konfigurasi jaringan IP atau status server Flask di Raspberry Pi Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perangkat Pengendali Jarak Jauh (Remote Control) --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 space-y-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Panel Kontrol Perangkat Perangkat</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    <form action="{{ route('owner.camera.start') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-emerald-700/20">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Start Engine AI
                        </button>
                    </form>
                    
                    <form action="{{ route('owner.camera.stop') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-rose-700/20">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            Stop Engine AI
                        </button>
                    </form>

                    <button type="button" id="btn-ambil-dataset"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-blue-700/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Ambil 50 Dataset
                    </button>
                </div>

                {{-- Komponen Progres Unggah Dataset --}}
                <div id="dataset-progress-wrap" class="hidden mt-4 bg-slate-50 border border-slate-200 p-4 rounded-xl shadow-inner animate-fadeIn">
                    <div class="flex items-center justify-between mb-2">
                        <span id="dataset-progress-text" class="text-xs font-bold text-slate-600">Mengambil foto objek dataset...</span>
                        <span id="dataset-progress-pct" class="text-xs font-black font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                        <div id="dataset-progress-bar" class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300 shadow-sm" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- Deteksi Objek Real-time Hasil Kamera --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Antarmuka Monitor Deteksi Instan</h3>
                    </div>
                    <span id="deteksi-count" class="text-xs font-bold font-mono px-2.5 py-0.5 bg-slate-200 text-slate-600 rounded-lg">0 produk</span>
                </div>
                <div id="deteksi-list" class="divide-y divide-slate-100 font-medium">
                    <div class="px-5 py-12 text-center text-sm text-slate-400">
                        <span class="inline-block animate-bounce mb-2">📥</span>
                        <p class="font-bold text-slate-500">Menunggu antrean pustaka frame deteksi...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL RIWAYAT AKTIVITAS (KOLOM KANAN) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden h-full flex flex-col">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Log Aktivitas Terbaru</h3>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-600 rounded-md font-mono">{{ $aktivitasTerbaru->count() }} Item</span>
                </div>
                
                <div class="divide-y divide-slate-100/80 overflow-y-auto flex-1 max-h-[570px] scrollbar-thin">
                    @forelse ($aktivitasTerbaru as $deteksi)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition duration-150">
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">{{ $deteksi->product->nama_produk ?? '—' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">⏱ {{ $deteksi->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="flex-shrink-0 ml-3 text-sm font-mono font-black text-rose-600 bg-rose-50 px-2 py-0.5 border border-rose-100 rounded-md">
                            -{{ $deteksi->jumlah }}
                        </span>
                    </div>
                    @empty
                    <div class="px-5 py-16 text-center text-sm text-slate-400 my-auto">
                        <div class="text-xl mb-2">📥</div>
                        <p class="font-bold text-slate-500">Belum ada pergerakan otomatis</p>
                        <p class="text-xs text-slate-400 mt-0.5 max-w-[180px] mx-auto leading-relaxed">Seluruh data log tangkapan keluar otomatis Pi tampil di sini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- INFORMASI PANEL LEGENDA --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Glosarium Status Kritis Kuantitas</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Kapasitas Stok Aman</span>
                <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-lg shadow-sm">✓ Aman</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Kapasitas Stok Menipis</span>
                <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black rounded-lg shadow-sm">⚠ Stok Menipis</span>
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
            ? 'bg-emerald-50 border border-emerald-200 text-emerald-700'
            : 'bg-amber-50 border border-amber-200 text-amber-700';
    }

    function badgeIcon(status) {
        return status === 'Aman' ? '✓' : '⚠';
    }

    // Pembaruan UI Render Deteksi Realtime agar Timbul dan Tebal
    function render(detections) {
        countEl.textContent = `${detections.length} produk`;

        if (!detections.length) {
            listEl.innerHTML = `
                <div class="px-5 py-12 text-center text-sm text-slate-400">
                    <p class="font-bold text-slate-500">Tidak ada produk terdeteksi dalam frame saat ini.</p>
                </div>`;
            return;
        }

        listEl.innerHTML = detections.map(d => `
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition duration-150">
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">${d.product_label}</p>
                    <p class="text-xs font-bold text-blue-600 font-mono mt-0.5">${d.count} pcs terdeteksi kamera</p>
                </div>
                <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-xs font-black rounded-lg shadow-sm ${badgeClass(d.status)}">
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
                <div class="px-5 py-12 text-center text-sm text-rose-600 font-semibold">
                    ⚠ Gagal melakukan sinkronisasi data deteksi dengan Raspberry Pi.
                </div>`;
        }
    }

    fetchDetections();
    setInterval(fetchDetections, 3000); // polling setiap 3 detik

    // Handler Aksi Pemicu Koleksi Dataset Gambar Latih
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
            progressText.textContent = 'Gagal memvalidasi sinyal telemetri dataset.';
        }
    }

    if (btnAmbilDataset) {
        btnAmbilDataset.addEventListener('click', async () => {
            btnAmbilDataset.disabled = true;
            btnAmbilDataset.classList.add('opacity-50', 'cursor-not-allowed');
            progressWrap.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressPct.textContent = '0%';
            progressText.textContent = 'Memetakan matriks, memulai pengambilan foto...';

            try {
                const res  = await fetch(FLASK_CAPTURE_URL, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ jumlah: 50 }),
                });
                const data = await res.json();

                if (!data.ok) {
                    progressText.textContent = data.message || 'Gagal mengirim instruksi pemicu kamera.';
                    btnAmbilDataset.disabled = false;
                    btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                datasetPollInterval = setInterval(pollDatasetStatus, 1000);
            } catch (err) {
                progressText.textContent = 'Raspberry Pi tidak merespons jabat tangan (handshake).';
                btnAmbilDataset.disabled = false;
                btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
})();
</script>
@endpush