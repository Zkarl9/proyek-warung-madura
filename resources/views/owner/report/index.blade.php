@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Rekap stok masuk dan keluar per periode')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="absolute -right-6 -top-10 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute right-16 -bottom-12 w-24 h-24 rounded-full bg-white/10"></div>
        <div class="relative flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg md:text-2xl font-bold truncate">📊 Laporan Stok</h2>
                <p class="text-blue-100 text-sm mt-0.5">
                    Periode: <strong>{{ $dariTanggal->format('d M Y') }}</strong> — <strong>{{ $sampaiTanggal->format('d M Y') }}</strong>
                </p>
            </div>
            <a href="{{ route('owner.report.cetak', request()->query()) }}" target="_blank"
               class="flex-shrink-0 flex items-center gap-1.5 bg-white text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 hover:shadow-md transition shadow text-sm">
                🖨️ Cetak
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
        <form method="GET" action="{{ route('owner.report.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dariTanggal->toDateString() }}"
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampaiTanggal->toDateString() }}"
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                    🔍 Filter
                </button>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 flex items-center gap-3">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">
                📥
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-500">Total Stok Masuk</p>
                <p class="text-2xl font-bold text-green-600 leading-tight">{{ $stockIn->sum('jumlah') }}</p>
                <p class="text-xs text-slate-400">{{ $stockIn->count() }} transaksi</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 flex items-center gap-3">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl">
                📤
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-500">Total Stok Keluar</p>
                <p class="text-2xl font-bold text-red-500 leading-tight">{{ $stockOut->sum('jumlah') }}</p>
                <p class="text-xs text-slate-400">{{ $stockOut->count() }} transaksi</p>
            </div>
        </div>
    </div>

    {{-- STOK MASUK --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Stok Masuk</h3>
            <span class="ml-auto px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">{{ $stockIn->count() }} data</span>
        </div>

        <div class="divide-y divide-slate-100 max-h-[420px] overflow-y-auto
                     [&::-webkit-scrollbar]:w-1.5
                     [&::-webkit-scrollbar-track]:bg-transparent
                     [&::-webkit-scrollbar-thumb]:bg-green-200
                     [&::-webkit-scrollbar-thumb]:rounded-full
                     hover:[&::-webkit-scrollbar-thumb]:bg-green-300">
            @forelse ($stockIn as $item)
            <div class="flex items-center gap-3 px-5 py-3 border-l-4 border-transparent hover:border-green-400 hover:bg-green-50/40 transition">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $item->product->nama_produk ?? '—' }}</p>
                    <p class="text-xs text-slate-400 font-mono">{{ $item->created_at->format('d M Y, H:i') }} · {{ $item->sumber }}</p>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 bg-green-100 text-green-700 text-sm font-bold font-mono rounded-full">
                    +{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-slate-400">
                <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m-7 4h8a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-3.414-3.414A1 1 0 0013.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">Tidak ada data stok masuk pada periode ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- STOK KELUAR --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Stok Keluar</h3>
            <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">{{ $stockOut->count() }} data</span>
        </div>

        <div class="divide-y divide-slate-100 max-h-[420px] overflow-y-auto
                     [&::-webkit-scrollbar]:w-1.5
                     [&::-webkit-scrollbar-track]:bg-transparent
                     [&::-webkit-scrollbar-thumb]:bg-red-200
                     [&::-webkit-scrollbar-thumb]:rounded-full
                     hover:[&::-webkit-scrollbar-thumb]:bg-red-300">
            @forelse ($stockOut as $item)
            <div class="flex items-center gap-3 px-5 py-3 border-l-4 border-transparent hover:border-red-400 hover:bg-red-50/40 transition">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $item->product->nama_produk ?? '—' }}</p>
                    <p class="text-xs text-slate-400 font-mono">
                        {{ $item->created_at->format('d M Y, H:i') }} ·
                        <span class="{{ $item->tipe === 'otomatis' ? 'text-violet-500' : 'text-amber-500' }}">
                            {{ $item->tipe === 'otomatis' ? '🤖 Kamera' : '✋ Manual' }}
                        </span>
                    </p>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 bg-red-100 text-red-700 text-sm font-bold font-mono rounded-full">
                    -{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}
                </span>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-slate-400">
                <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6m-7 4h8a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-3.414-3.414A1 1 0 0013.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">Tidak ada data stok keluar pada periode ini.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection