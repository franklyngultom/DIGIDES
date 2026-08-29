<x-layouts.app :title="isset($record) ? 'Edit Peraturan Desa' : 'Tambah Peraturan Desa'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.peraturan-desa.index') }}" class="hover:text-[#114443]">Buku Peraturan di Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Peraturan' : 'Tambah Peraturan Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Peraturan di Desa' : 'Pencatatan Peraturan di Desa' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi formulir registrasi peraturan desa sesuai format buku register administrasi umum</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.peraturan-desa.update', $record) : route('administrasi.peraturan-desa.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Registrasi <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $record->tahun ?? date('Y')) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Peraturan -->
                    <div>
                        <label for="jenis_peraturan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Peraturan <span class="text-rose-500">*</span></label>
                        <select id="jenis_peraturan" name="jenis_peraturan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="perdes" {{ old('jenis_peraturan', $record->jenis_peraturan ?? '') === 'perdes' ? 'selected' : '' }}>Peraturan Desa (Perdes)</option>
                            <option value="perkades" {{ old('jenis_peraturan', $record->jenis_peraturan ?? '') === 'perkades' ? 'selected' : '' }}>Peraturan Kepala Desa (Perkades)</option>
                            <option value="peraturan_bersama" {{ old('jenis_peraturan', $record->jenis_peraturan ?? '') === 'peraturan_bersama' ? 'selected' : '' }}>Peraturan Bersama Kepala Desa</option>
                        </select>
                        @error('jenis_peraturan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Ditetapkan -->
                    <div>
                        <label for="nomor_ditetapkan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Ditetapkan <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_ditetapkan" name="nomor_ditetapkan" value="{{ old('nomor_ditetapkan', $record->nomor_ditetapkan ?? '') }}" placeholder="Contoh: 01/PERDES/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_ditetapkan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Ditetapkan -->
                    <div>
                        <label for="tanggal_ditetapkan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Ditetapkan <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_ditetapkan" name="tanggal_ditetapkan" value="{{ old('tanggal_ditetapkan', isset($record) ? $record->tanggal_ditetapkan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_ditetapkan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tentang / Judul Peraturan -->
                <div>
                    <label for="tentang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tentang / Judul Peraturan <span class="text-rose-500">*</span></label>
                    <textarea id="tentang" name="tentang" rows="3" required placeholder="Contoh: Rencana Kerja Pemerintah Desa (RKPDes) Tahun Anggaran 2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('tentang', $record->tentang ?? '') }}</textarea>
                    @error('tentang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Uraian Singkat -->
                <div>
                    <label for="uraian_singkat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Uraian Singkat (Opsional)</label>
                    <textarea id="uraian_singkat" name="uraian_singkat" rows="2" placeholder="Ringkasan poin substansi isi peraturan desa..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('uraian_singkat', $record->uraian_singkat ?? '') }}</textarea>
                    @error('uraian_singkat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-[#e1ede8]">
                    <!-- Nomor Kesepakatan BPD -->
                    <div>
                        <label for="nomor_kesepakatan_bpd" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Kesepakatan BPD</label>
                        <input type="text" id="nomor_kesepakatan_bpd" name="nomor_kesepakatan_bpd" value="{{ old('nomor_kesepakatan_bpd', $record->nomor_kesepakatan_bpd ?? '') }}" placeholder="Contoh: 140/02/BPD/2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_kesepakatan_bpd') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Diundangkan -->
                    <div>
                        <label for="nomor_diundangkan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Diundangkan</label>
                        <input type="text" id="nomor_diundangkan" name="nomor_diundangkan" value="{{ old('nomor_diundangkan', $record->nomor_diundangkan ?? '') }}" placeholder="Nomor Lembaran Desa" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_diundangkan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Diundangkan -->
                    <div>
                        <label for="tanggal_diundangkan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Diundangkan</label>
                        <input type="date" id="tanggal_diundangkan" name="tanggal_diundangkan" value="{{ old('tanggal_diundangkan', isset($record) && $record->tanggal_diundangkan ? $record->tanggal_diundangkan->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_diundangkan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Unggah File PDF -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_pdf" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Fisik PDF (Maks. 10MB)</label>
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
                    <a href="{{ route('administrasi.peraturan-desa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Peraturan' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
