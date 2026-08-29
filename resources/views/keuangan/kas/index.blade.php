<x-layouts.app :title="'Buku Kas ' . ($kategori === 'bank' ? 'Bank' : 'Tunai')">
    <div x-data="{ 
        previewModal: false, 
        previewUrl: '', 
        previewTitle: '',
        isPdf: false,
        openPreview(url, title, pdf = false) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.isPdf = pdf;
            this.previewModal = true;
        }
    }" class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku Kas {{ $kategori === 'bank' ? 'Bank' : 'Tunai' }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                    {{ $kategori === 'bank' ? 'Buku Kas Saldo Bank Desa' : 'Buku Kas Umum (Tunai)' }}
                </h1>
                <p class="text-xs text-[#64748b] mt-0.5">
                    {{ $kategori === 'bank' ? 'Register transaksi kas rekening bank desa, setoran, penarikan, bunga, dan pajak' : 'Pencatatan harian mutasi penerimaan dan pengeluaran kas tunai operasional desa' }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('keuangan.kas.export-pdf', ['type' => $type, 'tahun' => $tahun, 'pembantu' => $pembantu]) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak BKU (PDF)</span>
                </a>

                @can('keuangan.manage')
                <a href="{{ route('keuangan.kas.create', ['type' => $type, 'pembantu' => $pembantu]) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Transaksi {{ $kategori === 'bank' ? 'Bank' : 'Tunai' }}</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Type Switch Tabs -->
        <div class="flex items-center gap-2 border-b border-[#e1ede8] pb-1">
            <a href="{{ route('keuangan.kas.index', ['type' => 'umum', 'tahun' => $tahun]) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-colors {{ $kategori === 'tunai' ? 'bg-[#114443] text-white' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                Kas Tunai
            </a>
            <a href="{{ route('keuangan.kas.index', ['type' => 'bank', 'tahun' => $tahun]) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-colors {{ $kategori === 'bank' ? 'bg-[#114443] text-white' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                Saldo / Kas Bank
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('keuangan.kas.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="hidden" name="type" value="{{ $type }}">
                
                <!-- Filter Tahun -->
                <div class="flex items-center gap-2">
                    <label for="filter-tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Jenis Pembantu (Opsional) -->
                <div class="flex items-center gap-2">
                    <label for="filter-pembantu" class="text-xs font-bold text-[#0c3837]">Pembantu:</label>
                    <select id="filter-pembantu" name="pembantu" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Pembantu</option>
                        <option value="umum" {{ $pembantu === 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="pajak" {{ $pembantu === 'pajak' ? 'selected' : '' }}>Pajak (PPh/PPN)</option>
                        <option value="panjar" {{ $pembantu === 'panjar' ? 'selected' : '' }}>Panjar / Kegiatan</option>
                    </select>
                </div>

                <!-- Search Box -->
                <div class="relative flex-1 md:w-56">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor bukti / uraian..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl cursor-pointer">Cari</button>
                @if($search || $pembantu)
                    <a href="{{ route('keuangan.kas.index', ['type' => $type, 'tahun' => $tahun]) }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <!-- Real-time Balance Box -->
            <div class="flex items-center gap-3 text-xs">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-xl font-medium">Masuk: <strong>Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</strong></span>
                <span class="px-3 py-1 bg-rose-50 text-rose-800 rounded-xl font-medium">Keluar: <strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></span>
                <span class="px-3 py-1 bg-[#114443] text-[#d4ed31] rounded-xl font-extrabold">Saldo: <strong>Rp {{ number_format($saldoKas, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Table Kas Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Tanggal</th>
                            <th class="px-4 py-3.5">No. Bukti</th>
                            <th class="px-4 py-3.5 text-center">Kategori / Pembantu</th>
                            <th class="px-4 py-3.5">Kode Rek.</th>
                            <th class="px-4 py-3.5">Uraian Transaksi</th>
                            <th class="px-4 py-3.5 text-center">Sumber</th>
                            <th class="px-4 py-3.5 text-right">Penerimaan</th>
                            <th class="px-4 py-3.5 text-right">Pengeluaran</th>
                            <th class="px-4 py-3.5 text-right">Saldo Kas</th>
                            <th class="px-4 py-3.5 text-center">Bukti</th>
                            <th class="px-5 py-3.5 text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-4 py-4 text-center font-bold text-[#64748b]">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0c3837] whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 font-bold text-[#0f172a] whitespace-nowrap font-mono">
                                    {{ $item->nomor_bukti }}
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item->kategori_kas === 'bank' ? 'bg-[#0c3837] text-[#d4ed31]' : 'bg-[#e2f48f] text-[#0c3837]' }}">
                                        {{ $item->kategori_kas === 'bank' ? 'Bank' : 'Tunai' }}
                                    </span>
                                    @if($item->jenis_pembantu)
                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 text-slate-700 ml-1">
                                            {{ ucfirst($item->jenis_pembantu) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center font-mono text-[#64748b]">
                                    {{ $item->kode_rekening ?? '-' }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0f172a] max-w-xs">
                                    {{ $item->uraian }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e2f0ed] text-[#114443]">
                                        {{ $item->sumber_dana }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-emerald-600 whitespace-nowrap">
                                    {{ $item->penerimaan > 0 ? number_format($item->penerimaan, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-rose-600 whitespace-nowrap">
                                    {{ $item->pengeluaran > 0 ? number_format($item->pengeluaran, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-4 text-right font-extrabold text-[#0c3837] whitespace-nowrap">
                                    Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    @if($item->file_bukti_path)
                                        @php
                                            $isPdf = str_ends_with(strtolower($item->file_bukti_path), '.pdf');
                                        @endphp
                                        <button @click="openPreview('{{ asset('storage/' . $item->file_bukti_path) }}', 'Bukti {{ addslashes($item->nomor_bukti) }}', {{ $isPdf ? 'true' : 'false' }})" class="px-2 py-1 bg-[#e2f0ed] hover:bg-[#114443] text-[#114443] hover:text-white rounded-lg text-[10px] font-bold transition-colors inline-flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat</span>
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    @can('keuangan.manage')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('keuangan.kas.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('keuangan.kas.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi kas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada transaksi kas {{ $kategori }} yang tercatat</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Catat Transaksi {{ ucfirst($kategori) }}" untuk menambah mutasi kas baru.</span>
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

        <!-- Modal Lampiran Bukti Popup -->
        <div x-show="previewModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-sm" 
             style="display: none;">
            <div @click.away="previewModal = false" class="bg-white rounded-3xl border border-[#e1ede8] shadow-2xl w-full max-w-3xl flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e1ede8] flex items-center justify-between bg-[#f7faf9]">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                        <h3 class="font-bold text-[#0c3837] text-sm truncate max-w-lg" x-text="previewTitle">Pratinjau Bukti Kas</h3>
                    </div>
                    <button @click="previewModal = false" class="p-1 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-600 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 bg-slate-100 flex items-center justify-center min-h-[300px] max-h-[75vh] overflow-auto">
                    <template x-if="isPdf">
                        <iframe :src="previewUrl" class="w-full h-[65vh] rounded-2xl border border-slate-200 bg-white" frameborder="0"></iframe>
                    </template>
                    <template x-if="!isPdf">
                        <img :src="previewUrl" class="max-h-[65vh] max-w-full rounded-2xl object-contain shadow-md" alt="Bukti Transaksi">
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
