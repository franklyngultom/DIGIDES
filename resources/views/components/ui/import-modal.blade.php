@props([
    'id' => 'import-modal',
    'title' => 'Impor Data Excel / CSV',
    'action' => '#',
    'templateUrl' => null,
    'description' => 'Unggah berkas format Excel (.xlsx / .xls) atau CSV untuk memasukkan data secara massal.'
])

<div x-data="{ open: false, fileName: '' }" 
     x-on:open-import-modal-{{ $id }}.window="open = true; fileName = ''"
     x-on:close-import-modal-{{ $id }}.window="open = false; fileName = ''"
     x-cloak>
    
    <template x-teleport="body">
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-sm"
             style="display: none;">
            
            <!-- Modal Dialog -->
            <div @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-7 w-full max-w-lg border border-[#e1ede8] shadow-2xl space-y-5 mx-auto relative overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-start justify-between pb-3 border-b border-[#e1ede8]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#0c3837] leading-snug">{{ $title }}</h3>
                            <p class="text-xs text-[#64748b] mt-0.5">{{ $description }}</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Template Download Banner -->
                @if($templateUrl)
                <div class="p-3.5 bg-[#f0f7f5] rounded-2xl border border-[#10b981]/20 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-4 h-4 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs font-semibold text-[#114443] truncate">Unduh format kolom template baku</span>
                    </div>
                    <a href="{{ $templateUrl }}" 
                       class="px-3 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] rounded-xl text-xs font-bold shadow-2xs transition-colors shrink-0 flex items-center gap-1">
                        <span>Unduh Template</span>
                    </a>
                </div>
                @endif

                <!-- Upload Form -->
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] mb-2">Pilih Berkas Excel / CSV *</label>
                        <div class="border-2 border-dashed border-[#e1ede8] hover:border-[#10b981] rounded-2xl p-6 text-center transition-colors bg-[#fbfdfc] relative cursor-pointer group">
                            <input type="file" 
                                   name="file" 
                                   required 
                                   accept=".csv, .xlsx, .xls, text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" 
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            
                            <template x-if="!fileName">
                                <div class="space-y-1.5 pointer-events-none">
                                    <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] mx-auto flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-[#0c3837]">Klik atau seret berkas ke sini</p>
                                    <p class="text-[11px] text-[#64748b]">Mendukung format .CSV atau .XLSX (Maks 10 MB)</p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="space-y-1.5 pointer-events-none">
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <p class="text-xs font-extrabold text-[#0c3837] truncate px-4" x-text="fileName"></p>
                                    <p class="text-[11px] text-emerald-600 font-semibold">Berkas siap diimpor</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#e1ede8]">
                        <button type="button" 
                                @click="open = false" 
                                class="px-4 py-2.5 text-xs font-bold text-[#64748b] hover:text-[#0c3837] rounded-xl transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] text-xs font-bold rounded-xl shadow-xs transition-colors cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span>Unggah & Impor</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>
</div>
