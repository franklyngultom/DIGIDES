<x-layouts.app>
    <x-slot:title>Detail Warga: {{ $penduduk->nama_lengkap }}</x-slot:title>

    <div x-data="{
        uploadModal: false,
        mutasiModal: false,
        previewModal: false,
        previewUrl: '',
        previewTitle: '',
        isPdf: false,
        openPreview(url, title, isPdfFile) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.isPdf = isPdfFile;
            this.previewModal = true;
        }
    }">
        <!-- Top Workspace Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                    <span class="text-xs text-[#64748b]">/</span>
                    <a href="{{ route('kependudukan.index') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Buku Induk</a>
                    <span class="text-xs text-[#64748b]">/</span>
                    <span class="text-xs font-bold text-[#0c3837]">{{ $penduduk->nama_lengkap }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">{{ $penduduk->nama_lengkap }}</h1>
                    @php
                        $statusVariant = match($penduduk->status_penduduk) {
                            'tetap' => 'emerald',
                            'sementara' => 'amber',
                            'pindah' => 'slate',
                            'meninggal' => 'rose',
                            default => 'slate'
                        };
                    @endphp
                    <x-badge :variant="$statusVariant" class="text-xs">
                        {{ ucfirst($penduduk->status_penduduk) }}
                    </x-badge>
                </div>
                <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">
                    NIK: <span class="font-mono font-bold text-[#0c3837]">{{ $penduduk->nik }}</span> · No. KK: <span class="font-mono text-[#0c3837]">{{ $penduduk->no_kk }}</span> · {{ $penduduk->umur }} Tahun ({{ $penduduk->kategori_usia }})
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('kependudukan.index') }}" 
                   class="px-4 py-2 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Buku Induk</span>
                </a>

                @can('kependudukan.edit')
                <a href="{{ route('kependudukan.edit', $penduduk) }}" 
                   id="btn-edit-warga"
                   class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Data</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Main Content 2 Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <!-- Left 2 Columns: Biodata & Documents -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 1. Biodata Kependudukan Card -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">📋</span>
                        <span>Biodata Lengkap Kependudukan</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                        @php
                            $fields = [
                                'Nomor Induk Kependudukan (NIK)' => $penduduk->nik,
                                'Nomor Kartu Keluarga (KK)'      => $penduduk->no_kk,
                                'Nama Lengkap'                   => $penduduk->nama_lengkap,
                                'Tempat / Tanggal Lahir'         => $penduduk->tempat_lahir . ', ' . $penduduk->tanggal_lahir->format('d F Y'),
                                'Jenis Kelamin'                  => $penduduk->jenis_kelamin_label,
                                'Agama'                          => $penduduk->agama,
                                'Golongan Darah'                 => $penduduk->golongan_darah ?? 'Tidak Diketahui',
                                'Status Perkawinan'              => $penduduk->status_perkawinan_label,
                                'Hubungan Dalam Keluarga'        => $penduduk->status_keluarga_label,
                                'Kewarganegaraan'                => $penduduk->kewarganegaraan,
                                'Pendidikan Terakhir'            => $penduduk->pendidikan_terakhir ?? '-',
                                'Pekerjaan'                      => $penduduk->pekerjaan ?? '-',
                                'Nomor Telepon / WhatsApp'       => $penduduk->telepon ?? '-',
                                'Sumber Data'                    => match($penduduk->sumber_data) {
                                                                        'prodeskel' => 'Prodeskel Kemendagri',
                                                                        'manual' => 'Input Manual Staff',
                                                                        'migrasi_legacy' => 'Migrasi DB Legacy',
                                                                        default => $penduduk->sumber_data
                                                                    },
                            ];
                        @endphp

                        @foreach($fields as $label => $value)
                        <div class="py-2 border-b border-[#e1ede8]/70">
                            <span class="text-[#64748b] block text-[11px] mb-0.5">{{ $label }}</span>
                            <span class="font-bold text-[#0c3837] text-xs {{ str_contains($label, 'NIK') || str_contains($label, 'KK') ? 'font-mono tracking-wider' : '' }}">{{ $value }}</span>
                        </div>
                        @endforeach

                        <div class="sm:col-span-2 py-2 border-b border-[#e1ede8]/70">
                            <span class="text-[#64748b] block text-[11px] mb-0.5">Alamat Lengkap Domisili</span>
                            <span class="font-bold text-[#0c3837] text-xs block">{{ $penduduk->alamat_lengkap }}</span>
                            <span class="text-[11px] text-[#64748b] mt-0.5 block">RT {{ $penduduk->rt }} / RW {{ $penduduk->rw }} · Dusun {{ $penduduk->dusun ?? '-' }}</span>
                        </div>
                    </div>
                </x-card>

                <!-- 2. Berkas Dokumen Pendukung (`Lihat Berkas`) Card -->
                <x-card class="space-y-4" id="section-berkas">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837] flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">📁</span>
                            <span>Manajemen Berkas Lampiran Warga</span>
                        </h3>

                        @can('kependudukan.edit')
                        <button type="button"
                                @click="uploadModal = true"
                                id="btn-upload-berkas"
                                class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Unggah Berkas Baru</span>
                        </button>
                        @endcan
                    </div>

                    @if($penduduk->documents->isEmpty())
                        <div class="py-8 text-center text-[#64748b] bg-[#f7faf9] rounded-2xl border border-dashed border-[#e1ede8]">
                            <span class="text-2xl block mb-1">📄</span>
                            <span class="font-semibold block text-xs text-[#0c3837]">Belum ada berkas dokumen tersimpan</span>
                            <span class="text-[11px] text-[#64748b]">Unggah scan KTP, Kartu Keluarga, Akta Kelahiran, atau Surat Nikah warga.</span>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                            @foreach($penduduk->documents as $doc)
                            <div class="p-3.5 bg-[#f7faf9] rounded-2xl border border-[#e1ede8] flex flex-col justify-between hover:border-[#10b981] transition-all group">
                                <div>
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="w-10 h-10 rounded-xl {{ $doc->is_pdf ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30' }} flex items-center justify-center text-lg">
                                            {{ $doc->is_pdf ? '📕' : '🖼️' }}
                                        </div>
                                        <x-badge variant="{{ $doc->is_pdf ? 'rose' : 'emerald' }}" class="text-[10px] uppercase font-mono">
                                            {{ $doc->is_pdf ? 'PDF' : 'IMAGE' }}
                                        </x-badge>
                                    </div>

                                    <span class="font-bold text-[#0c3837] text-xs block truncate" title="{{ $doc->nama_file }}">
                                        {{ $doc->jenis_dokumen_label }}
                                    </span>
                                    <span class="text-[10px] text-[#64748b] block truncate mt-0.5">
                                        {{ $doc->nama_file }} · {{ $doc->formatted_size }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 mt-4 pt-2.5 border-t border-[#e1ede8]">
                                    <!-- In-Page Modal Viewer Button -->
                                    <button type="button"
                                            @click="openPreview('{{ route('kependudukan.documents.stream', $doc) }}', '{{ $doc->jenis_dokumen_label }} - {{ $penduduk->nama_lengkap }}', {{ $doc->is_pdf ? 'true' : 'false' }})"
                                            id="btn-preview-{{ $doc->id }}"
                                            class="flex-1 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-[11px] font-bold rounded-xl text-center transition-colors cursor-pointer">
                                        Lihat Berkas
                                    </button>

                                    @can('kependudukan.edit')
                                    <form method="POST" action="{{ route('kependudukan.documents.destroy', $doc) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas {{ $doc->nama_file }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                id="btn-del-doc-{{ $doc->id }}"
                                                class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" 
                                                title="Hapus Berkas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Right Column: Riwayat Mutasi & Quick Info -->
            <div class="space-y-6">
                <!-- Riwayat Mutasi Card -->
                <x-card class="space-y-4">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837] flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">🔄</span>
                            <span>Riwayat Mutasi Warga</span>
                        </h3>

                        @can('kependudukan.edit')
                        <button type="button"
                                @click="mutasiModal = true"
                                id="btn-catat-mutasi"
                                class="px-3 py-1 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] text-xs font-bold rounded-full transition-colors cursor-pointer">
                            + Catat Mutasi
                        </button>
                        @endcan
                    </div>

                    @if($penduduk->mutasis->isEmpty())
                        <div class="py-6 text-center text-[#64748b]">
                            <span class="text-xs">Belum ada peristiwa mutasi tercatat untuk warga ini.</span>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($penduduk->mutasis as $mutasi)
                            <div class="p-3 bg-[#f7faf9] rounded-2xl border border-[#e1ede8] space-y-1">
                                <div class="flex items-center justify-between">
                                    <x-badge variant="{{ $mutasi->badge_color }}" class="text-[10px]">
                                        {{ $mutasi->jenis_mutasi_label }}
                                    </x-badge>
                                    <span class="text-[11px] font-mono text-[#64748b]">
                                        {{ $mutasi->tanggal_mutasi->format('d M Y') }}
                                    </span>
                                </div>
                                @if($mutasi->keterangan)
                                <p class="text-xs text-[#0f172a] mt-1">{{ $mutasi->keterangan }}</p>
                                @endif
                                <div class="text-[10px] text-[#64748b] pt-1 flex justify-between">
                                    <span>Oleh: {{ $mutasi->createdBy?->name ?? 'Staf' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Quick Classification Card -->
                <x-card class="bg-gradient-to-br from-[#e2f0ed] to-[#f7faf9] border-[#10b981]/30 space-y-3">
                    <span class="text-xs font-bold text-[#114443] uppercase tracking-wider block">Ringkasan Klasifikasi</span>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-[#114443]/10">
                            <span class="text-[#64748b]">Kategori Usia:</span>
                            <span class="font-extrabold text-[#0c3837]">{{ $penduduk->kategori_usia }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-[#114443]/10">
                            <span class="text-[#64748b]">Status Registrasi:</span>
                            <span class="font-extrabold text-[#0c3837]">{{ ucfirst($penduduk->status_penduduk) }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-[#64748b]">Terdaftar Pada:</span>
                            <span class="font-mono text-[#0c3837]">{{ $penduduk->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- 1. MODAL UNGGAH BERKAS -->
        <!-- ============================================================= -->
        <div x-show="uploadModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/50 backdrop-blur-xs" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="uploadModal = false" class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-2xl max-w-md w-full space-y-4">
                <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837]">Unggah Dokumen Lampiran</h3>
                    <button type="button" @click="uploadModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
                </div>

                <form method="POST" action="{{ route('kependudukan.documents.store', $penduduk) }}" enctype="multipart/form-data" id="form-upload-berkas" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="jenis_dokumen">
                            Jenis Dokumen <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis_dokumen" id="jenis_dokumen" required
                                class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                            <option value="ktp">Kartu Tanda Penduduk (KTP)</option>
                            <option value="kk">Kartu Keluarga (KK)</option>
                            <option value="akta_lahir">Akta Kelahiran</option>
                            <option value="surat_nikah">Surat Nikah / Akta Perkawinan</option>
                            <option value="ijazah">Ijazah / Surat Tanda Tamat Belajar</option>
                            <option value="lainnya">Dokumen Pendukung Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="file">
                            Pilih File (PDF, JPG, PNG - Maks 5 MB) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="file" id="file" required accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-[#e1ede8]">
                        <button type="button" @click="uploadModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-upload" class="px-5 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                            Unggah Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- 2. MODAL CATAT MUTASI -->
        <!-- ============================================================= -->
        <div x-show="mutasiModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/50 backdrop-blur-xs" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="mutasiModal = false" class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-2xl max-w-md w-full space-y-4">
                <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837]">Pencatatan Peristiwa Mutasi</h3>
                    <button type="button" @click="mutasiModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
                </div>

                <form method="POST" action="{{ route('kependudukan.mutasi.store', $penduduk) }}" enctype="multipart/form-data" id="form-catat-mutasi" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="jenis_mutasi">
                            Jenis Peristiwa Mutasi <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis_mutasi" id="jenis_mutasi" required
                                class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981]">
                            <option value="pindah_keluar">Pindah Keluar Desa</option>
                            <option value="pindah_masuk">Pindah Masuk Desa</option>
                            <option value="mati">Kematian / Meninggal Dunia</option>
                            <option value="lahir">Kelahiran Baru</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="tanggal_mutasi">
                            Tanggal Peristiwa <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mutasi" id="tanggal_mutasi" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="keterangan_mutasi">
                            Keterangan / Alasan Mutasi
                        </label>
                        <textarea name="keterangan" id="keterangan_mutasi" rows="2" placeholder="Contoh: Pindah mengikuti orang tua ke Bogor..."
                                  class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981]"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="berkas_pendukung">
                            Berkas Bukti / Surat Keterangan (Opsional)
                        </label>
                        <input type="file" name="berkas_pendukung" id="berkas_pendukung" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-[#e1ede8]">
                        <button type="button" @click="mutasiModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-mutasi" class="px-5 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                            Simpan Peristiwa Mutasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- 3. MODAL INSTANT DOCUMENT VIEWER (PDF / IMAGE) -->
        <!-- ============================================================= -->
        <div x-show="previewModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-md" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="previewModal = false" class="bg-white rounded-3xl border border-[#e1ede8] shadow-2xl max-w-4xl w-full h-[85vh] flex flex-col overflow-hidden">
                <!-- Viewer Header -->
                <div class="px-6 py-4 bg-[#f7faf9] border-b border-[#e1ede8] flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-[#114443] text-[#d4ed31] flex items-center justify-center text-sm font-bold">📄</span>
                        <div>
                            <h4 class="text-sm font-bold text-[#0c3837]" x-text="previewTitle"></h4>
                            <span class="text-[10px] text-[#64748b]">Pratinjau Berkas Lampiran Resmi</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a :href="previewUrl" target="_blank" class="px-3.5 py-1.5 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] text-xs font-bold rounded-full transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Buka di Tab Baru</span>
                        </a>
                        <button type="button" @click="previewModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">✕</button>
                    </div>
                </div>

                <!-- Viewer Body -->
                <div class="flex-1 bg-slate-900/5 p-4 flex items-center justify-center overflow-auto">
                    <template x-if="isPdf">
                        <iframe :src="previewUrl" class="w-full h-full rounded-2xl border border-[#e1ede8] bg-white" frameborder="0"></iframe>
                    </template>
                    <template x-if="!isPdf">
                        <img :src="previewUrl" class="max-w-full max-h-full object-contain rounded-2xl shadow-md border border-[#e1ede8]" alt="Document Preview">
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
