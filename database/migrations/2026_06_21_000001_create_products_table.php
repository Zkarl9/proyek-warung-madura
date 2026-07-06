<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('yolo_label')->unique()->comment('Label kelas model YOLOv8, misal: indomie_goreng');
            $table->string('kategori')->nullable();
            $table->unsignedInteger('stok_pajangan')->default(0)->comment('Stok aktual hasil deteksi kamera');
            $table->unsignedInteger('stok_minimum')->default(5)->comment('Ambang batas trigger notifikasi stok tipis');
            $table->unsignedBigInteger('harga')->default(0);
            $table->string('satuan')->default('pcs');
            $table->string('foto')->nullable();
            $table->enum('status_ai', ['belum_dilatih', 'proses_training', 'siap_deteksi'])->default('belum_dilatih');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};