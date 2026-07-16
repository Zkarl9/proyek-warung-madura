<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
 
class ReportController extends Controller
{
    /**
     * Menentukan rentang tanggal laporan.
     * Mendukung preset cepat (hari_ini, minggu_ini, bulan_ini, bulan_lalu)
     * dan menukar otomatis kalau "dari" > "sampai" biar tidak error/kosong.
     */
    private function resolvePeriode(Request $request): array
    {
        if ($request->filled('preset')) {
            switch ($request->input('preset')) {
                case 'hari_ini':
                    return [now()->startOfDay(), now()->endOfDay()];
                case 'minggu_ini':
                    return [now()->startOfWeek(), now()->endOfWeek()];
                case 'bulan_ini':
                    return [now()->startOfMonth(), now()->endOfMonth()];
                case 'bulan_lalu':
                    return [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()];
            }
        }
 
        $dariTanggal = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampaiTanggal = Carbon::parse($request->input('sampai', now()->endOfMonth()->toDateString()));
 
        // Kalau "dari" lebih besar dari "sampai", tukar otomatis
        if ($dariTanggal->greaterThan($sampaiTanggal)) {
            [$dariTanggal, $sampaiTanggal] = [$sampaiTanggal, $dariTanggal];
        }
 
        return [$dariTanggal, $sampaiTanggal];
    }
 
    public function index(Request $request)
    {
        [$dariTanggal, $sampaiTanggal] = $this->resolvePeriode($request);
        $productId = $request->input('product_id');
 
        $stockIn = StockMovement::masuk()->with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->orderByDesc('created_at')
            ->get();
 
        $stockOut = StockMovement::keluar()->with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->orderByDesc('created_at')
            ->get();
 
        // ===== Net Perubahan Stok =====
        $totalMasuk  = $stockIn->sum('jumlah');
        $totalKeluar = $stockOut->sum('jumlah');
        $netStok     = $totalMasuk - $totalKeluar;
 
        // ===== Omzet =====
        // Cuma dihitung dari stok keluar dengan alasan "terjual" — barang rusak/kadaluarsa/retur bukan pemasukan.
        $stockOutTerjual = $stockOut->where('alasan', 'terjual');
        $omzet = $stockOutTerjual->sum(fn ($item) => $item->jumlah * ($item->product->harga ?? 0));
 
        // ===== HPP & Laba Kotor =====
        // HPP dihitung per produk pakai rata-rata TERTIMBANG harga beli dari seluruh riwayat Stok Masuk
        // produk itu (bukan cuma dalam periode laporan ini). Produk yang belum pernah diisi harga beli
        // di Stok Masuk-nya akan dilewati dari perhitungan HPP (dianggap tidak diketahui, bukan 0).
        $hpp = 0;
        $adaProdukTanpaHargaBeli = false;
 
        foreach ($stockOutTerjual->groupBy('product_id') as $productId => $items) {
            $produk = $items->first()->product;
            if (! $produk) {
                continue;
            }
 
            $rataRataHargaBeli = $produk->hargaBeliRataRata();
            $jumlahTerjual = $items->sum('jumlah');
 
            if ($rataRataHargaBeli === null) {
                $adaProdukTanpaHargaBeli = true;
                continue;
            }
 
            $hpp += $jumlahTerjual * $rataRataHargaBeli;
        }
 
        $labaKotor = $omzet - $hpp;
        $marginPersen = $omzet > 0 ? round(($labaKotor / $omzet) * 100, 1) : 0;
 
        // ===== Rasio Deteksi Otomatis vs Manual =====
        $totalOtomatis  = $stockOut->where('tipe', 'otomatis')->sum('jumlah');
        $totalManual    = $stockOut->where('tipe', 'manual')->sum('jumlah');
        $persenOtomatis = $totalKeluar > 0 ? round($totalOtomatis / $totalKeluar * 100, 1) : 0;
        $persenManual   = $totalKeluar > 0 ? round($totalManual / $totalKeluar * 100, 1) : 0;
 
        // ===== Produk Paling Sering Keluar (Top 5), dilengkapi omzet & laba per produk =====
        $produkTerlaris = $stockOut
            ->groupBy('product_id')
            ->map(function ($items) {
                $produk = $items->first()->product;
                $terjual = $items->where('alasan', 'terjual');
                $jumlahTerjual = $terjual->sum('jumlah');
                $omzetProduk = $terjual->sum(fn ($item) => $item->jumlah * ($produk->harga ?? 0));
 
                $rataRataHargaBeli = $produk?->hargaBeliRataRata();
                $labaProduk = $rataRataHargaBeli !== null
                    ? $omzetProduk - ($jumlahTerjual * $rataRataHargaBeli)
                    : null;
 
                return [
                    'nama_produk' => $produk->nama_produk ?? '—',
                    'satuan'      => $produk->satuan ?? '',
                    'total'       => $items->sum('jumlah'),
                    'omzet'       => $omzetProduk,
                    'laba'        => $labaProduk,
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();
 
        // ===== Data Grafik Tren Harian =====
        $grafikHarian = collect();
        $period = CarbonPeriod::create($dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->startOfDay());
        foreach ($period as $tanggal) {
            $tgl = $tanggal->toDateString();
            $grafikHarian->push([
                'tanggal' => $tgl,
                'masuk'   => $stockIn->filter(fn ($i) => $i->created_at->toDateString() === $tgl)->sum('jumlah'),
                'keluar'  => $stockOut->filter(fn ($i) => $i->created_at->toDateString() === $tgl)->sum('jumlah'),
            ]);
        }
 
        $products = Product::orderBy('nama_produk')->get(['id', 'nama_produk']);
 
        return view('owner.report.index', compact(
            'stockIn',
            'stockOut',
            'dariTanggal',
            'sampaiTanggal',
            'netStok',
            'totalMasuk',
            'totalKeluar',
            'omzet',
            'hpp',
            'labaKotor',
            'marginPersen',
            'adaProdukTanpaHargaBeli',
            'totalOtomatis',
            'totalManual',
            'persenOtomatis',
            'persenManual',
            'produkTerlaris',
            'grafikHarian',
            'products',
            'productId',
        ));
    }
 
    public function cetak(Request $request)
    {
        [$dariTanggal, $sampaiTanggal] = $this->resolvePeriode($request);
        $productId = $request->input('product_id');
 
        $stockIn = StockMovement::masuk()->with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->get();
 
        $stockOut = StockMovement::keluar()->with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->get();
 
        return view('owner.report.cetak', compact('stockIn', 'stockOut', 'dariTanggal', 'sampaiTanggal'));
    }
}
