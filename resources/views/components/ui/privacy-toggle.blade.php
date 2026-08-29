@props([
    'defaultActive' => true,
    'class' => '',
])

<div x-data="{ privacyMode: {{ $defaultActive ? 'true' : 'false' }} }" 
     x-init="if (privacyMode) document.body.classList.add('privacy-active')"
     {{ $attributes->merge(['class' => 'flex items-center gap-3 ' . $class]) }}>
    <button type="button"
            @click="privacyMode = !privacyMode; document.body.classList.toggle('privacy-active', privacyMode)" 
            :class="privacyMode ? 'bg-[#10b981]' : 'bg-slate-300'"
            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#10b981]/40"
            role="switch" 
            :aria-checked="privacyMode">
        <span :class="privacyMode ? 'translate-x-5' : 'translate-x-0'" 
              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
    </button>
    <span class="text-xs font-bold text-[#0c3837] select-none" 
          x-text="privacyMode ? 'Privacy Mode Aktif (NIK Disamarkan)' : 'Privacy Mode Nonaktif'"></span>
</div>
