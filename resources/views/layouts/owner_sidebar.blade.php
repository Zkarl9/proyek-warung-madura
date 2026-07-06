@php
    // Menu dikelompokkan berdasarkan fungsinya agar lebih terstruktur
    $menuGroups = [
        [
            'group_title' => 'Utama',
            'items' => [
                ['route' => 'owner.dashboard', 'pattern' => 'owner.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
            ]
        ],
        [
            'group_title' => 'Manajemen Stok',
            'items' => [
                ['route' => 'owner.products.index', 'pattern' => 'owner.products.*', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'label' => 'Daftar Produk'],
                ['route' => 'owner.stock-in.index', 'pattern' => 'owner.stock-in.*', 'icon' => 'M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4', 'label' => 'Stok Masuk'],
                ['route' => 'owner.stock-out.index', 'pattern' => 'owner.stock-out.*', 'icon' => 'M17 16V4m0 0l4 4m-4-4l-4 4M7 8v12m0 0l-4-4m4 4l4-4', 'label' => 'Stok Keluar'],
            ]
        ],
        [
            'group_title' => 'Monitoring & Analisis',
            'items' => [
                ['route' => 'owner.camera.live', 'pattern' => 'owner.camera.live', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z', 'label' => 'Kamera Live Deteksi'],
                ['route' => 'owner.report.index', 'pattern' => 'owner.report.*', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Laporan Berkala'],
            ]
        ],
        [
            'group_title' => 'Sistem',
            'items' => [
                ['route' => 'owner.notification.index', 'pattern' => 'owner.notification.*', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Pengaturan'],
            ]
        ]
    ];
@endphp

<div class="space-y-5 py-2">
    @foreach ($menuGroups as $group)
        <div>
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ $group['group_title'] }}</p>
            
            <div class="space-y-1">
                @foreach ($group['items'] as $item)
                    @php $active = Route::is($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                              {{ $active
                                  ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-100'
                                  : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-105 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        
                        <span class="truncate transition-transform duration-200 {{ !$active ? 'group-hover:translate-x-0.5' : '' }}">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>