<x-persuratan.pengaturan-layout 
    pageTitle="Pengaturan Surat - DIGIDES"
    cardTitle="Pengaturan Surat"
    activeTab="pengaturan">

    <x-slot:cardActions>
        <a href="{{ route('persuratan.create') }}" 
           class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span>Halaman Pelayanan</span>
        </a>

        <button type="submit" 
                form="form-pengaturan-surat"
                class="px-4 py-1.5 rounded-xl bg-[#4338ca] hover:bg-[#3730a3] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all hover:scale-102 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            <span>Simpan</span>
        </button>
    </x-slot:cardActions>

    <form id="form-pengaturan-surat" 
          method="POST" 
          action="{{ route('persuratan.pengaturan.update') }}" 
          x-data="{
              selectedFormats: {{ json_encode($pengaturan->format_alamat_json ?? ['dusun', 'desa', 'kecamatan', 'kabupaten']) }},
              preview() {
                  const desa = '{{ $desa->nama_desa ?? 'Balam Sempurna' }}';
                  const kec = '{{ $desa->kecamatan ?? 'Bagan Sinembah' }}';
                  const kab = '{{ $desa->kabupaten ?? 'Rokan Hilir' }}';
                  const prov = '{{ $desa->provinsi ?? 'Riau' }}';
                  
                  let parts = [];
                  if (this.selectedFormats.includes('jalan')) parts.push('Jl. Lintas');
                  if (this.selectedFormats.includes('nomor')) parts.push('No. 12');
                  if (this.selectedFormats.includes('kampung')) parts.push('Kp. Baru');
                  if (this.selectedFormats.includes('rt')) parts.push('RT 01');
                  if (this.selectedFormats.includes('rw')) parts.push('RW 02');
                  if (this.selectedFormats.includes('dusun')) parts.push('Dusun Inovasi');
                  if (this.selectedFormats.includes('desa')) parts.push('Desa ' + desa);
                  if (this.selectedFormats.includes('kecamatan')) parts.push('Kecamatan ' + kec);
                  if (this.selectedFormats.includes('kabupaten')) parts.push('Kabupaten ' + kab);
                  if (this.selectedFormats.includes('provinsi')) parts.push('Provinsi ' + prov);
                  
                  return parts.length ? parts.join(' ') : 'Pilih minimal satu komponen format alamat.';
              }
          }">
        @csrf

        <div class="space-y-6">
            <!-- Row 1: Nomor HP & Email Pelayanan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nomor HP/WA Pelayanan -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Nomor HP/WA Pelayanan
                    </label>
                    <div class="flex items-center rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] focus-within:ring-2 focus-within:ring-[#fef08a] overflow-hidden transition-all shadow-2xs">
                        <span class="px-3 py-2.5 bg-[#fef9c3] text-[#854d0e] font-bold text-xs border-r border-[#fef08a] select-none">
                            +62
                        </span>
                        <input type="text" 
                               name="no_hp_pelayanan" 
                               value="{{ old('no_hp_pelayanan', str_starts_with($pengaturan->no_hp_pelayanan ?? '', '+62') ? substr($pengaturan->no_hp_pelayanan, 3) : ($pengaturan->no_hp_pelayanan ?? '')) }}"
                               placeholder="81234567890" 
                               class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium placeholder-slate-400 focus:outline-none">
                    </div>
                    @error('no_hp_pelayanan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat E-mail Pelayanan -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Alamat E-mail Pelayanan
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] focus-within:ring-2 focus-within:ring-[#fef08a] overflow-hidden transition-all shadow-2xs">
                        <input type="email" 
                               name="email_pelayanan" 
                               value="{{ old('email_pelayanan', $pengaturan->email_pelayanan) }}"
                               placeholder="pelayanan@desa.id" 
                               class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium placeholder-slate-400 focus:outline-none">
                    </div>
                    @error('email_pelayanan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-[#e1ede8] pt-2"></div>

            <!-- Row 2: Format Alamat Penduduk -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-[#334155]">
                    Format Alamat Penduduk
                </label>

                <!-- Checkbox bar yellow tint container -->
                <div class="p-3 bg-[#fefce8] rounded-xl border border-[#fef08a] flex flex-wrap items-center gap-x-5 gap-y-2.5 text-xs">
                    @php
                        $availableFormats = [
                            'jalan' => 'Jalan',
                            'nomor' => 'Nomor',
                            'kampung' => 'Kampung',
                            'rt' => 'RT',
                            'rw' => 'RW',
                            'dusun' => 'Dusun',
                            'desa' => 'Desa',
                            'kecamatan' => 'Kecamatan',
                            'kabupaten' => 'Kabupaten',
                            'provinsi' => 'Provinsi',
                        ];
                    @endphp

                    @foreach($availableFormats as $key => $label)
                    <label class="inline-flex items-center gap-1.5 cursor-pointer font-medium text-[#475569] select-none hover:text-[#0f172a]">
                        <input type="checkbox" 
                               name="format_alamat[]" 
                               value="{{ $key }}"
                               x-model="selectedFormats"
                               class="w-4 h-4 rounded text-[#1b5e5c] border-[#cbd5e1] focus:ring-[#f59e0b]">
                        <span class="text-xs">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>

                <!-- Preview bar (Grey background persis referensi) -->
                <div class="p-3 bg-[#e2e8f0] rounded-xl text-xs font-semibold text-[#334155] border border-[#cbd5e1] shadow-2xs">
                    <span x-text="preview()"></span>
                </div>
            </div>
        </div>
    </form>
</x-persuratan.pengaturan-layout>
