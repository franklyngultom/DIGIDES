<x-layouts.app title="Buku Ekspedisi Pengiriman Surat">
    <div class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku 08: Ekspedisi Surat</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Buku Ekspedisi Pengiriman Surat</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Register pencatatan pengiriman fisik surat dinas dan tanda bukti serah terima berkas kantor desa</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('administrasi.buku-ekspedisi.export-excel', request()->query()) }}" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Excel</span>
                </a>

                <a href="{{ route('administrasi.buku-ekspedisi.export-pdf', request()->query()) }}" target="_blank" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>PDF</span>
                </a>

                @can('administrasi.manage')
                <button type="button" @click="$dispatch('open-import-modal-buku-ekspedisi')" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Impor</span>
                </button>

                <a href="{{ route('administrasi.buku-ekspedisi.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Ekspedisi</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('administrasi.buku-ekspedisi.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Tahun</option>
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative flex-1 md:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor surat / perihal / penerima..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl">Cari</button>
                @if($tahun || $search)
                    <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <span class="text-xs text-[#64748b] font-medium">Menampilkan <strong>{{ $data->total() }}</strong> total pengiriman</span>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No Urut</th>
                            <th class="px-4 py-3.5">Tanggal Pengiriman</th>
                            <th class="px-4 py-3.5">Nomor & Tanggal Surat</th>
                            <th class="px-4 py-3.5">Perihal Surat</th>
                            <th class="px-4 py-3.5">Tujuan Penerima</th>
                            <th class="px-4 py-3.5">Petugas Pengirim</th>
                            <th class="px-4 py-3.5">Tanda Terima / Catatan</th>
                            <th class="px-5 py-3.5 text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($data as $item)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-5 py-4 text-center font-extrabold text-[#0c3837]">
                                    #{{ $item->nomor_urut }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0c3837]">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->isoFormat('D MMMM Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-[#0c3837]">{{ $item->nomor_surat }}</div>
                                    <div class="text-[11px] text-[#64748b]">{{ \Carbon\Carbon::parse($item->tanggal_surat)->isoFormat('D MMMM Y') }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-[#0f172a] line-clamp-2">{{ $item->perihal }}</div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0f172a]">
                                    {{ $item->tujuan_penerima }}
                                </td>
                                <td class="px-4 py-4 text-[#0c3837]">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f0ed] text-[#114443]">
                                        {{ $item->petugas_pengirim }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-[11px] text-[#64748b]">
                                    {{ $item->catatan ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @can('administrasi.manage')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('administrasi.buku-ekspedisi.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('administrasi.buku-ekspedisi.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ekspedisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada pengiriman surat tercatat</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Tambah Ekspedisi" untuk mencatat pengiriman surat dinas.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($data->hasPages())
                <div class="px-6 py-4 border-t border-[#e1ede8]">
                    {{ $data->links() }}
                </div>
            @endif
        </div>

        <x-ui.import-modal 
            id="buku-ekspedisi" 
            title="Impor Buku Ekspedisi Surat" 
            action="{{ route('administrasi.buku-ekspedisi.import') }}" 
            templateUrl="{{ route('administrasi.buku-ekspedisi.import-template') }}" 
            description="Unggah tanda bukti pengiriman dan tanda terima ekspedisi surat dinas secara massal." />

    </div>
</x-layouts.app>
