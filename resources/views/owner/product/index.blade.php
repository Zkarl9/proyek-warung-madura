@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Kelola daftar barang & label deteksi YOLOv8')


@section('content')
<div class="space-y-4">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg md:text-2xl font-bold truncate">📦 Daftar Produk</h2>
                <p class="text-blue-100 text-sm mt-0.5">
                    Total: <strong>{{ $products->total() ?? $products->count() }}</strong> produk terdaftar
                </p>
            </div>
            <a href="{{ route('owner.products.create') }}"
               class="flex-shrink-0 flex items-center gap-1.5 bg-white text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 transition shadow text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FILTER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
        <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-3 md:gap-3">

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari Produk</label>
                <input type="text" id="searchInput"
                    placeholder="🔍 Nama produk, label YOLO, kategori..."
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status AI</label>
                <select id="statusFilter"
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                    <option value="">Semua Status</option>
                    <option value="belum_dilatih">Belum Dilatih</option>
                    <option value="proses_training">Proses Training</option>
                    <option value="siap_deteksi">Siap Deteksi</option>
                </select>
            </div>
        </div>
    </div>

    @if ($products->count() === 0)
    {{-- ══════════════════════════════════════════════════ --}}
    {{-- EMPTY STATE --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow px-5 py-16 text-center">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm font-semibold">Belum ada produk terdaftar</p>
            <p class="text-xs">Tambahkan produk pertama agar kamera bisa mulai mendeteksinya</p>
            <a href="{{ route('owner.products.create') }}"
               class="mt-2 inline-flex items-center gap-1.5 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
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
            'belum_siap'      => 'bg-slate-100 text-slate-600',
            'belum_dilatih'   => 'bg-slate-100 text-slate-600',
            'proses_training' => 'bg-amber-100 text-amber-700',
            'siap_deteksi'    => 'bg-green-100 text-green-700',
        ];
    @endphp

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MOBILE / TABLET: CARD LIST --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($products as $produk)
        @php
            $stokChipCard = $produk->stok_pajangan == 0
                ? 'bg-red-100 text-red-700'
                : ($produk->isStokTipis() ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700');
            $stokLabelCard = $produk->stok_pajangan == 0 ? '🔴 Habis' : ($produk->isStokTipis() ? '🟡 Tipis' : '✓ Normal');
        @endphp
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 space-y-3"
             data-status="{{ $produk->status_ai }}"
             data-search="{{ strtolower($produk->nama_produk . ' ' . $produk->yolo_label . ' ' . $produk->kategori) }}">

            {{-- Row 1: Foto + Nama + Badge --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                    <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://via.placeholder.com/48' }}"
                         alt="{{ $produk->nama_produk }}"
                         class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-200 flex-shrink-0">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 text-sm leading-tight truncate">{{ $produk->nama_produk }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $produk->kategori ?? '—' }}</p>
                        <code class="inline-block mt-1 px-2 py-0.5 bg-slate-900 text-green-400 rounded text-xs font-mono">
                            {{ $produk->yolo_label }}
                        </code>
                    </div>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 text-xs font-bold rounded-full {{ $stokChipCard }}">
                    {{ $stokLabelCard }}
                </span>
            </div>

            {{-- Row 2: Stats --}}
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-slate-50 rounded-lg px-2 py-2">
                    <p class="text-xs text-slate-500">Stok</p>
                    <p class="font-bold text-slate-900 text-lg leading-tight">{{ $produk->stok_pajangan }}</p>
                    <p class="text-xs text-slate-400">{{ $produk->satuan }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-2 py-2">
                    <p class="text-xs text-slate-500">Min</p>
                    <p class="font-bold text-slate-900 text-lg leading-tight">{{ $produk->stok_minimum }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-2 py-2">
                    <p class="text-xs text-slate-500">Harga</p>
                    <p class="font-bold text-slate-900 text-sm leading-tight">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Row 3: Status AI --}}
            <div class="flex items-center justify-between gap-3">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusBadge[$produk->status_ai] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $statusLabel[$produk->status_ai] ?? 'Status Tidak Diketahui' }}
                </span>
                <div class="flex items-center gap-3">
                    @if (in_array($produk->status_ai, ['belum_dilatih', 'belum_siap']))
                        <form action="{{ route('owner.products.mintaDeteksi', $produk) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-green-600 hover:text-green-800 transition">
                                🧠 Minta Deteksi
                            </button>
                        </form>
                    @elseif ($produk->status_ai === 'proses_training')
                        <span class="text-xs font-semibold text-amber-600">⌛ Menunggu training</span>
                    @endif
                    <form action="{{ route('owner.products.ambilFoto', $produk) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                            📷 Ambil Foto
                        </button>
                    </form>
                </div>
            </div>

            {{-- Row 4: Aksi --}}
            <div class="flex gap-2 pt-1 border-t border-slate-100">
                <a href="{{ route('owner.products.edit', $produk) }}"
                   class="flex-1 text-center text-blue-600 hover:bg-blue-50 font-semibold text-sm py-2 rounded-lg transition border border-blue-200">
                    ✏️ Edit
                </a>
                <button onclick="bukaModalHapus({{ $produk->id }}, '{{ $produk->nama_produk }}')"
                    class="flex-1 text-red-600 hover:bg-red-50 font-semibold text-sm py-2 rounded-lg transition border border-red-200">
                    🗑️ Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>


    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->links() }}
    </div>
    @endif

    @endif

