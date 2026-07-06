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
<body class="bg-slate-50 text-slate-900 antialiased h-full">
    <div class="min-h-screen flex flex-col lg:flex-row">

        <div class="hidden lg:flex lg:w-1/2 bg-[#0a101f] text-white p-12 flex-col justify-between relative overflow-hidden border-r border-slate-800">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#141c2f_1px,transparent_1px),linear-gradient(to_bottom,#141c2f_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-16">
                    <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-base box-glow text-white">
                        SV
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white">StockVision</h1>
                        <p class="text-xs text-slate-400">Monitoring stok Warung Madura berbasis IoT & YOLOv8</p>
                    </div>
                </div>

                <div class="max-w-md">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-md">Pratinjau Live</span>
                        <span class="text-xs text-slate-400 flex items-center gap-2">
                            <span class="h-2 w-2 bg-emerald-400 rounded-full pulse-green"></span> Kamera Terhubung
                        </span>
                    </div>
                    
                    <div class="bg-[#141c2f]/80 backdrop-blur-md rounded-2xl border border-slate-800 p-5 space-y-4 shadow-2xl">
                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-slate-300">indomie_goreng</span>
                            <span class="text-sm font-mono font-semibold text-blue-400 bg-blue-950/50 px-2.5 py-1 rounded-lg border border-blue-900/50">7 pcs</span>
                        </div>
                        <div class="h-px bg-slate-800/60"></div>
                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-slate-300">aqua_600ml</span>
                            <span class="text-sm font-mono font-semibold text-blue-400 bg-blue-950/50 px-2.5 py-1 rounded-lg border border-blue-900/50">14 pcs</span>
                        </div>
                        <div class="h-px bg-slate-800/60"></div>
                        <div class="flex items-center justify-between py-1">
                            <span class="text-sm font-medium text-slate-300">kopi_abc</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">Stok Menipis</span>
                                <span class="text-sm font-mono font-semibold text-amber-400 bg-amber-950/50 px-2.5 py-1 rounded-lg border border-amber-900/50">2 pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 border-t border-slate-800/60 pt-6 flex flex-wrap gap-x-6 gap-y-2 text-xs font-mono text-slate-400">
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-microchip text-slate-500"></i> Raspberry Pi 5</span>
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-camera text-slate-500"></i> Camera Mod V3</span>
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-brain text-slate-500"></i> YOLOv8s AI</span>
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-between px-6 py-12 sm:px-12 lg:w-1/2 lg:px-20 xl:px-24 bg-white">
            
            <div class="flex items-center gap-3 lg:hidden mb-12">
                <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white shadow-md">
                    SV
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">StockVision</h1>
                    <p class="text-xs text-slate-500">Monitoring stok berbasis IoT & YOLOv8</p>
                </div>
            </div>

            <div class="my-auto w-full max-w-md mx-auto">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Selamat Datang Kembali</h2>
                    <p class="text-sm text-slate-500 mt-2">Kelola stok warung dari satu dashboard terintegrasi.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm flex items-start gap-3">
                        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm flex items-start gap-3">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                        <div class="relative group">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors duration-200 text-sm"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all duration-200 bg-slate-50/50 focus:bg-white text-slate-900 placeholder:text-slate-400"
                                placeholder="admin@stockvision.test"
                            >
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 hover:underline">Lupa sandi?</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors duration-200 text-sm"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all duration-200 bg-slate-50/50 focus:bg-white text-slate-900 placeholder:text-slate-400"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500/20 border-slate-300 rounded-md cursor-pointer transition"
                            >
                            <span class="text-sm text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full px-6 py-3 bg-blue-600 rounded-xl text-white font-semibold text-sm hover:bg-blue-700 active:transform active:scale-[0.99] transition-all duration-150 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20"
                    >
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline ms-1">Daftar di sini</a>
                    </p>
                </div>

                <div class="mt-8 p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                    <div class="flex items-center gap-2 text-slate-700 font-semibold text-xs mb-2.5 uppercase tracking-wider">
                        <i class="fa-solid fa-key text-blue-600"></i> Akun Uji Coba Demo
                    </div>
                    <div class="grid grid-cols-1 gap-1.5 text-xs font-mono text-slate-600">
                        <div class="flex justify-between border-b border-slate-200/40 pb-1">
                            <span>Admin:</span>
                            <span class="text-slate-900 select-all font-semibold">admin@stockvision.test</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200/40 pb-1">
                            <span>Owner:</span>
                            <span class="text-slate-900 select-all font-semibold">owner@stockvision.test</span>
                        </div>
                        <div class="flex justify-between pt-0.5">
                            <span>Sandi:</span>
                            <span class="text-slate-900 select-all font-semibold">password123</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center text-xs text-slate-400 mt-12 lg:mt-0">
                &copy; {{ date('Y') }} StockVision Tech. Hak Cipta Dilindungi.
            </div>
        </div>

    </div>
</body>
</html>