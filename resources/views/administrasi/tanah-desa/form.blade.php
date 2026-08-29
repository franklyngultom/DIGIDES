<x-layouts.app :title="isset($record) ? 'Edit Data Tanah' : 'Tambah Data Tanah'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.tanah-desa.index') }}" class="hover:text-[#114443]">Buku Tanah di Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Data Tanah' : 'Tambah Data Tanah' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Data Tanah Desa' : 'Pencatatan Bidang Tanah di Desa' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi data sertifikat, letter C, luas, pemilik asal, peruntukan, dan warkah tanah</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.tanah-desa.update', $record) : route('administrasi.tanah-desa.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Tanah -->
                    <div>
                        <label for="jenis_tanah" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Status Tanah <span class="text-rose-500">*</span></label>
                        <select id="jenis_tanah" name="jenis_tanah" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="tanah_kas_desa" {{ old('jenis_tanah', $record->jenis_tanah ?? '') === 'tanah_kas_desa' ? 'selected' : '' }}>Tanah Kas Desa</option>
                            <option value="tanah_bengkok" {{ old('jenis_tanah', $record->jenis_tanah ?? '') === 'tanah_bengkok' ? 'selected' : '' }}>Tanah Bengkok (Perangkat Desa)</option>
                            <option value="tanah_warga" {{ old('jenis_tanah', $record->jenis_tanah ?? '') === 'tanah_warga' ? 'selected' : '' }}>Tanah Warga / Hak Milik Adat</option>
                        </select>
                        @error('jenis_tanah') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Sertifikat / Letter C -->
                    <div>
                        <label for="nomor_sertifikat_letter_c" class="block text-xs font-bold text-[#0c3837] mb-1.5">No. Sertifikat / Nomor Letter C / Kohir <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_sertifikat_letter_c" name="nomor_sertifikat_letter_c" value="{{ old('nomor_sertifikat_letter_c', $record->nomor_sertifikat_letter_c ?? '') }}" placeholder="Contoh: Letter C No. 452 / SHM No. 1234" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_sertifikat_letter_c') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Pemilik Asal -->
                    <div>
                        <label for="nama_pemilik_asal" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nama Pemilik Asal / Atas Nama <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama_pemilik_asal" name="nama_pemilik_asal" value="{{ old('nama_pemilik_asal', $record->nama_pemilik_asal ?? '') }}" placeholder="Contoh: Pemerintah Desa Sukamaju / H. Sanusi" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nama_pemilik_asal') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Luas Tanah (m2) -->
                    <div>
                        <label for="luas_m2" class="block text-xs font-bold text-[#0c3837] mb-1.5">Luas Bidang Tanah (m²) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="luas_m2" name="luas_m2" value="{{ old('luas_m2', $record->luas_m2 ?? '') }}" placeholder="Contoh: 2500" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('luas_m2') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kelas Tanah -->
                    <div>
                        <label for="kelas_tanah" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kelas Tanah (D.I, D.II, S.I, dll)</label>
                        <input type="text" id="kelas_tanah" name="kelas_tanah" value="{{ old('kelas_tanah', $record->kelas_tanah ?? '') }}" placeholder="Contoh: D.II / Darat Kelas 2" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('kelas_tanah') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi / Blok -->
                    <div>
                        <label for="lokasi_blok" class="block text-xs font-bold text-[#0c3837] mb-1.5">Lokasi / Blok / Persil <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi_blok" name="lokasi_blok" value="{{ old('lokasi_blok', $record->lokasi_blok ?? '') }}" placeholder="Contoh: Blok Babakan Persil 24 RW 03" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('lokasi_blok') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Peruntukan Saat Ini -->
                <div>
                    <label for="peruntukan_saat_ini" class="block text-xs font-bold text-[#0c3837] mb-1.5">Peruntukan / Penggunaan Saat Ini <span class="text-rose-500">*</span></label>
                    <input type="text" id="peruntukan_saat_ini" name="peruntukan_saat_ini" value="{{ old('peruntukan_saat_ini', $record->peruntukan_saat_ini ?? '') }}" placeholder="Contoh: Bangunan Kantor Desa / Sawah Kas Desa / Lapangan Olahraga" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    @error('peruntukan_saat_ini') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Patok & Tanda Batas -->
                <div>
                    <label for="patok_tanda_batas" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan Batas / Patok Batas Tanah</label>
                    <textarea id="patok_tanda_batas" name="patok_tanda_batas" rows="2" placeholder="Utara: Jalan Desa, Timur: Tanah H. Ahmad, Selatan: Saluran Air, Barat: Tanah Kas..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('patok_tanda_batas', $record->patok_tanda_batas ?? '') }}</textarea>
                    @error('patok_tanda_batas') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah Warkah / Scan Sertifikat -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_warkah" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Warkah / Scan Sertifikat (PDF/JPG/PNG Maks. 10MB)</label>
                    <input type="file" id="file_warkah" name="file_warkah" accept="image/*,application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->file_warkah_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Berkas warkah saat ini tersimpan: <a href="{{ asset('storage/' . $record->file_warkah_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('file_warkah') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.tanah-desa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Data Tanah' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
