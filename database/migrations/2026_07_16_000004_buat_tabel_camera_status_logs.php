<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['ada', 'habis']);
            $table->timestamp('created_at')->useCurrent();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('camera_status_logs');
    }
};
