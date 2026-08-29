<x-layouts.app :title="isset($record) ? 'Edit Dokumen APBDes' : 'Tambah Dokumen APBDes'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.anggaran-desa.index') }}" class="hover:text-[#114443]">Buku Anggaran Pemerintah Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Dokumen' : 'Tambah Dokumen Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Dokumen APBDes' : 'Pencatatan Dokumen APBDes' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi formulir registrasi penetapan APBDes murni / perubahan beserta rincian ringkasan anggaran</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.anggaran-desa.update', $record) : route('administrasi.anggaran-desa.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Anggaran <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $record->tahun ?? date('Y')) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Dokumen -->
                    <div>
                        <label for="jenis_dokumen" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Dokumen APBDes <span class="text-rose-500">*</span></label>
                        <select id="jenis_dokumen" name="jenis_dokumen" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="apbdes" {{ old('jenis_dokumen', $record->jenis_dokumen ?? '') === 'apbdes' ? 'selected' : '' }}>APBDes (Penetapan Awal / Murni)</option>
                            <option value="apbdes_perubahan" {{ old('jenis_dokumen', $record->jenis_dokumen ?? '') === 'apbdes_perubahan' ? 'selected' : '' }}>APBDes Perubahan (PAK)</option>
                        </select>
                        @error('jenis_dokumen') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Perdes -->
                    <div>
                        <label for="nomor_perdes" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Perdes Penetapan <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_perdes" name="nomor_perdes" value="{{ old('nomor_perdes', $record->nomor_perdes ?? '') }}" placeholder="Contoh: Nomor 03 Tahun 2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_perdes') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Penetapan -->
                    <div>
                        <label for="tanggal_penetapan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Penetapan <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_penetapan" name="tanggal_penetapan" value="{{ old('tanggal_penetapan', isset($record) ? $record->tanggal_penetapan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_penetapan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-[#e1ede8]">
                    <!-- Total Pendapatan -->
                    <div>
                        <label for="total_pendapatan" class="block text-xs font-bold text-emerald-800 mb-1.5">Total Pendapatan Desa (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="total_pendapatan" name="total_pendapatan" value="{{ old('total_pendapatan', $record->total_pendapatan ?? '0') }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('total_pendapatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Total Belanja -->
                    <div>
                        <label for="total_belanja" class="block text-xs font-bold text-rose-800 mb-1.5">Total Belanja Desa (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="total_belanja" name="total_belanja" value="{{ old('total_belanja', $record->total_belanja ?? '0') }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('total_belanja') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Total Pembiayaan -->
                    <div>
                        <label for="total_pembiayaan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Total Pembiayaan Netto (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="total_pembiayaan" name="total_pembiayaan" value="{{ old('total_pembiayaan', $record->total_pembiayaan ?? '0') }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('total_pembiayaan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan / Catatan Evaluasi</label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Catatan hasil evaluasi bupati / lembaran persetujuan..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $record->keterangan ?? '') }}</textarea>
                    @error('keterangan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah File PDF -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_pdf" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Fisik APBDes (PDF Maks. 10MB)</label>
                    <input type="file" id="file_pdf" name="file_pdf" accept="application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->file_pdf_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>File APBDes saat ini tersimpan: <a href="{{ asset('storage/' . $record->file_pdf_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('file_pdf') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.anggaran-desa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Dokumen APBDes' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
