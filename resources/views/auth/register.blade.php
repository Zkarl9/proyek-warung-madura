<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar · StockVision</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-base">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-surface rounded-2xl border border-mist p-8 shadow-sm">
                <div class="mb-8">
                    <h1 class="text-3xl font-display font-bold text-ink mb-2">Daftar Akun</h1>
                    <p class="text-sm text-ink/70">Buat akun baru untuk mengelola warung Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-terracotta-soft border border-terracotta rounded-lg">
                        <ul class="list-disc list-inside space-y-1 text-sm text-terracotta">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 p-4 bg-teal-soft border border-teal rounded-lg text-teal-deep text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-ink mb-2">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="Budi Santoso"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-ink mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="budi@warung.test"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-ink mb-2">Nomor WhatsApp (Opsional)</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="62812345678"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-ink mb-2">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-ink mb-2">Konfirmasi Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="••••••••"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full px-6 py-2.5 bg-teal rounded-lg text-surface font-medium text-sm hover:bg-teal-deep transition-colors font-display"
                    >
                        Daftar
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-mist text-center">
                    <p class="text-sm text-ink/70">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-teal font-medium hover:text-teal-deep">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
