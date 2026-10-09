<x-layouts.app>
    <x-slot:title>Antrean Pengajuan Surat Online</x-slot:title>

    <div class="space-y-6">
        {{-- Header & Breadcrumbs --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1 text-xs text-[#64748b]">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#10b981]">Dashboard</a>
                    <span>/</span>
                    <span class="font-bold text-[#0c3837]">Persuratan</span>
                    <span>/</span>
                    <span class="font-bold text-[#0c3837]">Antrean Pengajuan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Antrean Pengajuan Surat Online</h1>
                <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Kelola, verifikasi berkas, dan proses permohonan surat masuk dari portal mandiri warga</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('persuratan.create') }}" 
                   class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Layanan Cetak Walk-In</span>
                </a>
                <a href="{{ route('persuratan.arsip.index') }}" 
                   class="px-4 py-2.5 bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span>Buku Arsip Terbit</span>
                </a>
            </div>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Quick Stats Badges (Interactive Status Filter Pills) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('persuratan.antrean.index', ['status' => 'menunggu']) }}" 
               class="p-4 rounded-2xl border transition-all {{ $statusFilter === 'menunggu' ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-400/30' : 'bg-white border-[#e1ede8] hover:border-amber-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700">Menunggu Review</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                </div>
                <div class="text-2xl font-black text-amber-800 mt-2">{{ $stats['menunggu'] }}</div>
                <p class="text-[11px] text-amber-600 mt-0.5">Permohonan baru masuk</p>
            </a>

            <a href="{{ route('persuratan.antrean.index', ['status' => 'diproses']) }}" 
               class="p-4 rounded-2xl border transition-all {{ $statusFilter === 'diproses' ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-400/30' : 'bg-white border-[#e1ede8] hover:border-blue-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-700">Sedang Diproses</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                </div>
                <div class="text-2xl font-black text-blue-800 mt-2">{{ $stats['diproses'] }}</div>
                <p class="text-[11px] text-blue-600 mt-0.5">Dalam tahap verifikasi staf</p>
            </a>

            <a href="{{ route('persuratan.antrean.index', ['status' => 'perlu_perbaikan']) }}" 
               class="p-4 rounded-2xl border transition-all {{ $statusFilter === 'perlu_perbaikan' ? 'bg-orange-50 border-orange-300 ring-2 ring-orange-400/30' : 'bg-white border-[#e1ede8] hover:border-orange-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-orange-700">Perlu Perbaikan</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                </div>
                <div class="text-2xl font-black text-orange-800 mt-2">{{ $stats['perlu_perbaikan'] }}</div>
                <p class="text-[11px] text-orange-600 mt-0.5">Menunggu respon warga</p>
            </a>

            <a href="{{ route('persuratan.antrean.index', ['status' => 'selesai']) }}" 
               class="p-4 rounded-2xl border transition-all {{ $statusFilter === 'selesai' ? 'bg-emerald-50 border-emerald-300 ring-2 ring-emerald-400/30' : 'bg-white border-[#e1ede8] hover:border-emerald-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700">Selesai Terbit</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="text-2xl font-black text-emerald-800 mt-2">{{ $stats['selesai'] }}</div>
                <p class="text-[11px] text-emerald-600 mt-0.5">Dokumen telah diserahkan</p>
            </a>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="bg-white rounded-3xl border border-[#e1ede8] p-5 shadow-xs">
            <form method="GET" action="{{ route('persuratan.antrean.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                {{-- Search Input --}}
                <div class="md:col-span-5 relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Cari Nomor Pengajuan, Nama, atau NIK..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">
                    <svg class="w-4 h-4 text-[#94a3b8] absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Status Filter --}}
                <div class="md:col-span-3">
                    <select name="status" 
                            class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">
                        <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="menunggu" {{ $statusFilter === 'menunggu' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="diproses" {{ $statusFilter === 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="perlu_perbaikan" {{ $statusFilter === 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="disetujui" {{ $statusFilter === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ $statusFilter === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Template Filter --}}
                <div class="md:col-span-3">
                    <select name="template" 
                            class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">
                        <option value="">Semua Jenis Surat</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" {{ request('template') == $tpl->id ? 'selected' : '' }}>
                                [{{ $tpl->kode_surat }}] {{ $tpl->nama_surat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="md:col-span-1 flex items-center gap-2">
                    <button type="submit" 
                            class="w-full py-2.5 bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold rounded-2xl flex items-center justify-center transition-all cursor-pointer">
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'template']) || ($statusFilter !== 'menunggu' && $statusFilter !== 'semua'))
                        <a href="{{ route('persuratan.antrean.index') }}" 
                           title="Reset Filter"
                           class="p-2.5 bg-[#f7faf9] hover:bg-[#e2f0ed] text-[#64748b] border border-[#e1ede8] rounded-2xl flex items-center justify-center transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f7faf9] border-b border-[#e1ede8] text-[11px] font-bold uppercase tracking-wider text-[#0c3837]">
                            <th class="px-5 py-3.5">Nomor & Tanggal</th>
                            <th class="px-5 py-3.5">Pemohon</th>
                            <th class="px-5 py-3.5">Jenis Surat & Keperluan</th>
                            <th class="px-5 py-3.5 text-center">Berkas</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60 text-xs text-[#0f172a]">
                        @forelse($pengajuan as $row)
                            @php
                                $badgeClass = match($row->status) {
                                    'menunggu'        => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'diproses'        => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'perlu_perbaikan' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'disetujui'       => 'bg-teal-50 text-teal-700 border-teal-200',
                                    'selesai'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'ditolak'         => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default           => 'bg-slate-50 text-slate-600 border-slate-200',
                                };
                                $docCount = is_array($row->dokumen_path_json) ? count($row->dokumen_path_json) : 0;
                            @endphp
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                {{-- Nomor & Tanggal --}}
                                <td class="px-5 py-4">
                                    <div class="font-bold font-mono text-[#0c3837]">{{ $row->nomor_pengajuan }}</div>
                                    <div class="text-[11px] text-[#64748b] mt-0.5">{{ $row->created_at->format('d M Y H:i') }}</div>
                                    <div class="text-[10px] text-[#94a3b8]">{{ $row->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Pemohon --}}
                                <td class="px-5 py-4">
                                    <div class="font-bold text-[#0c3837]">{{ $row->pemohon_nama }}</div>
                                    <div class="text-[11px] font-mono text-[#64748b] mt-0.5">NIK: {{ $row->pemohon_nik }}</div>
                                    @if($row->citizenProfile?->isVerified())
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-block text-[10px] font-semibold text-amber-600 mt-0.5">Belum Validasi</span>
                                    @endif
                                </td>

                                {{-- Jenis Surat & Keperluan --}}
                                <td class="px-5 py-4 max-w-xs">
                                    <div class="font-semibold text-[#0c3837] flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-full bg-[#e2f0ed] text-[10px] font-bold text-[#0c3837]">
                                            {{ $row->suratTemplate->kode_surat ?? '-' }}
                                        </span>
                                        <span class="truncate">{{ $row->suratTemplate->nama_surat ?? 'Template Dihapus' }}</span>
                                    </div>
                                    <p class="text-[11px] text-[#64748b] mt-1 line-clamp-2">
                                        {{ $row->keperluan }}
                                    </p>
                                </td>

                                {{-- Berkas --}}
                                <td class="px-5 py-4 text-center">
                                    @if($docCount > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                                            <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            {{ $docCount }} File
                                        </span>
                                    @else
                                        <span class="text-[11px] text-[#94a3b8] italic">Tanpa Berkas</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold border {{ $badgeClass }}">
                                        {{ $row->statusLabel() }}
                                    </span>
                                    @if($row->diproses_oleh && $row->diprosesByUser)
                                        <div class="text-[10px] text-[#94a3b8] mt-1">oleh {{ $row->diprosesByUser->name }}</div>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('persuratan.antrean.show', $row) }}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold transition-all shadow-2xs">
                                        <span>Proses</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#10b981] flex items-center justify-center mx-auto">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-[#0c3837]">Tidak Ada Pengajuan</h3>
                                        <p class="text-xs text-[#64748b]">Belum ada data permohonan surat masuk pada kategori filter ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pengajuan->hasPages())
                <div class="p-4 border-t border-[#e1ede8]">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
