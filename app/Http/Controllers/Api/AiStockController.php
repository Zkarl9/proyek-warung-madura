<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
            'detections.*.count' => ['required', 'integer', 'min:0'],
        ]);
        $graceSeconds = (int) config('services.raspi.flicker_grace_seconds', 5);
        $hasil = [];
        foreach ($request->detections as $item) {
            $product = Product::where('yolo_label', $item['product_label'])->first();
            if (! $product) {
                continue;
            }

            $incoming = (int) $item['count'];
            $confirmed = $product->stok_pajangan;
            $flickerKey = "stock_flicker:{$product->id}";

            if ($incoming === $confirmed) {
                $hasil[] = [
                    'yolo_label' => $product->yolo_label,
                    'stok_pajangan' => $product->stok_pajangan,
                    'stok_tipis' => $product->isStokTipis(),
                ];
                continue;
            }

            if ($incoming > $confirmed) {
                $jejak = Cache::get($flickerKey);

                if ($jejak && $jejak['nilai_sebelum'] === $incoming && (now()->timestamp - $jejak['at']) <= $graceSeconds) {
                    DB::transaction(function () use ($product, $incoming, $jejak) {
                        StockOut::where('id', $jejak['record_id'])->delete();
                        $product->update(['stok_pajangan' => $incoming]);
                    });
                    Cache::forget($flickerKey);
                    $hasil[] = [
                        'yolo_label' => $product->yolo_label,
                        'stok_pajangan' => $product->stok_pajangan,
                        'stok_tipis' => $product->isStokTipis(),
                        'catatan' => 'dibatalkan_gangguan_sesaat',
                    ];
                    continue;
                }

                $hasil[] = [
                    'yolo_label' => $product->yolo_label,
                    'stok_pajangan' => $product->stok_pajangan,
                    'stok_tipis' => $product->isStokTipis(),
                    'catatan' => 'kenaikan_diabaikan_input_manual_diperlukan',
                ];
                continue;
            }

            $selisih = $confirmed - $incoming;
            $record = null;
            DB::transaction(function () use ($product, $item, $selisih, &$record) {
                $product->update(['stok_pajangan' => $item['count']]);
                $record = StockOut::create([
                    'product_id' => $product->id,
                    'jumlah' => $selisih,
                    'tipe' => 'otomatis',
                    'keterangan' => 'Terdeteksi otomatis oleh kamera',
                ]);
            });

            Cache::put($flickerKey, [
                'record_id' => $record->id,
                'nilai_sebelum' => $confirmed,
                'at' => now()->timestamp,
            ], now()->addSeconds($graceSeconds));

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

    protected function kirimNotifikasiStokMenipis(Product $product, int $sisaStok): void
    {
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
