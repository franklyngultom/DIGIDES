<x-layouts.app title="Rencana Anggaran Biaya (RAB) Desa">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Rencana Anggaran Biaya (RAB)</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    Rencana Anggaran Biaya (RAB) Desa
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">
                    Pencatatan rincian kebutuhan material, upah HOK tenaga kerja, sewa peralatan, dan operasional kegiatan APBDes
                </p>
            </div>

            <div class="flex items-center gap-3">
                @can('keuangan.manage')
                <a href="{{ route('keuangan.rab.create', ['tahun' => $tahun]) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Buat RAB Baru</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Stat Highlights -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Total Dokumen RAB</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $totalRabs }} Dokumen</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Total Anggaran Terencana</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">RAB Disetujui</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $totalDisetujui }} Kegiatan</span>
                </div>
            </x-card>

            <x-card class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#edf5f2] text-[#4fa394] flex items-center justify-center font-bold text-lg shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] text-[#64748b] font-medium block">Draft Rancangan</span>
                    <span class="text-xl font-extrabold text-[#0c3837]">{{ $totalDraft }} Dokumen</span>
                </div>
            </x-card>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('keuangan.rab.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <!-- Filter Tahun -->
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Sumber Dana -->
                <div class="flex items-center gap-2">
                    <label for="filter-sumber" class="text-xs font-bold text-[#0c3837]">Sumber:</label>
                    <select id="filter-sumber" name="sumber_dana" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Sumber</option>
                        <option value="DDS" {{ $sumberDana === 'DDS' ? 'selected' : '' }}>DDS (Dana Desa)</option>
                        <option value="ADD" {{ $sumberDana === 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                        <option value="PBH" {{ $sumberDana === 'PBH' ? 'selected' : '' }}>PBH (Bagi Hasil Pajak)</option>
                        <option value="PAD" {{ $sumberDana === 'PAD' ? 'selected' : '' }}>PAD (Pendapatan Asli Desa)</option>
                        <option value="DLL" {{ $sumberDana === 'DLL' ? 'selected' : '' }}>DLL (Bantuan Keuangan)</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="flex items-center gap-2">
                    <label for="filter-status" class="text-xs font-bold text-[#0c3837]">Status:</label>
                    <select id="filter-status" name="status" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="direalisasikan" {{ $status === 'direalisasikan' ? 'selected' : '' }}>Direalisasikan</option>
                    </select>
                </div>

                <!-- Search Box -->
                <div class="relative flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor RAB / kegiatan / lokasi..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl cursor-pointer">Cari</button>
                @if($search || $sumberDana || $status)
                    <a href="{{ route('keuangan.rab.index', ['tahun' => $tahun]) }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>
        </div>

        <!-- Table RAB Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Nomor & Kegiatan RAB</th>
                            <th class="px-4 py-3.5">Bidang Pekerjaan</th>
                            <th class="px-4 py-3.5">Lokasi & Waktu</th>
                            <th class="px-4 py-3.5 text-center">Sumber</th>
                            <th class="px-4 py-3.5 text-center">Rincian Item</th>
                            <th class="px-4 py-3.5 text-right">Total Anggaran</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($rabs as $index => $item)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-4 py-4 text-center font-bold text-[#64748b]">
                                    {{ $rabs->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4 max-w-xs">
                                    <span class="font-mono font-bold text-xs text-[#114443] block">
                                        {{ $item->nomor_rab }}
                                    </span>
                                    <a href="{{ route('keuangan.rab.show', $item) }}" class="font-bold text-[#0c3837] hover:text-[#10b981] text-xs transition-colors block mt-0.5">
                                        {{ $item->nama_kegiatan }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-[#64748b] max-w-xs">
                                    <span class="font-semibold text-[#0c3837] block">{{ $item->bidang }}</span>
                                    @if($item->sub_bidang)
                                        <span class="text-[10px] text-slate-400 block">{{ $item->sub_bidang }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-medium text-[#0c3837] block">{{ $item->lokasi }}</span>
                                    <span class="text-[10px] text-[#64748b] block">{{ $item->waktu_pelaksanaan }}</span>
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f0ed] text-[#114443]">
                                        {{ $item->sumber_dana }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8]">
                                        {{ $item->items->count() }} Item
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-extrabold text-[#0c3837] whitespace-nowrap">
                                    Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <x-badge :variant="$item->status_variant">
                                        {{ $item->status_label }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('keuangan.rab.show', $item) }}" class="p-1.5 bg-[#e2f0ed] hover:bg-[#114443] text-[#114443] hover:text-white rounded-lg transition-colors cursor-pointer" title="Lihat Detail & Item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        <a href="{{ route('keuangan.rab.export-pdf', $item) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors cursor-pointer" title="Cetak Dokumen RAB (PDF)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>

                                        @can('keuangan.manage')
                                        <a href="{{ route('keuangan.rab.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors cursor-pointer" title="Edit RAB">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <form method="POST" action="{{ route('keuangan.rab.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen RAB {{ addslashes($item->nomor_rab) }}?')" class="inline">
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
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada dokumen Rencana Anggaran Biaya (RAB) yang tercatat</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Buat RAB Baru" untuk menyusun rancangan anggaran kegiatan desa.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($rabs->hasPages())
                <div class="px-6 py-4 border-t border-[#e1ede8]">
                    {{ $rabs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
