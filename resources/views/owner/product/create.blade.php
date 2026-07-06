@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Daftarkan barang baru untuk dideteksi kamera')

@section('content')
<div class="space-y-4">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.products.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-9 h-9 bg-white/20 hover:bg-white/30 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-lg md:text-2xl font-bold">➕ Tambah Produk Baru</h2>
                <p class="text-blue-100 text-sm mt-0.5">Isi semua data produk yang akan dideteksi kamera YOLOv8</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FORM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Informasi Dasar --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Informasi Dasar</h3>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label for="nama_produk" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required
                           placeholder="Misal: Aqua 600ml, Indomie Goreng"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                    @error('nama_produk')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                        <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}"
                               placeholder="Misal: Minuman, Snack"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label for="satuan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                            <option value="">— Pilih —</option>
                            @foreach (['pcs', 'pack', 'botol', 'sachet', 'dus'] as $satuan)
                                <option value="{{ $satuan }}" {{ old('satuan') === $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                            @endforeach
                        </select>
                        @error('satuan')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- YOLO Label --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Label Deteksi YOLOv8</h3>
            </div>
            <div class="p-5">
                <div class="border border-violet-200 bg-violet-50 rounded-xl p-4">
                    <label for="yolo_label" class="block text-sm font-semibold text-violet-700 mb-1">
                        🤖 YOLO Label <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-violet-500 mb-3">
                        Harus sama persis dengan nama class di dataset training. Gunakan huruf kecil &amp; underscore.
                    </p>

                    <div id="detectionPreview"
                         class="flex items-center gap-2 mb-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 bg-slate-100 text-slate-500">
                        <span id="detectionIcon">✋</span>
                        <span id="detectionLabel">Label kosong — produk tidak akan dikenali kamera</span>
                    </div>

                    <input type="text" id="yolo_label" name="yolo_label" value="{{ old('yolo_label') }}" required
                           placeholder="contoh: aqua_600ml, indomie_goreng"
                           class="w-full px-4 py-2.5 border border-violet-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500 transition font-mono text-sm placeholder-slate-400"
                           oninput="updateDetectionPreview(this.value)">
                    <p class="text-xs text-violet-400 mt-2">
                        💡 Contoh: <code class="bg-white px-1 rounded">aqua_600ml</code>, <code class="bg-white px-1 rounded">indomie_goreng</code>
                    </p>
                    @error('yolo_label')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Stok & Harga --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Stok &amp; Harga</h3>
            </div>
            <div class="p-5 space-y-4">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="stok_pajangan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok_pajangan" name="stok_pajangan"
                               value="{{ old('stok_pajangan', 0) }}" min="0" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                        @error('stok_pajangan')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="stok_minimum" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Stok Minimum <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok_minimum" name="stok_minimum"
                               value="{{ old('stok_minimum', 5) }}" min="0" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                        <p class="text-xs text-slate-400 mt-1.5">Pemicu notifikasi stok tipis.</p>
                        @error('stok_minimum')
                            <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="harga" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Harga Jual <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-600 font-semibold text-sm">Rp</span>
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}" min="0" required
                               placeholder="25000"
                               class="w-full pl-12 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                    </div>
                    @error('harga')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Foto Produk --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Foto Produk</h3>
            </div>
            <div class="p-5">
                <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-400 rounded-xl p-8 text-center bg-slate-50 hover:bg-blue-50 transition-all cursor-pointer group">
                    <input type="file" id="foto" name="foto" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <img id="previewFoto" src="" alt="" class="hidden h-32 w-32 object-cover rounded-xl mx-auto border-2 border-slate-200 shadow mb-2">
                    <div id="placeholderFoto" class="text-sm text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="font-semibold">📷 Klik untuk unggah foto</p>
                        <p class="text-xs text-slate-400 mt-1">JPG atau PNG, maks 4MB</p>
                    </div>
                </div>
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('owner.products.index') }}"
               class="px-5 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-semibold transition text-sm">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg font-semibold transition text-sm">
                💾 Simpan Produk
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const preview     = document.getElementById('previewFoto');
        const placeholder = document.getElementById('placeholderFoto');
        preview.src       = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    });

    window.updateDetectionPreview = function (value) {
        const preview = document.getElementById('detectionPreview');
        const icon    = document.getElementById('detectionIcon');
        const label   = document.getElementById('detectionLabel');
        if (!preview) return;
        if (value.trim() !== '') {
            preview.className = 'flex items-center gap-2 mb-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 bg-violet-100 text-violet-700';
            icon.textContent  = '🤖';
            label.textContent = 'Produk akan dikenali kamera: ' + value.trim();
        } else {
            preview.className = 'flex items-center gap-2 mb-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300 bg-slate-100 text-slate-500';
            icon.textContent  = '✋';
            label.textContent = 'Label kosong — produk tidak akan dikenali kamera';
        }
    };
</script>
@endpush
@endsection