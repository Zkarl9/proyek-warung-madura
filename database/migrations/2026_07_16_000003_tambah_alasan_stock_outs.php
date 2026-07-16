<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->enum('alasan', ['terjual', 'rusak', 'kadaluarsa', 'retur', 'lainnya'])
                  ->nullable()
                  ->after('tipe')
                  ->comment('Alasan barang keluar — dipakai buat hitung omzet (hanya alasan=terjual)');
        });
    }
 
    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropColumn('alasan');
        });
    }
};
