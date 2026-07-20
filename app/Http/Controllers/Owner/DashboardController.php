<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $barangHabis = Product::barangHabisGabungan()->orderBy('nama_produk')->get();
        $totalProduk = Product::count();

        // Omzet cuma dihitung dari stok keluar dengan alasan "terjual" — barang rusak/kadaluarsa/retur tidak dihitung sebagai pemasukan.
        $omzetHariIni = StockMovement::keluar()->where('alasan', 'terjual')
            ->whereDate('created_at', today())
            ->with('product')
            ->get()
            ->sum(fn ($item) => $item->jumlah * ($item->product->harga ?? 0));

        $recentSales = StockMovement::keluar()->with('product')->latest()->limit(10)->get();

        $barangTerlaris = StockMovement::keluar()->selectRaw('product_id, sum(jumlah) as total_terjual')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $grafikMingguan = StockMovement::keluar()->selectRaw('DATE(created_at) as tanggal, SUM(jumlah) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('owner.dashboard', compact(
            'barangHabis', 'totalProduk', 'omzetHariIni', 'recentSales', 'barangTerlaris', 'grafikMingguan'
        ));
    }
}
