<x-layouts.app :title="isset($decision) ? 'Edit Keputusan ' . $institution->singkatan : 'Catat Keputusan ' . $institution->singkatan">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan']) }}" class="hover:text-[#114443]">Kelembagaan</a>
                    <span>/</span>
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan']) }}" class="hover:text-[#114443]">{{ $institution->singkatan }}</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ isset($decision) ? 'Edit Keputusan' : 'Form Keputusan' }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ isset($decision) ? 'Edit Keputusan ' . $institution->singkatan : 'Catat Surat Keputusan / Ketetapan ' . $institution->singkatan }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">{{ $institution->nama_lembaga }}</p>
            </div>
            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan']) }}" class="px-4 py-2 bg-white border border-[#e1ede8] text-[#114443] font-bold text-xs rounded-2xl hover:bg-[#e2f0ed] transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-sm">
            <form action="{{ isset($decision) ? route('administrasi.kelembagaan.decisions.update', ['institution' => $institution->slug, 'decision' => $decision->id]) : route('administrasi.kelembagaan.decisions.store', ['institution' => $institution->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($decision))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Keputusan -->
                    <div>
                        <label for="nomor_keputusan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Nomor Keputusan / Surat Ketetapan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nomor_keputusan" name="nomor_keputusan" value="{{ old('nomor_keputusan', $decision->nomor_keputusan ?? '') }}" placeholder="Contoh: 140/01/KEP-BPD/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nomor_keputusan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Keputusan -->
                    <div>
                        <label for="tanggal_keputusan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tanggal Keputusan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="tanggal_keputusan" name="tanggal_keputusan" value="{{ old('tanggal_keputusan', isset($decision->tanggal_keputusan) ? $decision->tanggal_keputusan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tanggal_keputusan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tentang / Perihal -->
                    <div class="md:col-span-2">
                        <label for="tentang" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tentang / Judul Keputusan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="tentang" name="tentang" value="{{ old('tentang', $decision->tentang ?? '') }}" placeholder="Perihal atau ketetapan yang diputuskan..." required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tentang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Tahun Buku Register <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $decision->tahun ?? date('Y')) }}" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Uraian Singkat -->
                    <div class="md:col-span-2">
                        <label for="uraian_singkat" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                            Uraian Singkat / Ringkasan Isi Ketetapan
                        </label>
                        <textarea id="uraian_singkat" name="uraian_singkat" rows="3" placeholder="Ringkasan poin-poin keputusan..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('uraian_singkat', $decision->uraian_singkat ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#e1ede8] flex items-center justify-end gap-3">
                    <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan']) }}" class="px-5 py-2.5 bg-[#f7faf9] text-[#64748b] hover:text-[#0c3837] font-bold text-xs rounded-2xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors">
                        {{ isset($decision) ? 'Simpan Perubahan' : 'Catat Keputusan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
