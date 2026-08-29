<x-layouts.app :title="isset($record) ? 'Edit Agenda Surat' : 'Tambah Agenda Surat'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.buku-agenda.index') }}" class="hover:text-[#114443]">Buku Agenda Surat</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Agenda' : 'Tambah Agenda Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Agenda Surat' : 'Pencatatan Agenda Surat Masuk / Keluar' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi data penomoran agenda, asal instansi, tujuan, tanggal terima/kirim, dan berkas surat</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.buku-agenda.update', $record) : route('administrasi.buku-agenda.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Jenis Surat -->
                    <div>
                        <label for="jenis" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Agenda <span class="text-rose-500">*</span></label>
                        <select id="jenis" name="jenis" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="masuk" {{ old('jenis', $record->jenis ?? '') === 'masuk' ? 'selected' : '' }}>Surat Masuk (Diterima Desa)</option>
                            <option value="keluar" {{ old('jenis', $record->jenis ?? '') === 'keluar' ? 'selected' : '' }}>Surat Keluar (Dikirim Desa)</option>
                        </select>
                        @error('jenis') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Urut Agenda -->
                    <div>
                        <label for="nomor_urut" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Urut Agenda <span class="text-rose-500">*</span></label>
                        <input type="number" id="nomor_urut" name="nomor_urut" value="{{ old('nomor_urut', $record->nomor_urut ?? ((\App\Models\BukuAgenda::where('tahun', date('Y'))->max('nomor_urut') ?? 0) + 1)) }}" required min="1" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_urut') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $record->tahun ?? date('Y')) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e1ede8]">
                    <!-- Nomor Surat -->
                    <div>
                        <label for="nomor_surat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $record->nomor_surat ?? '') }}" placeholder="Contoh: 005/123/Kec/2026 atau 140/01/DS/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_surat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Asal Pengirim / Tujuan Penerima -->
                    <div>
                        <label for="asal_tujuan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Asal Pengirim / Tujuan Penerima <span class="text-rose-500">*</span></label>
                        <input type="text" id="asal_tujuan" name="asal_tujuan" value="{{ old('asal_tujuan', $record->asal_tujuan ?? '') }}" placeholder="Contoh: Camat Cikole / Dinas PMD / Warga RW 02" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('asal_tujuan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div>
                        <label for="tanggal_surat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal yang Tertera pada Surat <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', isset($record) ? $record->tanggal_surat->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_surat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Diterima / Dikirim -->
                    <div>
                        <label for="tanggal_diterima_dikirim" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Diterima / Dikirimkan <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_diterima_dikirim" name="tanggal_diterima_dikirim" value="{{ old('tanggal_diterima_dikirim', isset($record) ? $record->tanggal_diterima_dikirim->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_diterima_dikirim') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Perihal Surat -->
                <div>
                    <label for="perihal" class="block text-xs font-bold text-[#0c3837] mb-1.5">Perihal / Isi Ringkas Surat <span class="text-rose-500">*</span></label>
                    <textarea id="perihal" name="perihal" rows="3" required placeholder="Contoh: Undangan Rapat Koordinasi Penyaluran BLT Dana Desa Triwulan I" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('perihal', $record->perihal ?? '') }}</textarea>
                    @error('perihal') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Keterangan / Disposisi -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan / Disposisi Singkat</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan tindak lanjut / ditujukan ke Sekdes/Kaur..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $record->keterangan ?? '') }}</textarea>
                    @error('keterangan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unggah Berkas Scan Surat -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_surat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Scan Surat (PDF/JPG/PNG Maks. 10MB)</label>
                    <input type="file" id="file_surat" name="file_surat" accept="image/*,application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->file_surat_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Berkas scan saat ini tersimpan: <a href="{{ asset('storage/' . $record->file_surat_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('file_surat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.buku-agenda.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Agenda Surat' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
