<x-layouts.app :title="isset($record) ? 'Edit Transaksi Kas' : 'Catat Transaksi Kas Baru'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                <span>/</span>
                <a href="{{ route('keuangan.kas.index', ['type' => $type]) }}" class="hover:text-[#114443]">Buku Kas {{ $kategori === 'bank' ? 'Bank' : 'Tunai' }}</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Transaksi' : 'Catat Transaksi Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Transaksi Kas' : 'Pencatatan Transaksi Kas Baru' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Tentukan kategori kas (tunai/saldo bank) dan jenis pembantu (opsional) untuk memastikan akurasi saldo berjalan</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('keuangan.kas.update', $record) : route('keuangan.kas.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                    <!-- Kategori Kas (Tunai vs Saldo Bank) -->
                    <div>
                        <label for="kategori_kas" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Kategori Kas <span class="text-rose-500">*</span>
                        </label>
                        <select id="kategori_kas" name="kategori_kas" required class="w-full px-4 py-2.5 bg-white border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20">
                            <option value="tunai" {{ old('kategori_kas', $record->kategori_kas ?? $kategori) === 'tunai' ? 'selected' : '' }}>Kas Tunai (Fisik Brankas Desa)</option>
                            <option value="bank" {{ old('kategori_kas', $record->kategori_kas ?? $kategori) === 'bank' || old('kategori_kas', $record->kategori_kas ?? $kategori) === 'saldo' ? 'selected' : '' }}>Saldo / Kas Bank (Rekening Kas Desa)</option>
                        </select>
                        <span class="text-[10px] text-[#64748b] mt-1 block">Saldo berjalan dihitung mandiri per kategori kas agar tidak tercampur</span>
                        @error('kategori_kas') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Pembantu (Optional) -->
                    <div>
                        <label for="jenis_pembantu" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Jenis Pembantu <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <select id="jenis_pembantu" name="jenis_pembantu" class="w-full px-4 py-2.5 bg-white border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20">
                            <option value="" {{ old('jenis_pembantu', $record->jenis_pembantu ?? $pembantu) == '' ? 'selected' : '' }}>-- Tanpa Pembantu (Kas Utama) --</option>
                            <option value="umum" {{ old('jenis_pembantu', $record->jenis_pembantu ?? $pembantu) === 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="pajak" {{ old('jenis_pembantu', $record->jenis_pembantu ?? $pembantu) === 'pajak' ? 'selected' : '' }}>Pajak (PPh / PPN)</option>
                            <option value="panjar" {{ old('jenis_pembantu', $record->jenis_pembantu ?? $pembantu) === 'panjar' ? 'selected' : '' }}>Panjar / Kegiatan</option>
                        </select>
                        <span class="text-[10px] text-[#64748b] mt-1 block">Pilih umum / pajak / panjar jika transaksi ini masuk ke kas pembantu</span>
                        @error('jenis_pembantu') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun_anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Anggaran <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_anggaran" name="tahun_anggaran" value="{{ old('tahun_anggaran', $record->tahun_anggaran ?? request('tahun', date('Y'))) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun_anggaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="tanggal" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', isset($record) ? $record->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Nomor Bukti -->
                    <div>
                        <label for="nomor_bukti" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Bukti Kas / Kwitansi <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_bukti" name="nomor_bukti" value="{{ old('nomor_bukti', $record->nomor_bukti ?? '') }}" placeholder="Contoh: BKK-01/DDS/2026 atau BKM-02/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                        @error('nomor_bukti') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Rekening -->
                    <div>
                        <label for="kode_rekening" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kode Rekening (Opsional)</label>
                        <input type="text" id="kode_rekening" name="kode_rekening" value="{{ old('kode_rekening', $record->kode_rekening ?? '') }}" placeholder="Contoh: 5.2.1.01" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                        @error('kode_rekening') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sumber Dana <span class="text-rose-500">*</span></label>
                        <select id="sumber_dana" name="sumber_dana" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="DDS" {{ old('sumber_dana', $record->sumber_dana ?? 'DDS') === 'DDS' ? 'selected' : '' }}>DDS (Dana Desa)</option>
                            <option value="ADD" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                            <option value="PBH" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'PBH' ? 'selected' : '' }}>PBH (Bagi Hasil Pajak)</option>
                            <option value="PAD" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'PAD' ? 'selected' : '' }}>PAD (Pendapatan Asli Desa)</option>
                            <option value="DLL" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'DLL' ? 'selected' : '' }}>DLL (Bantuan Keuangan)</option>
                        </select>
                        @error('sumber_dana') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Uraian -->
                <div>
                    <label for="uraian" class="block text-xs font-bold text-[#0c3837] mb-1.5">Uraian Transaksi Kas <span class="text-rose-500">*</span></label>
                    <textarea id="uraian" name="uraian" rows="3" required placeholder="Contoh: Pembayaran honor kader Posyandu Melati bulan Januari 2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('uraian', $record->uraian ?? '') }}</textarea>
                    @error('uraian') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e1ede8]">
                    <!-- Penerimaan (Masuk) -->
                    <div>
                        <label for="penerimaan" class="block text-xs font-bold text-emerald-800 mb-1.5">Penerimaan / Uang Masuk (Rp)</label>
                        <input type="number" id="penerimaan" name="penerimaan" value="{{ old('penerimaan', $record->penerimaan ?? 0) }}" required min="0" step="0.01" placeholder="0" class="w-full px-4 py-2.5 bg-emerald-50/50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-800 focus:outline-none focus:border-emerald-500">
                        <span class="text-[10px] text-[#64748b] mt-1 block">Isi 0 jika transaksi ini merupakan pengeluaran</span>
                        @error('penerimaan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pengeluaran (Keluar) -->
                    <div>
                        <label for="pengeluaran" class="block text-xs font-bold text-rose-800 mb-1.5">Pengeluaran / Uang Keluar (Rp)</label>
                        <input type="number" id="pengeluaran" name="pengeluaran" value="{{ old('pengeluaran', $record->pengeluaran ?? 0) }}" required min="0" step="0.01" placeholder="0" class="w-full px-4 py-2.5 bg-rose-50/50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-800 focus:outline-none focus:border-rose-500">
                        <span class="text-[10px] text-[#64748b] mt-1 block">Isi 0 jika transaksi ini merupakan penerimaan</span>
                        @error('pengeluaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Unggah Berkas Bukti / Kwitansi -->
                <div class="pt-4 border-t border-[#e1ede8]">
                    <label for="file_bukti" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Fisik Bukti Kas / Kwitansi (PDF/JPG/PNG Maks. 10MB)</label>
                    <input type="file" id="file_bukti" name="file_bukti" accept="image/*,application/pdf" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($record) && $record->file_bukti_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Bukti kas saat ini tersimpan: <a href="{{ asset('storage/' . $record->file_bukti_path) }}" target="_blank" class="underline font-bold">Lihat Berkas</a></span>
                        </div>
                    @endif
                    @error('file_bukti') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('keuangan.kas.index', ['type' => $type]) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Transaksi Kas' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
