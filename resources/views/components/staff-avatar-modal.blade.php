@if(auth()->check() && auth()->user()->hasRole('Staff Desa'))
<div x-data="{
        isOpen: false,
        previewUrl: null,
        fileName: '',
        fileSize: '',
        isDragging: false,
        openModal() {
            this.isOpen = true;
            this.previewUrl = null;
            this.fileName = '';
            this.fileSize = '';
        },
        closeModal() {
            this.isOpen = false;
            this.previewUrl = null;
            this.fileName = '';
            this.fileSize = '';
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
     }"
     @open-avatar-modal.window="openModal()"
     @keydown.escape.window="closeModal()"
     x-cloak>

    <!-- Modal Backdrop -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">

        <!-- Modal Dialog Card -->
        <div x-show="isOpen"
             @click.outside="closeModal()"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
             class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#e1ede8] overflow-hidden">

            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-[#114443] to-[#0c3837] text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-[#d4ed31] border border-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold tracking-tight">Perbarui Foto Profil Staff</h3>
                        <p class="text-xs text-[#8bc3b8]">Unggah foto resmi untuk identitas staf pelayanan</p>
                    </div>
                </div>
                <button type="button" @click="closeModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white/80 hover:text-white flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('staff.avatar.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Current & Preview Avatar Compare -->
                <div class="flex items-center justify-center gap-6 py-2">
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full border-2 border-[#e1ede8] p-1 mx-auto mb-1.5 shadow-sm">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Foto Saat Ini" class="w-full h-full rounded-full object-cover">
                        </div>
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Foto Saat Ini</span>
                    </div>

                    <div class="text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full border-2 border-dashed border-[#10b981] p-1 mx-auto mb-1.5 shadow-sm bg-[#f7faf9] flex items-center justify-center overflow-hidden">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Pratinjau Baru" class="w-full h-full rounded-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <span class="text-[10px] text-slate-400 font-medium text-center px-1">Foto Baru</span>
                            </template>
                        </div>
                        <span class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider">Pratinjau Baru</span>
                    </div>
                </div>

                <!-- Dropzone / File Selector -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Pilih Berkas Foto Baru</label>
                    <label :class="isDragging ? 'border-[#10b981] bg-[#e2f0ed]/50' : 'border-[#e1ede8] hover:border-[#10b981] bg-[#f7faf9]'"
                           @dragover.prevent="isDragging = true"
                           @dragleave.prevent="isDragging = false"
                           @drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; handleFileChange({target: $refs.fileInput})"
                           class="border-2 border-dashed rounded-2xl p-5 flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors text-center group">
                        <input x-ref="fileInput" type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" required class="hidden" @change="handleFileChange($event)">
                        
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] group-hover:bg-[#114443] group-hover:text-[#d4ed31] text-[#114443] flex items-center justify-center transition-colors shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <div class="text-xs">
                            <span class="font-bold text-[#114443] group-hover:text-[#10b981]">Klik untuk memilih foto</span>
                            <span class="text-slate-500"> atau seret gambar ke sini</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Format yang didukung: JPG, PNG, WEBP (Maksimal 2 MB)</p>
                    </label>

                    <template x-if="fileName">
                        <div class="mt-2.5 p-2.5 rounded-xl bg-[#e2f0ed] border border-[#10b981]/30 flex items-center justify-between text-xs">
                            <span class="font-semibold text-[#0c3837] truncate" x-text="fileName"></span>
                            <span class="font-mono text-[#114443] font-bold shrink-0 ml-2" x-text="fileSize"></span>
                        </div>
                    </template>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-2 flex items-center justify-between gap-3">
                    @if(auth()->user()->avatar_path)
                    <button type="submit" form="delete-avatar-form" class="px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors cursor-pointer border border-rose-200">
                        Hapus Foto
                    </button>
                    @else
                    <div></div>
                    @endif

                    <div class="flex items-center gap-2">
                        <button type="button" @click="closeModal()" class="px-4 py-2.5 rounded-2xl bg-[#f1f5f4] hover:bg-[#e2ede9] text-[#64748b] hover:text-[#0c3837] font-semibold text-xs transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#114443] to-[#0c3837] hover:from-[#0c3837] hover:to-[#082424] text-white font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                            <span>Simpan Foto Profil</span>
                            <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </form>

            @if(auth()->user()->avatar_path)
            <form id="delete-avatar-form" method="POST" action="{{ route('staff.avatar.delete') }}" class="hidden" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil dan kembali ke avatar bawaan?')">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>
</div>
@endif
