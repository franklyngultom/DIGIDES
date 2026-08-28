@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs ' . $class]) }}>
    {{ $slot }}
</div>
