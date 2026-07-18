@extends('layouts.app')
 
@section('title', 'Stok Masuk')
@section('page-title', 'Stok Masuk')
@section('page-subtitle', 'Daftar barang yang pernah masuk ke toko')
 
@section('content')
<div class="space-y-6 animate-fade-in">
 
    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📥 Stok Masuk</h2>
                <p class="text-blue-200/80 text-xs md:text-sm mt-0.5 font-medium">
                    <strong class="text-white border-b border-blue-400 font-mono">{{ $stockIns->total() ?? $stockIns->count() }}</strong> catatan
                </p>
            </div>
            <a href="{{ route('owner.stock-in.create') }}"
               class="sm:self-center flex items-center justify-center gap-2 bg-white text-blue-900 font-black py-2.5 px-4 rounded-xl hover:bg-blue-50 transition active:scale-95 shadow-md shadow-slate-950/20 text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Stok Masuk
            </a>
        </div>
    </div>
 
    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border-2 border-slate-200/60 overflow-hidden">
        @if($stockIns->isEmpty())
        <div class="px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada catatan stok masuk</p>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Klik tombol di atas untuk mulai mencatat barang yang masuk.</p>
            </div>
        </div>
        @else

        {{-- CARD VIEW — mobile & tablet --}}
        <div class="lg:hidden divide-y divide-slate-100">
            @foreach ($stockIns as $item)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 text-sm leading-tight">{{ $item->product->nama_produk ?? '—' }}</p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                            {{ $item->created_at->format('d M Y') }} · {{ $item->created_at->format('H:i') }} WIB
                        </p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-black font-mono rounded-lg whitespace-nowrap">
                        +{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dari Mana</p>
                        <p class="font-bold text-slate-600 capitalize mt-0.5">{{ $item->sumber }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Harga Beli</p>
                        <p class="font-bold text-slate-700 font-mono mt-0.5">
                            @if ($item->harga_beli)
                                Rp{{ number_format($item->harga_beli, 0, ',', '.') }}
                            @else
                                <span class="text-slate-300 italic font-normal">—</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dicatat Oleh</p>
                        <p class="font-semibold text-slate-600 mt-0.5 truncate" title="{{ $item->user->name ?? '—' }}">
                            {{ $item->user->name ?? '—' }}
                        </p>
                    </div>
                </div>

                @if ($item->keterangan)
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan</p>
                    <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $item->keterangan }}</p>
                </div>
                @endif

                <div class="flex items-center gap-1.5 pt-1">
                    <a href="{{ route('owner.stock-in.edit', $item) }}"
                       class="flex-1 text-center px-2.5 py-2 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 text-slate-500 hover:text-blue-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="{{ route('owner.stock-in.destroy', $item) }}"
                          onsubmit="return confirm('Yakin mau hapus catatan ini? Stok akan disesuaikan otomatis.');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-2.5 py-2 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- TABLE VIEW — desktop --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left">
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right whitespace-nowrap">Jumlah</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Dari Mana</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right whitespace-nowrap">Harga Beli</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Dicatat Oleh</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($stockIns as $item)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-4 py-3 text-xs text-slate-500 font-mono whitespace-nowrap align-top">
                            {{ $item->created_at->format('d M Y') }}<br>
                            <span class="text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <p class="font-bold text-slate-800">{{ $item->product->nama_produk ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-right align-top whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-black font-mono rounded-lg">
                                +{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <span class="text-xs font-bold text-slate-600 capitalize">{{ $item->sumber }}</span>
                        </td>
                        <td class="px-4 py-3 align-top text-right whitespace-nowrap">
                            @if ($item->harga_beli)
                                <span class="text-xs font-bold text-slate-700 font-mono">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</span>
                            @else
                                <span class="text-xs text-slate-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top">
                            <span class="text-xs font-semibold text-slate-600 truncate block max-w-[140px]" title="{{ $item->user->name ?? '—' }}">
                                {{ $item->user->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top max-w-[220px]">
                            @if ($item->keterangan)
                                <p class="text-xs text-slate-500 leading-relaxed truncate" title="{{ $item->keterangan }}">{{ $item->keterangan }}</p>
                            @else
                                <span class="text-xs text-slate-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('owner.stock-in.edit', $item) }}"
                                   class="px-2.5 py-1.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 text-slate-500 hover:text-blue-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('owner.stock-in.destroy', $item) }}"
                                      onsubmit="return confirm('Yakin mau hapus catatan ini? Stok akan disesuaikan otomatis.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
 
    {{-- Pagination --}}
    @if($stockIns->hasPages())
    <div class="flex justify-center pt-2">
        {{ $stockIns->links() }}
    </div>
    @endif
 
</div>
@endsection
