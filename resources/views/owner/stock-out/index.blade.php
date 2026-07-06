@extends('layouts.app')

@section('title', 'Stok Keluar')
@section('page-title', 'Stok Keluar')
@section('page-subtitle', 'Log barang keluar — otomatis dari deteksi kamera atau manual')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg md:text-2xl font-bold truncate">📤 Riwayat Stok Keluar</h2>
                <p class="text-blue-100 text-sm mt-0.5">
                    Total: <strong>{{ $riwayat->total() ?? $riwayat->count() }}</strong> catatan
                </p>
            </div>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($riwayat as $item)
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 space-y-3">

            {{-- Row 1: Produk + Jumlah --}}
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="font-bold text-slate-900 text-sm leading-tight truncate">
                        {{ $item->product->nama_produk ?? '—' }}
                    </p>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">
                        {{ $item->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 bg-red-100 text-red-700 text-sm font-bold font-mono rounded-full">
                    -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                </span>
            </div>

            {{-- Row 2: Tipe --}}
            <div>
                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full
                    {{ $item->tipe === 'otomatis' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $item->tipe === 'otomatis' ? '🤖 Deteksi Kamera' : '✋ Manual' }}
                </span>
            </div>

            {{-- Row 3: Keterangan --}}
            @if ($item->keterangan)
            <div class="pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-500">Keterangan</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $item->keterangan }}</p>
            </div>
            @endif

        </div>
        @empty
        <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-slate-200 shadow px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-semibold">Belum ada riwayat stok keluar</p>
                <p class="text-xs">Stok keluar dicatat otomatis dari kamera atau input manual</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($riwayat->hasPages())
    <div class="flex justify-center">
        {{ $riwayat->links() }}
    </div>
    @endif

</div>
@endsection