@if (session('success') || session('error') || session('info') || $errors->any())
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
     class="fixed bottom-6 right-6 z-50 max-w-md w-full transition-all duration-300 transform"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-4 scale-95">

    @if (session('success'))
    <div class="bg-[#0c3837] text-white p-4 rounded-3xl shadow-xl border border-[#10b981]/40 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-[#10b981]/20 text-[#d4ed31] flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-bold text-[#d4ed31]">Berhasil</h4>
            <p class="text-xs text-[#e2f0ed] mt-0.5">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-white/60 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-rose-950 text-white p-4 rounded-3xl shadow-xl border border-rose-600/40 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-300 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-bold text-rose-300">Peringatan / Error</h4>
            <p class="text-xs text-rose-100 mt-0.5">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-white/60 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    @if (session('info'))
    <div class="bg-[#114443] text-white p-4 rounded-3xl shadow-xl border border-white/20 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-white/20 text-[#d4ed31] flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-bold text-[#d4ed31]">Informasi</h4>
            <p class="text-xs text-[#e2f0ed] mt-0.5">{{ session('info') }}</p>
        </div>
        <button @click="show = false" class="text-white/60 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-rose-950 text-white p-4 rounded-3xl shadow-xl border border-rose-600/40 flex items-start gap-3 mt-2">
        <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-300 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-bold text-rose-300">Terdapat kesalahan input:</h4>
            <ul class="text-xs text-rose-100 mt-1 list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-white/60 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif
</div>
@endif
