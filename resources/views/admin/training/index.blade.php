@extends('layouts.app')

@section('title', 'Training AI')
@section('page-title', 'Training AI')
@section('page-subtitle', 'Kelola dataset dari Raspi & kirim pengumuman ke Owner')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER DENGAN SUMMARY STATS --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>🤖</span> Pusat Training AI
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">
                    Manajemen dataset dan deployment model AI untuk sistem deteksi
                </p>
            </div>
            <span class="flex-shrink-0 inline-flex items-center gap-2 bg-amber-500/20 text-amber-200 border border-amber-400/30 font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider">
                ⏳ {{ $requests->count() }} Menunggu Approval
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- GRID UTAMA: PERMINTAAN + DATASET --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- PERMINTAAN DETEKSI OWNER --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-md border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Permintaan Deteksi Owner</label>
                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-black rounded-lg uppercase">
                    {{ $requests->count() }} Menunggu
                </span>
            </div>

            @if ($requests->isEmpty())
                <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                    <div class="w-14 h-14 mx-auto bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-inner mb-3">
                        <svg class="w-7 h-7 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">Tidak ada permintaan baru</h4>
                    <p class="text-xs text-slate-400 mt-1">Permintaan akan muncul saat owner mengajukan deteksi AI pada halaman produk.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($requests as $request)
                    <div class="flex items-center justify-between gap-3 p-4 bg-slate-50 rounded-2xl border border-transparent hover:bg-white hover:border-blue-200 transition-all">
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-slate-900 truncate">{{ $request->nama_produk }}</p>
                            <div class="mt-1.5 inline-flex items-center gap-1.5 px-2 py-0.5 bg-slate-900 text-slate-200 rounded-lg text-[10px] font-bold font-mono shadow-inner border border-slate-700">
                                <span class="text-emerald-400 font-sans text-[9px] uppercase tracking-wider">YOLO:</span>
                                <span class="truncate max-w-[130px] font-semibold text-white">{{ $request->yolo_label }}</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.training.approveRequest', $request) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="submit"
                                class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 px-3 py-1.5 rounded-lg transition duration-150 active:scale-95">
                                ✔️ Approve
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- DATASET DARI RASPBERRY PI --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Dataset dari Raspberry Pi</label>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-black rounded-lg uppercase">
                    {{ count($datasets) }} File
                </span>
            </div>

            @if (empty($datasets))
                <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                    <div class="w-14 h-14 mx-auto bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-inner mb-3">
                        <svg class="w-7 h-7 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">Belum ada dataset dikirim</h4>
                    <p class="text-xs text-slate-400 mt-1">Owner perlu mengambil dataset massal di halaman Kamera.</p>
                </div>
            @else
                <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
                    @foreach ($datasets as $path)
                    @php $filename = basename($path); @endphp
                    <div class="flex items-center justify-between gap-3 p-3.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black">ZIP</div>
                            <div class="min-w-0">
                                <p class="text-xs font-mono font-semibold text-slate-700 truncate">{{ $filename }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ number_format(Storage::disk('public')->size($path) / 1024, 0) }} KB</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.training.download', $filename) }}"
                           class="flex-shrink-0 text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-300 px-2.5 py-1.5 rounded-lg transition duration-150 active:scale-95">
                            ⬇️ Unduh
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- RIWAYAT PENGUMUMAN --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-5">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Riwayat Pengumuman Terkirim</label>

        @if ($pengumuman->isEmpty())
            <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                <h4 class="text-sm font-bold text-slate-800">Belum ada pengumuman terkirim</h4>
                <p class="text-xs text-slate-400 mt-1">Pengumuman otomatis terkirim saat kamu menandai produk siap deteksi.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($pengumuman->take(3) as $p)
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-wider">{{ $p->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-1.5">{{ $p->judul }}</h4>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $p->isi }}</p>
                    @if ($p->label_ids)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach (explode(',', $p->label_ids) as $label)
                        <span class="px-2 py-0.5 bg-slate-900 text-emerald-400 text-[10px] rounded-lg font-mono font-bold border border-slate-700">
                            {{ trim($label) }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection