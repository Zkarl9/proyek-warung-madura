@extends('layouts.app')

@section('title', 'Kamera Live')
@section('page-title', 'Kamera Live')
@section('page-subtitle', 'Streaming deteksi YOLOv8 dari Raspberry Pi 5')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>📷</span> Kamera Live
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">Raspberry Pi 5 — Camera Module V3</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-500/20 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-black tracking-widest">
                <span class="h-2 w-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>LIVE</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- RINGKASAN --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 group transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Perangkat</p>
            <div class="mt-2.5" id="status-perangkat-wrap">
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
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dipantau Kamera</p>
            <p id="stat-dipantau" class="text-2xl md:text-3xl font-black text-slate-800 mt-1.5 font-mono tracking-tight transition-colors duration-500">{{ $totalDipantauKamera }} <span class="text-xs font-sans text-slate-400 font-bold">produk</span></p>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 transition duration-200 hover:border-rose-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sedang Habis</p>
            <p id="stat-sedang-habis" class="text-2xl md:text-3xl font-black mt-1.5 font-mono tracking-tight transition-colors duration-500 {{ $sedangHabis->count() > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $sedangHabis->count() }} <span class="text-xs font-sans text-slate-400 font-bold">produk</span></p>
        </div>
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 transition duration-200 hover:border-blue-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Model AI</p>
            <p class="text-2xl md:text-3xl font-black text-purple-600 mt-1.5 font-mono tracking-tight">YOLOv8s</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- LAYOUT UTAMA: STREAMING & PANEL --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- STREAM & KONTROL (KOLOM KIRI & TENGAH) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Video Streaming --}}
            <div class="bg-slate-950 rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden relative group/stream">
                <div class="relative bg-slate-900 w-full aspect-video flex items-center justify-center">
                    <span class="absolute top-4 left-4 z-10 inline-flex items-center gap-1.5 px-3 py-1 bg-black/70 text-white text-[11px] font-black rounded-lg backdrop-blur-md border border-white/10">
                        <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span> REKAM
                    </span>

                    <img src="{{ config('services.raspi.stream_url') }}/stream.mjpg"
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
                            <p class="text-sm font-bold text-slate-200">Kamera Tidak Terhubung</p>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs leading-relaxed">Stream tidak tersedia. Cek koneksi jaringan atau pastikan server di Raspberry Pi sedang berjalan.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kontrol Perangkat --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 space-y-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kontrol Perangkat</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <form action="{{ route('owner.camera.start') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-emerald-700/20">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Jalankan Deteksi
                        </button>
                    </form>

                    <form action="{{ route('owner.camera.stop') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-rose-700/20">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            Hentikan Deteksi
                        </button>
                    </form>

                    <button type="button" id="btn-ambil-dataset"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition duration-150 active:scale-95 shadow-sm shadow-blue-700/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Ambil 50 Foto Dataset
                    </button>
                </div>

                {{-- Progres Unggah Dataset --}}
                <div id="dataset-progress-wrap" class="hidden mt-4 bg-slate-50 border border-slate-200 p-4 rounded-xl shadow-inner animate-fadeIn">
                    <div class="flex items-center justify-between mb-2">
                        <span id="dataset-progress-text" class="text-xs font-bold text-slate-600">Mengambil foto dataset...</span>
                        <span id="dataset-progress-pct" class="text-xs font-black font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                        <div id="dataset-progress-bar" class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300 shadow-sm" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- Deteksi Real-time --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Deteksi Saat Ini</h3>
                    </div>
                    <span id="deteksi-count" class="text-xs font-bold font-mono px-2.5 py-0.5 bg-slate-200 text-slate-600 rounded-lg">0 produk</span>
                </div>
                <div id="deteksi-list" class="divide-y divide-slate-100 font-medium">
                    <div class="px-5 py-12 text-center text-sm text-slate-400">
                        <span class="inline-block animate-bounce mb-2">📥</span>
                        <p class="font-bold text-slate-500">Menunggu data dari kamera...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL PRODUK SEDANG HABIS (KOLOM KANAN) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Produk Sedang Habis</h3>
                    </div>
                    <span id="badge-sedang-habis" class="text-xs font-bold px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-600 rounded-md font-mono transition-colors duration-500">{{ $sedangHabis->count() }}</span>
                </div>

                <div id="panel-sedang-habis" class="divide-y divide-slate-100/80 overflow-y-auto max-h-[280px] scrollbar-thin">
                    @forelse ($sedangHabis as $produk)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition duration-150">
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">{{ $produk->nama_produk }}</p>
                            <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">📷 {{ $produk->yolo_label }}</p>
                        </div>
                        <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-xs font-black rounded-lg shadow-sm bg-rose-50 border border-rose-200 text-rose-700">
                            🔴 Habis
                        </span>
                    </div>
                    @empty
                    <div class="px-5 py-16 text-center text-sm text-slate-400 my-auto">
                        <div class="text-xl mb-2">✅</div>
                        <p class="font-bold text-slate-500">Semua produk terpantau aman</p>
                        <p class="text-xs text-slate-400 mt-0.5 max-w-[180px] mx-auto leading-relaxed">Belum ada produk yang terdeteksi habis oleh kamera.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Deteksi --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Riwayat Deteksi</h3>
                    </div>
                    <span id="badge-riwayat" class="text-xs font-bold px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 rounded-md font-mono transition-colors duration-500">{{ $riwayatDeteksi->count() }}</span>
                </div>

                <div id="panel-riwayat" class="divide-y divide-slate-100/80 overflow-y-auto max-h-[300px] scrollbar-thin">
                    @forelse ($riwayatDeteksi as $log)
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition duration-150" data-log-id="{{ $log->id }}">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate">{{ $log->product->nama_produk ?? 'Produk telah dihapus' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">⏱ {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                        @if ($log->status === 'habis')
                        <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-[11px] font-black rounded-lg shadow-sm bg-rose-50 border border-rose-200 text-rose-700">
                            🔴 Habis
                        </span>
                        @else
                        <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-[11px] font-black rounded-lg shadow-sm bg-emerald-50 border border-emerald-200 text-emerald-700">
                            🟢 Ada Lagi
                        </span>
                        @endif
                    </div>
                    @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-400">
                        <div class="text-xl mb-2">📋</div>
                        <p class="font-bold text-slate-500">Belum ada riwayat</p>
                        <p class="text-xs text-slate-400 mt-0.5 max-w-[200px] mx-auto leading-relaxed">Perubahan status ada/habis dari kamera akan tercatat di sini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- LEGENDA STATUS --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Arti Status Deteksi</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Produk Ada di Rak</span>
                <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-lg shadow-sm">🟢 Ada</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Produk Kosong di Rak</span>
                <span class="px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black rounded-lg shadow-sm">🔴 Habis</span>
            </div>
        </div>
        <p class="px-5 pb-4 text-[11px] text-slate-400 leading-relaxed">Kamera cuma mendeteksi ada/tidaknya produk di rak, bukan menghitung jumlahnya. Notifikasi Telegram otomatis terkirim begitu status berubah jadi "Habis" dan bertahan stabil.</p>
    </div>

</div>
@endsection

@push('styles')
<style>
    @keyframes flashHighlight {
        0%   { background-color: rgba(251, 191, 36, 0.35); }
        100% { background-color: transparent; }
    }
    .flash-update {
        animation: flashHighlight 1.2s ease-out;
    }
    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .item-enter {
        animation: slideFadeIn 0.4s ease-out;
    }
    #panel-sedang-habis, #panel-riwayat, #deteksi-list {
        transition: opacity 0.25s ease;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const FLASK_BASE_URL       = '{{ config('services.raspi.stream_url') }}';
    const FLASK_DETECTIONS_URL = `${FLASK_BASE_URL}/detections`;
    const FLASK_CAPTURE_URL    = `${FLASK_BASE_URL}/capture-dataset`;
    const FLASK_DATASET_STATUS_URL = `${FLASK_BASE_URL}/dataset-status`;

    const listEl  = document.getElementById('deteksi-list');
    const countEl = document.getElementById('deteksi-count');

    function badgeClass(status) {
        return status === 'ada'
            ? 'bg-emerald-50 border border-emerald-200 text-emerald-700'
            : 'bg-rose-50 border border-rose-200 text-rose-700';
    }

    function badgeLabel(status) {
        return status === 'ada' ? '🟢 Ada' : '🔴 Habis';
    }

    // Render daftar deteksi live dari Flask (Raspberry Pi).
    // Format yang diharapkan: { detections: [{ product_label, status: 'ada'|'habis' }, ...] }
    function render(detections) {
        countEl.textContent = `${detections.length} produk`;

        if (!detections.length) {
            listEl.innerHTML = `
                <div class="px-5 py-12 text-center text-sm text-slate-400">
                    <p class="font-bold text-slate-500">Tidak ada produk dalam jangkauan kamera saat ini.</p>
                </div>`;
            return;
        }

        listEl.innerHTML = detections.map(d => `
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition duration-150">
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">${d.product_label}</p>
                </div>
                <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-xs font-black rounded-lg shadow-sm ${badgeClass(d.status)}">
                    ${badgeLabel(d.status)}
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
                    ⚠ Gagal mengambil data dari Raspberry Pi.
                </div>`;
        }
    }

    fetchDetections();
    setInterval(fetchDetections, 3000); // polling deteksi live (dari Flask/Raspberry Pi) setiap 3 detik

    // ============================================================
    // POLLING DATA LARAVEL — status_kamera, produk sedang habis, riwayat
    // ============================================================
    // Beda dari fetchDetections() di atas (yang langsung ke Flask/Raspberry Pi):
    // ini poll ke Laravel sendiri, karena data status_kamera & riwayat sudah
    // distabilkan/dikonfirmasi server (AiStockController), bukan data mentah.
    const KAMERA_DATA_URL = '{{ route('owner.camera.data') }}';

    const elStatusWrap    = document.getElementById('status-perangkat-wrap');
    const elStatDipantau  = document.getElementById('stat-dipantau');
    const elStatHabis     = document.getElementById('stat-sedang-habis');
    const elBadgeHabis    = document.getElementById('badge-sedang-habis');
    const elBadgeRiwayat  = document.getElementById('badge-riwayat');
    const elPanelHabis    = document.getElementById('panel-sedang-habis');
    const elPanelRiwayat  = document.getElementById('panel-riwayat');

    let sebelumnya = {
        totalDipantau: null,
        totalHabis: null,
        idHabis: new Set(),
        idRiwayat: new Set(),
    };

    function flash(el) {
        el.classList.remove('flash-update');
        void el.offsetWidth; // reset animasi kalau baru saja jalan
        el.classList.add('flash-update');
    }

    function renderSedangHabis(list) {
        if (!list.length) {
            elPanelHabis.innerHTML = `
                <div class="px-5 py-16 text-center text-sm text-slate-400 my-auto">
                    <div class="text-xl mb-2">✅</div>
                    <p class="font-bold text-slate-500">Semua produk terpantau aman</p>
                    <p class="text-xs text-slate-400 mt-0.5 max-w-[180px] mx-auto leading-relaxed">Belum ada produk yang terdeteksi habis oleh kamera.</p>
                </div>`;
            return;
        }

        elPanelHabis.innerHTML = list.map(p => `
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition duration-150 ${sebelumnya.idHabis.has(p.id) ? '' : 'item-enter'}">
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-slate-800 truncate tracking-tight">${p.nama_produk}</p>
                    <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">📷 ${p.yolo_label ?? ''}</p>
                </div>
                <span class="flex-shrink-0 ml-3 px-2.5 py-1 text-xs font-black rounded-lg shadow-sm bg-rose-50 border border-rose-200 text-rose-700">
                    🔴 Habis
                </span>
            </div>
        `).join('');
    }

    function renderRiwayat(list) {
        if (!list.length) {
            elPanelRiwayat.innerHTML = `
                <div class="px-5 py-12 text-center text-sm text-slate-400">
                    <div class="text-xl mb-2">📋</div>
                    <p class="font-bold text-slate-500">Belum ada riwayat</p>
                    <p class="text-xs text-slate-400 mt-0.5 max-w-[200px] mx-auto leading-relaxed">Perubahan status ada/habis dari kamera akan tercatat di sini.</p>
                </div>`;
            return;
        }

        elPanelRiwayat.innerHTML = list.map(log => {
            const badge = log.status === 'habis'
                ? `<span class="flex-shrink-0 ml-3 px-2.5 py-1 text-[11px] font-black rounded-lg shadow-sm bg-rose-50 border border-rose-200 text-rose-700">🔴 Habis</span>`
                : `<span class="flex-shrink-0 ml-3 px-2.5 py-1 text-[11px] font-black rounded-lg shadow-sm bg-emerald-50 border border-emerald-200 text-emerald-700">🟢 Ada Lagi</span>`;

            return `
            <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition duration-150 ${sebelumnya.idRiwayat.has(log.id) ? '' : 'item-enter'}" data-log-id="${log.id}">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-700 truncate">${log.nama_produk}</p>
                    <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">⏱ ${log.waktu_relatif}</p>
                </div>
                ${badge}
            </div>`;
        }).join('');
    }

    async function refreshKameraData() {
        try {
            const res  = await fetch(KAMERA_DATA_URL, { cache: 'no-store', headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.ok) return;

            // Status perangkat (online/offline)
            elStatusWrap.innerHTML = data.status_perangkat === 'online'
                ? `<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-lg shadow-sm"><span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span> ONLINE</span>`
                : `<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black rounded-lg shadow-sm"><span class="h-2 w-2 rounded-full bg-rose-500"></span> OFFLINE</span>`;

            // Kartu angka — cuma "flash" kalau nilainya benar-benar berubah, biar nggak norak tiap 5 detik
            if (sebelumnya.totalDipantau !== null && sebelumnya.totalDipantau !== data.total_dipantau_kamera) {
                flash(elStatDipantau);
            }
            elStatDipantau.innerHTML = `${data.total_dipantau_kamera} <span class="text-xs font-sans text-slate-400 font-bold">produk</span>`;

            const totalHabisBaru = data.sedang_habis.length;
            if (sebelumnya.totalHabis !== null && sebelumnya.totalHabis !== totalHabisBaru) {
                flash(elStatHabis);
                flash(elBadgeHabis);
            }
            elStatHabis.innerHTML = `${totalHabisBaru} <span class="text-xs font-sans text-slate-400 font-bold">produk</span>`;
            elStatHabis.classList.toggle('text-rose-600', totalHabisBaru > 0);
            elStatHabis.classList.toggle('text-emerald-600', totalHabisBaru === 0);
            elBadgeHabis.textContent = totalHabisBaru;

            if (sebelumnya.idRiwayat.size && data.riwayat_deteksi.length && !sebelumnya.idRiwayat.has(data.riwayat_deteksi[0].id)) {
                flash(elBadgeRiwayat);
            }
            elBadgeRiwayat.textContent = data.riwayat_deteksi.length;

            renderSedangHabis(data.sedang_habis);
            renderRiwayat(data.riwayat_deteksi);

            sebelumnya = {
                totalDipantau: data.total_dipantau_kamera,
                totalHabis: totalHabisBaru,
                idHabis: new Set(data.sedang_habis.map(p => p.id)),
                idRiwayat: new Set(data.riwayat_deteksi.map(l => l.id)),
            };
        } catch (err) {
            // diam-diam gagal, coba lagi di polling berikutnya — jangan ganggu tampilan yang sudah ada
        }
    }

    refreshKameraData();
    setInterval(refreshKameraData, 5000); // polling data Laravel setiap 5 detik

    // Ambil dataset foto untuk training
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
            progressText.textContent = 'Gagal memeriksa status pengambilan dataset.';
        }
    }

    if (btnAmbilDataset) {
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
                    progressText.textContent = data.message || 'Gagal mengirim perintah ke kamera.';
                    btnAmbilDataset.disabled = false;
                    btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                datasetPollInterval = setInterval(pollDatasetStatus, 1000);
            } catch (err) {
                progressText.textContent = 'Raspberry Pi tidak merespons.';
                btnAmbilDataset.disabled = false;
                btnAmbilDataset.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
})();
</script>
@endpush
