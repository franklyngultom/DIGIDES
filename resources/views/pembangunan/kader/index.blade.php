<x-layouts.app title="Buku Register Kader Pemberdayaan Masyarakat">
    <div class="space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku Register Kader Desa</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Kader Pemberdayaan Masyarakat (KPM)</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Database terpadu kader Posyandu, KPM Stunting, Pendamping Desa, Guru PAUD, dan PKK</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pembangunan.kader.export-pdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Register (PDF)</span>
                </a>

                @can('pembangunan.manage')
                <a href="{{ route('pembangunan.kader.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Kader Baru</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('pembangunan.kader.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <label for="filter-jenis" class="text-xs font-bold text-[#0c3837]">Jenis:</label>
                    <select id="filter-jenis" name="jenis" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                        <option value="">Semua Jenis Kader</option>
                        <option value="posyandu" {{ $jenis == 'posyandu' ? 'selected' : '' }}>Kader Posyandu</option>
                        <option value="kpm_stunting" {{ $jenis == 'kpm_stunting' ? 'selected' : '' }}>KPM Stunting</option>
                        <option value="pendamping_desa" {{ $jenis == 'pendamping_desa' ? 'selected' : '' }}>Pendamping Desa</option>
                        <option value="guru_paud" {{ $jenis == 'guru_paud' ? 'selected' : '' }}>Guru PAUD / TK</option>
                        <option value="pkk" {{ $jenis == 'pkk' ? 'selected' : '' }}>Kader PKK</option>
                        <option value="lainnya" {{ $jenis == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="relative flex-1 md:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari NIK / Nama / Jabatan / SK..." class="w-full pl-9 pr-4 py-1.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-3 py-1.5 bg-[#114443] text-white text-xs font-bold rounded-xl">Cari</button>
                @if($jenis || $search)
                    <a href="{{ route('pembangunan.kader.index') }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset Filter</a>
                @endif
            </form>

            <div class="flex items-center gap-3 text-xs">
                <span class="px-3 py-1 bg-slate-100 rounded-xl font-medium text-slate-700">Total: <strong>{{ $totalKader }}</strong> Kader</span>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-xl font-medium">Aktif: <strong>{{ $totalAktif }}</strong></span>
                <span class="px-3 py-1 bg-[#114443] text-[#d4ed31] rounded-xl font-bold">Honor/bln: <strong>Rp {{ number_format($totalHonor, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Table Data Kader -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Nama & NIK Kader</th>
                            <th class="px-4 py-3.5">Jenis Kader</th>
                            <th class="px-4 py-3.5">Jabatan</th>
                            <th class="px-4 py-3.5">Nomor & Tgl SK</th>
                            <th class="px-4 py-3.5 text-right">Honor / Bulan</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-5 py-4 text-center font-bold text-[#64748b]">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-[#0c3837]">{{ $item->penduduk->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-[#64748b]">{{ $item->penduduk->nik ?? '-' }} &bull; {{ $item->penduduk->dusun ?? 'Dusun' }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#e2f0ed] text-[#114443]">
                                        {{ str_replace('_', ' ', $item->jenis_kader) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0f172a]">
                                    {{ $item->jabatan }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-[#0f172a]">{{ $item->nomor_sk ?? '-' }}</div>
                                    @if($item->tanggal_sk)
                                        <div class="text-[11px] text-[#64748b]">{{ \Carbon\Carbon::parse($item->tanggal_sk)->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-[#0c3837]">
                                    Rp {{ number_format($item->honor_bulanan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $item->status_aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $item->status_aktif ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @can('pembangunan.manage')
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pembangunan.kader.edit', $item) }}" class="p-1.5 bg-slate-100 hover:bg-[#e2f0ed] text-slate-700 hover:text-[#114443] rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('pembangunan.kader.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kader ini?')">
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
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span class="font-medium text-slate-500">Belum ada kader desa yang tercatat</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Silakan klik tombol "Tambah Kader Baru" untuk mendaftarkan kader posyandu / KPM stunting.</span>
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
