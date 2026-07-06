<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Notifications\PermintaanDeteksiProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        return view('owner.product.index', compact('products'));
    }

    public function create()
    {
        return view('owner.product.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'yolo_label' => ['required', 'string', 'max:255', 'unique:products,yolo_label'],
            'kategori' => ['nullable', 'string'],
            'stok_pajangan' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('owner.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('owner.product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'yolo_label' => ['required', 'string', 'max:255', 'unique:products,yolo_label,' . $product->id],
            'kategori' => ['nullable', 'string'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('owner.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return back()->with('status', 'Produk berhasil dihapus.');
    }

    public function mintaDeteksi(Product $product)
    {
        if ($product->status_ai === 'siap_deteksi') {
            return back()->with('status', 'Produk sudah siap deteksi.');
        }

        if ($product->status_ai === 'proses_training') {
            return back()->with('status', 'Permintaan deteksi sudah terkirim dan sedang diproses oleh admin.');
        }

        $product->update([
            'diminta_deteksi_at' => now(),
            'status_ai' => 'proses_training',
        ]);

        Notification::send(
            User::where('role', 'admin')->get(),
            new PermintaanDeteksiProduk($product)
        );

        return back()->with('status', 'Permintaan deteksi telah dikirim ke admin. Silakan tunggu pengumuman selanjutnya.');
    }

    public function ambilFoto(Request $request, Product $product)
    {
        $raspiUrl = config('services.raspi.base_url') . '/capture/' . $product->yolo_label;

        try {
            $response = Http::timeout(10)->asForm()->post($raspiUrl, [
                'product_id' => $product->id,
            ]);

            $message = $response->successful()
                ? 'Raspi mulai mengambil 50 foto untuk training di background. Prosesnya butuh sekitar 15–30 detik, cek lagi nanti.'
                : 'Raspi merespons tapi gagal memulai proses (HTTP ' . $response->status() . ').';

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => $response->successful() ? 'ok' : 'error',
                    'message' => $message,
                ], $response->successful() ? 200 : 422);
            }

            return back()->with($response->successful() ? 'status' : 'error', $message);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $message = 'Gagal menghubungi Raspberry Pi. Pastikan Raspi menyala dan satu jaringan.';

            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }
    }
}