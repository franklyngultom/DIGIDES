@props([
    'day' => 'Mon',
    'date' => '18',
    'percentage' => '86%',
    'label' => 'Productive',
    'productiveTime' => '5h 12m',
    'timeAtWork' => '5h 45m',
    'variant' => 'lime',
    'curveD' => null,
    'class' => '',
])

@php
$curves = [
    'lime' => 'M0 30 Q 25 10, 50 25 T 100 5',
    'sage' => 'M0 25 Q 30 35, 60 15 T 100 20',
    'pine' => 'M0 35 Q 35 30, 70 10 T 100 8',
];

$d = $curveD ?? ($curves[$variant] ?? $curves['lime']);

$styles = [
    'lime' => [
        'card'       => 'bg-gradient-to-br from-[#ecfdf5] via-[#f0fdf4] to-white border border-emerald-200/80 text-[#0c3837] shadow-sm',
        'dayBadge'   => 'bg-white text-[#059669] border-emerald-200/80 shadow-xs',
        'dateNum'    => 'text-[#065f46]',
        'prodBadge'  => 'bg-emerald-100/90 text-[#065f46] border border-emerald-200/60',
        'stroke'     => '#059669',
        'circle'     => '#059669',
        'divider'    => 'border-emerald-100',
        'subText'    => 'text-[#64748b]',
        'valueText'  => 'text-[#065f46]',
        'lastCircle' => [100, 5],
    ],
    'sage' => [
        'card'       => 'bg-gradient-to-br from-[#1b5e5c] to-[#114443] text-white shadow-xs',
        'dayBadge'   => 'bg-white/15 text-white/90 border-white/20',
        'dateNum'    => 'text-white',
        'prodBadge'  => 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30',
        'stroke'     => '#10b981',
        'circle'     => '#10b981',
        'divider'    => 'border-white/15',
        'subText'    => 'text-white/70',
        'valueText'  => 'text-white',
        'lastCircle' => [100, 20],
    ],
    'pine' => [
        'card'       => 'bg-[#0c3837] text-white shadow-xs',
        'dayBadge'   => 'bg-white/10 text-[#8bc3b8] border-white/10',
        'dateNum'    => 'text-white',
        'prodBadge'  => 'bg-emerald-400 text-[#0c3837]',
        'stroke'     => '#34d399',
        'circle'     => '#34d399',
        'divider'    => 'border-white/15',
        'subText'    => 'text-[#8bc3b8]',
        'valueText'  => 'text-white',
        'lastCircle' => [100, 8],
    ],
];

$currentStyle = $styles[$variant] ?? $styles['lime'];
@endphp

<div {{ $attributes->merge(['class' => 'p-6 rounded-3xl relative overflow-hidden flex flex-col justify-between transition-all duration-200 hover:scale-[1.01] ' . $currentStyle['card'] . ' ' . $class]) }}>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div class="backdrop-blur-sm px-3.5 py-1.5 rounded-2xl text-center shadow-xs border {{ $currentStyle['dayBadge'] }}">
                <span class="text-[11px] font-bold block uppercase">{{ $day }}</span>
                <span class="text-xl font-black {{ $currentStyle['dateNum'] }}">{{ $date }}</span>
            </div>
            <span class="px-3 py-1 backdrop-blur-sm rounded-full text-xs font-extrabold shadow-xs {{ $currentStyle['prodBadge'] }}">
                {{ $percentage }} {{ $label }}
            </span>
        </div>

        <!-- Sparkline SVG Curve -->
        <div class="h-14 w-full my-2">
            <svg class="w-full h-full" viewBox="0 0 100 40" fill="none" preserveAspectRatio="none">
                <path d="{{ $d }}" stroke="{{ $currentStyle['stroke'] }}" stroke-width="3" stroke-linecap="round" fill="none" />
                <circle cx="{{ $currentStyle['lastCircle'][0] }}" cy="{{ $currentStyle['lastCircle'][1] }}" r="4" fill="{{ $currentStyle['circle'] }}" />
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 pt-3 border-t {{ $currentStyle['divider'] }}">
        <div>
            <span class="text-[11px] block font-semibold {{ $currentStyle['subText'] }}">Productive Time</span>
            <span class="text-base font-black {{ $currentStyle['valueText'] }}">{{ $productiveTime }}</span>
        </div>
        <div>
            <span class="text-[11px] block font-semibold {{ $currentStyle['subText'] }}">Time at Work</span>
            <span class="text-base font-black {{ $currentStyle['valueText'] }}">{{ $timeAtWork }}</span>
        </div>
    </div>
</div>
