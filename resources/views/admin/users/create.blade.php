@extends('layouts.app')

@section('title', 'Tambah Akun Owner')
@section('page-title', 'Tambah Akun Owner')
@section('page-subtitle', 'Buat akses login baru untuk pemilik warung')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative min-w-0">
            <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                <span>➕</span> Tambah Akun Owner
            </h2>
            <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">
                Buat akses login baru untuk pemilik warung
            </p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- FORM --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-5">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Data Akun</label>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition">
                @error('name') <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition">
                @error('email') <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    No. HP <span class="text-slate-400 normal-case font-medium">(opsional)</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="6281234567890"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition">
                @error('password') <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 border-t border-slate-100 pt-4">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:from-blue-700 hover:to-indigo-700 transition-all shadow active:scale-95">
                    💾 Simpan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition active:scale-95">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection