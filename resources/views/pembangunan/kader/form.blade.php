<x-layouts.app :title="isset($record) ? 'Edit Kader Desa' : 'Tambah Kader Desa'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                <span>/</span>
                <a href="{{ route('pembangunan.kader.index') }}" class="hover:text-[#114443]">Buku Register Kader</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($record) ? 'Edit Kader' : 'Tambah Kader Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($record) ? 'Edit Register Kader Desa' : 'Pendaftaran Kader Pemberdayaan Desa' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">Integrasikan data kader dengan NIK penduduk, jenis kaderisasi, nomor SK penetapan, dan honor bulanan</p>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form method="POST" action="{{ isset($record) ? route('pembangunan.kader.update', $record) : route('pembangunan.kader.store') }}" class="space-y-6">
                @csrf
                @if(isset($record))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Relasi Penduduk -->
                    <div>
                        <label for="penduduk_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">Pilih Warga / Penduduk <span class="text-rose-500">*</span></label>
                        <select id="penduduk_id" name="penduduk_id" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="">-- Pilih Penduduk (NIK & Nama) --</option>
                            @foreach($penduduks as $p)
                                <option value="{{ $p->id }}" {{ old('penduduk_id', $record->penduduk_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nik }} - {{ $p->nama_lengkap }} ({{ $p->dusun ?? 'Dusun' }})
                                </option>
                            @endforeach
                        </select>
                        @error('penduduk_id') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Kader -->
                    <div>
                        <label for="jenis_kader" class="block text-xs font-bold text-[#0c3837] mb-1.5">Klasifikasi Jenis Kader <span class="text-rose-500">*</span></label>
                        <select id="jenis_kader" name="jenis_kader" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="posyandu" {{ old('jenis_kader', $record->jenis_kader ?? 'posyandu') === 'posyandu' ? 'selected' : '' }}>Kader Posyandu</option>
                            <option value="kpm_stunting" {{ old('jenis_kader', $record->jenis_kader ?? '') === 'kpm_stunting' ? 'selected' : '' }}>KPM Penanganan Stunting</option>
                            <option value="pendamping_desa" {{ old('jenis_kader', $record->jenis_kader ?? '') === 'pendamping_desa' ? 'selected' : '' }}>Pendamping Lokal Desa (PLD)</option>
                            <option value="guru_paud" {{ old('jenis_kader', $record->jenis_kader ?? '') === 'guru_paud' ? 'selected' : '' }}>Guru PAUD / TK Desa</option>
                            <option value="pkk" {{ old('jenis_kader', $record->jenis_kader ?? '') === 'pkk' ? 'selected' : '' }}>Pengurus PKK Desa</option>
                            <option value="lainnya" {{ old('jenis_kader', $record->jenis_kader ?? '') === 'lainnya' ? 'selected' : '' }}>Kader Kemasyarakatan Lainnya</option>
                        </select>
                        @error('jenis_kader') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jabatan / Peran <span class="text-rose-500">*</span></label>
                        <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $record->jabatan ?? '') }}" placeholder="Contoh: Ketua Posyandu Melati RW 02 / Kader KPM" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jabatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Honor Bulanan -->
                    <div>
                        <label for="honor_bulanan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Alokasi Honorarium / Bulan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="honor_bulanan" name="honor_bulanan" value="{{ old('honor_bulanan', $record->honor_bulanan ?? 0) }}" required min="0" step="0.01" placeholder="Contoh: 250000" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        @error('honor_bulanan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor SK -->
                    <div>
                        <label for="nomor_sk" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor SK Pengangkatan Kepala Desa</label>
                        <input type="text" id="nomor_sk" name="nomor_sk" value="{{ old('nomor_sk', $record->nomor_sk ?? '') }}" placeholder="Contoh: 141.1/SK-05/DS/2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_sk') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal SK -->
                    <div>
                        <label for="tanggal_sk" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tanggal SK Pengangkatan</label>
                        <input type="date" id="tanggal_sk" name="tanggal_sk" value="{{ old('tanggal_sk', isset($record) && $record->tanggal_sk ? $record->tanggal_sk->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_sk') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label for="status_aktif" class="block text-xs font-bold text-[#0c3837] mb-1.5">Status Keaktifan Kader <span class="text-rose-500">*</span></label>
                    <select id="status_aktif" name="status_aktif" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        <option value="1" {{ old('status_aktif', $record->status_aktif ?? 1) == 1 ? 'selected' : '' }}>Aktif Menjalankan Tugas</option>
                        <option value="0" {{ old('status_aktif', $record->status_aktif ?? 1) == 0 ? 'selected' : '' }}>Non-Aktif / Purna Tugas</option>
                    </select>
                    @error('status_aktif') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan / Catatan Tambahan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan keikutsertaan pelatihan, sertifikasi kader, dsb..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $record->keterangan ?? '') }}</textarea>
                    @error('keterangan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="pt-6 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('pembangunan.kader.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($record) ? 'Simpan Perubahan' : 'Simpan Kader' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
