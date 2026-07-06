<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom stok_deteksi_terakhir untuk menyimpan angka mentah
     * hasil deteksi kamera — terpisah dari stok_pajangan (stok resmi).
     *
     * Kolom ini HANYA untuk referensi/UI, tidak pernah dipakai
     * untuk kalkulasi stok langsung.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stok_deteksi_terakhir')
                  ->nullable()
                  ->after('stok_pajangan')
                  ->comment('Angka mentah terakhir dari kamera YOLO — hanya referensi, bukan stok resmi');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stok_deteksi_terakhir');
        });
    }
};