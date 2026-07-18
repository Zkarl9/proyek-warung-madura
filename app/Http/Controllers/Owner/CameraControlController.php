<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CameraStatusLog;
use App\Models\Product;
use App\Support\RaspiStatus;
use Illuminate\Support\Facades\Http;

class CameraControlController extends Controller
{
    // Base URL Raspberry Pi — diambil dari .env via config/services.php
    protected string $raspiUrl;

    public function __construct()
    {
        $this->raspiUrl = config('services.raspi.base_url');
    }

    public function live()
    {
        $data = $this->ambilDataTerkini();

        return view('owner.camera.live', $data);
    }

    // Endpoint JSON dipoll oleh JS di halaman Kamera Live setiap beberapa detik,
    // supaya panel "Produk Sedang Habis" & "Riwayat Deteksi" bisa auto-update
    // tanpa reload halaman.
    public function data()
    {
        $data = $this->ambilDataTerkini();

        return response()->json([
            'ok' => true,
            'status_perangkat' => $data['statusPerangkat'],
            'total_dipantau_kamera' => $data['totalDipantauKamera'],
            'sedang_habis' => $data['sedangHabis']->map(fn (Product $p) => [
                'id' => $p->id,
                'nama_produk' => $p->nama_produk,
                'yolo_label' => $p->yolo_label,
            ])->values(),
            'riwayat_deteksi' => $data['riwayatDeteksi']->map(fn (CameraStatusLog $log) => [
                'id' => $log->id,
                'nama_produk' => $log->product->nama_produk ?? 'Produk telah dihapus',
                'status' => $log->status,
                'waktu_relatif' => $log->created_at->diffForHumans(),
                'timestamp' => $log->created_at->toIso8601String(),
            ])->values(),
        ]);
    }

    protected function ambilDataTerkini(): array
    {
        // Kamera sekarang cuma update status_kamera (ada/habis) + kirim notif — tidak lagi bikin catatan StockMovement.
        // Jadi yang ditampilkan di sini adalah kondisi TERKINI, bukan riwayat transaksi.
        $sedangHabis = Product::whereNotNull('yolo_label')
            ->where('status_kamera', 'habis')
            ->orderBy('nama_produk')
            ->get();

        $totalDipantauKamera = Product::whereNotNull('yolo_label')->count();

        $riwayatDeteksi = CameraStatusLog::with('product')
            ->latest('created_at')
            ->limit(20)
            ->get();

        $statusPerangkat = RaspiStatus::isOnline() ? 'online' : 'offline';

        return compact('sedangHabis', 'totalDipantauKamera', 'riwayatDeteksi', 'statusPerangkat');
    }

    public function start()
    {
        try {
            Http::timeout(5)->post("{$this->raspiUrl}/start");
            return back()->with('status', 'Deteksi AI berhasil dijalankan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi Raspberry Pi: ' . $e->getMessage());
        }
    }

    public function stop()
    {
        try {
            Http::timeout(5)->post("{$this->raspiUrl}/stop");
            return back()->with('status', 'Deteksi AI berhasil dihentikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi Raspberry Pi: ' . $e->getMessage());
        }
    }
}
