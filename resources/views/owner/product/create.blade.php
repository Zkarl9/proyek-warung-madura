@extends('layouts.app')
 
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Daftarkan barang baru untuk dideteksi kamera')
 
@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in">
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center gap-4">
            <a href="{{ route('owner.products.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-xl transition duration-200 active:scale-95 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>➕</span> Tambah Produk Baru
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-0.5">Isi spesifikasi data produk yang akan diintegrasikan dengan modul YOLOv8</p>
            </div>
        </div>
    </div>
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FORM UTAMA --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
 
        {{-- KOLOM KIRI: INPUT DATA --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Blok 1: Informasi Produk --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-blue-500 shadow-sm shadow-blue-200"></span>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Informasi Dasar Produk</h3>
                </div>
                
                <div class="p-5 space-y-4">
                    <div>
                        <label for="nama_produk" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Nama Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required
                               placeholder="Contoh: Aqua 600ml, Indomie Goreng Rendang"
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-medium text-slate-800 placeholder-slate-400">
                        @error('nama_produk')
                            <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                        @enderror
                    </div>
 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                            <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}"
                                   placeholder="Contoh: Minuman, Makanan Ringan"
                                   class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-medium text-slate-800 placeholder-slate-400">
                        </div>
                        <div>
                            <label for="satuan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Satuan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="satuan" name="satuan" required
                                        class="w-full pl-4 pr-10 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-medium text-slate-800 appearance-none cursor-pointer">
                                    <option value="" disabled selected>— Pilih Satuan —</option>
                                    @foreach (['pcs', 'pack', 'botol', 'sachet', 'dus'] as $satuan)
                                        <option value="{{ $satuan }}" {{ old('satuan') === $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                            @error('satuan')
                                <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- Blok 2: Stok & Harga --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-amber-500 shadow-sm shadow-amber-200"></span>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Manajemen Stok & Harga</h3>
                </div>
                
                <div class="p-5 space-y-4">
                    <div>
                        <label for="stok_pajangan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Stok Awal Pajangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="stok_pajangan" name="stok_pajangan"
                               value="{{ old('stok_pajangan', 0) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-bold text-slate-800 font-mono">
                        <p class="text-[11px] text-slate-400 font-medium mt-1.5">Status "Ada/Tidak Ada" akan otomatis mengikuti angka ini.</p>
                        @error('stok_pajangan')
                            <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                        @enderror
                    </div>
 
                    <div>
                        <label for="harga" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Harga Jual Nominal <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-inner">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold text-sm bg-slate-100/80 px-3 border border-r-0 border-slate-200 rounded-l-xl">Rp</span>
                            <input type="number" id="harga" name="harga" value="{{ old('harga') }}" min="0" required
                                   placeholder="Contoh: 15000"
                                   class="w-full pl-16 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-bold text-slate-800 font-mono placeholder-slate-400">
                        </div>
                        @error('harga')
                            <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
 
        {{-- KOLOM KANAN: YOLO CONFIG & MEDIA --}}
        <div class="space-y-6">
            
            {{-- KOTAK YOLO INTEL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-violet-100 overflow-hidden transition-all duration-300 hover:shadow-md ring-2 ring-violet-500/5">
                <div class="bg-gradient-to-r from-violet-50 to-indigo-50/40 border-b border-violet-100 px-5 py-4 flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-violet-500 shadow-sm shadow-violet-200 animate-pulse"></span>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kecerdasan Buatan (YOLO)</h3>
                </div>
                
                <div class="p-5 space-y-4">
                    <div class="border border-violet-100 bg-violet-50/40 rounded-xl p-4">
                        <label for="yolo_label" class="block text-xs font-bold text-violet-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <span>🤖</span> ID Kelas Dataset <span class="text-slate-400 normal-case font-medium">(opsional)</span>
                        </label>
                        <p class="text-[11px] text-violet-600/90 font-medium mb-3 leading-relaxed">
                            Isi hanya jika produk ini dipantau kamera. Kosongkan kalau produk berada di luar jangkauan kamera, di rak lain, atau memang dikelola manual saja.
                        </p>
 
                        <div id="detectionPreview"
                             class="flex items-center gap-2 mb-4 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 bg-slate-100 text-slate-500 border border-slate-200">
                            <span id="detectionIcon" class="text-base">✋</span>
                            <span id="detectionLabel">Manual saja (tidak dipantau kamera)</span>
                        </div>
 
                        <input type="text" id="yolo_label" name="yolo_label" value="{{ old('yolo_label') }}"
                               placeholder="Misal: aqua_600ml, indomie_miegoreng"
                               class="w-full px-4 py-2.5 border border-violet-200 rounded-xl bg-white focus:outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition font-mono text-sm font-bold text-violet-900 placeholder-slate-400"
                               oninput="updateDetectionPreview(this.value)">
                        
                        <div class="flex items-center gap-1 text-[11px] text-violet-500 font-medium mt-2.5 bg-white py-1 px-2 border border-violet-100/70 rounded-lg inline-block w-full">
                            <span>💡</span> Format ideal: <code class="font-bold bg-violet-50 px-1 rounded text-violet-700">nama_barang</code>
                        </div>
                        @error('yolo_label')
                            <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
 
            {{-- FOTO MEDIA UPLOAD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></span>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Visualisasi Produk</h3>
                </div>
                
                <div class="p-5">
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-blue-50/30 transition-all duration-300 cursor-pointer group flex flex-col items-center justify-center min-h-[200px]">
                        <input type="file" id="foto" name="foto" accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                        
                        {{-- Wrapper Gambar Preview --}}
                        <div id="previewContainer" class="hidden relative group/preview mb-2">
                            <img id="previewFoto" src="" alt="Pratinjau Foto" class="h-32 w-32 object-cover rounded-2xl border-4 border-white shadow-md transition-transform duration-200 group-hover/preview:scale-105">
                            <button type="button" id="removeFotoBtn" class="absolute -top-2 -right-2 bg-rose-500 hover:bg-rose-600 text-white p-1.5 rounded-full shadow-md transition duration-200 hover:scale-110 z-20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
 
                        {{-- Info Placeholder --}}
                        <div id="placeholderFoto" class="text-slate-400 transition-transform duration-300 group-hover:-translate-y-0.5">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-400 mx-auto mb-3 border border-slate-100 group-hover:text-blue-500 group-hover:shadow-md transition duration-300">
                                <svg class="w-6 h-6 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Klik atau seret foto ke sini</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Format Ekstensi PNG/JPG (Maks. 4MB)</p>
                        </div>
                    </div>
                    @error('foto')
                        <p class="text-xs text-rose-500 font-medium mt-2 flex items-center gap-1"><span>⚠</span> {{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            {{-- GRUP ACTION BUTTONS --}}
            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('owner.products.index') }}"
                   class="flex-1 text-center px-5 py-3 text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 font-bold transition duration-200 text-sm shadow-sm active:scale-95">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 font-bold shadow-md shadow-blue-200 transition duration-200 text-sm active:scale-95 flex items-center justify-center gap-1.5">
                    💾 Simpan Produk
                </button>
            </div>
        </div>
 
    </form>
</div>
 
@push('scripts')
<script>
    const fotoInput = document.getElementById('foto');
    const previewContainer = document.getElementById('previewContainer');
    const previewFoto = document.getElementById('previewFoto');
    const placeholderFoto = document.getElementById('placeholderFoto');
    const removeFotoBtn = document.getElementById('removeFotoBtn');
 
    fotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
 
        previewFoto.src = URL.createObjectURL(file);
        previewContainer.classList.remove('hidden');
        placeholderFoto.classList.add('hidden');
    });
 
    removeFotoBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Mencegah klik menembus ke file input
        fotoInput.value = ""; // Reset input file
        previewFoto.src = "";
        previewContainer.classList.add('hidden');
        placeholderFoto.classList.remove('hidden');
    });
 
    window.updateDetectionPreview = function (value) {
        const preview = document.getElementById('detectionPreview');
        const icon    = document.getElementById('detectionIcon');
        const label   = document.getElementById('detectionLabel');
        if (!preview) return;
        
        if (value.trim() !== '') {
            preview.className = 'flex items-center gap-2 mb-4 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 bg-violet-50 text-violet-700 border border-violet-200 shadow-sm';
            icon.textContent  = '🤖';
            label.textContent = 'Kamera YOLO siap mengenali: ' + value.trim();
        } else {
            preview.className = 'flex items-center gap-2 mb-4 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 bg-slate-50 text-slate-400 border border-slate-200';
            icon.textContent  = '✋';
            label.textContent = 'Manual saja (tidak dipantau kamera)';
        }
    };
 
    // Jalankan fungsi saat reload (jika ada input dari 'old value')
    updateDetectionPreview(document.getElementById('yolo_label').value);
</script>
@endpush
@endsection
