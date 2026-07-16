<?php
 
use App\Http\Controllers\Api\AiStockController;
use App\Http\Controllers\Api\DatasetUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 
// ══════════════════════════════════════════════════
// Endpoint yang dipanggil oleh Raspberry Pi (camera.py)
// ══════════════════════════════════════════════════
 
// Heartbeat — dipanggil tiap 20 detik dari heartbeat_loop()
Route::post('/raspberry/heartbeat', function (Request $request) {
    Cache::put('raspi_last_seen', now()->toIso8601String(), now()->addSeconds(30));
    return response()->json(['ok' => true, 'time' => now()->toTimeString()]);
})->middleware('throttle:60,1');
 
// Update hasil deteksi YOLOv8 real-time
Route::post('/ai/stock-update', [AiStockController::class, 'update'])->name('api.ai.stock.update');
 
// Upload dataset (.zip massal & .jpg satuan)
Route::post('/dataset/upload', [DatasetUploadController::class, 'upload'])->name('api.dataset.upload');
Route::post('/dataset/upload-massal', [DatasetUploadController::class, 'upload'])->name('api.dataset.upload-massal');
Route::post('/dataset/upload-single', [DatasetUploadController::class, 'uploadSingleImage'])->name('api.dataset.upload-single');
// Endpoint untuk Raspberry Pi mengambil daftar label produk yang dipantau kamera
Route::get('/products/camera-labels', function () {
    // Cuma produk yang punya yolo_label (dipantau kamera) — produk manual-saja tidak dikirim ke Pi
    $labels = \App\Models\Product::whereNotNull('yolo_label')->pluck('yolo_label');
    return response()->json([
        'ok' => true,
        'data' => $labels,
    ]);
});
