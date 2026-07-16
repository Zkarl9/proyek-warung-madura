<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
 
class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockMovement::masuk()
            ->with('product', 'user')
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
            'harga_beli' => ['nullable', 'integer', 'min:0'],
            'sumber'     => ['required', 'in:agen,distributor,retur'],
            'keterangan' => ['nullable', 'string'],
        ]);
 
        DB::transaction(function () use ($data, $request) {
            StockMovement::create([
                ...$data,
                'arah'    => 'masuk',
                'user_id' => $request->user()->id,
            ]);
 
            $product = Product::find($data['product_id']);
            $product->increment('stok_pajangan', $data['jumlah']);
 
            // Barang sudah diisi ulang secara fisik -> anggap status kamera balik "ada"
            // dan reset debounce/notifikasi supaya kalau nanti habis lagi, notif langsung kekirim.
            $product->update(['status_kamera' => 'ada']);
            Cache::forget("telegram_notif_sent:{$product->id}");
            Cache::forget("status_kamera_pending:{$product->id}");
        });
 
        return redirect()->route('owner.stock-in.index')->with('status', 'Stok masuk berhasil dicatat.');
    }
 
    public function edit(StockMovement $stockIn)
    {
        abort_unless($stockIn->arah === 'masuk', 404);
 
        $products = Product::orderBy('nama_produk')->get();
        $sumber = ['agen', 'distributor', 'retur'];
 
        return view('owner.stock-in.edit', compact('stockIn', 'products', 'sumber'));
    }
 
    public function update(Request $request, StockMovement $stockIn)
    {
        abort_unless($stockIn->arah === 'masuk', 404);
 
        $data = $request->validate([
            'jumlah'     => ['required', 'integer', 'min:1'],
            'harga_beli' => ['nullable', 'integer', 'min:0'],
            'sumber'     => ['required', 'in:agen,distributor,retur'],
            'keterangan' => ['nullable', 'string'],
        ]);
 
        DB::transaction(function () use ($data, $stockIn) {
            $product = $stockIn->product;
            $selisih = $data['jumlah'] - $stockIn->jumlah;
 
            // Selisih positif nambah stok, negatif mengurangi (kalau jumlah direvisi turun).
            $product->increment('stok_pajangan', $selisih);
 
            $stockIn->update($data);
        });
 
        return redirect()->route('owner.stock-in.index')->with('status', 'Catatan stok masuk berhasil diperbarui.');
    }
 
    public function destroy(StockMovement $stockIn)
    {
        abort_unless($stockIn->arah === 'masuk', 404);
 
        DB::transaction(function () use ($stockIn) {
            $product = $stockIn->product;
 
            // Batalkan efeknya ke stok, tapi jangan sampai minus.
            if ($product && $product->stok_pajangan >= $stockIn->jumlah) {
                $product->decrement('stok_pajangan', $stockIn->jumlah);
            }
 
            $stockIn->delete();
        });
 
        return back()->with('status', 'Catatan stok masuk berhasil dihapus.');
    }
}
