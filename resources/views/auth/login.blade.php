<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · StockVision</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-base">
    <div class="min-h-screen grid grid-cols-2 gap-0">
        <!-- Left: Live Preview -->
        <div class="bg-linear-to-br from-teal via-teal-deep to-teal-deep text-surface p-12 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-12">
                    <div class="h-12 w-12 bg-surface/20 rounded-lg flex items-center justify-center font-display font-bold text-lg">
                        SV
                    </div>
                    <div>
                        <h1 class="text-2xl font-display font-bold">StockVision</h1>
                        <p class="text-sm text-surface/80">Monitoring stok Warung Madura berbasis IoT & YOLOv8</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-mono text-surface/70 uppercase tracking-wider mb-4">Pratinjau live</p>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-body">indomie_goreng</span>
                                <span class="text-lg font-mono font-bold">7 pcs</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-body">aqua_600ml</span>
                                <span class="text-lg font-mono font-bold">14 pcs</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-body">kopi_abc</span>
                                <span class="text-lg font-mono font-bold">2 pcs <span class="text-amber ml-2">· tipis</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-xs text-surface/70 flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-2 w-2 bg-surface rounded-full animate-pulse"></span>
                    <span>Raspberry Pi 5</span>
                </div>
                <span>·</span>
                <span>Camera Module V3</span>
                <span>·</span>
                <span>YOLOv8s</span>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="bg-surface p-12 flex flex-col justify-center">
            <div class="max-w-sm">
                <h2 class="text-3xl font-display font-bold text-ink mb-2">Masuk</h2>
                <p class="text-sm text-ink/70 mb-8">Kelola stok warung dari satu dashboard.</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-terracotta-soft border border-terracotta rounded-lg text-terracotta text-sm flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 p-4 bg-teal-soft border border-teal rounded-lg text-teal-deep text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-ink mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-2.5 border border-mist rounded-lg focus:ring-2 focus:ring-teal focus:border-transparent outline-none transition bg-base"
                            placeholder="admin@warung.test"
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

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-4 w-4 text-teal focus:ring-teal border-mist rounded cursor-pointer"
                        >
                        <label for="remember" class="ml-2 block text-sm text-ink/70 cursor-pointer">Ingat saya</label>
                    </div>

                    <button
                        type="submit"
                        class="w-full px-6 py-2.5 bg-teal rounded-lg text-surface font-medium text-sm hover:bg-teal-deep transition-colors font-display"
                    >
                        Masuk
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-mist text-center">
                    <p class="text-sm text-ink/70">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-teal font-medium hover:text-teal-deep">Daftar di sini</a>
                    </p>
                </div>

                <div class="mt-6 p-4 bg-teal-soft rounded-lg border border-teal/30">
                    <p class="text-xs text-teal-deep font-medium mb-2">Demo Credentials:</p>
                    <div class="space-y-1 text-xs text-teal-deep/80 font-mono">
                        <p>👤 Admin: admin@stockvision.test</p>
                        <p>👤 Owner: owner@stockvision.test</p>
                        <p>🔑 Password: password123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>