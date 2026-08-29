@props([
    'desa' => null,
    'imageUrl' => null,
    'height' => 'h-44',
    'class' => '',
])

@php
$desaProfile = $desa ?? \App\Models\DesaProfile::current();
$image = $imageUrl ?? (file_exists(public_path('images/sukabumi-scenic.jpg')) ? asset('images/sukabumi-scenic.jpg') : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb');
@endphp

<div {{ $attributes->merge(['class' => 'relative rounded-2xl overflow-hidden ' . $height . ' border border-white/15 shadow-inner group ' . $class]) }}>
    <img src="{{ $image }}" 
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
         alt="{{ $desaProfile?->nama_desa ?? 'Pemandangan Desa' }}">
    <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/95 via-[#0c3837]/40 to-transparent flex flex-col justify-end p-4">
        <h4 class="text-base font-extrabold text-white tracking-wide">{{ $desaProfile?->nama_desa ?? 'Desa Sukamaju' }}</h4>
        <p class="text-[11px] text-[#8bc3b8] flex items-center gap-1 mt-0.5">
            <svg class="w-3.5 h-3.5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>{{ $desaProfile?->kabupaten ?? 'Sukabumi' }}, Indonesia • GMT+7</span>
        </p>
    </div>
</div>
