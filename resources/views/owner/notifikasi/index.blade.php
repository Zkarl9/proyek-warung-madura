@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')
@section('page-title', 'Konfigurasi Sistem')
@section('page-subtitle', 'Manajemen operasional notifikasi & parameter sensorik stok')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- HEADER KONSOL --}}
    <div class="relative bg-slate-900 rounded-2xl p-6 text-white shadow-2xl border border-slate-800">
        <div class="relative z-10">
            <h2 class="text-2xl font-black tracking-tight">⚙️ Pengaturan Sistem</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Sistem otomatisasi notifikasi dan ambang batas stok.</p>
        </div>
        <div class="absolute right-4 top-4">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full text-xs font-black uppercase tracking-wider text-white/80">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Sistem Online
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('owner.notification.update') }}" class="space-y-6">
        @csrf

        {{-- KARTU STATUS --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xl">🤖</div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Mode Notifikasi Aktif</h3>
                    <p class="text-[11px] font-bold text-slate-400">Aktifkan untuk menerima peringatan stok tipis via Telegram.</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked($setting->is_active)>
                <div class="w-14 h-7 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-all duration-300"></div>
                <div class="absolute left-1 top-1 h-5 w-5 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-7"></div>
            </label>
        </div>

        {{-- TELEGRAM CONFIG --}}
        <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200/80 overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-blue-500">➤</span> Konfigurasi Saluran Telegram
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Chat ID Tujuan</label>
                    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $setting->telegram_chat_id) }}"
                           placeholder="Contoh: 123456789"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 transition font-mono font-bold text-sm">
                    <p class="text-[11px] font-bold text-slate-400 mt-2">Dapatkan Chat ID dengan mengirim pesan ke <code class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">@userinfobot</code></p>
                </div>
            </div>
        </div>

     
</div>
@endsection