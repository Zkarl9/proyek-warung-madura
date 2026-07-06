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
        'belum_siap'      => 'bg-slate-100 text-slate-600',
        'belum_dilatih'   => 'bg-slate-100 text-slate-600',
        'proses_training' => 'bg-amber-100 text-amber-700',
        'siap_deteksi'    => 'bg-green-100 text-green-700',
    ][$product->status_ai] ?? 'bg-slate-100 text-slate-600';
@endphp

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
            <div class="min-w-0 flex-1">
                <h2 class="text-lg md:text-2xl font-bold truncate">✏️ Edit: {{ $product->nama_produk }}</h2>
                <div class="flex items-center gap-2 mt-0.5">
                    <p class="text-blue-100 text-sm">Status AI:</p>
                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $statusBadge }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FORM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <form id="formProduk" action="{{ route('owner.products.update', $product) }}" method="POST"
          enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

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
                    <input type="text" id="nama_produk" name="nama_produk" value="{{ $product->nama_produk }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                    @error('nama_produk')
                        <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                        <input type="text" id="kategori" name="kategori" value="{{ $product->kategori }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label for="satuan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm">
                            @foreach (['pcs', 'pack', 'botol', 'sachet', 'dus'] as $satuan)
                                <option value="{{ $satuan }}" {{ $product->satuan === $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                            @endforeach
                        </select>
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
                        Ubah label hanya jika model belum di-training ulang dengan label baru.
                    </p>

                    <div id="detectionPreview"
                         class="flex items-center gap-2 mb-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-300
                                {{ $product->yolo_label ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-500' }}">
                        <span id="detectionIcon">{{ $product->yolo_label ? '🤖' : '✋' }}</span>
                        <span id="detectionLabel">
                            {{ $product->yolo_label
                                ? 'Produk akan dikenali kamera: ' . $product->yolo_label
                                : 'Label kosong — produk tidak akan dikenali kamera' }}
                        </span>
                    </div>

                    <input type="text" id="yolo_label" name="yolo_label" value="{{ $product->yolo_label }}" required
                           placeholder="contoh: aqua_600ml, indomie_goreng"
                           class="w-full px-4 py-2.5 border border-violet-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500 transition font-mono text-sm placeholder-slate-400"
                           oninput="updateDetectionPreview(this.value)">
                    <p class="text-xs text-violet-400 mt-2">
                        💡 Huruf kecil &amp; underscore. Harus sama persis dengan label di model YOLOv8.
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
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok Saat Ini</label>
                        <input type="text" value="{{ $product->stok_pajangan }} {{ $product->satuan }}" disabled
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 text-sm font-mono">
                        <p class="text-xs text-slate-400 mt-1.5">Diperbarui otomatis dari kamera.</p>
                    </div>
                    <div>
                        <label for="stok_minimum" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Stok Minimum <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok_minimum" name="stok_minimum"
                               value="{{ $product->stok_minimum }}" min="0" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
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
                        <input type="number" id="harga" name="harga" value="{{ $product->harga }}" min="0" required
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
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Foto Produk</h3>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $statusBadge }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <div class="p-5 space-y-3">

                <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-400 rounded-xl p-8 text-center bg-slate-50 hover:bg-blue-50 transition-all cursor-pointer group">
                    <input type="file" id="foto" name="foto" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <img id="previewFoto"
                         src="{{ $product->foto ? asset('storage/' . $product->foto) : '' }}"
                         alt="{{ $product->nama_produk }}"
                         class="{{ $product->foto ? '' : 'hidden' }} h-32 w-32 object-cover rounded-xl mx-auto border-2 border-slate-200 shadow mb-2">
                    <div id="placeholderFoto" class="{{ $product->foto ? 'hidden' : '' }} text-sm text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="font-semibold">📷 Klik untuk ganti foto</p>
                        <p class="text-xs text-slate-400 mt-1">JPG atau PNG, maks 4MB</p>
                    </div>
                </div>
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5">⚠ {{ $message }}</p>
                @enderror

                {{-- Ambil Foto dari Kamera (BUKAN form lagi, biar gak nested sama formProduk) --}}
                <button type="button" id="btnAmbilFoto"
                    data-url="{{ route('owner.products.ambilFoto', $product) }}"
                    class="w-full flex items-center justify-center gap-2 py-2.5 border border-blue-300 text-blue-600 font-semibold text-sm rounded-lg hover:bg-blue-50 transition">
                    📷 Ambil Foto dari Kamera
                </button>

                @if (in_array($product->status_ai, ['belum_dilatih', 'belum_siap']))
                    <form action="{{ route('owner.products.mintaDeteksi', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full mt-3 flex items-center justify-center gap-2 py-2.5 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition">
                            🧠 Minta Deteksi AI
                        </button>
                    </form>
                @elseif ($product->status_ai === 'proses_training')
                    <div class="w-full mt-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-700 text-sm">
                        ⏳ Permintaan deteksi sedang diproses oleh admin.
                    </div>
                @endif

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
                💾 Simpan Perubahan
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

    document.getElementById('btnAmbilFoto').addEventListener('click', function () {
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
            alert('Gagal mengambil foto dari kamera. Cek koneksi ke Pi.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
</script>
@endpush
@endsection