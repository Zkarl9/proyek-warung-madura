<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('arah', ['masuk', 'keluar'])->comment('Gabungan stock_ins (masuk) & stock_outs (keluar) jadi satu tabel');
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga_beli')->nullable()->comment('Cuma diisi kalau arah=masuk');
            $table->string('sumber')->nullable()->comment('agen/distributor/retur — cuma diisi kalau arah=masuk');
            $table->enum('alasan', ['terjual', 'rusak', 'kadaluarsa', 'retur', 'lainnya'])->nullable()->comment('Cuma diisi kalau arah=keluar');
            $table->enum('sumber_catatan', ['manual', 'otomatis'])->default('manual')->comment('manual=diinput owner. otomatis=arsip lama dari kamera, fitur ini sudah tidak dipakai lagi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
 
        // ===== Migrasi data lama, TIDAK menghapus riwayat yang sudah ada =====
        if (Schema::hasTable('stock_ins')) {
            DB::statement("
                INSERT INTO stock_movements
                    (product_id, user_id, arah, jumlah, harga_beli, sumber, alasan, sumber_catatan, keterangan, created_at, updated_at)
                SELECT
                    product_id, user_id, 'masuk', jumlah, harga_beli, sumber, NULL, 'manual', keterangan, created_at, updated_at
                FROM stock_ins
            ");
        }
 
        if (Schema::hasTable('stock_outs')) {
            DB::statement("
                INSERT INTO stock_movements
                    (product_id, user_id, arah, jumlah, harga_beli, sumber, alasan, sumber_catatan, keterangan, created_at, updated_at)
                SELECT
                    product_id, user_id, 'keluar', jumlah, NULL, NULL, alasan, tipe, keterangan, created_at, updated_at
                FROM stock_outs
            ");
        }
 
        Schema::dropIfExists('stock_outs');
        Schema::dropIfExists('stock_ins');
    }
 
    public function down(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga_beli')->nullable();
            $table->string('sumber')->default('agen');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
 
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('jumlah');
            $table->enum('tipe', ['manual', 'otomatis'])->default('otomatis');
            $table->enum('alasan', ['terjual', 'rusak', 'kadaluarsa', 'retur', 'lainnya'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
 
        DB::statement("
            INSERT INTO stock_ins (product_id, user_id, jumlah, harga_beli, sumber, keterangan, created_at, updated_at)
            SELECT product_id, user_id, jumlah, harga_beli, sumber, keterangan, created_at, updated_at
            FROM stock_movements WHERE arah = 'masuk'
        ");
 
        DB::statement("
            INSERT INTO stock_outs (product_id, user_id, jumlah, tipe, alasan, keterangan, created_at, updated_at)
            SELECT product_id, user_id, jumlah, sumber_catatan, alasan, keterangan, created_at, updated_at
            FROM stock_movements WHERE arah = 'keluar'
        ");
 
        Schema::dropIfExists('stock_movements');
    }
};
