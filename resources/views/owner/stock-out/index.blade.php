@extends('layouts.app')

@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Log barang keluar — otomatis dari deteksi kamera atau manual')

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-slate-900 via-rose-950 to-slate-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📤 Riwayat Stok Keluar</h2>
                <p class="text-rose-200/80 text-xs md:text-sm mt-0.5 font-medium">
                    Total data log: <strong class="text-white border-b border-rose-400 font-mono">{{ $riwayat->total() ?? $riwayat->count() }}</strong> entri log
                </p>
            </div>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($riwayat as $item)
        <div class="bg-white rounded-2xl shadow-sm border-2 border-slate-200/60 p-5 space-y-4 hover:border-rose-500 hover:shadow-md transition-all duration-200 relative overflow-hidden group">
            
            {{-- Aksen Garis Atas (Sama-sama merah, otomatis memakai gradasi rose, manual memakai amber) --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 {{ $item->tipe === 'otomatis' ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-amber-500 to-orange-500' }}"></div>

            {{-- Row 1: Nama Produk & Jumlah Pengeluaran --}}
            <div class="flex items-start justify-between gap-4 pt-1">
                <div class="min-w-0">
                    <p class="font-black text-slate-800 text-base leading-snug break-words tracking-tight">
                        {{ $item->product->nama_produk ?? '—' }}
                    </p>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-bold font-mono mt-1">
                        <span>📆 {{ $item->created_at->format('d M Y') }}</span>
                        <span class="text-slate-300">•</span>
                        <span>⏰ {{ $item->created_at->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="inline-flex items-center px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black font-mono rounded-xl shadow-sm">
                        -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>
            </div>

            {{-- Row 2: Badge Tipe Pengeluaran (Sudah diganti dari hijau ke merah) --}}
            <div class="pt-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Metode Keluar</p>
                @if ($item->tipe === 'otomatis')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-black rounded-xl bg-rose-50 border border-rose-200 text-rose-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        🤖 Deteksi Kamera
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-black rounded-xl bg-amber-50 border border-amber-200 text-amber-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        ✋ Input Manual
                    </span>
                @endif
            </div>

            {{-- Row 3: Catatan Keterangan Tambahan --}}
            <div class="pt-3 border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan Alasan</p>
                @if ($item->keterangan)
                    <p class="text-xs text-slate-600 font-medium leading-relaxed bg-slate-50/50 rounded-lg p-2 border border-dashed border-slate-200">
                        "{!! nl2br(e($item->keterangan)) !!}"
                    </p>
                @else
                    <p class="text-xs text-slate-400 italic font-medium py-1">Tidak ada keterangan alasan.</p>
                @endif
            </div>

        </div>
        @empty
        {{-- Tampilan Kosong --}}
        <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-2xl border-2 border-dashed border-slate-200 shadow-sm px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada riwayat transaksi stok keluar</p>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Sistem belum mencatat adanya barang keluar, baik melalui deteksi AI kamera pintar Raspberry Pi maupun input manual.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($riwayat->hasPages())
    <div class="flex justify-center pt-2">
        {{ $riwayat->links() }}
    </div>
    @endif

</div>
@endsection