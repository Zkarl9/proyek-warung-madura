@extends('layouts.app')

@section('title', 'Ubah Akun Owner')
@section('page-title', 'Ubah Akun Owner')
@section('page-subtitle', $user->name)

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="card max-w-xl space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium mb-1.5">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
               class="w-full rounded-tag border border-mist px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal">
        @error('name') <p class="text-xs text-terracotta mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               class="w-full rounded-tag border border-mist px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal">
        @error('email') <p class="text-xs text-terracotta mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1.5">No. HP</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
               class="w-full rounded-tag border border-mist px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1.5">Password Baru <span class="text-ink/40">(kosongkan kalau gak diubah)</span></label>
        <input type="password" name="password" minlength="8"
               class="w-full rounded-tag border border-mist px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40 focus:border-teal">
        @error('password') <p class="text-xs text-terracotta mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-3 border-t border-mist pt-4">
        <button type="submit" class="rounded-tag bg-teal px-5 py-2.5 text-sm font-medium text-white hover:bg-teal-deep transition-colors">
            Simpan Perubahan
        </button>
        <a href="{{ route('admin.users.index') }}" class="rounded-tag border border-mist px-5 py-2.5 text-sm hover:bg-base transition-colors">
            Batal
        </a>
    </div>
</form>
@endsection