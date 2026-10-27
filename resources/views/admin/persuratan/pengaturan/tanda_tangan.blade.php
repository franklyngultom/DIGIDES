<x-persuratan.pengaturan-layout 
    pageTitle="Manajemen Tanda Tangan - DIGIDES"
    cardTitle="Manajemen Tanda Tangan"
    activeTab="tanda-tangan">

    <x-slot:cardActions>
        <!-- Tombol Camat -->
        <button type="button" 
                @click="$dispatch('open-camat-modal')"
                class="px-3.5 py-1.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all hover:scale-102 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Camat</span>
        </button>

        <!-- Tombol Buku Aparat Pemerintah Desa (Sync) -->
        <form method="POST" action="{{ route('persuratan.pengaturan.tanda-tangan.sync-aparatur') }}" class="inline">
            @csrf
            <button type="submit" 
                    class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all cursor-pointer">
                <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Buku Aparat Pemerintah Desa</span>
            </button>
        </form>

        <!-- Halaman Pelayanan -->
        <a href="{{ route('persuratan.create') }}" 
           class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span>Halaman Pelayanan</span>
        </a>
    </x-slot:cardActions>

    <div x-data="{ 
        showAddModal: false, 
        showCamatModal: false,
        editSigner: null,
        openEdit(signer) {
            this.editSigner = signer;
        }
    }" 
    @open-camat-modal.window="showCamatModal = true"
    class="space-y-6">

        <!-- Top Right Row: Tambah Penandatangan Manual -->
        <div class="flex items-center justify-end">
            <button type="button" 
                    @click="showAddModal = true"
                    class="px-4 py-2 rounded-xl bg-[#1b5e5c] hover:bg-[#0c3837] text-emerald-300 text-xs font-bold flex items-center gap-2 shadow-xs transition-all hover:scale-102 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Penandatangan</span>
            </button>
        </div>

        <!-- Table Penandatangan (Header Violet/Indigo persis screenshot) -->
        <div class="overflow-x-auto rounded-xl border border-[#e1ede8] shadow-2xs">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#5b52a3] text-white text-[12px] font-bold">
                    <tr>
                        <th class="px-6 py-3.5 text-center w-28">Urutan</th>
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Jabatan</th>
                        <th class="px-6 py-3.5 text-center w-44">Bisa Tanda Tangan</th>
                        <th class="px-6 py-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8] bg-white">
                    @forelse($signers as $signer)
                    <tr class="hover:bg-[#f8fafc] transition-colors">
                        <!-- Urutan (Amber Pill Box persis referensi) -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center gap-1 w-10 py-1 rounded-lg bg-[#f59e0b] text-white font-extrabold text-xs shadow-2xs">
                                <span>{{ $signer->urutan }}</span>
                                <svg class="w-2.5 h-2.5 opacity-80" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4l-5 6h10l-5-6zm0 16l5-6H7l5 6z"/>
                                </svg>
                            </span>
                        </td>

                        <!-- Nama -->
                        <td class="px-6 py-4 font-bold text-[#1e293b] text-xs">
                            <div class="flex items-center gap-2">
                                <span>{{ $signer->nama }}</span>
                                @if($signer->is_camat)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-sky-100 text-sky-800 border border-sky-200">Camat</span>
                                @endif
                            </div>
                            @if($signer->nip)
                                <span class="text-[10px] text-slate-400 font-normal block font-mono">NIP: {{ $signer->nip }}</span>
                            @endif
                        </td>

                        <!-- Jabatan -->
                        <td class="px-6 py-4 text-[#334155] font-semibold text-xs">
                            {{ $signer->jabatan }}
                        </td>

                        <!-- Toggle Switch Bisa Tanda Tangan (Persis Hijau Screenshot) -->
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('persuratan.pengaturan.tanda-tangan.toggle', $signer) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        title="Klik untuk ubah izin tanda tangan"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $signer->bisa_tanda_tangan ? 'bg-[#2dd4bf]' : 'bg-slate-300' }}">
                                    <span class="pointer-events-none inline-flex h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out items-center justify-center {{ $signer->bisa_tanda_tangan ? 'translate-x-5' : 'translate-x-0' }}">
                                        @if($signer->bisa_tanda_tangan)
                                            <svg class="h-3 w-3 text-[#0d9488]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </span>
                                </button>
                            </form>
                        </td>

                        <!-- Aksi (Edit & Hapus) -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        @click="openEdit({{ json_encode($signer) }})"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-[#10b981] hover:bg-[#edf5f2] transition-colors"
                                        title="Edit Penandatangan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('persuratan.pengaturan.tanda-tangan.destroy', $signer) }}" onsubmit="return confirm('Hapus penandatangan {{ $signer->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                            title="Hapus Penandatangan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Belum ada penandatangan terdaftar. Klik tombol <strong>Buku Aparat Pemerintah Desa</strong> untuk sinkronisasi otomatis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 1. Modal Tambah Penandatangan -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.away="showAddModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                    <h3 class="font-extrabold text-sm text-[#0c3837]">Tambah Penandatangan Baru</h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 text-xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('persuratan.pengaturan.tanda-tangan.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Pilih dari Aparatur (Opsional)</label>
                        <select name="aparatur_id" 
                                @change="
                                    const opt = $event.target.selectedOptions[0];
                                    if(opt.value) {
                                        $refs.namaInput.value = opt.dataset.nama || '';
                                        $refs.jabatanInput.value = opt.dataset.jabatan || '';
                                        $refs.nipInput.value = opt.dataset.nip || '';
                                    }
                                "
                                class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs">
                            <option value="">-- Manual / Bukan Aparatur --</option>
                            @foreach($aparaturs as $ap)
                                <option value="{{ $ap->id }}" 
                                        data-nama="{{ $ap->penduduk?->nama_lengkap }}" 
                                        data-jabatan="{{ $ap->jabatan }}" 
                                        data-nip="{{ $ap->nip }}">
                                    {{ $ap->penduduk?->nama_lengkap }} ({{ $ap->jabatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Nama Lengkap & Gelar *</label>
                        <input type="text" x-ref="namaInput" name="nama" required placeholder="Fauziana, S.Tr, Keb"
                               class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Jabatan Resmi *</label>
                        <input type="text" x-ref="jabatanInput" name="jabatan" required placeholder="Kepala Desa / Sekretaris Desa"
                               class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">NIP (Opsional)</label>
                            <input type="text" x-ref="nipInput" name="nip" placeholder="19750510..."
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">Nomor Urutan *</label>
                            <input type="number" name="urutan" value="{{ $signers->count() + 1 }}" min="1" required
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 pt-2 cursor-pointer">
                        <input type="checkbox" name="bisa_tanda_tangan" value="1" checked class="w-4 h-4 rounded text-[#1b5e5c]">
                        <span class="text-xs font-semibold text-[#0c3837]">Aktifkan hak tanda tangan langsung</span>
                    </label>

                    <div class="flex justify-end gap-2 pt-3 border-t border-[#e1ede8]">
                        <button type="button" @click="showAddModal = false" class="px-3 py-1.5 border rounded-xl text-xs">Batal</button>
                        <button type="submit" class="px-4 py-1.5 bg-[#1b5e5c] text-white rounded-xl text-xs font-bold">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Modal Quick Camat -->
        <div x-show="showCamatModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.away="showCamatModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                    <h3 class="font-extrabold text-sm text-[#0284c7]">Pengaturan Penandatangan Camat</h3>
                    <button type="button" @click="showCamatModal = false" class="text-slate-400 text-xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('persuratan.pengaturan.tanda-tangan.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="is_camat" value="1">
                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Nama Lengkap Camat *</label>
                        <input type="text" name="nama" required placeholder="Drs. H. Ahmad Fauzi, M.Si"
                               class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#334155] mb-1">Jabatan *</label>
                        <input type="text" name="jabatan" value="Camat {{ $desa->kecamatan ?? 'Bagan Sinembah' }}" required
                               class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">NIP Camat</label>
                            <input type="text" name="nip" placeholder="19680312..."
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">Nomor Urutan *</label>
                            <input type="number" name="urutan" value="{{ $signers->count() + 1 }}" min="1" required
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-[#e1ede8]">
                        <button type="button" @click="showCamatModal = false" class="px-3 py-1.5 border rounded-xl text-xs">Batal</button>
                        <button type="submit" class="px-4 py-1.5 bg-[#0284c7] text-white rounded-xl text-xs font-bold">Simpan Camat</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Modal Edit Penandatangan -->
        <div x-show="editSigner !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4" @click.away="editSigner = null">
                <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                    <h3 class="font-extrabold text-sm text-[#0c3837]">Edit Data Penandatangan</h3>
                    <button type="button" @click="editSigner = null" class="text-slate-400 text-xl font-bold">&times;</button>
                </div>
                <template x-if="editSigner">
                    <form method="POST" :action="'{{ url('/persuratan/pengaturan/tanda-tangan') }}/' + editSigner.id" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">Nama Lengkap *</label>
                            <input type="text" name="nama" :value="editSigner.nama" required
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#334155] mb-1">Jabatan *</label>
                            <input type="text" name="jabatan" :value="editSigner.jabatan" required
                                   class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-[#334155] mb-1">NIP</label>
                                <input type="text" name="nip" :value="editSigner.nip"
                                       class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#334155] mb-1">Urutan *</label>
                                <input type="number" name="urutan" :value="editSigner.urutan" min="1" required
                                       class="w-full px-3 py-2 bg-[#f8fafc] border border-slate-300 rounded-xl text-xs font-bold">
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2 pt-2 cursor-pointer">
                            <input type="checkbox" name="bisa_tanda_tangan" value="1" :checked="editSigner.bisa_tanda_tangan" class="w-4 h-4 rounded text-[#1b5e5c]">
                            <span class="text-xs font-semibold text-[#0c3837]">Bisa Tanda Tangan</span>
                        </label>
                        <div class="flex justify-end gap-2 pt-3 border-t border-[#e1ede8]">
                            <button type="button" @click="editSigner = null" class="px-3 py-1.5 border rounded-xl text-xs">Batal</button>
                            <button type="submit" class="px-4 py-1.5 bg-[#1b5e5c] text-white rounded-xl text-xs font-bold">Simpan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-persuratan.pengaturan-layout>
