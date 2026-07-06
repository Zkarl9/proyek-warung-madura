@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')
@section('page-title', 'Tambah Stok Masuk')
@section('page-subtitle', 'Catat penambahan stok dari restock')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.stock-in.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-9 h-9 bg-white/20 hover:bg-white/30 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-lg md:text-2xl font-bold">📥 Tambah Stok Masuk</h2>
                <p class="text-blue-100 text-sm mt-0.5">Catat penambahan stok dari restock manual</p>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('owner.stock-in.store') }}" class="space-y-4">
        @csrf

        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Detail Stok Masuk</h3>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                        <option value="">— Pilih produk —</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->nama_produk }} (stok: {{ $product->stok_pajangan }} {{ $product->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Jumlah Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" required
                               placeholder="0"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                        @error('jumlah')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Sumber <span class="text-red-500">*</span>
                        </label>
                        <select name="sumber" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                            @foreach ($sumber as $s)
                                <option value="{{ $s }}" @selected(old('sumber') === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        @error('sumber')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Keterangan
                        <span class="ml-1 text-xs font-normal text-slate-400">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="3"
                              placeholder="Misal: restock dari supplier A, tanggal pengiriman..."
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm resize-none">{{ old('keterangan') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('owner.stock-in.index') }}"
               class="px-5 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-semibold transition text-sm">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg font-semibold transition text-sm">
                💾 Simpan
            </button>
        </div>

    </form>
</div>
@endsection