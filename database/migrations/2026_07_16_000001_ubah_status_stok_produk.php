<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('status_kamera', ['ada', 'habis'])->default('ada')->after('stok_pajangan');
            $table->dropColumn('stok_minimum');
        });
    }
 
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stok_minimum')->default(5)->after('stok_pajangan');
            $table->dropColumn('status_kamera');
        });
    }
};
