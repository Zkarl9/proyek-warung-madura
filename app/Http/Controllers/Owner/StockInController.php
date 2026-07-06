<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with('product', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('owner.stock-in.index', compact('stockIns'));
    }

    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();
        $sumber = ['agen', 'distributor', 'retur'];

        return view('owner.stock-in.create', compact('products', 'sumber'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'sumber'     => ['required', 'in:agen,distributor,retur'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data, $request) {
            StockIn::create([
                ...$data,
                'user_id' => $request->user()->id,
            ]);

            $product = Product::find($data['product_id']);
            $product->increment('stok_pajangan', $data['jumlah']);

            // Reset debounce notifikasi Telegram kalau stok udah balik aman
            // (di atas stok_minimum), supaya kalau nanti menipis lagi,
            // notif langsung kekirim tanpa nunggu cache 15 menit habis.
            $product->refresh();
            if (! $product->isStokTipis()) {
                Cache::forget("telegram_notif_sent:{$product->id}");
            }
        });

        return redirect()->route('owner.stock-in.index')->with('status', 'Stok masuk berhasil dicatat.');
    }
}