<x-layouts.app :title="isset($record) ? 'Edit Lembaran/Berita Desa' : 'Tambah Lembaran/Berita Desa'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.lembaran-desa.index') }}" class="hover:text-[#114443]">Buku Lembaran & Berita Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Publikasi' : 'Tambah Publikasi Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Lembaran / Berita Desa' : 'Pengundangan Lembaran / Berita Desa' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi data pengundangan resmi peraturan desa ke dalam Lembaran Desa atau Berita Desa</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.lembaran-desa.update', $record) : route('administrasi.lembaran-desa.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Pengundangan <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $record->tahun ?? date('Y')) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis -->
                    <div>
                        <label for="jenis" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Publikasi <span class="text-rose-500">*</span></label>
                        <select id="jenis" name="jenis" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="lembaran_desa" {{ old('jenis', $record->jenis ?? '') === 'lembaran_desa' ? 'selected' : '' }}>Lembaran Desa (Untuk Perdes)</option>
                            <option value="berita_desa" {{ old('jenis', $record->jenis ?? '') === 'berita_desa' ? 'selected' : '' }}>Berita Desa (Untuk Perkades)</option>
                        </select>
                        @error('jenis') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Seri -->
                    <div>
                        <label for="nomor_seri" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Seri / Nomor Pengundangan <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_seri" name="nomor_seri" value="{{ old('nomor_seri', $record->nomor_seri ?? '') }}" placeholder="Contoh: Seri A No. 01/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_seri') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Diundangkan -->
                    <div>
                        <label for="tanggal_diundangkan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Diundangkan <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_diundangkan" name="tanggal_diundangkan" value="{{ old('tanggal_diundangkan', isset($record) ? $record->tanggal_diundangkan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_diundangkan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-xs font-bold text-[#0c3837] mb-1.5">Judul Lembaran / Berita Desa <span class="text-rose-500">*</span></label>
                    <textarea id="judul" name="judul" rows="2" required placeholder="Contoh: Lembaran Desa Sukamaju Tentang Anggaran Pendapatan dan Belanja Desa Tahun 2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('judul', $record->judul ?? '') }}</textarea>
                    @error('judul') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Isi Singkat -->
                <div>
                    <label for="isi_singkat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Isi / Ringkasan Singkat</label>
                    <textarea id="isi_singkat" name="isi_singkat" rows="3" placeholder="Pokok-pokok pengundangan dan pemberlakuan..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('isi_singkat', $record->isi_singkat ?? '') }}</textarea>
                    @error('isi_singkat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah File PDF -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_pdf" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Fisik Lembaran/Berita Desa (PDF Maks. 10MB)</label>
                    <input type="file" id="file_pdf" name="file_pdf" accept="application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->file_pdf_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>File PDF saat ini tersimpan: <a href="{{ asset('storage/' . $record->file_pdf_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('file_pdf') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.lembaran-desa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Publikasi' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
