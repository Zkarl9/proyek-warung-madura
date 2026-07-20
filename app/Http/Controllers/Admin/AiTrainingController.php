<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AiTrainingController extends Controller
{
    public function index()
    {
        $datasets    = Storage::disk('public')->files('datasets');
        $models      = Storage::disk('public')->files('ai_models');
        $produk      = Product::orderBy('nama_produk')->get();
        $requests    = Product::where('status_ai', 'proses_training')
            ->orderByDesc('diminta_deteksi_at')
            ->get();

        return view('admin.training.index', compact('datasets', 'models', 'produk', 'requests'));
    }

    public function downloadDataset(string $filename)
    {
        $path = "datasets/{$filename}";
        abort_unless(Storage::disk('public')->exists($path), 404);
        return Storage::disk('public')->download($path);
    }

    public function uploadModel(Request $request)
    {
        $request->validate([
            'model_file'  => ['required', 'file', 'extensions:pt', 'max:512000'],
            'product_ids' => ['array'],
        ]);

        $request->file('model_file')->store('ai_models', 'public');

        if ($request->filled('product_ids')) {
            Product::whereIn('id', $request->product_ids)
                ->update(['status_ai' => 'siap_deteksi']);
        }

        return back()->with('status', 'Model berhasil diupload.');
    }

    public function approveRequest(Product $product)
    {
        if ($product->status_ai !== 'proses_training') {
            return back()->with('error', 'Produk tidak dalam status permintaan deteksi.');
        }

        $product->update(['status_ai' => 'siap_deteksi']);

        return back()->with('status', "Produk {$product->nama_produk} telah ditandai siap deteksi. Owner bisa mengecek statusnya langsung di halaman Produk.");
    }
}
