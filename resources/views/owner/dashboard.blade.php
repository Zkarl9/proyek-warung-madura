@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan stok warung hari ini')

@section('content')
<div class="space-y-4">

    {{-- PENGUMUMAN DARI ADMIN --}}
    @if ($pengumuman->isNotEmpty())
        @foreach ($pengumuman as $pengumumanItem)
        <div id="announcementCard-{{ $pengumumanItem->id }}" class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-600 rounded-l-xl"></div>
            <div class="pl-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 bg-blue-600 text-white rounded-full">📢 Pengumuman Admin</span>
                            <span class="text-xs text-slate-400">{{ $pengumumanItem->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="font-bold text-blue-900 text-sm">{{ $pengumumanItem->judul }}</p>
                        <p class="text-sm text-blue-700 mt-0.5">{{ $pengumumanItem->isi }}</p>
                        @if ($pengumumanItem->label_ids)
                        <div class="mt-2">
                            <p class="text-xs text-blue-600 font-semibold mb-1">🏷️ Label YOLO yang bisa digunakan:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach (explode(',', $pengumumanItem->label_ids) as $label)
                                <code class="px-2.5 py-1 bg-white border border-blue-300 text-blue-700 text-xs font-mono rounded-lg font-bold">
                                    {{ trim($label) }}
                                </code>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('owner.products.index') }}"
                           class="flex-shrink-0 text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                            Lihat Produk
                        </a>
                        <button type="button" class="dismissAnnouncement flex-shrink-0 text-xs font-semibold bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-200 transition"
                            data-announcement-id="{{ $pengumumanItem->id }}">
                            Oke
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg md:text-2xl font-bold">📊 Dashboard</h2>
                <p class="text-blue-100 text-sm mt-0.5">{{ now()->translatedFormat('l, d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Produk</p>
            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $totalProduk }}</p>
            <p class="text-xs text-slate-400 mt-1">produk terdaftar</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Stok Tipis</p>
            <p class="text-3xl font-bold mt-1 {{ $stokTipis->count() > 0 ? 'text-red-500' : 'text-green-600' }}">
                {{ $stokTipis->count() }}
            </p>
            <p class="text-xs text-slate-400 mt-1">perlu restock</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Terjual 7 Hari</p>
            <p class="text-3xl font-bold text-blue-600 mt-1 font-mono">{{ $grafikMingguan->sum('total') }}</p>
            <p class="text-xs text-slate-400 mt-1">item terjual</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Keluar Hari Ini</p>
            <p class="text-3xl font-bold text-slate-900 mt-1 font-mono">{{ $itemTerjualHariIni }}</p>
            <p class="text-xs text-slate-400 mt-1">item terdeteksi</p>
        </div>
    </div>

    {{-- STOK TIPIS + GRAFIK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Stok Tipis --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Stok Tipis</h3>
            </div>
            <div class="p-5">
                @if ($stokTipis->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-2xl mb-1">✅</p>
                        <p class="text-sm text-slate-400">Semua stok masih aman.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($stokTipis as $produk)
                        @php $persen = $produk->stok_minimum > 0 ? min(100, ($produk->stok_pajangan / $produk->stok_minimum) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-medium text-slate-800 truncate">{{ $produk->nama_produk }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-mono font-bold ml-2 flex-shrink-0
                                    {{ $produk->stok_pajangan == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $produk->stok_pajangan }} {{ $produk->satuan }}
                                </span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $produk->stok_pajangan == 0 ? 'bg-red-500' : 'bg-amber-400' }}"
                                     style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Grafik Mingguan --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Tren Barang Keluar (7 Hari)</h3>
            </div>
            <div class="p-5">
                <canvas id="grafikMingguan" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- BARANG TERLARIS --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Barang Terlaris</h3>
        </div>
        <div class="p-5">
            @if ($barangTerlaris->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">Belum ada data penjualan.</p>
            @else
            <div class="space-y-3">
                @php $maks = $barangTerlaris->max('total_terjual'); @endphp
                @foreach ($barangTerlaris as $index => $item)
                <div class="flex items-center gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-slate-800 truncate">{{ $item->product->nama_produk ?? '—' }}</span>
                            <span class="text-sm font-mono font-bold text-blue-600 ml-2 flex-shrink-0">{{ $item->total_terjual }}</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-blue-500"
                                 style="width: {{ $maks > 0 ? ($item->total_terjual / $maks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- LOG AKTIVITAS --}}
    <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Log Aktivitas Terakhir</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($recentSales as $sale)
            <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $sale->product->nama_produk ?? '—' }}</p>
                    <p class="text-xs text-slate-400 font-mono">{{ $sale->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                    <span class="text-sm font-mono font-bold text-red-500">-{{ $sale->jumlah }}</span>
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full
                        {{ $sale->tipe === 'otomatis' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $sale->tipe === 'otomatis' ? '🤖 AI' : '✋ Manual' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-sm text-slate-400">
                Belum ada pergerakan stok terdeteksi.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const announcementKey = 'ownerAnnouncementDismissed';
    const announcementIds = @json($pengumuman->pluck('id')->values());

    function getDismissedAnnouncements() {
        try {
            const stored = JSON.parse(localStorage.getItem(announcementKey));
            if (Array.isArray(stored)) {
                return stored.map(Number).filter((value) => !Number.isNaN(value));
            }
            if (typeof stored === 'number') {
                return [stored];
            }
            if (typeof stored === 'string' && stored.trim().length > 0) {
                const parsed = Number(stored);
                return Number.isNaN(parsed) ? [] : [parsed];
            }
            return [];
        } catch {
            return [];
        }
    }

    function isAnnouncementDismissed(id) {
        return getDismissedAnnouncements().includes(Number(id));
    }

    function dismissAnnouncement(id) {
        const dismissed = getDismissedAnnouncements();
        const numericId = Number(id);
        if (!dismissed.includes(numericId)) {
            dismissed.push(numericId);
            localStorage.setItem(announcementKey, JSON.stringify(dismissed));
        }
    }

    function hideAnnouncementCard(id) {
        document.getElementById(`announcementCard-${id}`)?.classList.add('hidden');
    }

    announcementIds.forEach(function (id) {
        if (isAnnouncementDismissed(id)) {
            hideAnnouncementCard(id);
        }
    });

    document.querySelectorAll('.dismissAnnouncement').forEach(function (button) {
        button.addEventListener('click', function () {
            const id = this.dataset.announcementId;
            if (!id) return;
            dismissAnnouncement(id);
            hideAnnouncementCard(id);
        });
    });

    new Chart(document.getElementById('grafikMingguan'), {
        type: 'line',
        data: {
            labels: {!! json_encode($grafikMingguan->pluck('tanggal')->map(fn ($t) => \Carbon\Carbon::parse($t)->translatedFormat('d M'))) !!},
            datasets: [{
                label: 'Barang Keluar',
                data: {!! json_encode($grafikMingguan->pluck('total')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                tension: 0.35,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } },
            }
        }
    });
</script>
@endpush