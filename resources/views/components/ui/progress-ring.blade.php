@props([
    'percentage' => 82,
    'label' => 'Efisiensi',
    'strokeColor' => '#10b981',
    'bgColor' => '#e2f0ed',
    'size' => '32',
    'strokeWidth' => '3.5',
    'class' => '',
])

@php
$dashArray = $percentage . ', 100';
@endphp

<div {{ $attributes->merge(['class' => 'relative w-' . $size . ' h-' . $size . ' flex items-center justify-center shrink-0 ' . $class]) }}>
    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
        <!-- Background Ring -->
        <path class="text-[{{ $bgColor }}]" 
              style="stroke: {{ $bgColor }};"
              stroke-width="{{ $strokeWidth }}" 
              stroke="currentColor" 
              fill="none"
              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
        
        <!-- Foreground Value Ring -->
        <path class="transition-all duration-700 ease-out" 
              style="stroke: {{ $strokeColor }};"
              stroke-width="{{ $strokeWidth }}" 
              stroke-dasharray="{{ $dashArray }}" 
              stroke-linecap="round" 
              stroke="currentColor" 
              fill="none"
              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
    </svg>
    <div class="absolute flex flex-col items-center justify-center text-center">
        <span class="text-2xl font-black text-[#0c3837] tracking-tight">{{ $percentage }}%</span>
        <span class="text-[9px] font-bold text-[#64748b] uppercase tracking-wider">{{ $label }}</span>
    </div>
</div>
