<x-layouts.app :title="isset($record) ? 'Edit Pos Anggaran APBDes' : 'Tambah Pos Anggaran APBDes'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                <span>/</span>
                <a href="{{ route('keuangan.apbdes.index') }}" class="hover:text-[#114443]">Master APBDes</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Pos' : 'Tambah Pos Anggaran Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Pos Rekening APBDes' : 'Entri Pos Rekening Anggaran APBDes' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi data kode rekening standar Kemendagri, bidang belanja/pendapatan, pagu anggaran, dan sumber dana</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('keuangan.apbdes.update', $record) : route('keuangan.apbdes.store') }}" class="space-y-6">
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

                    <!-- Jenis Anggaran -->
                    <div>
                        <label for="jenis" class="block text-xs font-bold text-[#0c3837] mb-1.5">Klasifikasi Jenis <span class="text-rose-500">*</span></label>
                        <select id="jenis" name="jenis" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="pendapatan" {{ old('jenis', $record->jenis ?? '') === 'pendapatan' ? 'selected' : '' }}>Pendapatan Desa</option>
                            <option value="belanja" {{ old('jenis', $record->jenis ?? 'belanja') === 'belanja' ? 'selected' : '' }}>Belanja Desa</option>
                            <option value="pembiayaan" {{ old('jenis', $record->jenis ?? '') === 'pembiayaan' ? 'selected' : '' }}>Pembiayaan Desa</option>
                        </select>
                        @error('jenis') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Rekening -->
                    <div>
                        <label for="kode_rekening" class="block text-xs font-bold text-[#0c3837] mb-1.5">Kode Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" id="kode_rekening" name="kode_rekening" value="{{ old('kode_rekening', $record->kode_rekening ?? '') }}" placeholder="Contoh: 5.2.1.01 atau 4.1.1.01" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                        @error('kode_rekening') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bidang -->
                    <div>
                        <label for="bidang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Bidang / Sub Bidang</label>
                        <input type="text" id="bidang" name="bidang" value="{{ old('bidang', $record->bidang ?? '') }}" placeholder="Contoh: Bidang 2: Pelaksanaan Pembangunan Desa" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('bidang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sumber Dana <span class="text-rose-500">*</span></label>
                        <select id="sumber_dana" name="sumber_dana" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="DDS" {{ old('sumber_dana', $record->sumber_dana ?? 'DDS') === 'DDS' ? 'selected' : '' }}>DDS (Dana Desa)</option>
                            <option value="ADD" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                            <option value="PBH" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'PBH' ? 'selected' : '' }}>PBH (Bagi Hasil Pajak & Retribusi)</option>
                            <option value="PAD" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'PAD' ? 'selected' : '' }}>PAD (Pendapatan Asli Desa)</option>
                            <option value="DLL" {{ old('sumber_dana', $record->sumber_dana ?? '') === 'DLL' ? 'selected' : '' }}>DLL (Bantuan Keuangan Lainnya)</option>
                        </select>
                        @error('sumber_dana') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Uraian -->
                <div>
                    <label for="uraian" class="block text-xs font-bold text-[#0c3837] mb-1.5">Uraian Akun Anggaran <span class="text-rose-500">*</span></label>
                    <textarea id="uraian" name="uraian" rows="2" required placeholder="Contoh: Belanja Pembangunan Jalan Usaha Tani Dusun Babakan" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('uraian', $record->uraian ?? '') }}</textarea>
                    @error('uraian') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e1ede8]">
                    <!-- Pagu Anggaran -->
                    <div>
                        <label for="anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Pagu Anggaran Tahunan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="anggaran" name="anggaran" value="{{ old('anggaran', $record->anggaran ?? 0) }}" required min="0" step="0.01" placeholder="Contoh: 150000000" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        @error('anggaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Realisasi Anggaran Saat Ini -->
                    <div>
                        <label for="realisasi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Realisasi Penyerapan (Rp)</label>
                        <input type="number" id="realisasi" name="realisasi" value="{{ old('realisasi', $record->realisasi ?? 0) }}" min="0" step="0.01" placeholder="Contoh: 75000000" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#10b981]">
                        @error('realisasi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('keuangan.apbdes.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Pos Anggaran' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
