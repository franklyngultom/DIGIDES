@props([
    'variant' => 'emerald',
    'dot' => false,
    'class' => '',
])

@php
$variants = [
    'lime'    => 'bg-[#e2f48f] text-[#0c3837] border border-[#d4ed31]/60 font-bold',
    'emerald' => 'bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30 font-semibold',
    'sage'    => 'bg-[#4fa394]/15 text-[#1b5e5c] border border-[#4fa394]/30 font-semibold',
    'pine'    => 'bg-[#0c3837] text-[#d4ed31] border border-[#1b5e5c] font-bold',
    'slate'   => 'bg-slate-100 text-slate-700 border border-slate-200 font-medium',
    'amber'   => 'bg-amber-50 text-amber-800 border border-amber-200 font-semibold',
    'rose'    => 'bg-rose-50 text-rose-700 border border-rose-200 font-semibold',
];

$dots = [
    'lime'    => 'bg-[#0c3837]',
    'emerald' => 'bg-[#10b981]',
    'sage'    => 'bg-[#4fa394]',
    'pine'    => 'bg-[#d4ed31]',
    'slate'   => 'bg-slate-400',
    'amber'   => 'bg-amber-500',
    'rose'    => 'bg-rose-500',
];

$badgeStyle = $variants[$variant] ?? $variants['emerald'];
$dotStyle   = $dots[$variant] ?? $dots['emerald'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs ' . $badgeStyle . ' ' . $class]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotStyle }}"></span>
    @endif
    {{ $slot }}
</span>
