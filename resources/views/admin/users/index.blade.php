@extends('layouts.app')

@section('title', 'Akun Owner')
@section('page-title', 'Akun Owner')
@section('page-subtitle', 'Kelola akun pemilik warung yang bisa login')

@section('content')
<div class="space-y-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-4 md:p-6 text-white shadow-lg">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg md:text-2xl font-bold truncate">👤 Akun Owner</h2>
                <p class="text-blue-100 text-sm mt-0.5">
                    Total: <strong>{{ $users->total() ?? $users->count() }}</strong> akun terdaftar
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="flex-shrink-0 flex items-center gap-1.5 bg-white text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 transition shadow text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah
            </a>
        </div>
    </div>

    {{-- CARD GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($users as $user)
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 space-y-3">

            {{-- Row 1: Avatar + Nama --}}
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-slate-900 text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                </div>
            </div>

            {{-- Row 2: Detail --}}
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-slate-50 rounded-lg px-3 py-2">
                    <p class="text-xs text-slate-500">No. HP</p>
                    <p class="text-sm font-mono font-semibold text-slate-800 truncate">{{ $user->phone ?? '—' }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-3 py-2">
                    <p class="text-xs text-slate-500">Bergabung</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Row 3: Aksi --}}
            <div class="flex gap-2 pt-1 border-t border-slate-100">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="flex-1 text-center text-blue-600 hover:bg-blue-50 font-semibold text-sm py-2 rounded-lg transition border border-blue-200">
                    ✏️ Ubah
                </a>
                <button onclick="bukaModalHapus({{ $user->id }}, '{{ $user->name }}')"
                    class="flex-1 text-red-600 hover:bg-red-50 font-semibold text-sm py-2 rounded-lg transition border border-red-200">
                    🗑️ Hapus
                </button>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-slate-200 shadow px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm font-semibold">Belum ada akun owner</p>
                <p class="text-xs">Klik "Tambah" untuk membuat akun owner baru</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="flex justify-center">
        {{ $users->links() }}
    </div>
    @endif

</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    onclick="if(event.target===this) tutupModalHapus()">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5 rounded-t-2xl">
            <h3 class="text-lg font-bold text-white">🗑️ Hapus Akun?</h3>
        </div>
        <div class="p-5 space-y-4">
            <p class="text-sm text-slate-600">
                <strong id="namaUserHapus" class="text-slate-900"></strong> tidak akan bisa login lagi setelah dihapus.
            </p>
            <form id="formHapus" method="POST" class="flex gap-3 justify-end pt-2 border-t border-slate-200">
                @csrf
                @method('DELETE')
                <button type="button" onclick="tutupModalHapus()"
                    class="px-5 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-semibold transition text-sm">
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