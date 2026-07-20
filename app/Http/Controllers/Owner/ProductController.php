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
            'yolo_label' => ['nullable', 'string', 'max:255', 'unique:products,yolo_label'],
            'kategori' => ['nullable', 'string'],
            'stok_pajangan' => ['required', 'integer', 'min:0'],
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
            'yolo_label' => ['nullable', 'string', 'max:255', 'unique:products,yolo_label,' . $product->id],
            'kategori' => ['nullable', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:4096'],
            'hapus_foto' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('foto')) {
            // Upload foto baru — hapus foto lama kalau ada.
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('products', 'public');
        } elseif ($request->boolean('hapus_foto') && $product->foto) {
            // Tidak upload foto baru, tapi user menekan tombol hapus (X) — bersihkan foto lama.
            Storage::disk('public')->delete($product->foto);
            $data['foto'] = null;
        }

        unset($data['hapus_foto']);
        $product->update($data);

        return redirect()->route('owner.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        // Bersihkan cache debounce & notifikasi kamera biar tidak nyangkut kalau ID dipakai ulang.
        \Illuminate\Support\Facades\Cache::forget("status_kamera_pending:{$product->id}");
        \Illuminate\Support\Facades\Cache::forget("telegram_notif_sent:{$product->id}");

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

        \Illuminate\Support\Facades\Notification::send(
            \App\Models\User::where('role', 'admin')->get(),
            new \App\Notifications\PermintaanDeteksiProduk($product)
        );

        return back()->with('status', 'Permintaan deteksi telah dikirim ke admin. Cek status "Proses Training / Siap Deteksi" di halaman produk ini secara berkala.');
    }
}
