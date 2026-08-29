<x-layouts.app title="Buku Proyek & Hasil Pembangunan">
    <div class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku Proyek Pembangunan</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Buku Kegiatan & Hasil Pembangunan Desa</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Daftar usulan kegiatan RKP Desa, volume fisik, alokasi biaya, dan pemantauan tahapan pengerjaan TPK</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pembangunan.proyek.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Laporan (PDF)</span>
                </a>

                @can('pembangunan.manage')
                <a href="{{ route('pembangunan.proyek.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Proyek Baru</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('pembangunan.proyek.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="filter-status" class="text-xs font-bold text-[#0c3837]">Status:</label>
                    <select id="filter-status" name="status" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Status</option>
                        <option value="perencanaan" {{ $status == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="proses" {{ $status == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai 100%</option>
                        <option value="tertunda" {{ $status == 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                    </select>
                </div>

                <div class="relative flex-1 md:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama proyek / lokasi / TPK..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl">Cari</button>
                @if($status || $search)
                    <a href="{{ route('pembangunan.proyek.index', ['tahun' => $tahun]) }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <span class="text-xs text-[#64748b] font-medium">Menampilkan <strong>{{ $data->total() }}</strong> kegiatan</span>
        </div>

        <!-- Project Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($data as $item)
                <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                    <div>
                        <!-- Project Cover / Thumbnail -->
                        <div class="relative h-40 bg-slate-100 overflow-hidden">
                            @if($item->foto_100_persen)
                                <img src="{{ asset('storage/' . $item->foto_100_persen) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $item->nama_kegiatan }}">
                            @elseif($item->foto_50_persen)
                                <img src="{{ asset('storage/' . $item->foto_50_persen) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $item->nama_kegiatan }}">
                            @elseif($item->foto_titik_nol)
                                <img src="{{ asset('storage/' . $item->foto_titik_nol) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $item->nama_kegiatan }}">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                    <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[11px]">Belum ada foto</span>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md shadow-xs {{ $item->status_progres === 'selesai' ? 'bg-emerald-500/90 text-white' : ($item->status_progres === 'proses' ? 'bg-blue-500/90 text-white' : 'bg-amber-500/90 text-white') }}">
                                    {{ $item->status_progres }}
                                </span>
                            </div>

                            <div class="absolute bottom-3 right-3 bg-[#0c3837]/80 backdrop-blur-md text-[#d4ed31] px-2.5 py-0.5 rounded-xl text-xs font-bold">
                                {{ $item->persentase_selesai }}%
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-2.5">
                            <h3 class="font-bold text-sm text-[#0c3837] line-clamp-1 group-hover:text-[#10b981] transition-colors">
                                {{ $item->nama_kegiatan }}
                            </h3>
                            <div class="text-xs text-[#64748b] space-y-1">
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $item->lokasi }} &bull; Vol: {{ $item->volume }}</span>
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="truncate">TPK: {{ $item->pelaksana_tpk }}</span>
                                </p>
                            </div>

                            <div class="pt-2 border-t border-[#e1ede8] flex items-center justify-between text-xs">
                                <span class="text-[#64748b]">Pagu:</span>
                                <span class="font-bold text-[#0c3837]">Rp {{ number_format($item->anggaran_biaya, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-5 pb-5 pt-1 flex items-center justify-between gap-2 border-t border-[#e1ede8]/60 mt-1">
                        <a href="{{ route('pembangunan.proyek.show', $item) }}" class="text-xs text-[#114443] hover:text-[#0c3837] font-bold flex items-center gap-1">
                            <span>Detail & Foto</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        @can('pembangunan.manage')
                        <div class="flex items-center gap-1">
                            <a href="{{ route('pembangunan.proyek.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('pembangunan.proyek.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data proyek ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 rounded-3xl border border-[#e1ede8] text-center text-slate-400">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="font-medium text-slate-500 block">Belum ada proyek pembangunan tercatat</span>
                    <span class="text-xs text-slate-400 mt-0.5">Klik tombol "Tambah Proyek Baru" untuk memasukkan rencana kegiatan pembangunan fisik desa.</span>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($data->hasPages())
            <div class="px-6 py-4 bg-white rounded-3xl border border-[#e1ede8]">
                {{ $data->links() }}
            </div>
        @endif

    </div>
</x-layouts.app>
