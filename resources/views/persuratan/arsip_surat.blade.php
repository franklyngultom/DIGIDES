<x-layouts.app title="Buku Register Arsip Surat Keluar">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#114443]">Dashboard</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Buku Register Arsip Surat</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Buku Register Arsip Surat Keluar</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Seluruh arsip surat resmi yang telah diterbitkan baik melalui pelayanan walk-in maupun permohonan online warga</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('persuratan.antrean.index') }}" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#0c3837] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Antrean Pengajuan Online</span>
                </a>

                @can('persuratan.create')
                <a href="{{ route('persuratan.create') }}" class="px-4 py-2.5 bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Pelayanan Walk-In</span>
                </a>
                @endcan
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Toolbar -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('persuratan.arsip.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-[#0c3837]">Tanggal:</label>
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="text-xs text-[#0c3837] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                    <span class="text-xs text-[#64748b]">s/d</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="text-xs text-[#0c3837] bg-[#f7faf9] border border-[#e1ede8] rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#10b981]">
                </div>

                <button type="submit" class="px-3.5 py-1.5 bg-[#0c3837] text-white text-xs font-bold rounded-xl hover:bg-[#114443] transition-colors">
                    Filter
                </button>

                @if(request('tanggal_awal') || request('tanggal_akhir'))
                    <a href="{{ route('persuratan.arsip.index') }}" class="text-xs text-[#64748b] hover:text-rose-600 underline">Reset</a>
                @endif
            </form>

            <span class="text-xs text-[#64748b] font-medium">Total <strong>{{ $arsips->total() }}</strong> berkas arsip terdaftar</span>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Nomor & Tanggal Surat</th>
                            <th class="px-4 py-3.5">Jenis Surat</th>
                            <th class="px-4 py-3.5">Nama Pemohon (Warga)</th>
                            <th class="px-4 py-3.5">Keperluan</th>
                            <th class="px-4 py-3.5 text-center">Sumber Layanan</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($arsips as $arsip)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold font-mono text-[#0c3837]">{{ $arsip->nomor_surat }}</div>
                                    <div class="text-[11px] text-[#64748b] mt-0.5">{{ $arsip->tanggal_terbit ? \Carbon\Carbon::parse($arsip->tanggal_terbit)->isoFormat('D MMMM Y') : '-' }}</div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-[#0c3837]">
                                    <span class="px-2 py-0.5 rounded-full bg-[#e2f0ed] text-[10px] font-bold text-[#0c3837] mr-1">
                                        {{ $arsip->template->kode_surat ?? '-' }}
                                    </span>
                                    {{ $arsip->template->nama_surat ?? '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-[#0c3837]">{{ $arsip->penduduk?->nama_lengkap ?? $arsip->payload_data['nama_lengkap'] ?? '-' }}</div>
                                    <div class="text-[11px] text-[#64748b] font-mono">NIK: {{ $arsip->penduduk?->nik ?? $arsip->payload_data['nik'] ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 max-w-xs truncate text-[#64748b]">
                                    {{ $arsip->keperluan ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($arsip->pengajuan)
                                        <a href="{{ route('persuratan.antrean.show', $arsip->pengajuan) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200 hover:bg-blue-100 transition-colors">
                                            Online ({{ $arsip->pengajuan->nomor_pengajuan }})
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold">
                                            Walk-In Desk
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $arsip->status === 'terbit' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ ucfirst($arsip->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('persuratan.arsip.download', $arsip) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0c3837] hover:bg-[#114443] text-white text-[11px] font-bold rounded-xl transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>PDF</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-xs text-[#94a3b8]">
                                    Belum ada arsip surat keluar yang diterbitkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($arsips->hasPages())
                <div class="p-4 border-t border-[#e1ede8]">
                    {{ $arsips->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
