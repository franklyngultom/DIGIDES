@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'class' => ''
])

@php
$variants = [
    'primary' => 'bg-[#114443] hover:bg-[#0c3837] text-white shadow-xs focus:ring-2 focus:ring-[#10b981]/50',
    'lime' => 'bg-[#d4ed31] hover:bg-[#c2dc26] text-[#0c3837] font-semibold shadow-xs focus:ring-2 focus:ring-[#d4ed31]/50',
    'emerald' => 'bg-[#10b981] hover:bg-[#059669] text-white shadow-xs focus:ring-2 focus:ring-[#10b981]/50',
    'secondary' => 'bg-white hover:bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8] shadow-xs focus:ring-2 focus:ring-[#10b981]/30',
    'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs focus:ring-2 focus:ring-rose-500/50',
    'ghost' => 'bg-transparent hover:bg-[#e2f0ed] text-[#114443]',
];

$sizes = [
    'sm' => 'px-3.5 py-1.5 text-xs',
    'md' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-7 py-3 text-base',
];

$style = ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center font-medium rounded-full transition-all duration-150 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed ' . $style . ' ' . $class]) }}>
    {{ $slot }}
</button>
