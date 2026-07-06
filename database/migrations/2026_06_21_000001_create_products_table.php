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

            // Angka mentah terakhir dari kamera YOLO â€” hanya referensi, bukan stok resmi.
            // Tidak pernah dipakai untuk kalkulasi stok langsung.
            $table->unsignedInteger('stok_deteksi_terakhir')->nullable()
                  ->comment('Angka mentah terakhir dari kamera YOLO â€” hanya referensi, bukan stok resmi');

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
