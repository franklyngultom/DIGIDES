<x-persuratan.pengaturan-layout 
    pageTitle="Sistem Penomoran Surat - DIGIDES"
    cardTitle="Sistem Penomoran Surat"
    activeTab="penomoran">

    <x-slot:cardActions>
        <a href="{{ route('persuratan.create') }}" 
           class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span>Halaman Pelayanan</span>
        </a>

        <button type="submit" 
                form="form-penomoran"
                class="px-4 py-1.5 rounded-xl bg-[#4338ca] hover:bg-[#3730a3] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all hover:scale-102 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            <span>Simpan</span>
        </button>
    </x-slot:cardActions>

    <form id="form-penomoran"
          method="POST" 
          action="{{ route('persuratan.pengaturan.penomoran.update') }}"
          x-data="{
              format: '{{ old('format_nomor_umum', $pengaturan->format_nomor_umum ?? '[nnn]/[kode]/DIGIDES/[br]/[t]') }}',
              inkremen: {{ old('inkremen_terakhir', $pengaturan->inkremen_terakhir ?? 0) }},
              sampleKode: 'SKU',
              addTag(tag) {
                  this.format += tag;
              },
              preview() {
                  const year = new Date().getFullYear();
                  const romanMonths = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
                  const currentMonth = new Date().getMonth();
                  const romanMonth = romanMonths[currentMonth];
                  const monthNum = String(currentMonth + 1).padStart(2, '0');
                  const seq = String(parseInt(this.inkremen) || 0).padStart(3, '0');
                  
                  let output = this.format;
                  output = output.replace(/\[nnn\]/g, seq);
                  output = output.replace(/\{no\}/g, seq);
                  output = output.replace(/\[kode\]/g, this.sampleKode);
                  output = output.replace(/\{kode\}/g, this.sampleKode);
                  output = output.replace(/\[br\]/g, romanMonth);
                  output = output.replace(/\[bln\]/g, monthNum);
                  output = output.replace(/\{bulan\}/g, monthNum);
                  output = output.replace(/\[t\]/g, year);
                  output = output.replace(/\{tahun\}/g, year);
                  
                  return output;
              }
          }">
        @csrf

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                <!-- 1. Aturan Penomoran Surat -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Aturan Penomoran Surat <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="aturan_penomoran" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="Berlaku Secara Umum" {{ old('aturan_penomoran', $pengaturan->aturan_penomoran) === 'Berlaku Secara Umum' ? 'selected' : '' }}>Berlaku Secara Umum</option>
                            <option value="Per Jenis Surat" {{ old('aturan_penomoran', $pengaturan->aturan_penomoran) === 'Per Jenis Surat' ? 'selected' : '' }}>Per Jenis Surat</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Format Umum Penomoran Surat -->
                <div class="md:col-span-5">
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Format Umum Penomoran Surat <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] focus-within:ring-2 focus-within:ring-[#fef08a] overflow-hidden shadow-2xs">
                        <!-- Dropdown helper icon / tags -->
                        <div class="relative" x-data="{ openTags: false }">
                            <button type="button" 
                                    @click="openTags = !openTags" 
                                    class="px-2.5 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white flex items-center justify-center cursor-pointer transition-colors"
                                    title="Klik untuk sisipkan kode token">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openTags" 
                                 @click.away="openTags = false" 
                                 x-cloak 
                                 class="absolute left-0 mt-1 w-48 bg-white border border-[#cbd5e1] rounded-xl shadow-xl z-30 p-2 text-xs space-y-1">
                                <button type="button" @click="addTag('[nnn]'); openTags = false" class="w-full text-left px-2 py-1 rounded hover:bg-[#edf5f2] font-mono text-[#114443]">[nnn] Nomor Urut</button>
                                <button type="button" @click="addTag('[kode]'); openTags = false" class="w-full text-left px-2 py-1 rounded hover:bg-[#edf5f2] font-mono text-[#114443]">[kode] Kode Surat</button>
                                <button type="button" @click="addTag('[br]'); openTags = false" class="w-full text-left px-2 py-1 rounded hover:bg-[#edf5f2] font-mono text-[#114443]">[br] Bulan Romawi</button>
                                <button type="button" @click="addTag('[bln]'); openTags = false" class="w-full text-left px-2 py-1 rounded hover:bg-[#edf5f2] font-mono text-[#114443]">[bln] Bulan Angka</button>
                                <button type="button" @click="addTag('[t]'); openTags = false" class="w-full text-left px-2 py-1 rounded hover:bg-[#edf5f2] font-mono text-[#114443]">[t] Tahun 4 Digit</button>
                            </div>
                        </div>

                        <input type="text" 
                               name="format_nomor_umum" 
                               x-model="format"
                               required
                               class="w-full px-3 py-2.5 bg-transparent text-xs font-mono font-bold text-[#1e293b] focus:outline-none">
                    </div>

                    <!-- Blue italic example output persis referensi gambar -->
                    <p class="text-[11px] text-[#2563eb] italic mt-1.5 font-medium">
                        Contoh output: <span class="font-bold underline" x-text="preview()"></span>
                    </p>
                    @error('format_nomor_umum') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- 3. Inkremen Terakhir -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Inkremen Terakhir <span class="text-rose-500">[nnn] *</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <input type="number" 
                               name="inkremen_terakhir" 
                               x-model="inkremen"
                               min="0"
                               required
                               class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-bold focus:outline-none">
                    </div>
                    @error('inkremen_terakhir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Panduan Token Penomoran -->
            <div class="p-4 rounded-xl bg-[#f8fafc] border border-[#e2e8f0] text-xs text-[#475569] space-y-2">
                <h4 class="font-extrabold text-[#0f172a] uppercase tracking-wider text-[11px]">Daftar Tag Token yang Didukung:</h4>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 font-mono text-[11px]">
                    <div class="p-2 bg-white rounded-lg border border-[#cbd5e1]"><span class="text-[#0284c7] font-bold">[nnn]</span>: Nomor 001</div>
                    <div class="p-2 bg-white rounded-lg border border-[#cbd5e1]"><span class="text-[#0284c7] font-bold">[kode]</span>: Kode SKU/SKTM</div>
                    <div class="p-2 bg-white rounded-lg border border-[#cbd5e1]"><span class="text-[#0284c7] font-bold">[br]</span>: Romawi (X, XI)</div>
                    <div class="p-2 bg-white rounded-lg border border-[#cbd5e1]"><span class="text-[#0284c7] font-bold">[bln]</span>: Bulan Angka (10)</div>
                    <div class="p-2 bg-white rounded-lg border border-[#cbd5e1]"><span class="text-[#0284c7] font-bold">[t]</span>: Tahun (2026)</div>
                </div>
            </div>
        </div>
    </form>
</x-persuratan.pengaturan-layout>
