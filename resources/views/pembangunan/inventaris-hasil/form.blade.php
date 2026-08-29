<x-layouts.app :title="isset($item) ? 'Edit Inventaris Hasil: ' . $item->nomor_inventaris : 'Pencatatan Inventaris Hasil Pembangunan'">
    <div class="max-w-4xl mx-auto space-y-6"
         x-data="{
             proyekData: {{ json_encode($proyekList->mapWithKeys(function($p) {
                 return [$p->id => [
                     'nama'        => $p->nama_kegiatan,
                     'volume'      => $p->volume,
                     'lokasi'      => $p->lokasi,
                     'sumber_dana' => $p->sumber_dana,
                     'nilai'       => (float)($p->realisasi_biaya > 0 ? $p->realisasi_biaya : $p->anggaran_biaya),
                     'tahun'       => $p->tahun_anggaran,
                 ]];
             })) }},
             selectedProyekId: '{{ old('pembangunan_proyek_id', $item->pembangunan_proyek_id ?? ($selectedProyek->id ?? '')) }}',
             onProyekChange() {
                 if (this.selectedProyekId && this.proyekData[this.selectedProyekId]) {
                     const p = this.proyekData[this.selectedProyekId];
                     document.getElementById('nama_hasil_pembangunan').value = p.nama;
                     document.getElementById('volume').value = p.volume;
                     document.getElementById('lokasi').value = p.lokasi;
                     document.getElementById('sumber_dana').value = p.sumber_dana;
                     document.getElementById('nilai_aset').value = p.nilai;
                     document.getElementById('tahun_anggaran').value = p.tahun;
                 }
             }
         }">
        
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                <span>/</span>
                <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $tahun]) }}" class="hover:text-[#114443]">Inventaris Hasil</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($item) ? 'Edit Inventaris' : 'Catat Hasil Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($item) ? 'Edit Inventaris Hasil Pembangunan' : 'Pencatatan Inventaris Hasil Pembangunan' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">
                Dokumentasikan sarana prasarana fisik yang telah selesai dikerjakan agar masuk dalam register aset desa
            </p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($item) ? route('pembangunan.inventaris-hasil.update', $item) : route('pembangunan.inventaris-hasil.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <!-- Pilihan Asal Proyek Pembangunan (Opsional Autofill) -->
                <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                    <label for="pembangunan_proyek_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                        Hubungkan dengan Proyek Kegiatan Pembangunan <span class="text-xs font-normal text-slate-400">(Opsional / Autofill)</span>
                    </label>
                    <select id="pembangunan_proyek_id" name="pembangunan_proyek_id" x-model="selectedProyekId" @change="onProyekChange()" class="w-full px-4 py-2.5 bg-white border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        <option value="">-- Tanpa Relasi Proyek (Input Manual Langsung) --</option>
                        @foreach($proyekList as $p)
                            <option value="{{ $p->id }}" {{ (string)old('pembangunan_proyek_id', $item->pembangunan_proyek_id ?? ($selectedProyek->id ?? '')) === (string)$p->id ? 'selected' : '' }}>
                                [Tahun {{ $p->tahun_anggaran }}] {{ $p->nama_kegiatan }} &bull; Progres: {{ $p->persentase_selesai }}% ({{ ucfirst($p->status_progres) }})
                            </option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-[#64748b] mt-1 block">Memilih proyek akan mengisi otomatis nama kegiatan, volume, lokasi, dan nilai perolehannya.</span>
                    @error('pembangunan_proyek_id') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun_anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Selesai / Anggaran <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_anggaran" name="tahun_anggaran" value="{{ old('tahun_anggaran', $item->tahun_anggaran ?? ($selectedProyek->tahun_anggaran ?? $tahun)) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun_anggaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Inventaris -->
                    <div>
                        <label for="nomor_inventaris" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Inventaris Aset <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_inventaris" name="nomor_inventaris" value="{{ old('nomor_inventaris', $item->nomor_inventaris ?? 'INV-BANG/' . date('Y') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" placeholder="Contoh: INV-BANG/2026/001" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                        @error('nomor_inventaris') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kategori Aset -->
                    <div>
                        <label for="kategori_aset" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kategori Aset <span class="text-rose-500">*</span></label>
                        <select id="kategori_aset" name="kategori_aset" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="jalan_jembatan" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'jalan_jembatan' ? 'selected' : '' }}>Jalan & Jembatan</option>
                            <option value="bangunan_gedung" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'bangunan_gedung' ? 'selected' : '' }}>Bangunan & Gedung</option>
                            <option value="irigasi_sanitasi" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'irigasi_sanitasi' ? 'selected' : '' }}>Irigasi & Drainase</option>
                            <option value="sarana_air_bersih" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'sarana_air_bersih' ? 'selected' : '' }}>Sarana Air Bersih</option>
                            <option value="sarana_olahraga" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'sarana_olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                            <option value="fasilitas_umum" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'fasilitas_umum' ? 'selected' : '' }}>Fasilitas Umum & Sosial</option>
                            <option value="lainnya" {{ old('kategori_aset', $item->kategori_aset ?? '') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori_aset') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Nama Hasil Pembangunan -->
                <div>
                    <label for="nama_hasil_pembangunan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nama Sarana / Hasil Pembangunan <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_hasil_pembangunan" name="nama_hasil_pembangunan" value="{{ old('nama_hasil_pembangunan', $item->nama_hasil_pembangunan ?? ($selectedProyek->nama_kegiatan ?? '')) }}" placeholder="Contoh: Jalan Rabat Beton Usaha Tani Dusun Babakan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    @error('nama_hasil_pembangunan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Volume / Dimensi Fisik -->
                    <div>
                        <label for="volume" class="block text-xs font-bold text-[#0c3837] mb-1.5">Volume & Dimensi Ukuran <span class="text-rose-500">*</span></label>
                        <input type="text" id="volume" name="volume" value="{{ old('volume', $item->volume ?? ($selectedProyek->volume ?? '')) }}" placeholder="Contoh: 450 Meter (Lebar 2.5m, Tebal 15cm)" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('volume') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi Pembangunan -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Lokasi / Titik Keberadaan <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $item->lokasi ?? ($selectedProyek->lokasi ?? '')) }}" placeholder="Contoh: Dusun Babakan RT 02 / RW 03" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('lokasi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sumber Dana <span class="text-rose-500">*</span></label>
                        <select id="sumber_dana" name="sumber_dana" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="DDS" {{ old('sumber_dana', $item->sumber_dana ?? ($selectedProyek->sumber_dana ?? 'DDS')) === 'DDS' ? 'selected' : '' }}>DDS (Dana Desa)</option>
                            <option value="ADD" {{ old('sumber_dana', $item->sumber_dana ?? ($selectedProyek->sumber_dana ?? '')) === 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                            <option value="Bantuan Provinsi" {{ old('sumber_dana', $item->sumber_dana ?? ($selectedProyek->sumber_dana ?? '')) === 'Bantuan Provinsi' ? 'selected' : '' }}>Bantuan Keuangan Provinsi</option>
                            <option value="PAD" {{ old('sumber_dana', $item->sumber_dana ?? ($selectedProyek->sumber_dana ?? '')) === 'PAD' ? 'selected' : '' }}>PAD (Pendapatan Asli Desa)</option>
                            <option value="Swadaya" {{ old('sumber_dana', $item->sumber_dana ?? ($selectedProyek->sumber_dana ?? '')) === 'Swadaya' ? 'selected' : '' }}>Swadaya Masyarakat</option>
                        </select>
                        @error('sumber_dana') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nilai Perolehan Aset (Rp) -->
                    <div>
                        <label for="nilai_aset" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nilai Perolehan / Biaya (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="nilai_aset" name="nilai_aset" value="{{ old('nilai_aset', $item->nilai_aset ?? ($selectedProyek->realisasi_biaya ?? ($selectedProyek->anggaran_biaya ?? 0))) }}" step="0.01" min="0" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        @error('nilai_aset') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Serah Terima (BAST) -->
                    <div>
                        <label for="tanggal_serah_terima" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Serah Terima (BAST)</label>
                        <input type="date" id="tanggal_serah_terima" name="tanggal_serah_terima" value="{{ old('tanggal_serah_terima', isset($item) && $item->tanggal_serah_terima ? $item->tanggal_serah_terima->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_serah_terima') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kondisi Fisik -->
                    <div>
                        <label for="kondisi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kondisi Fisik Saat Ini <span class="text-rose-500">*</span></label>
                        <select id="kondisi" name="kondisi" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="baik" {{ old('kondisi', $item->kondisi ?? 'baik') === 'baik' ? 'selected' : '' }}>Baik (Berfungsi Normal)</option>
                            <option value="rusak_ringan" {{ old('kondisi', $item->kondisi ?? '') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan (Perlu Perawatan)</option>
                            <option value="rusak_berat" {{ old('kondisi', $item->kondisi ?? '') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat (Tidak Berfungsi)</option>
                        </select>
                        @error('kondisi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Pengelolaan -->
                    <div>
                        <label for="status_pengelolaan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Status Pengelolaan <span class="text-rose-500">*</span></label>
                        <select id="status_pengelolaan" name="status_pengelolaan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="dikelola_desa" {{ old('status_pengelolaan', $item->status_pengelolaan ?? 'dikelola_desa') === 'dikelola_desa' ? 'selected' : '' }}>Dikelola Pemerintah Desa</option>
                            <option value="diserahkan_ke_masyarakat" {{ old('status_pengelolaan', $item->status_pengelolaan ?? '') === 'diserahkan_ke_masyarakat' ? 'selected' : '' }}>Diserahkan ke Masyarakat (RW/RT)</option>
                            <option value="dikelola_bumdes" {{ old('status_pengelolaan', $item->status_pengelolaan ?? '') === 'dikelola_bumdes' ? 'selected' : '' }}>Dikelola Unit Usaha BUMDes</option>
                            <option value="dihibahkan" {{ old('status_pengelolaan', $item->status_pengelolaan ?? '') === 'dihibahkan' ? 'selected' : '' }}>Dihibahkan ke Pihak Ketiga</option>
                        </select>
                        @error('status_pengelolaan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Penanggung Jawab -->
                    <div>
                        <label for="penanggung_jawab" class="block text-xs font-bold text-[#0c3837] mb-1.5">Penanggung Jawab / Pengelola</label>
                        <input type="text" id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab', $item->penanggung_jawab ?? 'Kaur Umum & Kepala Dusun') }}" placeholder="Contoh: Kaur Umum / Ketua RW 02" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('penanggung_jawab') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Keterangan / Spesifikasi -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan & Spesifikasi Teknis (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan batas tanah, spesifikasi material yang digunakan, atau instruksi pemeliharaan berkala..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $item->keterangan ?? '') }}</textarea>
                    @error('keterangan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Foto Fisik Hasil Pembangunan -->
                    <div>
                        <label for="foto_hasil" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Foto Dokumentasi Hasil Fisik (JPG/PNG Maks. 5MB)</label>
                        <input type="file" id="foto_hasil" name="foto_hasil" accept="image/*" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                        @if(isset($item) && $item->foto_hasil_path)
                            <div class="mt-2 flex items-center gap-2">
                                <img src="{{ asset('storage/' . $item->foto_hasil_path) }}" class="w-12 h-12 object-cover rounded-xl border border-[#e1ede8]" alt="Foto Hasil">
                                <span class="text-xs text-slate-500 font-medium">Foto fisik tersimpan</span>
                            </div>
                        @endif
                        @error('foto_hasil') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Berkas BAST Serah Terima (PDF) -->
                    <div>
                        <label for="file_bast" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Berita Acara Serah Terima (BAST - PDF Maks. 10MB)</label>
                        <input type="file" id="file_bast" name="file_bast" accept=".pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                        @if(isset($item) && $item->file_bast_path)
                            <div class="mt-2 text-xs text-[#10b981] font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <a href="{{ asset('storage/' . $item->file_bast_path) }}" target="_blank" class="underline">Unduh Berkas BAST</a>
                            </div>
                        @endif
                        @error('file_bast') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#e1ede8]">
                    <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $tahun]) }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-7 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($item) ? 'Simpan Perubahan' : 'Simpan Data Inventaris' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
