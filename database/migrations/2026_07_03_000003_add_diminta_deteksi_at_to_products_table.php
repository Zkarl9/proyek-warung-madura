<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'diminta_deteksi_at')) {
                $table->timestamp('diminta_deteksi_at')->nullable()->after('status_ai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'diminta_deteksi_at')) {
                $table->dropColumn('diminta_deteksi_at');
            }
        });
    }
};
