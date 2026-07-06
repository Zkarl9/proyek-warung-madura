# Script ini menghapus SEMUA migration lama dan menulis ulang 7 file migration yang benar.
# Jalankan dari root project (folder yang ada file artisan-nya), via PowerShell.

Remove-Item -Path "database\migrations\*.php" -Force

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'owner'])->default('owner');
            $table->string('phone')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
'@
Set-Content -Path "database\migrations\0001_01_01_000000_create_users_table.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
'@
Set-Content -Path "database\migrations\0001_01_01_000001_create_cache_table.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedSmallInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('connection');
            $table->string('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();

            $table->index(['connection', 'queue', 'failed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
'@
Set-Content -Path "database\migrations\0001_01_01_000002_create_jobs_table.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');

            // Label kelas model YOLOv8, misal: indomie_goreng.
            // Nullable karena produk boleh dibuat dulu sebelum dilatih ke model AI.
            $table->string('yolo_label')->nullable()->unique()
                  ->comment('Label kelas model YOLOv8, misal: indomie_goreng');

            // Status kesiapan produk terhadap sistem deteksi AI.
            $table->enum('status_ai', ['belum_dilatih', 'proses_training', 'siap_deteksi'])
                  ->default('belum_dilatih');

            // Kapan terakhir kali admin meminta ulang proses deteksi/training.
            $table->timestamp('diminta_deteksi_at')->nullable();

            $table->string('kategori')->nullable();

            $table->unsignedInteger('stok_pajangan')->default(0)
                  ->comment('Stok aktual hasil deteksi kamera');

            // Angka mentah terakhir dari kamera YOLO — hanya referensi, bukan stok resmi.
            // Tidak pernah dipakai untuk kalkulasi stok langsung.
            $table->unsignedInteger('stok_deteksi_terakhir')->nullable()
                  ->comment('Angka mentah terakhir dari kamera YOLO — hanya referensi, bukan stok resmi');

            $table->unsignedInteger('stok_minimum')->default(5)
                  ->comment('Ambang batas trigger notifikasi stok tipis');

            $table->unsignedBigInteger('harga')->default(0);
            $table->string('satuan')->default('pcs');
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
'@
Set-Content -Path "database\migrations\2026_06_21_000001_create_products_table.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('jumlah');
            $table->string('sumber')->default('agen')->comment('agen, distributor, retur, dll');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('jumlah');
            $table->enum('tipe', ['manual', 'otomatis'])->default('otomatis')
                  ->comment('otomatis = hasil deteksi YOLOv8 dari Raspi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
        Schema::dropIfExists('stock_ins');
    }
};
'@
Set-Content -Path "database\migrations\2026_06_21_000002_create_stock_movement_tables.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengaturan channel notifikasi milik masing-masing user (WA/Telegram)
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('wa_number')->nullable();
            $table->string('fonnte_token')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel notifikasi bawaan Laravel (dipakai oleh Notification facade)
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_settings');
    }
};
'@
Set-Content -Path "database\migrations\2026_06_21_000003_create_notification_tables.php" -Value $content -Encoding UTF8

$content = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('label_ids')->nullable(); // contoh: "aqua_botol, minyak_goreng"
            $table->string('model_file')->nullable(); // path file best.pt
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('announcement_dismissals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'announcement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_dismissals');
        Schema::dropIfExists('announcements');
    }
};
'@
Set-Content -Path "database\migrations\2026_06_21_000004_create_announcements_table.php" -Value $content -Encoding UTF8

Write-Host "Selesai. Sekarang jalankan: php artisan migrate:fresh"