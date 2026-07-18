@extends('layouts.app')
 
@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Daftar barang yang keluar dari toko')
 
@section('content')
<div class="space-y-6 animate-fade-in">
 
    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-slate-900 via-rose-950 to-slate-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📤 Stok Keluar</h2>
                <p class="text-rose-200/80 text-xs md:text-sm mt-0.5 font-medium">
                    <strong class="text-white border-b border-rose-400 font-mono">{{ $riwayat->total() ?? $riwayat->count() }}</strong> catatan
                </p>
            </div>
            <a href="{{ route('owner.stock-out.create') }}"
               class="sm:self-center flex items-center justify-center gap-2 bg-white text-rose-900 font-black py-2.5 px-4 rounded-xl hover:bg-rose-50 transition active:scale-95 shadow-md shadow-slate-950/20 text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Stok Keluar
            </a>
        </div>
    </div>
 
    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border-2 border-slate-200/60 overflow-hidden">
        @if($riwayat->isEmpty())
        <div class="px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada catatan stok keluar</p>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Klik tombol di atas untuk mulai mencatat barang yang keluar.</p>
            </div>
        </div>
        @else

        {{-- CARD VIEW — mobile & tablet --}}
        <div class="lg:hidden divide-y divide-slate-100">
            @foreach ($riwayat as $item)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 text-sm leading-tight">{{ $item->product->nama_produk ?? '—' }}</p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                            {{ $item->created_at->format('d M Y') }} · {{ $item->created_at->format('H:i') }} WIB
                        </p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black font-mono rounded-lg whitespace-nowrap">
                        -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if ($item->tipe === 'otomatis')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-black rounded-lg bg-rose-50 border border-rose-200 text-rose-700">
                            📷 Kamera
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-black rounded-lg bg-amber-50 border border-amber-200 text-amber-700">
                            ✋ Manual
                        </span>
                    @endif
                    @if ($item->alasan)
                        <span class="text-xs font-bold text-slate-600 capitalize">{{ $item->alasan }}</span>
                    @endif
                </div>

                @if ($item->keterangan)
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</p>
                    <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $item->keterangan }}</p>
                </div>
                @endif

                @if ($item->tipe !== 'otomatis')
                <div class="flex items-center gap-1.5 pt-1">
                    <a href="{{ route('owner.stock-out.edit', $item) }}"
                       class="flex-1 text-center px-2.5 py-2 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="{{ route('owner.stock-out.destroy', $item) }}"
                          onsubmit="return confirm('Yakin mau hapus catatan ini? Stok akan dikembalikan otomatis.');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-2.5 py-2 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
                @else
                <p class="text-[11px] text-slate-300 italic pt-1">Arsip — dicatat otomatis oleh kamera</p>
                @endif
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
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Tercatat Dari</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Alasan</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($riwayat as $item)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-4 py-3 text-xs text-slate-500 font-mono whitespace-nowrap align-top">
                            {{ $item->created_at->format('d M Y') }}<br>
                            <span class="text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <p class="font-bold text-slate-800">{{ $item->product->nama_produk ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-right align-top whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black font-mono rounded-lg">
                                -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top whitespace-nowrap">
                            @if ($item->tipe === 'otomatis')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-black rounded-lg bg-rose-50 border border-rose-200 text-rose-700">
                                    📷 Kamera
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-black rounded-lg bg-amber-50 border border-amber-200 text-amber-700">
                                    ✋ Manual
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top whitespace-nowrap">
                            @if ($item->alasan)
                                <span class="text-xs font-bold text-slate-600 capitalize">{{ $item->alasan }}</span>
                            @else
                                <span class="text-xs text-slate-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top max-w-[220px]">
                            @if ($item->keterangan)
                                <p class="text-xs text-slate-500 leading-relaxed truncate" title="{{ $item->keterangan }}">{{ $item->keterangan }}</p>
                            @else
                                <span class="text-xs text-slate-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top text-right whitespace-nowrap">
                            @if ($item->tipe !== 'otomatis')
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('owner.stock-out.edit', $item) }}"
                                   class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('owner.stock-out.destroy', $item) }}"
                                      onsubmit="return confirm('Yakin mau hapus catatan ini? Stok akan dikembalikan otomatis.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 text-slate-500 hover:text-rose-700 rounded-lg font-bold text-[11px] transition active:scale-95">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-[11px] text-slate-300 italic">Arsip</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
 
    {{-- Pagination --}}
    @if($riwayat->hasPages())
    <div class="flex justify-center pt-2">
        {{ $riwayat->links() }}
    </div>
    @endif
 
</div>
@endsection
