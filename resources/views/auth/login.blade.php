<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · StockVision</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .box-glow {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.25);
        }
        .pulse-green {
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(52, 211, 153, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.22),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.20),_transparent_30%)]"></div>
        <div class="absolute top-10 left-10 h-40 w-40 rounded-full bg-blue-500/15 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 h-52 w-52 rounded-full bg-emerald-500/15 blur-3xl"></div>

        <div class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-md rounded-[28px] border border-white/70 bg-white/90 p-8 shadow-[0_25px_80px_rgba(15,23,42,0.25)] backdrop-blur-xl sm:p-10">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 shadow-lg shadow-blue-500/20">
                        <svg class="h-10 w-10 text-white" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="12" y="20" width="40" height="28" rx="6" fill="currentColor" opacity="0.95"/>
                            <path d="M20 20L24 12H40L44 20" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="32" cy="34" r="10" stroke="white" stroke-width="4"/>
                            <circle cx="32" cy="34" r="4" fill="white"/>
                            <path d="M16 28H20" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            <path d="M44 28H48" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Masuk ke StockVision</h2>
                    <p class="mt-2 text-sm text-slate-500">Kelola stok warung dengan dashboard yang cepat, aman, dan modern.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 p-4 text-sm text-rose-700 flex items-start gap-3">
                        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700 flex items-start gap-3">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Alamat Email</label>
                        <div class="group relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 transition-colors duration-200 group-focus-within:text-blue-600"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-3 pl-11 pr-4 text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                placeholder="admin@stockvision.test"
                            >
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 hover:underline">Lupa sandi?</a>
                            @endif
                        </div>
                        <div class="group relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 transition-colors duration-200 group-focus-within:text-blue-600"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-3 pl-11 pr-4 text-slate-900 outline-none transition-all duration-200 placeholder:text-slate-400 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="flex cursor-pointer select-none items-center gap-2.5">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="h-4 w-4 rounded-md border-slate-300 text-blue-600 transition focus:ring-blue-500/20"
                            >
                            <span class="text-sm font-medium text-slate-600">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:translate-y-[-1px] hover:shadow-xl hover:shadow-blue-500/30"
                    >
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="mt-6 rounded-2xl border border-slate-200/70 bg-slate-50/70 px-4 py-3 text-center text-sm text-slate-500">
                    Akses aman untuk admin dan owner.
                </div>
            </div>
        </div>
    </div>
</body>
</html>