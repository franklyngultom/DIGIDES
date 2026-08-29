@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'class' => '',
])

@php
$variants = [
    'primary'   => 'bg-[#114443] hover:bg-[#0c3837] text-white shadow-xs focus:ring-2 focus:ring-[#10b981]/50',
    'pine'      => 'bg-[#0c3837] hover:bg-[#082424] text-[#d4ed31] shadow-xs focus:ring-2 focus:ring-[#d4ed31]/50',
    'lime'      => 'bg-[#d4ed31] hover:bg-[#c2dc26] text-[#0c3837] font-bold shadow-xs focus:ring-2 focus:ring-[#d4ed31]/50',
    'emerald'   => 'bg-[#10b981] hover:bg-[#059669] text-white shadow-xs focus:ring-2 focus:ring-[#10b981]/50',
    'secondary' => 'bg-white hover:bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8] shadow-xs focus:ring-2 focus:ring-[#10b981]/30',
    'outline'   => 'bg-transparent hover:bg-[#e2f0ed] text-[#114443] border border-[#114443]/30',
    'ghost'     => 'bg-transparent hover:bg-[#e2f0ed] text-[#114443]',
    'danger'    => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs focus:ring-2 focus:ring-rose-500/50',
];

$sizes = [
    'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
    'md' => 'px-5 py-2.5 text-sm gap-2',
    'lg' => 'px-7 py-3 text-base gap-2.5',
];

$btnClass = 'inline-flex items-center justify-center font-semibold rounded-full transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . $class;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $btnClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $btnClass]) }}>
        {{ $slot }}
    </button>
@endif
