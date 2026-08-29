<x-layouts.app :title="isset($record) ? 'Edit Aset Desa' : 'Tambah Aset Desa'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.inventaris-aset.index') }}" class="hover:text-[#114443]">Buku Inventaris & Kekayaan Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Aset' : 'Tambah Aset Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Inventaris & Kekayaan Desa' : 'Pencatatan Inventaris & Kekayaan Desa' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi formulir pencatatan aset inventaris desa beserta kondisi fisik dan harga perolehan</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.inventaris-aset.update', $record) : route('administrasi.inventaris-aset.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun Pengadaan -->
                    <div>
                        <label for="tahun_pengadaan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Pengadaan <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_pengadaan" name="tahun_pengadaan" value="{{ old('tahun_pengadaan', $record->tahun_pengadaan ?? date('Y')) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun_pengadaan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis / Nama Barang -->
                    <div>
                        <label for="jenis_barang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis / Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" id="jenis_barang" name="jenis_barang" value="{{ old('jenis_barang', $record->jenis_barang ?? '') }}" placeholder="Contoh: Laptop Kantor / Kursi Rapat / Bangunan Balai" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jenis_barang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Barang -->
                    <div>
                        <label for="kode_barang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kode Barang / Nomor Seri</label>
                        <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $record->kode_barang ?? '') }}" placeholder="Contoh: AST-2026-001" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('kode_barang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Asal Usul Perolehan -->
                    <div>
                        <label for="asal_usul" class="block text-xs font-bold text-[#0c3837] mb-1.5">Asal Usul Perolehan <span class="text-rose-500">*</span></label>
                        <select id="asal_usul" name="asal_usul" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="apbdes" {{ old('asal_usul', $record->asal_usul ?? '') === 'apbdes' ? 'selected' : '' }}>APBDes (Dana Desa / PADes)</option>
                            <option value="bantuan_pemerintah" {{ old('asal_usul', $record->asal_usul ?? '') === 'bantuan_pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah Pusat</option>
                            <option value="bantuan_provinsi" {{ old('asal_usul', $record->asal_usul ?? '') === 'bantuan_provinsi' ? 'selected' : '' }}>Bantuan Provinsi</option>
                            <option value="bantuan_kabupaten" {{ old('asal_usul', $record->asal_usul ?? '') === 'bantuan_kabupaten' ? 'selected' : '' }}>Bantuan Kabupaten</option>
                            <option value="hibah" {{ old('asal_usul', $record->asal_usul ?? '') === 'hibah' ? 'selected' : '' }}>Hibah / Sumbangan Pihak Ketiga</option>
                            <option value="lainnya" {{ old('asal_usul', $record->asal_usul ?? '') === 'lainnya' ? 'selected' : '' }}>Lain-lain yang Sah</option>
                        </select>
                        @error('asal_usul') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Harga Perolehan (Rp) -->
                    <div>
                        <label for="harga_perolehan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Harga Perolehan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan', $record->harga_perolehan ?? '0') }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('harga_perolehan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kondisi Barang -->
                    <div>
                        <label for="kondisi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kondisi Barang <span class="text-rose-500">*</span></label>
                        <select id="kondisi" name="kondisi" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="baik" {{ old('kondisi', $record->kondisi ?? '') === 'baik' ? 'selected' : '' }}>Baik (Berfungsi Normal)</option>
                            <option value="rusak_ringan" {{ old('kondisi', $record->kondisi ?? '') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan (Dapat Diperbaiki)</option>
                            <option value="rusak_berat" {{ old('kondisi', $record->kondisi ?? '') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat (Tidak Berfungsi)</option>
                        </select>
                        @error('kondisi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Identitas Barang / Spesifikasi -->
                <div>
                    <label for="identitas_barang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Identitas & Spesifikasi Barang <span class="text-rose-500">*</span></label>
                    <textarea id="identitas_barang" name="identitas_barang" rows="3" required placeholder="Merk, type, ukuran, bahan, nomor rangka/mesin, kapasitas..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('identitas_barang', $record->identitas_barang ?? '') }}</textarea>
                    @error('identitas_barang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Lokasi Penempatan -->
                <div>
                    <label for="lokasi_penempatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Lokasi Penempatan / Ruangan <span class="text-rose-500">*</span></label>
                    <input type="text" id="lokasi_penempatan" name="lokasi_penempatan" value="{{ old('lokasi_penempatan', $record->lokasi_penempatan ?? '') }}" placeholder="Contoh: Ruang Pelayanan / Sekretariat Desa / Balai RW 02" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    @error('lokasi_penempatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah Foto / Berkas Dokumen -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="foto_barang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Foto / Berkas Dokumen Aset (JPG, PNG, PDF Maks. 10MB)</label>
                    <input type="file" id="foto_barang" name="foto_barang" accept="image/*,application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->foto_barang_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Berkas aset saat ini tersimpan: <a href="{{ asset('storage/' . $record->foto_barang_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('foto_barang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.inventaris-aset.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Aset' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
