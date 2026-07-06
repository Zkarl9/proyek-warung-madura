<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengaturan channel notifikasi milik masing-masing user (WA/Telegram)
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('wa_number')->nullable();
            $table->string('fonnte_token')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel notifikasi bawaan Laravel (dipakai oleh Notification facade)
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_settings');
    }
};
