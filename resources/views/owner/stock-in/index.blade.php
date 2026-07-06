@extends('layouts.app')

@section('title', 'Stok Masuk')
@section('page-title', 'Stok Masuk')
@section('page-subtitle', 'Riwayat penambahan stok dari restock manual')

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📥 Riwayat Stok Masuk</h2>
                <p class="text-blue-200/80 text-xs md:text-sm mt-0.5 font-medium">
                    Total data log: <strong class="text-white border-b border-blue-400 font-mono">{{ $stockIns->total() ?? $stockIns->count() }}</strong> entri log
                </p>
            </div>
            <a href="{{ route('owner.stock-in.create') }}"
               class="sm:self-center flex items-center justify-center gap-2 bg-white text-blue-900 font-black py-2.5 px-4 rounded-xl hover:bg-blue-50 transition active:scale-95 shadow-md shadow-slate-950/20 text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Log Manual
            </a>
        </div>
    </div>

    {{-- CARD LIST --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($stockIns as $item)
        <div class="bg-white rounded-2xl shadow-sm border-2 border-slate-200/60 p-5 space-y-4 hover:border-blue-500 hover:shadow-md transition-all duration-200 relative overflow-hidden group">
            
            {{-- Badge Status / Dekorasi Atas --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

            {{-- Row 1: Judul Produk & Jumlah Kuantitas --}}
            <div class="flex items-start justify-between gap-4 pt-1">
                <div class="min-w-0">
                    <p class="font-black text-slate-800 text-base leading-snug break-words tracking-tight">
                        {{ $item->product->nama_produk ?? '—' }}
                    </p>
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-bold font-mono mt-1">
                        <span>📆 {{ $item->created_at->format('d M Y') }}</span>
                        <span class="text-slate-300">•</span>
                        <span>⏰ {{ $item->created_at->format('H:i') }} WiB</span>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="inline-flex items-center px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-black font-mono rounded-xl shadow-sm">
                        +{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>
            </div>

            {{-- Row 2: Informasi Detail Sumber & Petugas --}}
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div class="bg-slate-50 border border-slate-200/60 rounded-xl px-3 py-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Asal Sumber</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <p class="font-black text-slate-700 text-xs capitalize">{{ $item->sumber }}</p>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-200/60 rounded-xl px-3 py-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dicatat Oleh</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <p class="font-black text-slate-700 text-xs truncate" title="{{ $item->user->name ?? '—' }}">
                            {{ $item->user->name ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Row 3: Catatan Keterangan Tambahan --}}
            <div class="pt-3 border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Tambahan</p>
                @if ($item->keterangan)
                    <p class="text-xs text-slate-600 font-medium leading-relaxed bg-slate-50/50 rounded-lg p-2 border border-dashed border-slate-200">
                        "{{ $item->keterangan }}"
                    </p>
                @else
                    <p class="text-xs text-slate-400 italic font-medium py-1">Tidak ada keterangan tambahan.</p>
                @endif
            </div>

        </div>
        @empty
        {{-- Tampilan saat data kosong --}}
        <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-2xl border-2 border-dashed border-slate-200 shadow-sm px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada riwayat transaksi stok masuk</p>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Klik tombol "Tambah Log Manual" di atas untuk mencatatkan siklus masuk persediaan barang dagang.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($stockIns->hasPages())
    <div class="flex justify-center pt-2">
        {{ $stockIns->links() }}
    </div>
    @endif

</div>
@endsection