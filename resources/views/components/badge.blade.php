@props(['variant' => 'emerald', 'class' => ''])

@php
$variants = [
    'emerald' => 'bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30',
    'lime' => 'bg-emerald-100/80 text-emerald-800 border border-emerald-200/80 font-semibold',
    'pine' => 'bg-[#0c3837] text-emerald-300 border border-emerald-700/50',
    'slate' => 'bg-slate-100 text-slate-700 border border-slate-200',
    'rose' => 'bg-rose-50 text-rose-700 border border-rose-200',
    'amber' => 'bg-amber-50 text-amber-800 border border-amber-200',
];
$badgeStyle = $variants[$variant] ?? $variants['emerald'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium ' . $badgeStyle . ' ' . $class]) }}>
    {{ $slot }}
</span>
