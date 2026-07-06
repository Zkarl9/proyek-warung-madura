<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Product;
use App\Models\StockOut;

class DashboardController extends Controller
{
    public function index()
    {
        $stokTipis = Product::stokTipis()->orderBy('stok_pajangan')->get();
        $totalProduk = Product::count();
        $itemTerjualHariIni = StockOut::whereDate('created_at', today())->sum('jumlah');
        $recentSales = StockOut::with('product')->latest()->limit(10)->get();

        $barangTerlaris = StockOut::selectRaw('product_id, sum(jumlah) as total_terjual')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $grafikMingguan = StockOut::selectRaw('DATE(created_at) as tanggal, SUM(jumlah) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $pengumuman = Announcement::whereDoesntHave('dismissedBy', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'stokTipis', 'totalProduk', 'itemTerjualHariIni', 'recentSales', 'barangTerlaris', 'grafikMingguan', 'pengumuman'
        ));
    }
}