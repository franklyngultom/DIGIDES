<x-layouts.app title="Master & Realisasi APBDes">
    <div class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Master & Realisasi APBDes</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Anggaran Pendapatan dan Belanja Desa (APBDes)</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Struktur kode rekening anggaran, pagu alokasi per bidang, serta monitoring persentase serapan</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('keuangan.apbdes.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak APBDes (PDF)</span>
                </a>

                @can('keuangan.manage')
                <a href="{{ route('keuangan.apbdes.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pos Anggaran</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('keuangan.apbdes.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="filter-jenis" class="text-xs font-bold text-[#0c3837]">Jenis:</label>
                    <select id="filter-jenis" name="jenis" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Jenis</option>
                        <option value="pendapatan" {{ $jenis == 'pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                        <option value="belanja" {{ $jenis == 'belanja' ? 'selected' : '' }}>Belanja</option>
                        <option value="pembiayaan" {{ $jenis == 'pembiayaan' ? 'selected' : '' }}>Pembiayaan</option>
                    </select>
                </div>

                <div class="relative flex-1 md:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode rekening / uraian / bidang..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl">Cari</button>
                @if($jenis || $search)
                    <a href="{{ route('keuangan.apbdes.index', ['tahun' => $tahun]) }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <!-- Summary Badges -->
            <div class="flex items-center gap-3 text-xs">
                <span class="px-3 py-1 bg-slate-100 rounded-xl font-medium text-slate-700">Total Pagu: <strong>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</strong></span>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-xl font-medium">Realisasi: <strong>Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Table APBDes Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Kode Rek.</th>
                            <th class="px-4 py-3.5">Jenis & Bidang</th>
                            <th class="px-4 py-3.5">Uraian Akun Anggaran</th>
                            <th class="px-4 py-3.5 text-center">Sumber</th>
                            <th class="px-4 py-3.5 text-right">Pagu Anggaran</th>
                            <th class="px-4 py-3.5 text-right">Realisasi</th>
                            <th class="px-4 py-3.5 text-center">Serapan</th>
                            <th class="px-5 py-3.5 text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($data as $index => $item)
                            @php
                                $persen = $item->anggaran > 0 ? round(($item->realisasi / $item->anggaran) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-5 py-4 text-center font-bold text-[#64748b]">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4 font-mono font-bold text-[#0c3837]">
                                    {{ $item->kode_rekening }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $item->jenis === 'pendapatan' ? 'bg-emerald-100 text-emerald-800' : ($item->jenis === 'belanja' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $item->jenis }}
                                    </span>
                                    @if($item->bidang)
                                        <div class="text-[11px] text-[#64748b] mt-0.5 truncate max-w-xs">{{ $item->bidang }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0f172a] max-w-md">
                                    {{ $item->uraian }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f0ed] text-[#114443]">
                                        {{ $item->sumber_dana }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-[#0c3837]">
                                    Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-slate-700">
                                    Rp {{ number_format($item->realisasi, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $persen >= 80 ? 'bg-emerald-100 text-emerald-800' : ($persen >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $persen }}%
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @can('keuangan.manage')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('keuangan.apbdes.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('keuangan.apbdes.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pos anggaran ini?')">
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
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada data pos anggaran APBDes</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Tambah Pos Anggaran" untuk menambahkan rincian rekening APBDes.</span>
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

    </div>
</x-layouts.app>
