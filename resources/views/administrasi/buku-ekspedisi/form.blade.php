<x-layouts.app :title="isset($record) ? 'Edit Buku Ekspedisi' : 'Tambah Buku Ekspedisi'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                <span>/</span>
                <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="hover:text-[#114443]">Buku Ekspedisi Surat</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Ekspedisi' : 'Tambah Ekspedisi Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Ekspedisi Pengiriman Surat' : 'Pencatatan Ekspedisi Pengiriman Surat' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Lengkapi data nomor urut ekspedisi, tanggal kirim, perihal, dan penerima berkas fisik kantor desa</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('administrasi.buku-ekspedisi.update', $record) : route('administrasi.buku-ekspedisi.store') }}" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Urut Ekspedisi -->
                    <div>
                        <label for="nomor_urut" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Urut Ekspedisi <span class="text-rose-500">*</span></label>
                        <input type="number" id="nomor_urut" name="nomor_urut" value="{{ old('nomor_urut', $record->nomor_urut ?? ($nextNomorUrut ?? 1)) }}" required min="1" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_urut') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $record->tahun ?? ($currentYear ?? date('Y'))) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Pengiriman -->
                    <div>
                        <label for="tanggal_pengiriman" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal Pengiriman Surat <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman', isset($record) ? $record->tanggal_pengiriman->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_pengiriman') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Surat -->
                    <div>
                        <label for="nomor_surat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Surat Dinas <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $record->nomor_surat ?? '') }}" placeholder="Contoh: 140/04/DS/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_surat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div>
                        <label for="tanggal_surat" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal yang Tertera pada Surat <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', isset($record) ? $record->tanggal_surat->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_surat') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tujuan Penerima -->
                    <div>
                        <label for="tujuan_penerima" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tujuan Penerima Surat <span class="text-rose-500">*</span></label>
                        <input type="text" id="tujuan_penerima" name="tujuan_penerima" value="{{ old('tujuan_penerima', $record->tujuan_penerima ?? '') }}" placeholder="Contoh: Camat Cikole / Kepala Bappeda Kab. Sukabumi" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tujuan_penerima') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Perihal Surat -->
                <div>
                    <label for="perihal" class="block text-xs font-bold text-[#0c3837] mb-1.5">Perihal / Isi Ringkas Surat <span class="text-rose-500">*</span></label>
                    <textarea id="perihal" name="perihal" rows="3" required placeholder="Contoh: Laporan Realisasi Penyerapan Dana Desa Tahap I Tahun Anggaran 2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('perihal', $record->perihal ?? '') }}</textarea>
                    @error('perihal') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Petugas Pengirim -->
                    <div>
                        <label for="petugas_pengirim" class="block text-xs font-bold text-[#0c3837] mb-1.5">Petugas Pengirim / Kurir <span class="text-rose-500">*</span></label>
                        <input type="text" id="petugas_pengirim" name="petugas_pengirim" value="{{ old('petugas_pengirim', $record->petugas_pengirim ?? auth()->user()->name) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('petugas_pengirim') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan / Tanda Terima -->
                    <div>
                        <label for="catatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Catatan / Bukti Tanda Terima</label>
                        <input type="text" id="catatan" name="catatan" value="{{ old('catatan', $record->catatan ?? '') }}" placeholder="Contoh: Diterima oleh Staf Umum Kec. (Bpk. Dani)" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('catatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Ekspedisi' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
