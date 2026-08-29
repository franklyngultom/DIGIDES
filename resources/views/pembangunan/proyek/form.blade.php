<x-layouts.app :title="isset($record) ? 'Edit Proyek Pembangunan' : 'Tambah Proyek Pembangunan'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                <span>/</span>
                <a href="{{ route('pembangunan.proyek.index') }}" class="hover:text-[#114443]">Buku Proyek</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Kegiatan Pembangunan' : 'Entri Kegiatan Pembangunan Baru' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi rincian usulan RKP Desa, volume pekerjaan, anggaran, progres pengerjaan, dan foto dokumentasi</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('pembangunan.proyek.update', $record) : route('pembangunan.proyek.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun_anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Anggaran <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_anggaran" name="tahun_anggaran" value="{{ old('tahun_anggaran', $record->tahun_anggaran ?? request('tahun', date('Y'))) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun_anggaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Progres -->
                    <div>
                        <label for="status_progres" class="block text-xs font-bold text-[#0c3837] mb-1.5">Status Pelaksanaan <span class="text-rose-500">*</span></label>
                        <select id="status_progres" name="status_progres" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="perencanaan" {{ old('status_progres', $record->status_progres ?? '') === 'perencanaan' ? 'selected' : '' }}>Tahap Perencanaan</option>
                            <option value="proses" {{ old('status_progres', $record->status_progres ?? 'proses') === 'proses' ? 'selected' : '' }}>Sedang Berjalan (Proses)</option>
                            <option value="selesai" {{ old('status_progres', $record->status_progres ?? '') === 'selesai' ? 'selected' : '' }}>Selesai 100%</option>
                            <option value="tertunda" {{ old('status_progres', $record->status_progres ?? '') === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                        </select>
                        @error('status_progres') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Persentase Selesai -->
                    <div>
                        <label for="persentase_selesai" class="block text-xs font-bold text-[#0c3837] mb-1.5">Persentase Selesai (0 - 100%) <span class="text-rose-500">*</span></label>
                        <input type="number" id="persentase_selesai" name="persentase_selesai" value="{{ old('persentase_selesai', $record->persentase_selesai ?? 0) }}" required min="0" max="100" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        @error('persentase_selesai') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Nama Kegiatan -->
                <div>
                    <label for="nama_kegiatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nama Kegiatan Pembangunan <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $record->nama_kegiatan ?? '') }}" placeholder="Contoh: Pengaspalan Jalan Usaha Tani Dusun Babakan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    @error('nama_kegiatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Lokasi Proyek (Dusun / RT / RW) <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $record->lokasi ?? '') }}" placeholder="Contoh: Dusun Babakan RW 03" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('lokasi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Volume -->
                    <div>
                        <label for="volume" class="block text-xs font-bold text-[#0c3837] mb-1.5">Volume / Dimensi Fisik <span class="text-rose-500">*</span></label>
                        <input type="text" id="volume" name="volume" value="{{ old('volume', $record->volume ?? '') }}" placeholder="Contoh: Panjang 500m x Lebar 3m" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('volume') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pagu Anggaran Biaya -->
                    <div>
                        <label for="anggaran_biaya" class="block text-xs font-bold text-[#0c3837] mb-1.5">Pagu Anggaran Biaya (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="anggaran_biaya" name="anggaran_biaya" value="{{ old('anggaran_biaya', $record->anggaran_biaya ?? 0) }}" required min="0" step="0.01" placeholder="Contoh: 120000000" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        @error('anggaran_biaya') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Realisasi Biaya -->
                    <div>
                        <label for="realisasi_biaya" class="block text-xs font-bold text-[#0c3837] mb-1.5">Realisasi Biaya Akhir (Rp)</label>
                        <input type="number" id="realisasi_biaya" name="realisasi_biaya" value="{{ old('realisasi_biaya', $record->realisasi_biaya ?? 0) }}" min="0" step="0.01" placeholder="Contoh: 118500000" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#10b981]">
                        @error('realisasi_biaya') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sumber Dana <span class="text-rose-500">*</span></label>
                        <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $record->sumber_dana ?? 'Dana Desa (DDS)') }}" required placeholder="Contoh: Dana Desa (DDS) / Bantuan Provinsi" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('sumber_dana') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pelaksana TPK -->
                    <div>
                        <label for="pelaksana_tpk" class="block text-xs font-bold text-[#0c3837] mb-1.5">Pelaksana Kegiatan (TPK) <span class="text-rose-500">*</span></label>
                        <input type="text" id="pelaksana_tpk" name="pelaksana_tpk" value="{{ old('pelaksana_tpk', $record->pelaksana_tpk ?? 'TPK Desa Sukamaju') }}" required placeholder="Contoh: TPK Desa / Kaur Pembangunan" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('pelaksana_tpk') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Manfaat Warga -->
                <div>
                    <label for="manfaat_warga" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kelompok Penerima Manfaat / Warga Sasaran</label>
                    <textarea id="manfaat_warga" name="manfaat_warga" rows="2" placeholder="Contoh: 150 KK petani dan warga pengguna jalan akses perkebunan" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('manfaat_warga', $record->manfaat_warga ?? '') }}</textarea>
                    @error('manfaat_warga') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah Foto Dokumentasi (Titik Nol, 50%, 100%) -->
                <div class="pt-4 border-t border-[#e1ede8] space-y-4">
                    <h4 class="text-xs font-bold text-[#0c3837]">Unggah Dokumentasi Foto & RAB Proyek</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Foto 0% -->
                        <div>
                            <label for="foto_titik_nol" class="block text-xs font-semibold text-[#0c3837] mb-1">Foto Titik Nol (0%)</label>
                            <input type="file" id="foto_titik_nol" name="foto_titik_nol" accept="image/*" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#114443] file:text-white cursor-pointer">
                            @if(isset($record) && $record->foto_titik_nol)
                                <div class="mt-1 text-[11px] text-[#10b981] font-medium">Foto 0% tersimpan</div>
                            @endif
                            @error('foto_titik_nol') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Foto 50% -->
                        <div>
                            <label for="foto_50_persen" class="block text-xs font-semibold text-[#0c3837] mb-1">Foto Progres 50%</label>
                            <input type="file" id="foto_50_persen" name="foto_50_persen" accept="image/*" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#114443] file:text-white cursor-pointer">
                            @if(isset($record) && $record->foto_50_persen)
                                <div class="mt-1 text-[11px] text-[#10b981] font-medium">Foto 50% tersimpan</div>
                            @endif
                            @error('foto_50_persen') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Foto 100% -->
                        <div>
                            <label for="foto_100_persen" class="block text-xs font-semibold text-[#0c3837] mb-1">Foto Selesai (100%)</label>
                            <input type="file" id="foto_100_persen" name="foto_100_persen" accept="image/*" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#114443] file:text-white cursor-pointer">
                            @if(isset($record) && $record->foto_100_persen)
                                <div class="mt-1 text-[11px] text-[#10b981] font-medium">Foto 100% tersimpan</div>
                            @endif
                            @error('foto_100_persen') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- File RAB PDF -->
                    <div class="pt-2">
                        <label for="file_rab" class="block text-xs font-semibold text-[#0c3837] mb-1">Unggah Berkas Rencana Anggaran Biaya (RAB PDF Maks. 10MB)</label>
                        <input type="file" id="file_rab" name="file_rab" accept="application/pdf" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#114443] file:text-white cursor-pointer">
                        @if(isset($record) && $record->file_rab_path)
                            <div class="mt-1 text-[11px] text-[#10b981] font-medium">Berkas RAB PDF tersimpan</div>
                        @endif
                        @error('file_rab') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ isset($record) ? route('pembangunan.proyek.show', $record) : route('pembangunan.proyek.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Proyek' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
