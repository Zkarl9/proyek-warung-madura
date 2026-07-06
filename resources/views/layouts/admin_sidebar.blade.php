@php
    $menuGroups = [
        [
            'group_title' => 'Utama',
            'items' => [
                ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard Admin'],
            ]
        ],
        [
            'group_title' => 'Kendali Data',
            'items' => [
                ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Kelola Pengguna'],
                ['route' => 'admin.training.index', 'pattern' => 'admin.training.*', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5.625 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'label' => 'AI Training'],
            ]
        ]
    ];
@endphp

<div class="space-y-8 py-4">
    @foreach ($menuGroups as $group)
        <div>
            {{-- Header Grup --}}
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                {{ $group['group_title'] }}
            </p>
            
            <div class="space-y-1">
                @foreach ($group['items'] as $item)
                    @php $active = Route::is($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group relative flex items-center gap-3.5 px-4 py-3 mx-2 rounded-xl text-sm font-bold transition-all duration-300
                              {{ $active 
                                 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' 
                                 : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' }}">
                        
                        {{-- Icon --}}
                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        
                        {{-- Label --}}
                        <span class="truncate">{{ $item['label'] }}</span>

                        {{-- Indikator Aktif --}}
                        @if($active)
                            <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>