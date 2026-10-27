<x-persuratan.pengaturan-layout 
    pageTitle="Pengaturan Kop Surat - DIGIDES"
    cardTitle="Pengaturan Kop Surat"
    activeTab="kop-surat">

    <x-slot:cardActions>
        <a href="{{ route('persuratan.create') }}" 
           class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span>Halaman Pelayanan</span>
        </a>

        <button type="button" 
                @click="$dispatch('open-edit-kop')"
                class="px-4 py-1.5 rounded-xl bg-[#d97706] hover:bg-[#b45309] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all hover:scale-102 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Edit Kop Surat</span>
        </button>
    </x-slot:cardActions>

    <div x-data="{ showEditModal: false }" @open-edit-kop.window="showEditModal = true" class="space-y-6">
        <!-- Pratinjau Kop Surat (Persis Referensi Gambar Screenshot) -->
        <div class="p-6 md:p-12 bg-white rounded-2xl border border-slate-200 shadow-md max-w-3xl mx-auto">
            <div class="flex items-center justify-between gap-6 pb-2">
                <!-- Logo Left -->
                <div class="w-24 shrink-0 flex items-center justify-center">
                    @if($pengaturan->kop_logo_path && file_exists(storage_path('app/public/' . $pengaturan->kop_logo_path)))
                        <img src="{{ asset('storage/' . $pengaturan->kop_logo_path) }}" alt="Logo Kop" class="max-h-24 max-w-full object-contain">
                    @elseif($desa->logo_path && file_exists(storage_path('app/public/' . $desa->logo_path)))
                        <img src="{{ asset('storage/' . $desa->logo_path) }}" alt="Logo Desa" class="max-h-24 max-w-full object-contain">
                    @else
                        <div class="w-20 h-20 rounded-xl bg-[#0c3837] text-emerald-300 flex flex-col items-center justify-center font-bold text-lg shadow-sm">
                            <span>DIGIDES</span>
                            <span class="text-[9px] uppercase tracking-wider text-white">Desa Digital</span>
                        </div>
                    @endif
                </div>

                <!-- Text Header Lines -->
                <div class="flex-1 text-center font-serif text-[#0f172a]">
                    <h3 class="text-base md:text-lg font-black tracking-wider uppercase leading-tight">
                        {{ $pengaturan->kop_baris_1 ?: 'PEMERINTAH KOTA DIGIKAB' }}
                    </h3>
                    <h4 class="text-sm md:text-base font-black tracking-wider uppercase leading-tight mt-0.5">
                        {{ $pengaturan->kop_baris_2 ?: 'KECAMATAN DIGICAM' }}
                    </h4>
                    <h2 class="text-lg md:text-xl font-black tracking-widest uppercase leading-tight mt-1 text-[#082424]">
                        {{ $pengaturan->kop_baris_3 ?: 'DESA DIGIDES' }}
                    </h2>
                    <p class="text-[11px] md:text-xs font-sans text-slate-700 italic mt-1.5 leading-snug">
                        {{ $pengaturan->kop_alamat ?: 'Alamat: Desa Digides Kecamatan Digicam Kota Digikab, Kode Pos: 43074' }}
                    </p>
                </div>
            </div>

            <!-- Double Bottom Line (Thick + Thin) -->
            <div class="mt-3 border-t-[3px] border-black"></div>
            <div class="mt-[2px] border-t-[1px] border-black"></div>
        </div>

        <p class="text-center text-xs text-[#64748b]">
            * Tampilan di atas adalah pratinjau langsung yang akan dicetak pada seluruh naskah surat resmi & dokumen PDF.
        </p>

        <!-- Edit Kop Surat Modal (CRUD Update) -->
        <div x-show="showEditModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             @keydown.escape.window="showEditModal = false">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 text-left shadow-2xl space-y-5" @click.away="showEditModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#f59e0b]/20 text-[#b45309] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 class="font-extrabold text-sm text-[#0c3837]">Edit Redaksi Kop Surat Resmi</h3>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
                </div>

                <form method="POST" action="{{ route('persuratan.pengaturan.kop-surat.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Baris 1 (Pemerintah Kabupaten / Kota) *</label>
                        <input type="text" name="kop_baris_1" value="{{ old('kop_baris_1', $pengaturan->kop_baris_1) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8fafc] border border-slate-300 text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Baris 2 (Kecamatan) *</label>
                        <input type="text" name="kop_baris_2" value="{{ old('kop_baris_2', $pengaturan->kop_baris_2) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8fafc] border border-slate-300 text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Baris 3 (Nama Desa / Kelurahan) *</label>
                        <input type="text" name="kop_baris_3" value="{{ old('kop_baris_3', $pengaturan->kop_baris_3) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8fafc] border border-slate-300 text-xs font-black focus:outline-none focus:border-[#10b981]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Alamat Kantor & Kontak Lengkap *</label>
                        <textarea name="kop_alamat" rows="2" required
                                  class="w-full px-3.5 py-2.5 rounded-xl bg-[#f8fafc] border border-slate-300 text-xs font-medium focus:outline-none focus:border-[#10b981]">{{ old('kop_alamat', $pengaturan->kop_alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Unggah Logo Kop Khusus (Opsional)</label>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/webp"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#edf5f2] file:text-[#114443] hover:file:bg-[#e2f0ed] cursor-pointer">
                        <span class="text-[10px] text-slate-400 block mt-1">Format: PNG transparan, JPG, WebP. Maks 2MB.</span>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-[#e1ede8]">
                        <button type="button" @click="showEditModal = false" 
                                class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold hover:bg-slate-50 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl bg-[#1b5e5c] hover:bg-[#114443] text-white text-xs font-bold shadow-xs cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-persuratan.pengaturan-layout>
