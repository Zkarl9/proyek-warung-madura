<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->unsignedInteger('harga_beli')->nullable()->after('jumlah')
                  ->comment('Harga beli per satuan saat restock ini — dipakai hitung HPP & Laba Kotor (rata-rata tertimbang)');
        });
    }
 
    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropColumn('harga_beli');
        });
    }
};
