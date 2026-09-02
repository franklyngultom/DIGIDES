@props([
    'items' => [],
    'title' => 'Upcoming Schedule',
    'subtitle' => 'Jadwal layanan dan koordinasi aparatur kantor desa',
    'class' => '',
])

<div x-data="{
    createModal: false,
    editModal: false,
    deleteModal: false,
    editItem: {
        id: '',
        title: '',
        tag: 'Persuratan',
        time: '',
        pic: '',
        description: '',
        updateUrl: ''
    },
    deleteItem: {
        id: '',
        title: '',
        deleteUrl: ''
    },
    openEdit(item) {
        this.editItem = {
            id: item.id,
            title: item.title,
            tag: item.tag || 'Persuratan',
            time: item.time,
            pic: item.pic || '',
            description: item.description || '',
            updateUrl: '{{ url('/schedules') }}/' + item.id
        };
        this.editModal = true;
    },
    openDelete(item) {
        this.deleteItem = {
            id: item.id,
            title: item.title,
            deleteUrl: '{{ url('/schedules') }}/' + item.id
        };
        this.deleteModal = true;
    }
}" {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs ' . $class]) }}>
    <!-- Header -->
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-lg font-bold text-[#0c3837] tracking-tight">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-xs text-[#64748b] mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        
        <!-- Button Tambah Jadwal -->
        <button type="button" 
                @click="createModal = true"
                id="btn-tambah-schedule"
                class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-xs font-bold transition-all shadow-2xs flex items-center gap-1.5 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Jadwal</span>
        </button>
    </div>

    @if($items->isEmpty())
        <div class="py-10 text-center text-[#64748b] bg-[#f7faf9] rounded-2xl border border-dashed border-[#e1ede8]">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="font-semibold block text-xs text-[#0c3837]">Belum ada jadwal kegiatan mendatang.</span>
            <span class="text-[11px] text-slate-400 mt-0.5 block">Klik tombol "+ Tambah Jadwal" di atas untuk menambahkan agenda baru.</span>
        </div>
    @else
        <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-[#e1ede8] before:content-['']">
            @foreach($items as $item)
                <div class="relative flex items-start gap-4 group">
                    <!-- Milestone Dot -->
                    <div class="w-7 h-7 rounded-full bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 z-10 shadow-xs border-2 border-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 bg-[#f7faf9] group-hover:bg-[#edf5f2] p-3.5 rounded-2xl border border-[#e1ede8] transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative">
                        <div class="flex-1 pr-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-[#0c3837]">{{ $item->title ?? 'Aktivitas' }}</span>
                                @php
                                    $tagClass = match(strtolower($item->tag ?? '')) {
                                        'persuratan' => 'bg-[#e2f48f] text-[#0c3837]',
                                        'kependudukan' => 'bg-[#e2f0ed] text-[#114443]',
                                        'administrasi' => 'bg-[#dbeafe] text-[#1e40af]',
                                        'keuangan' => 'bg-[#ecfdf5] text-[#047857]',
                                        'pembangunan' => 'bg-[#fef3c7] text-[#92400e]',
                                        default => 'bg-[#f1f5f9] text-[#475569]',
                                    };
                                @endphp
                                @if(!empty($item->tag))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tagClass }}">
                                        {{ $item->tag }}
                                    </span>
                                @endif
                            </div>
                            @if(!empty($item->description))
                                <p class="text-[11px] text-[#64748b] mt-0.5">{{ $item->description }}</p>
                            @endif
                        </div>

                        <!-- Right Info & Action Buttons -->
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-left sm:text-right">
                                <span class="text-xs font-mono font-bold text-[#114443] block">{{ $item->time ?? '-' }}</span>
                                @if(!empty($item->pic))
                                    <span class="text-[10px] text-[#64748b]">{{ $item->pic }}</span>
                                @endif
                            </div>

                            <!-- Edit & Delete Buttons -->
                            <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <!-- Edit Button -->
                                <button type="button" 
                                        @click="openEdit({{ json_encode($item) }})"
                                        id="btn-edit-schedule-{{ $item->id }}"
                                        title="Edit Jadwal"
                                        class="p-1.5 bg-white hover:bg-[#e2f0ed] text-[#114443] border border-[#e1ede8] rounded-xl transition-all shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button type="button" 
                                        @click="openDelete({{ json_encode($item) }})"
                                        id="btn-delete-schedule-{{ $item->id }}"
                                        title="Hapus Jadwal"
                                        class="p-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-100 rounded-xl transition-all shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- ============================================================= -->
    <!-- MODAL CREATE SCHEDULE                                         -->
    <!-- ============================================================= -->
    <div x-show="createModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-xs" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="createModal = false" class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-2xl max-w-md w-full space-y-4">
            <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-[#0c3837]">Tambah Jadwal Baru</h3>
                        <span class="text-[10px] text-[#64748b]">Agenda layanan dan koordinasi desa</span>
                    </div>
                </div>
                <button type="button" @click="createModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
            </div>

            <form method="POST" action="{{ route('schedules.store') }}" id="form-create-schedule" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="create-title">
                        Nama Kegiatan / Agenda <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="create-title" required placeholder="Contoh: Pelayanan Surat Keterangan Usaha (SKU)"
                           class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="create-tag">
                            Kategori Modul <span class="text-rose-500">*</span>
                        </label>
                        <select name="tag" id="create-tag" required class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                            <option value="Persuratan">Persuratan</option>
                            <option value="Kependudukan">Kependudukan</option>
                            <option value="Administrasi">Administrasi</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Pembangunan">Pembangunan</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="create-time">
                            Waktu / Jam <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="time" id="create-time" required placeholder="09:30 WIB"
                               class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="create-pic">
                        Petugas / Penanggung Jawab (PIC)
                    </label>
                    <input type="text" name="pic" id="create-pic" placeholder="Contoh: Staff Pelayanan / Sekretariat"
                           class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="create-desc">
                        Keterangan Singkat / Catatan
                    </label>
                    <textarea name="description" id="create-desc" rows="2" placeholder="Contoh: 3 Berkas pemohon walk-in desk"
                              class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-[#e1ede8]">
                    <button type="button" @click="createModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-create-schedule" class="px-5 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL EDIT SCHEDULE                                           -->
    <!-- ============================================================= -->
    <div x-show="editModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-xs" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="editModal = false" class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-2xl max-w-md w-full space-y-4">
            <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-[#0c3837]">Edit Jadwal Kegiatan</h3>
                        <span class="text-[10px] text-[#64748b]">Perbarui rincian agenda layanan</span>
                    </div>
                </div>
                <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
            </div>

            <form method="POST" :action="editItem.updateUrl" id="form-edit-schedule" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="edit-title">
                        Nama Kegiatan / Agenda <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="edit-title" required x-model="editItem.title"
                           class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="edit-tag">
                            Kategori Modul <span class="text-rose-500">*</span>
                        </label>
                        <select name="tag" id="edit-tag" required x-model="editItem.tag" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                            <option value="Persuratan">Persuratan</option>
                            <option value="Kependudukan">Kependudukan</option>
                            <option value="Administrasi">Administrasi</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Pembangunan">Pembangunan</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="edit-time">
                            Waktu / Jam <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="time" id="edit-time" required x-model="editItem.time"
                               class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="edit-pic">
                        Petugas / Penanggung Jawab (PIC)
                    </label>
                    <input type="text" name="pic" id="edit-pic" x-model="editItem.pic"
                           class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1" for="edit-desc">
                        Keterangan Singkat / Catatan
                    </label>
                    <textarea name="description" id="edit-desc" rows="2" x-model="editItem.description"
                              class="w-full px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-[#e1ede8]">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-edit-schedule" class="px-5 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================= -->
    <!-- MODAL KONFIRMASI DELETE SCHEDULE                              -->
    <!-- ============================================================= -->
    <div x-show="deleteModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-xs" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="deleteModal = false" class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-2xl max-w-sm w-full space-y-4 text-center">
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#0c3837]">Hapus Jadwal Kegiatan?</h3>
                <p class="text-xs text-[#64748b] mt-1">
                    Agenda "<strong class="text-rose-600" x-text="deleteItem.title"></strong>" akan dihapus secara permanen dari daftar jadwal.
                </p>
            </div>

            <form method="POST" :action="deleteItem.deleteUrl" id="form-delete-schedule">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-2 pt-2">
                    <button type="button" @click="deleteModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                        Batal
                    </button>
                    <button type="submit" id="btn-confirm-delete-schedule" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                        Ya, Hapus Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
