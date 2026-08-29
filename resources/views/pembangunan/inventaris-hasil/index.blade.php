<x-layouts.app title="Buku Inventaris Hasil Pembangunan">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Inventaris Hasil Pembangunan</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    Buku Inventaris Hasil Pembangunan
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">
                    Pencatatan aset fisik, infrastruktur, sarana, dan prasarana hasil pelaksanaan pembangunan desa yang telah diserahterimakan
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pembangunan.inventaris-hasil.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Buku Inventaris (PDF)</span>
                </a>

                @can('pembangunan.manage')
                <a href="{{ route('pembangunan.inventaris-hasil.create', ['tahun' => $tahun]) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Inventaris Baru</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Stat Highlights -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Total Aset Fisik</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $totalAset }} Aset</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Total Nilai Perolehan</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Kondisi Baik</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $asetBaik }} Sarana</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Perlu Pemeliharaan</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $asetPerluPerbaikan }} Sarana</span>
                </div>
            </x-card>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('pembangunan.inventaris-hasil.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <!-- Filter Tahun -->
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kategori -->
                <div class="flex items-center gap-2">
                    <label for="filter-kategori" class="text-xs font-bold text-[#0c3837]">Kategori:</label>
                    <select id="filter-kategori" name="kategori" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Kategori</option>
                        <option value="jalan_jembatan" {{ $kategori === 'jalan_jembatan' ? 'selected' : '' }}>Jalan & Jembatan</option>
                        <option value="bangunan_gedung" {{ $kategori === 'bangunan_gedung' ? 'selected' : '' }}>Bangunan & Gedung</option>
                        <option value="irigasi_sanitasi" {{ $kategori === 'irigasi_sanitasi' ? 'selected' : '' }}>Irigasi & Drainase</option>
                        <option value="sarana_air_bersih" {{ $kategori === 'sarana_air_bersih' ? 'selected' : '' }}>Sarana Air Bersih</option>
                        <option value="sarana_olahraga" {{ $kategori === 'sarana_olahraga' ? 'selected' : '' }}>Sarana Olahraga</option>
                        <option value="fasilitas_umum" {{ $kategori === 'fasilitas_umum' ? 'selected' : '' }}>Fasilitas Umum</option>
                    </select>
                </div>

                <!-- Filter Kondisi -->
                <div class="flex items-center gap-2">
                    <label for="filter-kondisi" class="text-xs font-bold text-[#0c3837]">Kondisi:</label>
                    <select id="filter-kondisi" name="kondisi" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ $kondisi === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ $kondisi === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ $kondisi === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <!-- Search Box -->
                <div class="relative flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor inventaris / nama sarana / lokasi..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl cursor-pointer">Cari</button>
                @if($search || $kategori || $kondisi || $statusPengelolaan)
                    <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $tahun]) }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>
        </div>

        <!-- Table Data Inventaris Hasil -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Nomor & Hasil Pembangunan</th>
                            <th class="px-4 py-3.5">Kategori Aset</th>
                            <th class="px-4 py-3.5">Lokasi & Volume</th>
                            <th class="px-4 py-3.5 text-center">Sumber & BAST</th>
                            <th class="px-4 py-3.5 text-right">Nilai Perolehan</th>
                            <th class="px-4 py-3.5 text-center">Kondisi</th>
                            <th class="px-4 py-3.5">Pengelolaan</th>
                            <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($items as $index => $row)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-4 py-4 text-center font-bold text-[#64748b]">
                                    {{ $items->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4 max-w-xs">
                                    <span class="font-mono font-bold text-xs text-[#114443] block">
                                        {{ $row->nomor_inventaris }}
                                    </span>
                                    <a href="{{ route('pembangunan.inventaris-hasil.show', $row) }}" class="font-bold text-[#0c3837] hover:text-[#10b981] text-xs transition-colors block mt-0.5">
                                        {{ $row->nama_hasil_pembangunan }}
                                    </a>
                                    @if($row->proyek)
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Asal Proyek: {{ $row->proyek->nama_kegiatan }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-semibold bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8]">
                                        {{ $row->kategori_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 max-w-xs">
                                    <span class="font-medium text-[#0c3837] block">{{ $row->lokasi }}</span>
                                    <span class="text-[10px] text-[#64748b] block mt-0.5">Volume: {{ $row->volume }}</span>
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f0ed] text-[#114443] block mb-0.5">
                                        {{ $row->sumber_dana }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">
                                        {{ $row->tanggal_serah_terima ? \Carbon\Carbon::parse($row->tanggal_serah_terima)->format('d/m/Y') : 'Tahun ' . $row->tahun_anggaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-extrabold text-[#0c3837] whitespace-nowrap">
                                    Rp {{ number_format($row->nilai_aset, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <x-badge :variant="$row->kondisi_variant">
                                        {{ $row->kondisi_label }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-4 max-w-xs">
                                    <span class="font-semibold text-[#0c3837] block text-[11px]">{{ $row->status_pengelolaan_label }}</span>
                                    @if($row->penanggung_jawab)
                                        <span class="text-[10px] text-slate-400 block">PJ: {{ $row->penanggung_jawab }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pembangunan.inventaris-hasil.show', $row) }}" class="p-1.5 bg-[#e2f0ed] hover:bg-[#114443] text-[#114443] hover:text-white rounded-lg transition-colors cursor-pointer" title="Lihat Detail & Foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        @can('pembangunan.manage')
                                        <a href="{{ route('pembangunan.inventaris-hasil.edit', $row) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors cursor-pointer" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('pembangunan.inventaris-hasil.destroy', $row) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan inventaris {{ addslashes($row->nomor_inventaris) }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada data inventaris hasil pembangunan yang tercatat</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Catat hasil infrastruktur atau konversi dari proyek fisik yang telah selesai 100%.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="px-6 py-4 border-t border-[#e1ede8]">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
