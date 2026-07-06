@php
    $navItems = [
        ['route' => 'owner.dashboard',         'pattern' => 'owner.dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
        ['route' => 'owner.products.index',    'pattern' => 'owner.products.*',   'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'label' => 'Produk'],
        ['route' => 'owner.stock-in.index',    'pattern' => 'owner.stock-in.*',   'icon' => 'M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4', 'label' => 'Stok Masuk'],
        ['route' => 'owner.stock-out.index',   'pattern' => 'owner.stock-out.*',  'icon' => 'M17 16V4m0 0l4 4m-4-4l-4 4M7 8v12m0 0l-4-4m4 4l4-4', 'label' => 'Stok Keluar'],
        ['route' => 'owner.report.index',      'pattern' => 'owner.report.*',     'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Laporan'],
        ['route' => 'owner.camera.live',       'pattern' => 'owner.camera.live',  'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z', 'label' => 'Kamera Live'],
        ['route' => 'owner.notification.index','pattern' => 'owner.notification.*','icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label' => 'Setting'],
    ];
@endphp

@foreach ($navItems as $item)
@php $active = Route::is($item['pattern']); @endphp
<a href="{{ route($item['route']) }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
          {{ $active
              ? 'bg-blue-600 text-white shadow-sm'
              : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
    </svg>
    {{ $item['label'] }}
</a>
@endforeach