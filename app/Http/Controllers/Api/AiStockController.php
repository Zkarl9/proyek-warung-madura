<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
 
class AiStockController extends Controller
{
    public function update(Request $request)
    {
        abort_unless($request->bearerToken() === config('services.raspi.api_key'), 401);
 
        $request->validate([
            'camera_id' => ['required'],
            'zone' => ['required', 'string'],
            'detections' => ['required', 'array'],
            'detections.*.product_label' => ['required', 'string'],
            'detections.*.status' => ['required', 'in:ada,habis'],
        ]);
 
        // Jendela stabilisasi (anti-flicker): status baru baru dikonfirmasi
        // kalau bertahan minimal sekian detik berturut-turut.
        $graceSeconds = (int) config('services.raspi.flicker_grace_seconds', 8);
        $hasil = [];
 
        foreach ($request->detections as $item) {
            $product = Product::where('yolo_label', $item['product_label'])->first();
            if (! $product) {
                continue;
            }
 
            $statusBaru = $item['status'];
            $statusTersimpan = $product->status_kamera;
            $debounceKey = "status_kamera_pending:{$product->id}";
 
            // Status sama dengan yang tersimpan, tidak ada perubahan.
            if ($statusBaru === $statusTersimpan) {
                Cache::forget($debounceKey);
                $hasil[] = $this->ringkasan($product);
                continue;
            }
 
            $pending = Cache::get($debounceKey);
 
            // Status baru sudah konsisten sejak deteksi sebelumnya & sudah lewat jendela stabilisasi -> konfirmasi.
            if ($pending && $pending['status'] === $statusBaru && (now()->timestamp - $pending['at']) >= $graceSeconds) {
                $product->update(['status_kamera' => $statusBaru]);
                Cache::forget($debounceKey);
 
                // Catat riwayat perubahan status — dipakai buat panel "Riwayat Deteksi" di halaman Kamera Live.
                \App\Models\CameraStatusLog::create([
                    'product_id' => $product->id,
                    'status' => $statusBaru,
                    'created_at' => now(),
                ]);
 
                if ($statusBaru === 'habis') {
                    $this->kirimNotifikasiBarangHabis($product);
                } else {
                    // Balik terdeteksi ada lagi -> reset supaya notifikasi bisa terkirim ulang kalau habis lagi nanti.
                    Cache::forget("telegram_notif_sent:{$product->id}");
                }
 
                $hasil[] = $this->ringkasan($product);
                continue;
            }
 
            // Belum stabil, mulai/lanjutkan hitung mundur jendela stabilisasi.
            if (! $pending || $pending['status'] !== $statusBaru) {
                Cache::put($debounceKey, [
                    'status' => $statusBaru,
                    'at' => now()->timestamp,
                ], now()->addSeconds($graceSeconds + 10));
            }
 
            $hasil[] = $this->ringkasan($product) + ['catatan' => 'menunggu_stabilisasi'];
        }
 
        return response()->json(['status' => 'ok', 'data' => $hasil]);
    }
 
    protected function ringkasan(Product $product): array
    {
        return [
            'yolo_label' => $product->yolo_label,
            'status_kamera' => $product->status_kamera,
        ];
    }
 
    protected function kirimNotifikasiBarangHabis(Product $product): void
    {
        $notifKey = "telegram_notif_sent:{$product->id}";
        if (Cache::has($notifKey)) {
            return;
        }
        Cache::put($notifKey, true, now()->addMinutes(15));
 
        $pesan = "🔴 *BARANG HABIS*\n\n"
            . "📦 Produk: {$product->nama_produk}\n"
            . "📷 Terdeteksi kosong di rak oleh kamera\n"
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
