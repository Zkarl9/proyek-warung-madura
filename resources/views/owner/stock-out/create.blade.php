@extends('layouts.app')
 
@section('title', 'Tambah Stok Keluar')
@section('page-title', 'Tambah Stok Keluar')
@section('page-subtitle', 'Catat barang keluar secara manual')
 
@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">
 
    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-slate-900 via-rose-950 to-slate-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center gap-4">
            <a href="{{ route('owner.stock-out.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition active:scale-95">
                <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight">📤 Tambah Stok Keluar</h2>
                <p class="text-rose-200/80 text-xs md:text-sm mt-0.5 font-medium">Catat barang keluar secara manual (terjual, rusak, retur, dll)</p>
            </div>
        </div>
    </div>
 
    {{-- FORM --}}
    <form method="POST" action="{{ route('owner.stock-out.store') }}" class="space-y-6">
        @csrf
 
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Detail Barang Keluar</h3>
            </div>
            <div class="p-5 space-y-4">
 
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Produk <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" id="product_id" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition text-sm font-bold text-slate-800 cursor-pointer">
                        <option value="">— Pilih produk —</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-stok="{{ $product->stok_pajangan }}" @selected(old('product_id') == $product->id)>
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
                            Jumlah Keluar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" min="1" required
                               placeholder="0"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition text-sm font-black font-mono text-slate-800">
                        <p id="stokHint" class="text-[11px] text-slate-400 font-medium mt-1.5"></p>
                        @error('jumlah')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
 
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                            Alasan <span class="text-red-500">*</span>
                        </label>
                        <select name="alasan" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition text-sm font-bold text-slate-800 cursor-pointer">
                            @foreach ($alasan as $a)
                                <option value="{{ $a }}" @selected(old('alasan') === $a)>{{ ucfirst($a) }}</option>
                            @endforeach
                        </select>
                        @error('alasan')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>
 
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Keterangan <span class="ml-1 text-[11px] font-normal text-slate-400 lowercase">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="3"
                              placeholder="Misal: Dibeli pelanggan langsung, kemasan penyok, dsb..."
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition text-sm font-medium text-slate-800 resize-none">{{ old('keterangan') }}</textarea>
                </div>
 
            </div>
        </div>
 
        {{-- Tombol --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('owner.stock-out.index') }}"
               class="px-5 py-2.5 text-slate-600 border-2 border-slate-200 rounded-xl hover:bg-slate-50 font-bold transition text-xs uppercase tracking-wider active:scale-95">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-xl hover:shadow-lg font-bold transition text-xs uppercase tracking-wider active:scale-95">
                💾 Simpan Catatan
            </button>
        </div>
 
    </form>
</div>
 
@push('scripts')
<script>
    const selectProduk = document.getElementById('product_id');
    const hint = document.getElementById('stokHint');
    function updateHint() {
        const opt = selectProduk.options[selectProduk.selectedIndex];
        const stok = opt ? opt.getAttribute('data-stok') : null;
        hint.textContent = stok !== null ? `Stok tersedia: ${stok}` : '';
    }
    selectProduk.addEventListener('change', updateHint);
    updateHint();
</script>
@endpush
@endsection
