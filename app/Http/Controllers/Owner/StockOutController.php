<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockOut;

class StockOutController extends Controller
{
  public function index()
{
    $riwayat = StockOut::with('product')
        ->latest()
        ->paginate(10);

    return view('owner.stock-out.index', compact('riwayat'));
}
}