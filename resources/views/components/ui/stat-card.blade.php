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
        'card'       => 'digides-stat-lime border border-[#84eba9]/60 text-[#0d3b22] shadow-sm',
        'dayBadge'   => 'bg-white/85 text-[#14532d] border-white/70 shadow-xs',
        'dateNum'    => 'text-[#0d3b22]',
        'prodBadge'  => 'bg-white/80 text-[#14532d] border border-white/70',
        'stroke'     => '#14532d',
        'circle'     => '#14532d',
        'divider'    => 'border-[#14532d]/15',
        'subText'    => 'text-[#14532d]/80',
        'valueText'  => 'text-[#0d3b22]',
        'lastCircle' => [100, 5],
    ],
    'sage' => [
        'card'       => 'digides-stat-sage border border-[#84eba9]/40 text-white shadow-sm',
        'dayBadge'   => 'bg-white/20 text-white border-white/30',
        'dateNum'    => 'text-white',
        'prodBadge'  => 'bg-white/20 text-white border border-white/30',
        'stroke'     => '#dcfce7',
        'circle'     => '#dcfce7',
        'divider'    => 'border-white/20',
        'subText'    => 'text-white/85',
        'valueText'  => 'text-white',
        'lastCircle' => [100, 20],
    ],
    'pine' => [
        'card'       => 'digides-stat-pine border border-[#84eba9]/35 text-white shadow-sm',
        'dayBadge'   => 'bg-white/15 text-[#dcfce7] border-white/25',
        'dateNum'    => 'text-white',
        'prodBadge'  => 'bg-[#d2f1a8] text-[#0d3b22]',
        'stroke'     => '#d2f1a8',
        'circle'     => '#d2f1a8',
        'divider'    => 'border-white/20',
        'subText'    => 'text-[#dcfce7]',
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
