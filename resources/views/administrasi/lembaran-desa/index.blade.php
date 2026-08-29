<x-layouts.app title="Buku Lembaran Desa dan Berita Desa">
    <div x-data="{ 
        previewModal: false, 
        previewUrl: '', 
        previewTitle: '',
        openPreview(url, title) {
            this.previewUrl = url;
            this.previewTitle = title;
            this.previewModal = true;
        }
    }" class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index') }}" class="hover:text-[#114443]">Administrasi Umum</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku 06: Lembaran Desa & Berita Desa</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Buku Lembaran Desa & Berita Desa</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Register pengundangan resmi lembaran desa (Perdes) dan berita publikasi peraturan desa (Perkades)</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('administrasi.lembaran-desa.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Rekap (PDF)</span>
                </a>

                @can('administrasi.manage')
                <a href="{{ route('administrasi.lembaran-desa.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Lembaran/Berita</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('administrasi.lembaran-desa.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
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
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor seri / judul..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl">Cari</button>
                @if($tahun || $search)
                    <a href="{{ route('administrasi.lembaran-desa.index') }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <span class="text-xs text-[#64748b] font-medium">Menampilkan <strong>{{ $data->total() }}</strong> total lembaran</span>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Jenis</th>
                            <th class="px-4 py-3.5">Nomor Seri & Tanggal</th>
                            <th class="px-4 py-3.5">Judul Lembaran / Berita</th>
                            <th class="px-4 py-3.5">Ringkasan</th>
                            <th class="px-4 py-3.5 text-center">Berkas PDF</th>
                            <th class="px-5 py-3.5 text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-5 py-4 text-center font-bold text-[#64748b]">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $item->jenis === 'lembaran_desa' ? 'bg-[#e2f48f] text-[#0c3837]' : 'bg-[#e2f0ed] text-[#114443]' }}">
                                        {{ str_replace('_', ' ', $item->jenis) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-[#0c3837]">{{ $item->nomor_seri }}</div>
                                    <div class="text-[11px] text-[#64748b]">{{ \Carbon\Carbon::parse($item->tanggal_diundangkan)->isoFormat('D MMMM Y') }}</div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0f172a]">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-4 py-4 text-[11px] text-[#64748b]">
                                    {{ $item->isi_singkat ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($item->file_pdf_path)
                                        <button @click="openPreview('{{ asset('storage/' . $item->file_pdf_path) }}', '{{ addslashes($item->judul) }}')" class="px-2.5 py-1 bg-[#e2f0ed] hover:bg-[#114443] text-[#114443] hover:text-white rounded-lg text-[11px] font-bold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat Berkas</span>
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[11px]">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @can('administrasi.manage')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('administrasi.lembaran-desa.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('administrasi.lembaran-desa.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada data lembaran/berita desa</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Tambah Lembaran/Berita" untuk mengundangkan publikasi baru.</span>
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

        <!-- Modal PDF Preview Popup -->
        <div x-show="previewModal" 
             x-transition.opacity 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#082424]/60 backdrop-blur-sm" 
             style="display: none;">
            <div @click.away="previewModal = false" class="bg-white rounded-3xl border border-[#e1ede8] shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-[#e1ede8] flex items-center justify-between bg-[#f7faf9]">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                        <h3 class="font-bold text-[#0c3837] text-sm truncate max-w-lg" x-text="previewTitle">Pratinjau Lembaran Desa</h3>
                    </div>
                    <button @click="previewModal = false" class="p-1 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 bg-slate-100 p-2">
                    <iframe :src="previewUrl" class="w-full h-full rounded-2xl border border-slate-200 bg-white" frameborder="0"></iframe>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
