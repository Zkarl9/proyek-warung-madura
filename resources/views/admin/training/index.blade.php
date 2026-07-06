@extends('layouts.app')

@section('title', 'Training AI')
@section('page-title', 'Training AI')
@section('page-subtitle', 'Kelola dataset dari Raspi & upload model hasil training')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <h2 class="text-lg md:text-2xl font-bold">🤖 Training AI</h2>
        <p class="text-blue-100 text-sm mt-0.5">Kelola dataset dari Raspberry Pi & kirim pengumuman ke Owner</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Permintaan Deteksi Owner --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Permintaan Deteksi Owner</h3>
                <span class="ml-auto text-xs text-slate-400">{{ $requests->count() }} permintaan</span>
            </div>
            <div class="p-5">
                @if ($requests->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-400">Belum ada permintaan deteksi dari owner.</p>
                        <p class="text-xs text-slate-300 mt-1">Permintaan muncul saat owner klik tombol Minta Deteksi di halaman produk.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($requests as $request)
                        <li class="py-3 flex flex-col gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $request->nama_produk }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Label: <code class="font-mono">{{ $request->yolo_label }}</code></p>
                                <p class="text-xs text-slate-400 mt-1">Diminta pada {{ $request->diminta_deteksi_at?->format('d M Y H:i') ?? '—' }}</p>
                            </div>
                            <form action="{{ route('admin.training.approveRequest', $request) }}" method="POST" class="text-right">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-semibold text-green-600 hover:text-green-800 transition px-3 py-2 rounded-lg border border-green-200 bg-green-50">
                                    ✔️ Tandai Siap Deteksi
                                </button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Dataset dari Raspberry Pi --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Dataset dari Raspberry Pi</h3>
                <span class="ml-auto text-xs text-slate-400">{{ count($datasets) }} file</span>
            </div>
            <div class="p-5">
                @if (empty($datasets))
                    <div class="text-center py-8">
                        <p class="text-3xl mb-2">📂</p>
                        <p class="text-sm text-slate-400">Belum ada dataset dikirim dari Raspi.</p>
                        <p class="text-xs text-slate-300 mt-1">Owner perlu klik "Ambil Dataset Massal" di halaman Kamera.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($datasets as $path)
                        @php $filename = basename($path); @endphp
                        <li class="flex items-center justify-between py-3 gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-mono text-slate-700 truncate">{{ $filename }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ number_format(Storage::disk('public')->size($path) / 1024, 0) }} KB
                                </p>
                            </div>
                            <a href="{{ route('admin.training.download', $filename) }}"
                               class="flex-shrink-0 text-xs font-semibold text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200 transition">
                                ⬇️ Unduh
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>

    {{-- Riwayat Pengumuman --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Riwayat Pengumuman Terkirim</h3>
        </div>
        <div class="p-5">
            @forelse ($pengumuman as $p)
            <div class="border border-slate-200 rounded-xl p-4 mb-3 last:mb-0">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <p class="font-semibold text-slate-900 text-sm">{{ $p->judul }}</p>
                    <span class="flex-shrink-0 text-xs text-slate-400 font-mono">{{ $p->created_at->format('d M Y') }}</span>
                </div>
                <p class="text-sm text-slate-600 mb-2">{{ $p->isi }}</p>
                @if ($p->label_ids)
                <div class="flex flex-wrap gap-1.5">
                    @foreach (explode(',', $p->label_ids) as $label)
                    <code class="px-2 py-0.5 bg-violet-100 text-violet-700 text-xs rounded font-mono">{{ trim($label) }}</code>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-6">
                <p class="text-sm text-slate-400">Belum ada pengumuman terkirim.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection