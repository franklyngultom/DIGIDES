<x-layouts.app :title="isset($aparatur) ? 'Edit Aparat Desa' : 'Tambah Aparat Desa'">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'umum']) }}" class="hover:text-[#114443]">Administrasi Umum</a>
                    <span>/</span>
                    <a href="{{ route('administrasi.aparatur.index') }}" class="hover:text-[#114443]">Buku Aparat Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ isset($aparatur) ? 'Edit Aparat' : 'Form Registrasi' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ isset($aparatur) ? 'Edit Data Aparat Desa' : 'Registrasi Aparat Pemerintah Desa' }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">Lengkapi formulir sesuai SK pengangkatan dan penetapan perangkat desa</p>
            </div>
            <a href="{{ route('administrasi.aparatur.index') }}" class="px-4 py-2 bg-white border border-[#e1ede8] text-[#114443] font-bold text-xs rounded-2xl hover:bg-[#e2f0ed] transition-colors">
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form action="{{ isset($aparatur) ? route('administrasi.aparatur.update', $aparatur->id) : route('administrasi.aparatur.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($aparatur))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilih Warga / Penduduk -->
                    <div class="md:col-span-2">
                        <label for="penduduk_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Pilih Warga / Penduduk <span class="text-rose-500">*</span>
                        </label>
                        <select name="penduduk_id" id="penduduk_id" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="">-- Pilih Nama Warga dari Buku Induk Penduduk --</option>
                            @foreach($penduduks as $p)
                                <option value="{{ $p->id }}" {{ old('penduduk_id', $aparatur->penduduk_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} (NIK: {{ $p->nik }}) - {{ $p->dusun ?? 'Dusun' }}
                                </option>
                            @endforeach
                        </select>
                        @error('penduduk_id') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nama Jabatan / Kedudukan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $aparatur->jabatan ?? '') }}" placeholder="Contoh: Kepala Desa, Sekretaris Desa, Kaur Keuangan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jabatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIP / No. Induk Pegawai -->
                    <div>
                        <label for="nip" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            NIP / Nomor Induk Perangkat Desa
                        </label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $aparatur->nip ?? '') }}" placeholder="Kosongkan jika bukan PNS/P3K" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nip') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Kepegawaian -->
                    <div>
                        <label for="status_kepegawaian" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Status Kepegawaian <span class="text-rose-500">*</span>
                        </label>
                        <select name="status_kepegawaian" id="status_kepegawaian" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="perangkat_desa" {{ old('status_kepegawaian', $aparatur->status_kepegawaian ?? '') == 'perangkat_desa' ? 'selected' : '' }}>Perangkat Desa</option>
                            <option value="pns" {{ old('status_kepegawaian', $aparatur->status_kepegawaian ?? '') == 'pns' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                            <option value="pppk" {{ old('status_kepegawaian', $aparatur->status_kepegawaian ?? '') == 'pppk' ? 'selected' : '' }}>PPPK</option>
                            <option value="honorer" {{ old('status_kepegawaian', $aparatur->status_kepegawaian ?? '') == 'honorer' ? 'selected' : '' }}>Staf Honorer / Kontrak</option>
                        </select>
                        @error('status_kepegawaian') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label for="status_aktif" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Status Keaktifan Dinas
                        </label>
                        <select name="status_aktif" id="status_aktif" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="1" {{ old('status_aktif', $aparatur->status_aktif ?? 1) == 1 ? 'selected' : '' }}>Aktif Menjabat</option>
                            <option value="0" {{ old('status_aktif', $aparatur->status_aktif ?? 1) == 0 ? 'selected' : '' }}>Purna Tugas / Non-Aktif</option>
                        </select>
                    </div>

                    <!-- Jam Masuk Standar -->
                    <div>
                        <label for="jam_masuk_standar" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Jam Masuk Kantor Standar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="jam_masuk_standar" name="jam_masuk_standar" value="{{ old('jam_masuk_standar', $aparatur->jam_masuk_standar ?? '08:00:00') }}" placeholder="08:00:00" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jam_masuk_standar') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jam Pulang Standar -->
                    <div>
                        <label for="jam_pulang_standar" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Jam Pulang Kantor Standar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="jam_pulang_standar" name="jam_pulang_standar" value="{{ old('jam_pulang_standar', $aparatur->jam_pulang_standar ?? '16:00:00') }}" placeholder="16:00:00" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jam_pulang_standar') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Toleransi Terlambat -->
                    <div>
                        <label for="toleransi_terlambat_menit" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Toleransi Keterlambatan (Menit) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="toleransi_terlambat_menit" name="toleransi_terlambat_menit" value="{{ old('toleransi_terlambat_menit', $aparatur->toleransi_terlambat_menit ?? 15) }}" min="0" max="120" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('toleransi_terlambat_menit') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.aparatur.index') }}" class="px-5 py-2.5 bg-[#f7faf9] text-[#64748b] hover:text-[#0c3837] font-bold text-xs rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors">
                        {{ isset($aparatur) ? 'Simpan Perubahan' : 'Daftarkan Aparat' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
