<x-layouts.app :title="isset($activity) ? 'Edit Kegiatan ' . $institution->singkatan : 'Catat Kegiatan ' . $institution->singkatan">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan']) }}" class="hover:text-[#114443]">Kelembagaan</a>
                    <span>/</span>
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan']) }}" class="hover:text-[#114443]">{{ $institution->singkatan }}</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ isset($activity) ? 'Edit Kegiatan' : 'Form Kegiatan' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ isset($activity) ? 'Edit Kegiatan ' . $institution->singkatan : 'Catat Kegiatan / Program Kerja ' . $institution->singkatan }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">{{ $institution->nama_lembaga }}</p>
            </div>
            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan']) }}" class="px-4 py-2 bg-white border border-[#e1ede8] text-[#114443] font-bold text-xs rounded-2xl hover:bg-[#e2f0ed] transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form action="{{ isset($activity) ? route('administrasi.kelembagaan.activities.update', ['institution' => $institution->slug, 'activity' => $activity->id]) : route('administrasi.kelembagaan.activities.store', ['institution' => $institution->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($activity))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Kegiatan -->
                    <div class="md:col-span-2">
                        <label for="nama_kegiatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nama Kegiatan / Program Kerja <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $activity->nama_kegiatan ?? '') }}" placeholder="Nama kegiatan yang dilaksanakan..." required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nama_kegiatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Kegiatan -->
                    <div>
                        <label for="tanggal_kegiatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', isset($activity->tanggal_kegiatan) ? $activity->tanggal_kegiatan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_kegiatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Lokasi / Tempat Pelaksanaan
                        </label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $activity->lokasi ?? '') }}" placeholder="Contoh: Balai Desa, RW 03, Lapangan Desa" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Penanggung Jawab -->
                    <div>
                        <label for="penanggung_jawab" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Penanggung Jawab Kegiatan
                        </label>
                        <input type="text" id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab', $activity->penanggung_jawab ?? '') }}" placeholder="Nama koordinator pelaksana..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Anggaran -->
                    <div>
                        <label for="anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Biaya / Anggaran Kegiatan (Rp)
                        </label>
                        <input type="number" id="anggaran" name="anggaran" value="{{ old('anggaran', $activity->anggaran ?? 0) }}" placeholder="0" min="0" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Sumber Pendanaan
                        </label>
                        <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $activity->sumber_dana ?? 'Kas Lembaga') }}" placeholder="Contoh: APBDes, Kas Lembaga, Swadaya" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tahun Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $activity->tahun ?? date('Y')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    </div>

                    <!-- Output / Hasil -->
                    <div class="md:col-span-2">
                        <label for="output_hasil" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Hasil / Output Capaian Kegiatan
                        </label>
                        <textarea id="output_hasil" name="output_hasil" rows="3" placeholder="Deskripsi hasil yang dicapai, jumlah peserta hadir, output nyata..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('output_hasil', $activity->output_hasil ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan']) }}" class="px-5 py-2.5 bg-[#f7faf9] text-[#64748b] hover:text-[#0c3837] font-bold text-xs rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors">
                        {{ isset($activity) ? 'Simpan Perubahan' : 'Catat Kegiatan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
