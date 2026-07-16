<?php
 
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AiTrainingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\StockInController;
use App\Http\Controllers\Owner\StockOutController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\NotificationSettingController;
use App\Http\Controllers\Owner\CameraControlController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
 
Route::redirect('/', '/login');
 
// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
 
// Announcement (dismiss - shared antara owner/admin, dipanggil tanpa prefix di JS)
Route::post('/announcements/{announcement}/dismiss', [AnnouncementController::class, 'dismiss'])
    ->middleware('auth')
    ->name('announcements.dismiss');
 
// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
 
    Route::get('/training', [AiTrainingController::class, 'index'])->name('training.index');
    Route::get('/training/download/{filename}', [AiTrainingController::class, 'downloadDataset'])->name('training.download');
    Route::post('/training/upload-model', [AiTrainingController::class, 'uploadModel'])->name('training.upload');
    Route::post('/training/requests/{product}/approve', [AiTrainingController::class, 'approveRequest'])->name('training.approveRequest');
 
    Route::resource('/users', UserController::class)->except('show');
});
 
// Owner
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
 
    Route::resource('/products', ProductController::class);
    Route::post('/products/{product}/minta-deteksi', [ProductController::class, 'mintaDeteksi'])->name('products.mintaDeteksi');
 
    Route::resource('/stock-in', StockInController::class)->except(['show']);
    Route::resource('/stock-out', StockOutController::class)->except(['show']);
 
    Route::get('/laporan', [ReportController::class, 'index'])->name('report.index');
    Route::get('/laporan/cetak', [ReportController::class, 'cetak'])->name('report.cetak');
 
    Route::get('/kamera', function () {
        // Kamera sekarang cuma update status_kamera (ada/habis) + kirim notif — tidak lagi bikin catatan StockOut.
        // Jadi yang ditampilkan di sini adalah kondisi TERKINI, bukan riwayat transaksi.
        $sedangHabis = \App\Models\Product::whereNotNull('yolo_label')
            ->where('status_kamera', 'habis')
            ->orderBy('nama_produk')
            ->get();
 
        $totalDipantauKamera = \App\Models\Product::whereNotNull('yolo_label')->count();
 
        $riwayatDeteksi = \App\Models\CameraStatusLog::with('product')
            ->latest('created_at')
            ->limit(20)
            ->get();
 
        $statusPerangkat = \App\Support\RaspiStatus::isOnline() ? 'online' : 'offline';
 
        return view('owner.camera.live', compact('sedangHabis', 'totalDipantauKamera', 'riwayatDeteksi', 'statusPerangkat'));
    })->name('camera.live');
 
    // Kontrol kamera — hanya menerima POST
    Route::post('/kamera/start',   [CameraControlController::class, 'start'])->name('camera.start');
    Route::post('/kamera/stop',    [CameraControlController::class, 'stop'])->name('camera.stop');
 
    // Fallback — kalau ada GET request nyasar (refresh/back button), redirect aman ke halaman kamera
    Route::get('/kamera/start',   fn () => redirect()->route('owner.camera.live'));
    Route::get('/kamera/stop',    fn () => redirect()->route('owner.camera.live'));
 
    Route::get('/notifikasi', [NotificationSettingController::class, 'index'])->name('notification.index');
    Route::post('/notifikasi', [NotificationSettingController::class, 'update'])->name('notification.update');
});
