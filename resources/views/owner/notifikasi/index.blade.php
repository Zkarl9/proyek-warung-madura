@extends('layouts.app')

@section('title', 'Setting')
@section('page-title', 'Setting')
@section('page-subtitle', 'Atur ke mana alert stok tipis dikirim')

@section('content')

@php
    $waConfigured       = !empty($setting->wa_number) && !empty($setting->fonnte_token);
    $telegramConfigured = !empty($setting->telegram_chat_id);
@endphp

<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg md:text-2xl font-bold truncate">🔔 Pengaturan Notifikasi</h2>
                <p class="text-blue-100 text-sm mt-0.5">Atur ke mana alert stok tipis dikirim</p>
            </div>
            <span class="flex-shrink-0 px-3 py-1 text-xs font-bold rounded-full
                {{ $setting->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-500' }}">
                {{ $setting->is_active ? '✓ Aktif' : '✕ Nonaktif' }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('owner.notification.update') }}" class="space-y-4">
        @csrf

        {{-- Toggle Aktif --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-5">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full flex-shrink-0
                        {{ $setting->is_active ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Notifikasi Aktif</p>
                        <p class="text-xs text-slate-500 mt-0.5">Matikan sementara jika tidak ingin menerima alert stok tipis.</p>
                    </div>
                </div>

                {{-- Toggle switch --}}
                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           class="sr-only peer" @checked($setting->is_active)>
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer-checked:bg-blue-600 transition-colors duration-200"></div>
                    <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                </label>
            </div>
        </div>

        {{-- WhatsApp --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">WhatsApp (Fonnte)</h3>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                    {{ $waConfigured ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $waConfigured ? '✓ Terhubung' : 'Belum diisi' }}
                </span>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp Tujuan</label>
                    <input type="text" name="wa_number"
                           value="{{ old('wa_number', $setting->wa_number) }}"
                           placeholder="6281234567890"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                    <p class="text-xs text-slate-400 mt-1.5">Format: 628xxxxxxxxxx (tanpa + atau 0 di depan)</p>
                    @error('wa_number')
                        <p class="text-xs text-red-500 mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Token Fonnte</label>
                    <input type="text" name="fonnte_token"
                           value="{{ old('fonnte_token', $setting->fonnte_token) }}"
                           placeholder="Token dari dashboard fonnte.com"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                    <p class="text-xs text-slate-400 mt-1.5">Ambil token dari dashboard <code class="bg-slate-100 px-1 rounded">fonnte.com</code></p>
                    @error('fonnte_token')
                        <p class="text-xs text-red-500 mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Telegram --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-blue-400"></span>
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">
                        Telegram
                        <span class="ml-1 text-xs font-normal text-slate-400 normal-case">(opsional)</span>
                    </h3>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                    {{ $telegramConfigured ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $telegramConfigured ? '✓ Terhubung' : 'Belum diisi' }}
                </span>
            </div>
            <div class="p-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Chat ID</label>
                    <input type="text" name="telegram_chat_id"
                           value="{{ old('telegram_chat_id', $setting->telegram_chat_id) }}"
                           placeholder="Contoh: 123456789"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm font-mono">
                    <p class="text-xs text-slate-400 mt-1.5">Dapatkan Chat ID dengan mengirim pesan ke <code class="bg-slate-100 px-1 rounded">@userinfobot</code> di Telegram.</p>
                    @error('telegram_chat_id')
                        <p class="text-xs text-red-500 mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg font-semibold transition text-sm">
                💾 Simpan Pengaturan
            </button>
        </div>

    </form>
</div>
@endsection