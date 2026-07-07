@extends('layouts.app')

@section('title', 'Akun Owner')
@section('page-title', 'Akun Owner')
@section('page-subtitle', 'Kelola akun pemilik warung yang bisa login')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2">
                    <span>👤</span> Akun Owner
                </h2>
                <p class="text-blue-200/80 text-xs md:text-sm font-medium mt-1">
                    Total <strong class="text-white">{{ $users->total() ?? $users->count() }}</strong> akun pemilik warung terdaftar
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 bg-white text-slate-900 font-bold py-2.5 px-4 rounded-xl hover:bg-blue-50 transition shadow text-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Owner
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- DAFTAR AKUN --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Daftar Akun</label>
            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-black rounded-lg uppercase">
                {{ $users->total() ?? $users->count() }} Akun
            </span>
        </div>

        @if ($users->isEmpty())
            <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                <div class="w-14 h-14 mx-auto bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-inner mb-3">
                    <svg class="w-7 h-7 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum ada akun owner</h4>
                <p class="text-xs text-slate-400 mt-1">Klik "Tambah Owner" untuk membuat akun owner baru.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($users as $user)
                <div class="p-4 bg-slate-50 rounded-2xl border border-transparent hover:bg-white hover:border-blue-200 transition-all space-y-3">

                    {{-- Row 1: Avatar + Nama --}}
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black flex-shrink-0 shadow-inner border border-slate-700">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-slate-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- Row 2: Detail --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-white rounded-lg px-3 py-2 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. HP</p>
                            <p class="text-sm font-mono font-semibold text-slate-800 truncate">{{ $user->phone ?? '—' }}</p>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bergabung</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Row 3: Aksi --}}
                    <div class="flex gap-2 pt-1 border-t border-slate-200">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="flex-1 text-center text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-300 px-3 py-1.5 rounded-lg transition duration-150 active:scale-95">
                            ✏️ Ubah
                        </a>
                        <button onclick="bukaModalHapus({{ $user->id }}, '{{ $user->name }}')"
                            class="flex-1 text-xs font-bold text-red-700 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-300 px-3 py-1.5 rounded-lg transition duration-150 active:scale-95">
                            🗑️ Hapus
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
            <div class="flex justify-center mt-5">
                {{ $users->links() }}
            </div>
            @endif
        @endif
    </div>

</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    onclick="if(event.target===this) tutupModalHapus()">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 px-6 py-5">
            <h3 class="text-lg font-black text-white flex items-center gap-2">🗑️ Hapus Akun?</h3>
        </div>
        <div class="p-5 space-y-4">
            <p class="text-sm text-slate-600">
                <strong id="namaUserHapus" class="text-slate-900"></strong> tidak akan bisa login lagi setelah dihapus.
            </p>
            <form id="formHapus" method="POST" class="flex gap-3 justify-end pt-2 border-t border-slate-200">
                @csrf
                @method('DELETE')
                <button type="button" onclick="tutupModalHapus()"
                    class="px-5 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-semibold transition text-sm active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-bold transition text-sm active:scale-95">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.bukaModalHapus = function (id, nama) {
        document.getElementById('namaUserHapus').textContent = nama;
        document.getElementById('formHapus').action = `/admin/users/${id}`;
        document.getElementById('modalHapus').classList.remove('hidden');
    };
    window.tutupModalHapus = function () {
        document.getElementById('modalHapus').classList.add('hidden');
    };
</script>
@endpush
@endsection