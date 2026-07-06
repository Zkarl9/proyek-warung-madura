<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dariTanggal = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampaiTanggal = Carbon::parse($request->input('sampai', now()->endOfMonth()->toDateString()));

        $stockIn = StockIn::with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get();

        $stockOut = StockOut::with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get();

        return view('owner.report.index', compact('stockIn', 'stockOut', 'dariTanggal', 'sampaiTanggal'));
    }

    public function cetak(Request $request)
    {
        $dariTanggal = Carbon::parse($request->input('dari', now()->startOfMonth()->toDateString()));
        $sampaiTanggal = Carbon::parse($request->input('sampai', now()->endOfMonth()->toDateString()));

        $stockIn = StockIn::with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->get();

        $stockOut = StockOut::with('product')
            ->whereBetween('created_at', [$dariTanggal->copy()->startOfDay(), $sampaiTanggal->copy()->endOfDay()])
            ->get();

        return view('owner.report.cetak', compact('stockIn', 'stockOut', 'dariTanggal', 'sampaiTanggal'));
    }
}