<x-layouts.app>
    <x-slot:title>Buku Register Mutasi Penduduk</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <a href="{{ route('kependudukan.index') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Buku Induk</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Register Mutasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Buku Register Mutasi Penduduk</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Pencatatan resmi peristiwa demografi desa: Kelahiran, Kematian, Pindah Datang & Pindah Keluar</p>
        </div>

        <div>
            <a href="{{ route('kependudukan.index') }}" 
               class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Buku Induk Warga</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-lg">
                📊
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Total Mutasi {{ now()->year }}</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['tahun_ini']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold text-lg">
                👶
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Kelahiran</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['lahir']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-lg">
                🕊️
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Kematian</span>
                <span class="text-xl font-extrabold text-rose-700">{{ number_format($stats['mati']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold text-lg">
                🚚
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Pindah Datang / Keluar</span>
                <span class="text-xl font-extrabold text-amber-800">{{ number_format($stats['pindah']) }}</span>
            </div>
        </x-card>
    </div>

    <!-- Filters & Table Section -->
    <x-card class="space-y-4 mt-6">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('kependudukan.mutasi.index') }}" class="flex flex-wrap gap-3 items-center justify-between pb-4 border-b border-[#e1ede8]" id="form-filter-mutasi">
            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                <select name="jenis_mutasi" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" onchange="this.form.submit()" id="filter-jenis-mutasi">
                    <option value="">Semua Jenis Mutasi</option>
                    <option value="lahir" @selected(request('jenis_mutasi') === 'lahir')>Kelahiran</option>
                    <option value="mati" @selected(request('jenis_mutasi') === 'mati')>Kematian</option>
                    <option value="pindah_keluar" @selected(request('jenis_mutasi') === 'pindah_keluar')>Pindah Keluar</option>
                    <option value="pindah_masuk" @selected(request('jenis_mutasi') === 'pindah_masuk')>Pindah Masuk</option>
                </select>

                <select name="bulan" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" onchange="this.form.submit()" id="filter-bulan">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" @selected(request('bulan') == $m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" onchange="this.form.submit()" id="filter-tahun">
                    @foreach(range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}" @selected(request('tahun', now()->year) == $y)>{{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-xs font-bold transition-all cursor-pointer">
                    Filter
                </button>

                @if(request()->anyFilled(['jenis_mutasi','bulan','tahun']))
                    <a href="{{ route('kependudukan.mutasi.index') }}" class="px-2.5 py-2 text-xs text-rose-600 hover:underline font-semibold">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Mutasi Register Table -->
        <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
            <table class="w-full text-left text-xs" id="table-mutasi">
                <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                    <tr>
                        <th class="py-3.5 px-4">Tanggal Peristiwa</th>
                        <th class="py-3.5 px-4">Jenis Mutasi</th>
                        <th class="py-3.5 px-4">Nama Warga</th>
                        <th class="py-3.5 px-4">NIK (Nomor Induk)</th>
                        <th class="py-3.5 px-4">Keterangan / Alasan</th>
                        <th class="py-3.5 px-4">Dicatat Oleh</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]">
                    @forelse($mutasis as $mutasi)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <td class="py-3.5 px-4 whitespace-nowrap font-medium text-[#0f172a] font-mono">
                            {{ $mutasi->tanggal_mutasi->format('d M Y') }}
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <x-badge variant="{{ $mutasi->badge_color }}">
                                {{ $mutasi->jenis_mutasi_label }}
                            </x-badge>
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap font-bold text-[#0c3837]">
                            {{ $mutasi->penduduk?->nama_lengkap ?? '-' }}
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[#64748b]">
                            {{ $mutasi->penduduk?->masked_nik ?? '-' }}
                        </td>

                        <td class="py-3.5 px-4 text-[#0f172a] max-w-xs">
                            {{ Str::limit($mutasi->keterangan ?? '-', 75) }}
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap text-[#64748b]">
                            {{ $mutasi->createdBy?->name ?? 'Sistem' }}
                        </td>

                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($mutasi->penduduk)
                            <a href="{{ route('kependudukan.show', $mutasi->penduduk) }}" 
                               id="btn-detail-mutasi-{{ $mutasi->id }}"
                               class="p-2 text-[#114443] hover:bg-[#e2f0ed] rounded-xl transition-colors inline-block" 
                               title="Lihat Detail Warga">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-[#64748b]">
                            <span class="block text-sm font-semibold">Belum ada data mutasi yang tercatat untuk filter ini.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mutasis->hasPages())
        <div class="pt-2">
            {{ $mutasis->links() }}
        </div>
        @endif
    </x-card>
</x-layouts.app>
