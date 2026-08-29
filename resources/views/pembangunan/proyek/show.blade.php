<x-layouts.app :title="$proyek->nama_kegiatan">
    <div x-data="{ 
        previewModal: false, 
        previewUrl: '', 
        previewTitle: '',
        isPdf: false,
        openPreview(url, title, pdf = false) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.isPdf = pdf;
            this.previewModal = true;
        }
    }" class="max-w-5xl mx-auto space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                    <span>/</span>
                    <a href="{{ route('pembangunan.proyek.index', ['tahun' => $proyek->tahun_anggaran]) }}" class="hover:text-[#114443]">Buku Proyek</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold truncate max-w-xs">{{ $proyek->nama_kegiatan }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">{{ $proyek->nama_kegiatan }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $proyek->status_progres === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($proyek->status_progres === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $proyek->status_progres }} ({{ $proyek->persentase_selesai }}%)
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($proyek->status_progres === 'selesai' || $proyek->persentase_selesai >= 100)
                    @if($proyek->inventarisHasil)
                        <a href="{{ route('pembangunan.inventaris-hasil.show', $proyek->inventarisHasil) }}" class="px-4 py-2.5 bg-emerald-50 border border-emerald-300 text-emerald-800 hover:bg-emerald-100 text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Lihat di Inventaris Aset</span>
                        </a>
                    @else
                        @can('pembangunan.manage')
                        <a href="{{ route('pembangunan.inventaris-hasil.create', ['proyek_id' => $proyek->id, 'tahun' => $proyek->tahun_anggaran]) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Daftarkan ke Inventaris Hasil</span>
                        </a>
                        @endcan
                    @endif
                @endif

                <a href="{{ route('pembangunan.proyek.index', ['tahun' => $proyek->tahun_anggaran]) }}" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-2xl transition-colors">
                    &larr; Kembali
                </a>

                @can('pembangunan.manage')
                <a href="{{ route('pembangunan.proyek.edit', $proyek) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Proyek</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Main Project Info Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm space-y-6">
            
            <!-- Progress Bar Tracker -->
            <div class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-[#0c3837]">Progres Pelaksanaan Fisik</span>
                    <span class="font-extrabold text-sm text-[#114443]">{{ $proyek->persentase_selesai }}% Selesai</span>
                </div>
                <div class="w-full bg-slate-200 h-3 rounded-full overflow-hidden">
                    <div class="bg-[#10b981] h-full rounded-full transition-all duration-500" style="width: {{ $proyek->persentase_selesai }}%"></div>
                </div>
            </div>

            <!-- Key Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[#64748b] block mb-1">Lokasi Proyek:</span>
                    <strong class="text-[#0c3837] text-sm">{{ $proyek->lokasi }}</strong>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[#64748b] block mb-1">Volume Fisik:</span>
                    <strong class="text-[#0c3837] text-sm">{{ $proyek->volume }}</strong>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[#64748b] block mb-1">Pagu Anggaran:</span>
                    <strong class="text-[#0c3837] text-sm">Rp {{ number_format($proyek->anggaran_biaya, 0, ',', '.') }}</strong>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[#64748b] block mb-1">Sumber Dana:</span>
                    <strong class="text-[#0c3837] text-sm">{{ $proyek->sumber_dana }}</strong>
                </div>
            </div>

            <!-- Pelaksana & Manfaat Warga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e1ede8] text-xs">
                <div>
                    <h4 class="font-bold text-[#0c3837] mb-1">Tim Pelaksana Kegiatan (TPK):</h4>
                    <p class="text-slate-700 bg-[#f7faf9] p-3 rounded-xl border border-[#e1ede8]">{{ $proyek->pelaksana_tpk }}</p>
                </div>
                <div>
                    <h4 class="font-bold text-[#0c3837] mb-1">Penerima Manfaat / Kelompok Warga:</h4>
                    <p class="text-slate-700 bg-[#f7faf9] p-3 rounded-xl border border-[#e1ede8]">{{ $proyek->manfaat_warga ?? 'Seluruh warga masyarakat desa sekitar lokasi kegiatan' }}</p>
                </div>
            </div>

            <!-- File RAB Attachment -->
            @if($proyek->file_rab_path)
                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-[#0c3837]">Dokumen Rencana Anggaran Biaya (RAB)</h4>
                        <p class="text-[11px] text-[#64748b]">Berkas lampiran teknis dan rincian material proyek (PDF)</p>
                    </div>
                    <button @click="openPreview('{{ asset('storage/' . $proyek->file_rab_path) }}', 'RAB {{ addslashes($proyek->nama_kegiatan) }}', true)" class="px-4 py-2 bg-[#e2f0ed] hover:bg-[#114443] text-[#114443] hover:text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Lihat Berkas RAB (PDF)</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- 3-Phase Photo Gallery: Titik Nol (0%), 50%, 100% -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm space-y-4">
            <div>
                <h3 class="text-base font-bold text-[#0c3837]">Dokumentasi Progres Fisik (3 Tahapan Titik)</h3>
                <p class="text-xs text-[#64748b]">Foto visual autentik tahapan titik nol, progres pertengahan 50%, dan serah terima tuntas 100%</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                
                <!-- Tahap 1: Titik Nol (0%) -->
                <div class="rounded-2xl border border-[#e1ede8] overflow-hidden flex flex-col bg-[#f7faf9]">
                    <div class="p-3 bg-white border-b border-[#e1ede8] flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0c3837]">Tahap 1: Titik Nol (0%)</span>
                        <span class="w-2.5 h-2.5 rounded-full {{ $proyek->foto_titik_nol ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    </div>
                    <div class="h-48 bg-slate-100 flex items-center justify-center overflow-hidden relative group">
                        @if($proyek->foto_titik_nol)
                            <img src="{{ asset('storage/' . $proyek->foto_titik_nol) }}" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" @click="openPreview('{{ asset('storage/' . $proyek->foto_titik_nol) }}', 'Foto Titik Nol (0%) - {{ addslashes($proyek->nama_kegiatan) }}', false)" alt="Foto Titik Nol">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs font-bold px-3 py-1 rounded-full bg-[#0c3837]/80">Klik Perbesar</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">Belum ada foto titik nol</span>
                        @endif
                    </div>
                </div>

                <!-- Tahap 2: Progres 50% -->
                <div class="rounded-2xl border border-[#e1ede8] overflow-hidden flex flex-col bg-[#f7faf9]">
                    <div class="p-3 bg-white border-b border-[#e1ede8] flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0c3837]">Tahap 2: Progres 50%</span>
                        <span class="w-2.5 h-2.5 rounded-full {{ $proyek->foto_50_persen ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    </div>
                    <div class="h-48 bg-slate-100 flex items-center justify-center overflow-hidden relative group">
                        @if($proyek->foto_50_persen)
                            <img src="{{ asset('storage/' . $proyek->foto_50_persen) }}" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" @click="openPreview('{{ asset('storage/' . $proyek->foto_50_persen) }}', 'Foto Progres 50% - {{ addslashes($proyek->nama_kegiatan) }}', false)" alt="Foto 50 Persen">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs font-bold px-3 py-1 rounded-full bg-[#0c3837]/80">Klik Perbesar</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">Belum ada foto 50%</span>
                        @endif
                    </div>
                </div>

                <!-- Tahap 3: Serah Terima 100% -->
                <div class="rounded-2xl border border-[#e1ede8] overflow-hidden flex flex-col bg-[#f7faf9]">
                    <div class="p-3 bg-white border-b border-[#e1ede8] flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0c3837]">Tahap 3: Selesai 100%</span>
                        <span class="w-2.5 h-2.5 rounded-full {{ $proyek->foto_100_persen ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    </div>
                    <div class="h-48 bg-slate-100 flex items-center justify-center overflow-hidden relative group">
                        @if($proyek->foto_100_persen)
                            <img src="{{ asset('storage/' . $proyek->foto_100_persen) }}" class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition-transform" @click="openPreview('{{ asset('storage/' . $proyek->foto_100_persen) }}', 'Foto Selesai (100%) - {{ addslashes($proyek->nama_kegiatan) }}', false)" alt="Foto 100 Persen">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs font-bold px-3 py-1 rounded-full bg-[#0c3837]/80">Klik Perbesar</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">Belum ada foto 100%</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Popup Preview Gambar/PDF -->
        <div x-show="previewModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-sm" 
             style="display: none;">
            <div @click.away="previewModal = false" class="bg-white rounded-3xl border border-[#e1ede8] shadow-2xl w-full max-w-4xl flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e1ede8] flex items-center justify-between bg-[#f7faf9]">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                        <h3 class="font-bold text-[#0c3837] text-sm truncate max-w-lg" x-text="previewTitle">Pratinjau Dokumentasi</h3>
                    </div>
                    <button @click="previewModal = false" class="p-1 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 bg-slate-100 flex items-center justify-center min-h-[300px] max-h-[80vh] overflow-auto">
                    <template x-if="isPdf">
                        <iframe :src="previewUrl" class="w-full h-[70vh] rounded-2xl border border-slate-200 bg-white" frameborder="0"></iframe>
                    </template>
                    <template x-if="!isPdf">
                        <img :src="previewUrl" class="max-h-[70vh] max-w-full rounded-2xl object-contain shadow-md" alt="Dokumentasi">
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
