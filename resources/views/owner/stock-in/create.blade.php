@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')
@section('page-title', 'Tambah Stok Masuk')
@section('page-subtitle', 'Catat penambahan stok dari restock')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center gap-4">
            <a href="{{ route('owner.stock-in.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition active:scale-95">
                <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📥 Tambah Stok Masuk</h2>
                <p class="text-blue-200/80 text-xs md:text-sm mt-0.5 font-medium">Catat penambahan stok dari restock manual</p>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('owner.stock-in.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Detail Stok Masuk</h3>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-bold text-slate-800 cursor-pointer">
                        <option value="">— Pilih produk —</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->nama_produk }} (Stok saat ini: {{ $product->stok_pajangan }} {{ $product->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                            Jumlah Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" required
                               placeholder="0"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-black font-mono text-slate-800">
                        @error('jumlah')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                            Sumber <span class="text-red-500">*</span>
                        </label>
                        <select name="sumber" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-bold text-slate-800 cursor-pointer">
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
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Keterangan <span class="ml-1 text-[11px] font-normal text-slate-400 lowercase">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="3"
                              placeholder="Misal: Restock dari Supplier Utama, Invoice #1029..."
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-medium text-slate-800 resize-none">{{ old('keterangan') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('owner.stock-in.index') }}"
               class="px-5 py-2.5 text-slate-600 border-2 border-slate-200 rounded-xl hover:bg-slate-50 font-bold transition text-xs uppercase tracking-wider active:scale-95">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg font-bold transition text-xs uppercase tracking-wider active:scale-95">
                💾 Simpan Catatan
            </button>
        </div>

    </form>
</div>
@endsection