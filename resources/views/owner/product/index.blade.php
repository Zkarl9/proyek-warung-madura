@extends('layouts.app')
 
@section('title', 'Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Kelola daftar barang & label deteksi YOLOv8')
 
@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER DENGAN SUMMARY STATS --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>📦</span> Daftar Manajemen Produk
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">
                    Total: <span class="bg-blue-500/30 text-blue-200 px-2 py-0.5 rounded-md font-bold text-sm">{{ $products->total() ?? $products->count() }}</span> produk terintegrasi sistem
                </p>
            </div>
            <a href="{{ route('owner.products.create') }}"
               class="flex-shrink-0 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-xl transition duration-200 shadow-md shadow-blue-900/40 active:scale-95 text-sm">
                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FILTER & PENCARIAN --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 relative">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Produk</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none text-sm">🔍</span>
                    <input type="text" id="searchInput"
                           placeholder="Ketik nama produk, label YOLO, atau kategori..."
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-medium text-slate-800 placeholder-slate-400">
                </div>
            </div>
 
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status AI Dataset</label>
                <div class="relative">
                    <select id="statusFilter"
                            class="w-full pl-4 pr-10 py-2.5 bg-slate-50/50 border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white transition duration-200 text-sm font-medium text-slate-800 appearance-none cursor-pointer">
                        <option value="">Semua Status Model</option>
                        <option value="belum_dilatih">🔴 Belum Dilatih</option>
                        <option value="proses_training">🟡 Proses Training</option>
                        <option value="siap_deteksi">🟢 Siap Deteksi</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    @if ($products->count() === 0)
    {{-- EMPTY STATE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md px-6 py-16 text-center max-w-xl mx-auto">
        <div class="flex flex-col items-center justify-center space-y-4">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-inner">
                <svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada produk yang terdaftar</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto leading-relaxed">Tambahkan entitas produk pertama Anda agar modul computer vision YOLOv8 dapat mulai melatih model deteksi visual.</p>
            </div>
            <a href="{{ route('owner.products.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl transition duration-200 text-xs shadow-md">
                <span>➕</span> Tambah Produk Sekarang
            </a>
        </div>
    </div>
    @else
 
    @php
        $statusLabel = [
            'belum_siap'      => 'Belum Siap',
            'belum_dilatih'   => 'Belum Dilatih',
            'proses_training' => 'Proses Training',
            'siap_deteksi'    => 'Siap Deteksi',
        ];
        $statusBadge = [
            'belum_siap'      => 'bg-slate-100 text-slate-600 border border-slate-200',
            'belum_dilatih'   => 'bg-rose-50 text-rose-700 border border-rose-200',
            'proses_training' => 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse',
            'siap_deteksi'    => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        ];
    @endphp
 
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- CARD LAYOUT (SUDAH DI-FIX) --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $produk)
        @php
            $stokChipCard = $produk->isAda()
                ? 'bg-emerald-100 text-emerald-800 border border-emerald-300'
                : 'bg-rose-100 text-rose-800 border border-rose-300';
 
            $stokLabelCard = $produk->isAda() ? '🟢 Ada' : '🔴 Tidak Ada';
        @endphp
        
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-5 space-y-4 flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:border-blue-300 group"
             data-status="{{ $produk->status_ai }}"
             data-search="{{ strtolower($produk->nama_produk . ' ' . $produk->yolo_label . ' ' . $produk->kategori) }}">
 
            <div>
                {{-- Row 1: Foto + Info Utama --}}
                <div class="flex items-start gap-3.5">
                    <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://via.placeholder.com/64' }}"
                         alt="{{ $produk->nama_produk }}"
                         class="w-14 h-14 rounded-xl object-cover bg-slate-50 border border-slate-200 flex-shrink-0 shadow-sm group-hover:scale-105 transition duration-200">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider truncate max-w-[100px]">{{ $produk->kategori ?? 'No Kategori' }}</span>
                            {{-- FIX DI SINI: Mengeluarkan shadow-sm dari penutup variabel Blade --}}
                            <span class="flex-shrink-0 px-2 py-0.5 text-[10px] font-black rounded-lg shadow-sm {{ $stokChipCard }}">
                                {{ $stokLabelCard }}
                            </span>
                        </div>
                        
                        <h4 class="font-extrabold text-slate-900 text-base md:text-lg leading-snug mt-1 break-words" title="{{ $produk->nama_produk }}">
                            {{ $produk->nama_produk }}
                        </h4>
                        
                        {{-- Tag YOLO Token / Manual --}}
                        @if($produk->yolo_label)
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2 py-1 bg-slate-900 text-slate-200 rounded-lg text-[11px] font-bold font-mono shadow-inner border border-slate-700">
                            <span class="text-emerald-400 font-sans text-[9px] uppercase tracking-wider">📷 YOLO Class:</span>
                            <span class="truncate max-w-[130px] font-semibold text-white">{{ $produk->yolo_label }}</span>
                        </div>
                        @else
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-2 py-1 bg-slate-100 text-slate-500 rounded-lg text-[11px] font-bold border border-slate-200">
                            <span class="font-sans text-[9px] uppercase tracking-wider">✋ Manual saja — tidak dipantau kamera</span>
                        </div>
                        @endif
                    </div>
                </div>
 
                {{-- Row 2: Grid Metrik Data Fisik --}}
                <div class="grid grid-cols-2 gap-2 text-center mt-4 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                    <div class="border-r border-slate-200">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Stok</p>
                        <p class="font-extrabold text-slate-800 text-base font-mono mt-0.5">{{ $produk->stok_pajangan }}</p>
                        <p class="text-[9px] text-slate-400 font-medium truncate">{{ $produk->satuan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Harga</p>
                        <p class="font-black text-blue-600 text-xs md:text-sm font-mono mt-1.5 truncate">
                            Rp{{ number_format($produk->harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
 
            {{-- Bagian Action Bawah --}}
            <div class="space-y-3 pt-2">
                {{-- Row 3: Status AI Integrator --}}
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-200">
                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg shadow-sm {{ $statusBadge[$produk->status_ai] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $statusLabel[$produk->status_ai] ?? 'Unknown AI Status' }}
                    </span>
                    
                    <div class="flex items-center gap-2">
                        @if (in_array($produk->status_ai, ['belum_dilatih', 'belum_siap']))
                            <form action="{{ route('owner.products.mintaDeteksi', $produk) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 px-2.5 py-1 rounded-lg transition duration-150 active:scale-95">
                                    🧠 Latih AI
                                </button>
                            </form>
                        @elseif ($produk->status_ai === 'proses_training')
                            <span class="text-[11px] font-bold text-amber-700 flex items-center gap-1 bg-amber-50 border border-amber-300 px-2 py-1 rounded-lg">⌛ Training...</span>
                        @endif
                        
                    </div>
                </div>
 
                {{-- Row 4: Aksi Utama Form Manajemen --}}
                <div class="flex gap-2">
                    <a href="{{ route('owner.products.edit', $produk) }}"
                       class="flex-1 text-center font-bold text-xs py-2 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-600 rounded-xl transition duration-150 active:scale-95 flex items-center justify-center gap-1">
                        ✏️ Edit
                    </a>
                    <button onclick="bukaModalHapus({{ $produk->id }}, '{{ $produk->nama_produk }}')"
                            class="flex-1 text-center font-bold text-xs py-2 bg-rose-50 hover:bg-rose-100 border border-rose-300 text-rose-600 rounded-xl transition duration-150 active:scale-95 flex items-center justify-center gap-1">
                        🗑️ Hapus
                    </button>
                </div>
            </div>
 
        </div>
        @endforeach
    </div>
 
    {{-- Blok Pagination Kustom Tailwind --}}
    @if($products->hasPages())
    <div class="flex justify-center pt-4">
        {{ $products->links() }}
    </div>
    @endif
 
    @endif
</div>
 
{{-- ══════════════════════════════════════════════════ --}}
{{-- MODAL HAPUS --}}
{{-- ══════════════════════════════════════════════════ --}}
<div id="modalHapus"
     class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300"
     onclick="if(event.target===this) tutupModalHapus()">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden border border-slate-200 transform scale-100 transition-transform duration-300">
        <div class="bg-gradient-to-r from-rose-500 to-red-600 px-5 py-4 flex items-center gap-2">
            <span class="text-xl">⚠️</span>
            <h3 class="text-md font-bold text-white">Konfirmasi Hapus</h3>
        </div>
        <div class="p-5 space-y-4">
            <p class="text-xs md:text-sm text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus data produk <strong id="namaProdukHapus" class="text-slate-900 bg-slate-50 px-1.5 py-0.5 border border-slate-300 rounded"></strong> secara permanen? Seluruh data dataset gambar latih juga akan ikut terhapus.
            </p>
            <form id="formHapus" method="POST" class="flex gap-2 justify-end pt-3 border-t border-slate-200">
                @csrf
                @method('DELETE')
                <button type="button" onclick="tutupModalHapus()"
                        class="px-4 py-2 text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 font-bold transition text-xs active:scale-95">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl hover:shadow-lg font-bold transition text-xs active:scale-95">
                    Hapus Total
                </button>
            </form>
        </div>
    </div>
</div>
 
@push('scripts')
<script>
(function () {
    const searchInput  = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const productGrid  = document.getElementById('productGrid');
 
    function filterAll() {
        const term   = (searchInput?.value || '').toLowerCase().trim();
        const status = statusFilter?.value || '';
 
        if (!productGrid) return;
        
        const cards = productGrid.querySelectorAll('[data-search]');
 
        cards.forEach(function (card) {
            const searchText = card.dataset.search || '';
            const cardStatus = card.dataset.status || '';
            
            let matchSearch = term === '' || searchText.includes(term);
            let matchStatus = status === '' || cardStatus === status;
 
            if (matchSearch && matchStatus) {
                card.style.setProperty('display', '', 'important');
            } else {
                card.style.setProperty('display', 'none', 'important');
            }
        });
    }
 
    if (searchInput)  searchInput.addEventListener('input', filterAll);
    if (statusFilter) statusFilter.addEventListener('change', filterAll);
 
    window.bukaModalHapus = function (id, nama) {
        document.getElementById('namaProdukHapus').textContent = nama;
        document.getElementById('formHapus').action = `/owner/products/${id}`;
        
        const modal = document.getElementById('modalHapus');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };
 
    window.tutupModalHapus = function () {
        const modal = document.getElementById('modalHapus');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
})();
</script>
@endpush
@endsection
