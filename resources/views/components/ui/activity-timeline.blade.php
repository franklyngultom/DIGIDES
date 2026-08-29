@props([
    'items' => [],
    'title' => 'Upcoming Activity',
    'subtitle' => 'Jadwal dan linimasa pelayanan internal kantor desa',
    'viewAllRoute' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs ' . $class]) }}>
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-lg font-bold text-[#0c3837] tracking-tight">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs text-[#64748b] mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($viewAllRoute)
            <a href="{{ $viewAllRoute }}" class="px-3.5 py-1.5 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] rounded-full text-xs font-bold transition-colors">
                Lihat Semua &rarr;
            </a>
        @endif
    </div>

    @if(empty($items))
        {{ $slot }}
    @else
        <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-[#e1ede8] before:content-['']">
            @foreach($items as $item)
                <div class="relative flex items-start gap-4 group">
                    <!-- Milestone Dot -->
                    <div class="w-7 h-7 rounded-full bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 z-10 shadow-xs border-2 border-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 bg-[#f7faf9] group-hover:bg-[#edf5f2] p-3.5 rounded-2xl border border-[#e1ede8] transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-[#0c3837]">{{ $item['title'] ?? 'Aktivitas' }}</span>
                                @if(isset($item['tag']))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f48f] text-[#0c3837]">
                                        {{ $item['tag'] }}
                                    </span>
                                @endif
                            </div>
                            @if(isset($item['desc']))
                                <p class="text-[11px] text-[#64748b] mt-0.5">{{ $item['desc'] }}</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-mono font-bold text-[#114443] block">{{ $item['time'] ?? '-' }}</span>
                            @if(isset($item['pic']))
                                <span class="text-[10px] text-[#94a3b8]">{{ $item['pic'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
