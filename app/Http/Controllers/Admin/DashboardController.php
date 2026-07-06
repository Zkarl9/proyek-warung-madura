<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockOut;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $produkSiapDeteksi = Product::where('status_ai', 'siap_deteksi')->count();
        $totalOwner = User::where('role', 'owner')->count();
        $deteksiHariIni = StockOut::whereDate('created_at', today())->where('tipe', 'otomatis')->sum('jumlah');

        $statusAi = Product::selectRaw('status_ai, count(*) as total')
            ->groupBy('status_ai')
            ->pluck('total', 'status_ai');

        return view('admin.dashboard', compact(
            'totalProduk', 'produkSiapDeteksi', 'totalOwner', 'deteksiHariIni', 'statusAi'
        ));
    }
}
