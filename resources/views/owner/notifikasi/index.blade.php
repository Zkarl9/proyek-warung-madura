@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')
@section('page-title', 'Konfigurasi Sistem')
@section('page-subtitle', 'Manajemen operasional notifikasi & parameter sensorik stok')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 py-6">

    {{-- HEADER KONSOL --}}
    <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-slate-950/95 p-6 shadow-[0_25px_80px_rgba(15,23,42,0.18)] sm:p-8">
        <div class="relative z-10 space-y-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">⚙️ Pengaturan Sistem</h2>
                <p class="mt-2 text-sm text-slate-300 sm:text-base">Sistem otomasi notifikasi dan ambang batas stok untuk warung Anda.</p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white/90 ring-1 ring-white/10 backdrop-blur">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-70"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                </span>
                Sistem Online
            </div>
        </div>
        <div class="hidden sm:block absolute -right-10 top-8 h-44 w-44 rounded-full bg-blue-500/20 blur-3xl"></div>
    </div>

    <form method="POST" action="{{ route('owner.notification.update') }}" class="space-y-6">
        @csrf

        {{-- KARTU STATUS --}}
        <div class="rounded-[26px] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200/10">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-50 text-2xl text-blue-600 shadow-sm shadow-blue-200/60">📡</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Mode Notifikasi Aktif</h3>
                        <p class="mt-1 text-sm text-slate-500">Terima peringatan stok rendah melalui Telegram ketika sistem aktif.</p>
                    </div>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked($setting->is_active)>
                    <div class="h-9 w-16 rounded-full bg-slate-200 transition-colors duration-300 peer-checked:bg-blue-600"></div>
                    <span class="absolute left-1 top-1 h-7 w-7 rounded-full bg-white shadow-sm transition-transform duration-300 peer-checked:translate-x-7"></span>
                </label>
            </div>
        </div>

        {{-- TELEGRAM CONFIG --}}
        <div class="rounded-[26px] border border-slate-200/80 bg-white shadow-sm shadow-slate-200/10 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4">
                <h3 class="text-xs font-black uppercase tracking-[0.32em] text-slate-600 flex items-center gap-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-500 text-white">T</span>
                    Konfigurasi Saluran Telegram
                </h3>
            </div>
            <div class="p-6 space-y-5">
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">Chat ID Tujuan</label>
                    <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $setting->telegram_chat_id) }}"
                           placeholder="Contoh: 123456789"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/10">
                    <p class="text-sm text-slate-500">Dapatkan Chat ID dengan mengirim pesan ke <code class="rounded bg-slate-100 px-1.5 py-0.5 text-slate-700">@userinfobot</code></p>
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-center sm:justify-end">
            <button type="submit"
                    class="w-full max-w-sm rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-blue-500/20 transition duration-200 hover:shadow-blue-500/30 active:scale-95 sm:w-auto">
                Simpan Konfigurasi Sistem
            </button>
        </div>
    </form>
</div>
@endsection