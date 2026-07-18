@extends('layouts.app')
 
@section('title', 'Ubah Produk')
@section('page-title', 'Ubah Produk')
@section('page-subtitle', $product->nama_produk)
 
@section('content')
@php
    $statusLabel = [
        'belum_siap'      => 'Belum Siap',
        'belum_dilatih'   => 'Belum Dilatih',
        'proses_training' => 'Proses Training',
        'siap_deteksi'    => 'Siap Deteksi',
    ][$product->status_ai] ?? 'Status Tidak Diketahui';
 
    $statusBadge = [
        'belum_siap'      => 'bg-slate-100 text-slate-600 border border-slate-200',
        'belum_dilatih'   => 'bg-rose-50 text-rose-700 border border-rose-200',
        'proses_training' => 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse',
        'siap_deteksi'    => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    ][$product->status_ai] ?? 'bg-slate-100 text-slate-600';
@endphp
 
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-5 md:p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex items-center gap-4">
            <a href="{{ route('owner.products.index') }}"
               class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition active:scale-95">
                <svg class="w-5 h-5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0 flex-1">
                <h2 class="text-xl md:text-2xl font-black tracking-tight truncate">✏️ Edit: {{ $product->nama_produk }}</h2>
                <div class="flex items-center gap-2 mt-1.5 text-xs md:text-sm">
                    <span class="text-blue-200/80 font-medium">Status AI Integrator:</span>
                    <span class="px-2.5 py-0.5 text-[11px] font-black rounded-lg shadow-sm {{ $statusBadge }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>
    </div>
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MAIN FORM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <form id="formProduk" action="{{ route('owner.products.update', $product) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
 
        {{-- Informasi Dasar --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Informasi Dasar</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label for="nama_produk" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_produk" name="nama_produk" value="{{ $product->nama_produk }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-medium text-slate-800">
                    @error('nama_produk')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="kategori" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Kategori</label>
                        <input type="text" id="kategori" name="kategori" value="{{ $product->kategori }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-medium text-slate-800">
                    </div>
                    <div>
                        <label for="satuan" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-bold text-slate-800 cursor-pointer">
                            @foreach (['pcs', 'pack', 'botol', 'sachet', 'dus'] as $satuan)
                                <option value="{{ $satuan }}" {{ $product->satuan === $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- YOLO Label --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Label Deteksi YOLOv8</h3>
            </div>
            <div class="p-5">
                <div class="border border-violet-200 bg-violet-50/50 rounded-xl p-4 shadow-inner">
                    <label for="yolo_label" class="block text-xs font-black text-violet-700 uppercase tracking-wide mb-1">
                        🤖 YOLO Class Label <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-violet-500/90 mb-3 font-medium">
                        Ubah label hanya jika model belum di-training ulang dengan nama dataset baru.
                    </p>
 
                    <div id="detectionPreview"
                         class="flex items-center gap-2 mb-4 px-3 py-2 rounded-lg text-xs font-bold transition-all duration-300 border
                                {{ $product->yolo_label ? 'bg-violet-100/70 text-violet-800 border-violet-300' : 'bg-slate-100 text-slate-500 border-slate-300' }}">
                        <span id="detectionIcon" class="text-sm">{{ $product->yolo_label ? '🤖' : '✋' }}</span>
                        <span id="detectionLabel">
                            {{ $product->yolo_label ? 'Produk terikat kamera: ' . $product->yolo_label : 'Label kosong — produk tidak akan terdeteksi visual' }}
                        </span>
                    </div>
 
                    <input type="text" id="yolo_label" name="yolo_label" value="{{ $product->yolo_label }}"
                           placeholder="contoh: aqua_600ml, indomie_goreng"
                           class="w-full px-4 py-2.5 border border-violet-300 rounded-xl bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 transition font-mono text-sm text-slate-900 placeholder-slate-400 font-bold"
                           oninput="updateDetectionPreview(this.value)">
                    <p class="text-xs text-violet-500 mt-2 font-medium">
                        💡 Gunakan huruf kecil & underscore (`_`). Harus sinkron dengan file konfigurasi kelas YOLO.
                    </p>
                    @error('yolo_label')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
 
        {{-- Stok & Harga --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Stok &amp; Harga</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Stok Saat Ini</label>
                    <input type="text" value="{{ $product->stok_pajangan }} {{ $product->satuan }} — {{ $product->isAda() ? 'Ada' : 'Tidak Ada' }}" disabled
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-500 text-sm font-black font-mono shadow-inner">
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Diubah lewat menu Stok Masuk / Stok Keluar, bukan dari form ini.</p>
                </div>
 
                <div>
                    <label for="harga" class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">
                        Harga Jual <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-slate-500 font-bold text-sm">Rp</span>
                        <input type="number" id="harga" name="harga" value="{{ $product->harga }}" min="0" required
                               class="w-full pl-12 pr-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition text-sm font-black font-mono text-slate-800">
                    </div>
                    @error('harga')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
 
        {{-- Foto Produk --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Gambar Produk</h3>
                </div>
            </div>
            <div class="p-5 space-y-4">
                <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-400 rounded-2xl p-6 text-center bg-slate-50 hover:bg-blue-50/40 transition-all cursor-pointer group shadow-inner">
                    <input type="file" id="foto" name="foto" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                    <input type="hidden" id="hapus_foto" name="hapus_foto" value="0">

                    {{-- Wrapper Preview + Tombol Hapus (X) --}}
                    <div id="previewContainer" class="{{ $product->foto ? '' : 'hidden' }} relative inline-block group/preview mb-2">
                        <img id="previewFoto"
                             src="{{ $product->foto ? asset('storage/' . $product->foto) : '' }}"
                             alt="{{ $product->nama_produk }}"
                             onerror="this.style.setProperty('display','none','important'); document.getElementById('previewContainer').style.setProperty('display','none','important'); document.getElementById('placeholderFoto').style.setProperty('display','block','important');"
                             class="h-32 w-32 object-cover rounded-xl mx-auto border-2 border-slate-200 shadow-md group-hover:scale-105 transition duration-200">
                        <button type="button" id="removeFotoBtn"
                                class="absolute -top-2 -right-2 bg-rose-500 hover:bg-rose-600 text-white p-1.5 rounded-full shadow-md transition duration-200 hover:scale-110 z-20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div id="placeholderFoto" class="{{ $product->foto ? 'hidden' : '' }} text-slate-400 py-4">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="font-bold text-xs uppercase tracking-wide text-slate-500">📷 Klik / Seret untuk Ganti Foto</p>
                        <p class="text-[11px] text-slate-400 mt-1">Format gambar JPG/PNG, Maksimal file 4MB</p>
                    </div>
                </div>
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                @enderror
 
 
                {{-- INFO ACTION FORM SEBELUMNYA DI SINI DIHAPUS & DI-PINDAH KE FORM TERPISAH DI BAWAH --}}
                @if ($product->status_ai === 'proses_training')
                    <div class="w-full rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-700 text-xs font-semibold shadow-sm flex items-center gap-2">
                        <span>⏳</span> Permintaan deployment model/deteksi sedang dieksekusi sistem.
                    </div>
                @endif
            </div>
        </div>
 
        {{-- Form Action Footer Utama --}}
        <div class="flex gap-3 justify-end pt-2">
            <a href="{{ route('owner.products.index') }}"
               class="px-5 py-2.5 text-slate-600 border-2 border-slate-200 rounded-xl hover:bg-slate-50 font-bold transition text-xs uppercase tracking-wider active:scale-95">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg font-bold transition text-xs uppercase tracking-wider active:scale-95">
                💾 Simpan Perubahan
            </button>
        </div>
    </form>
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- EXTERNAL SECONDARY FORM (MINTA DETEKSI AI) --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if (in_array($product->status_ai, ['belum_dilatih', 'belum_siap']))
        <form action="{{ route('owner.products.mintaDeteksi', $product) }}" method="POST" class="w-full">
            @csrf
            <div class="bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="text-center sm:text-left">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Model Siap Di-training?</h4>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Kirim sinyal antrean ke pelatih model YOLOv8 untuk mendaftarkan objek ini.</p>
                </div>
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition active:scale-95 shadow-md shadow-emerald-700/20 flex items-center justify-center gap-2">
                    🧠 Minta Deteksi AI
                </button>
            </div>
        </form>
    @endif
 
</div>
 
@push('scripts')
<script>
(function() {
    const fotoInput        = document.getElementById('foto');
    const previewContainer = document.getElementById('previewContainer');
    const previewFoto       = document.getElementById('previewFoto');
    const placeholderFoto   = document.getElementById('placeholderFoto');
    const removeFotoBtn     = document.getElementById('removeFotoBtn');
    const hapusFotoInput    = document.getElementById('hapus_foto');

    function showPreview() {
        if (previewContainer) previewContainer.style.setProperty('display', 'inline-block', 'important');
        if (previewFoto) previewFoto.style.setProperty('display', 'block', 'important');
        if (placeholderFoto) placeholderFoto.style.setProperty('display', 'none', 'important');
    }

    function showPlaceholder() {
        if (previewContainer) previewContainer.style.setProperty('display', 'none', 'important');
        if (placeholderFoto) placeholderFoto.style.setProperty('display', 'block', 'important');
    }

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            previewFoto.src = URL.createObjectURL(file);
            showPreview();
            hapusFotoInput.value = '0'; // batal hapus kalau user pilih foto baru
        });
    }

    if (removeFotoBtn) {
        removeFotoBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah klik menembus ke file input di belakangnya
            fotoInput.value = '';               // reset file yang lagi dipilih (kalau ada)
            previewFoto.src = '';
            showPlaceholder();
            hapusFotoInput.value = '1';         // tandai: hapus foto lama pas disimpan
        });
    }
 
    window.updateDetectionPreview = function (value) {
        const preview = document.getElementById('detectionPreview');
        const icon    = document.getElementById('detectionIcon');
        const label   = document.getElementById('detectionLabel');
        if (!preview) return;
        
        if (value.trim() !== '') {
            preview.className = 'flex items-center gap-2 mb-4 px-3 py-2 rounded-lg text-xs font-bold transition-all duration-300 bg-violet-100/70 text-violet-800 border border-violet-300';
            if (icon) icon.textContent  = '🤖';
            if (label) label.textContent = 'Produk terikat kamera: ' + value.trim();
        } else {
            preview.className = 'flex items-center gap-2 mb-4 px-3 py-2 rounded-lg text-xs font-bold transition-all duration-300 bg-slate-100 text-slate-500 border border-slate-300';
            if (icon) icon.textContent  = '✋';
            if (label) label.textContent = 'Label kosong — produk tidak akan terdeteksi visual';
        }
    };
 
    const btnAmbilFoto = document.getElementById('btnAmbilFoto');
    if (btnAmbilFoto) {
        btnAmbilFoto.addEventListener('click', function () {
            const btn = this;
            const url = btn.dataset.url;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = '⏳ Mengambil foto...';
 
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('#formProduk input[name="_token"]').value,
                    'Accept': 'application/json',
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal mengambil foto');
                return res.json().catch(() => ({}));
            })
            .then(() => location.reload())
            .catch(() => {
                alert('Gagal mengambil foto dari kamera. Cek koneksi ke Raspberry Pi Anda.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }
})();
</script>
@endpush
@endsection
