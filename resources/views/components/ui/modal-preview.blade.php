@props([
    'id' => 'preview-modal',
    'title' => 'Pratinjau Dokumen',
    'subtitle' => null,
    'maxWidth' => 'max-w-4xl',
])

<div x-data="{ open: false, src: '' }"
     x-on:open-preview-modal.window="open = true; src = $event.detail.src || ''"
     x-on:keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="{{ $id }}-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop Blur -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#082424]/60 backdrop-blur-sm transition-opacity"
         @click="open = false"></div>

    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full {{ $maxWidth }} border border-[#e1ede8] flex flex-col max-h-[90vh]">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-[#e1ede8] flex items-center justify-between bg-[#f7faf9]">
                <div>
                    <h3 class="text-lg font-bold text-[#0c3837] tracking-tight" id="{{ $id }}-title">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="text-xs text-[#64748b] mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                <button type="button" 
                        @click="open = false" 
                        class="w-9 h-9 rounded-full bg-white border border-[#e1ede8] text-slate-400 hover:text-[#0c3837] hover:bg-[#e2f0ed] flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Content (PDF iframe or Slot) -->
            <div class="p-6 flex-1 overflow-y-auto min-h-[420px] bg-slate-50 flex items-center justify-center">
                <template x-if="src">
                    <iframe :src="src" class="w-full h-[65vh] rounded-2xl border border-[#e1ede8] bg-white shadow-xs"></iframe>
                </template>
                <template x-if="!src">
                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 border-t border-[#e1ede8] bg-[#f7faf9] flex items-center justify-end gap-3">
                <button type="button" 
                        @click="open = false" 
                        class="px-5 py-2 text-xs font-semibold rounded-full border border-[#e1ede8] bg-white text-[#0c3837] hover:bg-[#f7faf9] transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
