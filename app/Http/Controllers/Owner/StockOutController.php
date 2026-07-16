<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class StockOutController extends Controller
{
    public function index()
    {
        $riwayat = StockMovement::keluar()
            ->with('product')
            ->latest()
            ->paginate(10);
 
        return view('owner.stock-out.index', compact('riwayat'));
    }
 
    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();
        $alasan = ['terjual', 'rusak', 'kadaluarsa', 'retur', 'lainnya'];
 
        return view('owner.stock-out.create', compact('products', 'alasan'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'alasan'     => ['required', 'in:terjual,rusak,kadaluarsa,retur,lainnya'],
            'keterangan' => ['nullable', 'string'],
        ]);
 
        $product = Product::findOrFail($data['product_id']);
 
        if ($data['jumlah'] > $product->stok_pajangan) {
            return back()->withInput()->withErrors([
                'jumlah' => 'Jumlah keluar tidak boleh melebihi stok saat ini (' . $product->stok_pajangan . ' ' . $product->satuan . ').',
            ]);
        }
 
        DB::transaction(function () use ($data, $product, $request) {
            StockMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $request->user()->id,
                'arah'           => 'keluar',
                'jumlah'         => $data['jumlah'],
                'sumber_catatan' => 'manual',
                'alasan'         => $data['alasan'],
                'keterangan'     => $data['keterangan'] ?? null,
            ]);
 
            $product->decrement('stok_pajangan', $data['jumlah']);
        });
 
        return redirect()->route('owner.stock-out.index')->with('status', 'Stok keluar berhasil dicatat.');
    }
 
    public function edit(StockMovement $stockOut)
    {
        abort_unless($stockOut->arah === 'keluar', 404);
 
        $products = Product::orderBy('nama_produk')->get();
        $alasan = ['terjual', 'rusak', 'kadaluarsa', 'retur', 'lainnya'];
 
        return view('owner.stock-out.edit', compact('stockOut', 'products', 'alasan'));
    }
 
    public function update(Request $request, StockMovement $stockOut)
    {
        abort_unless($stockOut->arah === 'keluar', 404);
 
        $data = $request->validate([
            'jumlah'     => ['required', 'integer', 'min:1'],
            'alasan'     => ['required', 'in:terjual,rusak,kadaluarsa,retur,lainnya'],
            'keterangan' => ['nullable', 'string'],
        ]);
 
        DB::transaction(function () use ($data, $stockOut) {
            $product = $stockOut->product;
            $jumlahLama = $stockOut->jumlah;
            $selisih = $data['jumlah'] - $jumlahLama;
 
            // Kalau jumlah direvisi naik, stok berkurang lagi sebesar selisihnya (dan sebaliknya).
            if ($selisih > 0 && $selisih > $product->stok_pajangan) {
                abort(422, 'Revisi jumlah melebihi stok yang tersedia.');
            }
 
            $product->decrement('stok_pajangan', $selisih);
 
            $stockOut->update([
                'jumlah'     => $data['jumlah'],
                'alasan'     => $data['alasan'],
                'keterangan' => $data['keterangan'],
            ]);
        });
 
        return redirect()->route('owner.stock-out.index')->with('status', 'Catatan stok keluar berhasil diperbarui.');
    }
 
    public function destroy(StockMovement $stockOut)
    {
        abort_unless($stockOut->arah === 'keluar', 404);
 
        DB::transaction(function () use ($stockOut) {
            // Hapus catatan -> kembalikan jumlahnya ke stok pajangan.
            $stockOut->product?->increment('stok_pajangan', $stockOut->jumlah);
            $stockOut->delete();
        });
 
        return back()->with('status', 'Catatan stok keluar berhasil dihapus.');
    }
}
