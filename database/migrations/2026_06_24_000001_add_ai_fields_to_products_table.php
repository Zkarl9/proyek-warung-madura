<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'yolo_label')) {
                $table->string('yolo_label')->nullable()->after('nama_produk');
            }
            if (!Schema::hasColumn('products', 'status_ai')) {
                $table->string('status_ai')->default('belum_siap')->after('yolo_label');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['yolo_label', 'status_ai']);
        });
    }
};