</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- MODAL HAPUS --}}
{{-- ══════════════════════════════════════════════════ --}}
<div id="modalHapus"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    onclick="if(event.target===this) tutupModalHapus()">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5 rounded-t-2xl">
            <h3 class="text-lg font-bold text-white">🗑️ Hapus Produk?</h3>
        </div>
        <div class="p-5 space-y-4">
            <p class="text-sm text-slate-600">
                Produk <strong id="namaProdukHapus" class="text-slate-900"></strong> akan dihapus
                permanen beserta seluruh riwayat fotonya. Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="formHapus" method="POST" class="flex gap-3 justify-end pt-2 border-t border-slate-200">
                @csrf
                @method('DELETE')
                <button type="button" onclick="tutupModalHapus()"
                    class="px-5 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg font-semibold transition text-sm">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // ── Filter ──────────────────────────────────────────────────────────────
    const searchInput  = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    function filterAll() {
        const term   = (searchInput?.value || '').toLowerCase();
        const status = statusFilter?.value || '';

        // Tabel rows
        document.querySelectorAll('#tableBody tr').forEach(function (row) {
            const s = row.dataset.search || '';
            const a = row.dataset.status || '';
            let show = true;
            if (term && !s.includes(term)) show = false;
            if (status && a !== status) show = false;
            row.style.display = show ? '' : 'none';
        });

        // Mobile cards
        document.querySelectorAll('.product-card > div[data-search]').forEach(function (card) {
            const s = card.dataset.search || '';
            const a = card.dataset.status || '';
            let show = true;
            if (term && !s.includes(term)) show = false;
            if (status && a !== status) show = false;
            card.style.display = show ? '' : 'none';
        });
    }

    if (searchInput)  searchInput.addEventListener('keyup', filterAll);
    if (statusFilter) statusFilter.addEventListener('change', filterAll);

    // ── Modal hapus ──────────────────────────────────────────────────────────
    window.bukaModalHapus = function (id, nama) {
        document.getElementById('namaProdukHapus').textContent = nama;
        document.getElementById('formHapus').action = `/owner/products/${id}`;
        document.getElementById('modalHapus').classList.remove('hidden');
    };

    window.tutupModalHapus = function () {
        document.getElementById('modalHapus').classList.add('hidden');
    };
})();
</script>
@endpush
@endsection