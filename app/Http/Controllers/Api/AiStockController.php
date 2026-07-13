<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class AiStockController extends Controller
{
    /**
     * Dipanggil script Python di Raspi setiap hasil deteksi YOLOv8 berubah.
     *
     * CATATAN PERUBAHAN (real-time sync):
     * Debounce per-produk 5 detik yang dulu ada di sini SUDAH DIHAPUS.
     * Filtering "apakah ini perubahan nyata" sekarang sepenuhnya
     * ditangani di sisi Python (send_detection_loop di app.py) — Python
     * hanya mengirim item ke endpoint ini kalau count-nya benar-benar
     * berubah dari baseline terakhir. Jadi setiap request yang sampai
     * ke sini dianggap valid dan diproses langsung, tanpa ditahan lagi,
     * supaya stok_pajangan di web selalu sesuai kondisi kamera saat itu.
     *
     * CATATAN TAMBAHAN (stok masuk otomatis):
     * Sebelumnya hanya penurunan stok yang dicatat (StockOut). Sekarang
     * kenaikan stok (barang dikembalikan/ditambah di depan kamera) juga
     * dicatat sebagai StockIn dengan sumber 'otomatis', supaya laporan
     * stok masuk & keluar selalu akurat dan seimbang.
     */
    public function update(Request $request)
    {
        // Flask kirim token via header "Authorization: Bearer ..." — BUKAN field api_key di body
        abort_unless($request->bearerToken() === config('services.raspi.api_key'), 401);
        $request->validate([
            'camera_id' => ['required'],
            'zone' => ['required', 'string'],
            'detections' => ['required', 'array'],
            'detections.*.product_label' => ['required', 'string'],
            'detections.*.count' => ['required', 'integer', 'min:0'],
        ]);
        // Catatan: status Online/Offline ('raspi_last_seen') sudah ditangani
        // oleh endpoint /raspberry/heartbeat (dipanggil tiap 20 detik oleh Python).
        // Jangan tulis ulang cache key ini di sini — kalau ditulis sebagai objek
        // Carbon mentah (bukan string ISO 8601), RaspiStatus::isOnline() akan
        // gagal unserialize dan melempar __PHP_Incomplete_Class error.
        $hasil = [];
        foreach ($request->detections as $item) {
            $product = Product::where('yolo_label', $item['product_label'])->first();
            if (! $product) {
                // TODO: label ini kedeteksi kamera tapi belum ada Product yang
                // yolo_label-nya cocok. Silent skip. Kalau mau ketahuan di log:
                // Log::info("AI Stock: label '{$item['product_label']}' tidak terdaftar di Produk.");
                continue;
            }
            $selisih = $product->stok_pajangan - $item['count'];
            DB::transaction(function () use ($product, $item, $selisih) {
                $product->update(['stok_pajangan' => $item['count']]);
                // Histori stok keluar/masuk dicatat untuk SEMUA perubahan
                // (biar laporan & "Aktivitas Terbaru" akurat, bukan hanya saat menipis)
                if ($selisih > 0) {
                    StockOut::create([
                        'product_id' => $product->id,
                        'jumlah' => $selisih,
                        'tipe' => 'otomatis',
                        'keterangan' => 'Hasil deteksi YOLOv8 real-time',
                    ]);
                } elseif ($selisih < 0) {
                    StockIn::create([
                        'product_id' => $product->id,
                        'user_id' => null,
                        'jumlah' => abs($selisih),
                        'sumber' => 'otomatis',
                        'keterangan' => 'Hasil deteksi YOLOv8 real-time (barang dikembalikan)',
                    ]);
                }
            });
            // Threshold FULL ngikutin "Stok Minimum" yang diisi per produk
            // lewat web (Tambah/Edit Produk) — bukan lagi angka global dari Python.
            if ($product->isStokTipis()) {
                $this->kirimNotifikasiStokMenipis($product, (int) $item['count']);
            }
            $hasil[] = [
                'yolo_label' => $product->yolo_label,
                'stok_pajangan' => $product->stok_pajangan,
                'stok_tipis' => $product->isStokTipis(),
            ];
        }
        return response()->json(['status' => 'ok', 'data' => $hasil]);
    }
    /**
     * Kirim notifikasi stok menipis ke semua owner yang sudah
     * mengisi Telegram Chat ID & mengaktifkan notifikasi (lewat halaman web).
     */
    protected function kirimNotifikasiStokMenipis(Product $product, int $sisaStok): void
    {
        // Debounce khusus notifikasi: jangan kirim spam tiap kali stok berubah
        // buat produk yang sama selama masih di bawah minimum.
        $notifKey = "telegram_notif_sent:{$product->id}";
        if (Cache::has($notifKey)) {
            return;
        }
        Cache::put($notifKey, true, now()->addMinutes(15));
        $pesan = "⚠️ *STOK MENIPIS*\n\n"
            . "📦 Produk: {$product->nama_produk}\n"
            . "🔢 Sisa stok: {$sisaStok} pcs\n"
            . "🕒 " . now()->format('d M Y, H:i') . " WIB";
        $chatIds = NotificationSetting::where('is_active', true)
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id');
        foreach ($chatIds as $chatId) {
            try {
                Http::post("https://api.telegram.org/bot" . config('services.telegram.bot_token') . "/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $pesan,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (\Exception $e) {
                Log::warning("Gagal kirim notifikasi Telegram ke {$chatId}: " . $e->getMessage());
            }
        }
    }
}
