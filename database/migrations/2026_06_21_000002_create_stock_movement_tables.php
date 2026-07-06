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
