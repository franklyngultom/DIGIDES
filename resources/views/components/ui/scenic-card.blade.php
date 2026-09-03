@props([
    'desa' => null,
    'imageUrl' => null,
    'height' => 'h-44',
    'class' => '',
])

@php
$desaProfile = $desa ?? \App\Models\DesaProfile::current();
$image = $imageUrl ?? ($desaProfile?->foto_desa_url ?? asset('images/sukabumi-scenic.jpg'));
$canEdit = auth()->check() && auth()->user()->can('desa.update');
@endphp

<div x-data="{ 
        openModal: false, 
        photoPreview: null,
        previewFile(e) {
            const file = e.target.files[0];
            if (file) {
                this.photoPreview = URL.createObjectURL(file);
            }
        }
     }" 
     {{ $attributes->merge(['class' => 'relative rounded-2xl overflow-hidden ' . $height . ' border border-white/15 shadow-inner group ' . $class]) }}>
    
    <!-- Background Image -->
    <img src="{{ $image }}" 
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
         alt="{{ $desaProfile?->nama_desa ?? 'Pemandangan Desa' }}">

    <!-- Admin Quick Edit Trigger Button (Visible to Authorized Admins) -->
    @if ($canEdit)
        <div class="absolute top-2.5 right-2.5 z-10 opacity-85 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
            <button type="button" 
                    @click="openModal = true"
                    title="Edit atau Hapus Foto Desa"
                    class="p-1.5 bg-black/40 hover:bg-[#114443] text-white/90 hover:text-[#d4ed31] backdrop-blur-md rounded-xl border border-white/20 shadow-md transition-all flex items-center gap-1.5 text-[11px] font-semibold cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="hidden sm:inline pr-1">Edit Foto</span>
            </button>
        </div>
    @endif

    <!-- Card Overlay & Caption -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/95 via-[#0c3837]/40 to-transparent flex flex-col justify-end p-4 pointer-events-none">
        <h4 class="text-base font-extrabold text-white tracking-wide">{{ $desaProfile?->nama_desa ?? 'Desa Sukamaju' }}</h4>
        <p class="text-[11px] text-[#8bc3b8] flex items-center gap-1 mt-0.5">
            <svg class="w-3.5 h-3.5 text-[#d4ed31] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $desaProfile?->kabupaten ?? 'Sukabumi' }}, Indonesia • GMT+7</span>
        </p>
    </div>

    <!-- Quick Edit Modal for Admin (Teleported to Body to avoid stacking context bleed) -->
    @if ($canEdit)
        <template x-teleport="body">
            <div x-show="openModal" 
                 x-cloak
                 style="display: none;"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-[#082424]/75 backdrop-blur-sm"
                 @keydown.escape.window="openModal = false; photoPreview = null;">
                
                <div @click.outside="openModal = false; photoPreview = null;"
                     class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl border border-[#e1ede8] text-slate-800 space-y-5 animate-in fade-in zoom-in-95 duration-200 relative z-10">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0c3837]">Kelola Foto Lanskap Desa</h3>
                                <p class="text-[11px] text-[#64748b]">Ubah foto pemandangan di panel samping</p>
                            </div>
                        </div>
                        <button type="button" @click="openModal = false; photoPreview = null;" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Status & Current Preview -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#0c3837]">Pratinjau Foto Aktif:</span>
                            @if ($desaProfile?->has_custom_foto_desa)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981]/15 text-[#0c3837] border border-[#10b981]/30">Foto Kustom</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">Foto Bawaan</span>
                            @endif
                        </div>

                        <div class="relative rounded-2xl overflow-hidden h-32 border border-[#e1ede8] bg-[#0c3837] shadow-inner">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover" alt="Preview Baru">
                            </template>
                            <template x-if="!photoPreview">
                                <img src="{{ $image }}" class="w-full h-full object-cover" alt="Foto Saat Ini">
                            </template>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/80 to-transparent flex flex-col justify-end p-3">
                                <span class="text-xs font-bold text-white">{{ $desaProfile?->nama_desa ?? 'Desa Sukamaju' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Upload Foto Baru -->
                    <form action="{{ route('admin.desa.foto.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Pilih Foto Lanskap Baru</label>
                            <input type="file" name="foto_desa" accept="image/png,image/jpeg,image/webp" required @change="previewFile($event)"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                            <span class="text-[10px] text-[#64748b] mt-1 block">Format: JPG, PNG, WEBP (Maksimal 5 MB)</span>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-[#114443] hover:bg-[#0c3837] text-white font-bold rounded-2xl text-xs shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Foto Lanskap Baru</span>
                        </button>
                    </form>

                    <!-- Action: Hapus Foto Kustom (jika ada) & Pengaturan Lengkap -->
                    <div class="pt-3 border-t border-[#e1ede8] flex items-center justify-between gap-2">
                        @if ($desaProfile?->has_custom_foto_desa)
                            <form action="{{ route('admin.desa.foto.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto lanskap kustom dan kembali ke foto bawaan sistem?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-bold hover:underline flex items-center gap-1 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Hapus Foto (Reset ke Default)</span>
                                </button>
                            </form>
                        @else
                            <span class="text-[11px] text-[#64748b]">Foto saat ini: Bawaan sistem</span>
                        @endif

                        <a href="{{ route('admin.desa.index') }}" class="text-[11px] text-[#114443] hover:text-[#0c3837] font-semibold hover:underline">
                            Pengaturan Lengkap &rarr;
                        </a>
                    </div>

                </div>
            </div>
        </template>
    @endif
</div>
