@props([
    'variant' => 'default',
    'padding' => 'p-6',
    'class' => '',
])

@php
$variants = [
    'default' => 'bg-white border-[#e1ede8] text-[#0f172a] shadow-xs',
    'lime'    => 'bg-gradient-to-br from-[#e2f48f] to-[#f4fce0] border-[#d4ed31]/60 text-[#0c3837] shadow-xs',
    'sage'    => 'bg-[#4fa394] border-transparent text-white shadow-xs',
    'pine'    => 'bg-[#0c3837] border-transparent text-white shadow-xs',
    'soft'    => 'bg-[#edf5f2] border-[#e1ede8] text-[#0c3837]',
    'glass'   => 'bg-white/80 backdrop-blur-md border-white/40 shadow-sm text-[#0f172a]',
];

$paddings = [
    'none' => '',
    'sm'   => 'p-4',
    'p-6'  => 'p-6',
    'lg'   => 'p-8',
];

$cardClass = ($variants[$variant] ?? $variants['default']) . ' ' . ($paddings[$padding] ?? $padding);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl border transition-all duration-200 ' . $cardClass . ' ' . $class]) }}>
    @if(isset($header))
        <div class="mb-4 pb-3 border-b border-[#e1ede8]/60 flex items-center justify-between">
            {{ $header }}
        </div>
    @endif

    {{ $slot }}

    @if(isset($footer))
        <div class="mt-4 pt-4 border-t border-[#e1ede8]/60 flex items-center justify-between">
            {{ $footer }}
        </div>
    @endif
</div>
