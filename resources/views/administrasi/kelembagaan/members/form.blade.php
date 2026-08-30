<x-layouts.app :title="isset($member) ? 'Edit Anggota ' . $institution->singkatan : 'Tambah Anggota ' . $institution->singkatan">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan']) }}" class="hover:text-[#114443]">Kelembagaan</a>
                    <span>/</span>
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota']) }}" class="hover:text-[#114443]">{{ $institution->singkatan }}</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ isset($member) ? 'Edit Anggota' : 'Form Anggota' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ isset($member) ? 'Edit Anggota ' . $institution->singkatan : 'Tambah Anggota / Pengurus ' . $institution->singkatan }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">{{ $institution->nama_lembaga }}</p>
            </div>
            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota']) }}" class="px-4 py-2 bg-white border border-[#e1ede8] text-[#114443] font-bold text-xs rounded-2xl hover:bg-[#e2f0ed] transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form action="{{ isset($member) ? route('administrasi.kelembagaan.members.update', ['institution' => $institution->slug, 'member' => $member->id]) : route('administrasi.kelembagaan.members.store', ['institution' => $institution->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($member))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nama Lengkap Pengurus / Anggota <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $member->nama_lengkap ?? '') }}" placeholder="Nama lengkap beserta gelar..." required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nama_lengkap') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Hubungkan dengan Data Penduduk (Opsional) -->
                    <div class="md:col-span-2">
                        <label for="penduduk_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Hubungkan dengan Data Warga (Buku Induk Penduduk)
                        </label>
                        <select name="penduduk_id" id="penduduk_id" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="">-- Pilih jika warga desa setempat --</option>
                            @foreach($penduduks as $p)
                                <option value="{{ $p->id }}" {{ old('penduduk_id', $member->penduduk_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }} (NIK: {{ $p->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nomor Induk Kependudukan (NIK)
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', $member->nik ?? '') }}" placeholder="16 Digit NIK..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Jabatan / Posisi di Lembaga <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $member->jabatan ?? '') }}" placeholder="Contoh: Ketua, Sekretaris, Bendahara, Anggota Pokja" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jabatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor SK Pengangkatan -->
                    <div>
                        <label for="nomor_sk_pengangkatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nomor SK Pengangkatan
                        </label>
                        <input type="text" id="nomor_sk_pengangkatan" name="nomor_sk_pengangkatan" value="{{ old('nomor_sk_pengangkatan', $member->nomor_sk_pengangkatan ?? '') }}" placeholder="Contoh: 140/01/SK/2026" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Tanggal SK -->
                    <div>
                        <label for="tanggal_sk" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tanggal SK Penetapan
                        </label>
                        <input type="date" id="tanggal_sk" name="tanggal_sk" value="{{ old('tanggal_sk', isset($member->tanggal_sk) ? $member->tanggal_sk->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Periode Mulai -->
                    <div>
                        <label for="periode_mulai" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tahun Mulai Periode
                        </label>
                        <input type="number" id="periode_mulai" name="periode_mulai" value="{{ old('periode_mulai', $member->periode_mulai ?? date('Y')) }}" placeholder="2024" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Periode Selesai -->
                    <div>
                        <label for="periode_selesai" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tahun Selesai Periode
                        </label>
                        <input type="number" id="periode_selesai" name="periode_selesai" value="{{ old('periode_selesai', $member->periode_selesai ?? date('Y') + 5) }}" placeholder="2030" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Kontak / HP -->
                    <div>
                        <label for="kontak" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nomor HP / WhatsApp
                        </label>
                        <input type="text" id="kontak" name="kontak" value="{{ old('kontak', $member->kontak ?? '') }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label for="status_aktif" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Status Keaktifan
                        </label>
                        <select name="status_aktif" id="status_aktif" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                            <option value="1" {{ old('status_aktif', $member->status_aktif ?? 1) == 1 ? 'selected' : '' }}>Aktif Menjabat</option>
                            <option value="0" {{ old('status_aktif', $member->status_aktif ?? 1) == 0 ? 'selected' : '' }}>Purna Tugas / Non-Aktif</option>
                        </select>
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Keterangan Tambahan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan peran atau keanggotaan..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $member->keterangan ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota']) }}" class="px-5 py-2.5 bg-[#f7faf9] text-[#64748b] hover:text-[#0c3837] font-bold text-xs rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors">
                        {{ isset($member) ? 'Simpan Perubahan' : 'Daftarkan Anggota' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
