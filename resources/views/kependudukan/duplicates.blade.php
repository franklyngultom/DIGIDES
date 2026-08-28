<x-layouts.app>
    <x-slot:title>Pemindai Duplikasi NIK</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <a href="{{ route('kependudukan.index') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Buku Induk</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Pemindai Duplikasi NIK</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Pemindai Duplikasi & Anomali NIK</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Utilitas rekonsiliasi data kependudukan desa otomatis sekali klik</p>
        </div>

        <div>
            <a href="{{ route('kependudukan.index') }}" 
               class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Buku Induk</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
        <x-card class="p-4 flex items-center gap-3 border-l-4 {{ $total_issues > 0 ? 'border-l-rose-500' : 'border-l-[#10b981]' }}">
            <div class="w-11 h-11 rounded-2xl {{ $total_issues > 0 ? 'bg-rose-50 text-rose-600' : 'bg-[#e2f0ed] text-[#114443]' }} flex items-center justify-center font-bold text-lg">
                {{ $total_issues > 0 ? '⚠️' : '✅' }}
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Total Isu Terdeteksi</span>
                <span class="text-2xl font-extrabold {{ $total_issues > 0 ? 'text-rose-600' : 'text-[#0c3837]' }}">{{ $total_issues }} Isu</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3 border-l-4 border-l-amber-500">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-lg">
                🔁
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">NIK Terdaftar Ganda</span>
                <span class="text-2xl font-extrabold text-amber-700">{{ $duplicates->count() }} Kasus</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3 border-l-4 border-l-purple-500">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-lg">
                🔍
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Anomali Format NIK</span>
                <span class="text-2xl font-extrabold text-purple-700">{{ $anomali->count() }} Data</span>
            </div>
        </x-card>
    </div>

    <!-- Scan Results Content -->
    @if($total_issues === 0)
        <x-card class="py-12 text-center space-y-3 mt-6">
            <div class="w-16 h-16 rounded-3xl bg-[#e2f0ed] text-[#10b981] flex items-center justify-center text-3xl mx-auto border border-[#10b981]/30">
                ✨
            </div>
            <h2 class="text-xl font-bold text-[#0c3837]">Seluruh Basis Data Kependudukan Bersih & Terstandarisasi!</h2>
            <p class="text-xs text-[#64748b] max-w-md mx-auto">
                Tidak ditemukan NIK ganda maupun anomali format (bukan 16 digit / non-numerik). Basis data kependudukan desa aman dan siap untuk pelayanan publik.
            </p>
            <div class="pt-2">
                <a href="{{ route('kependudukan.index') }}" class="px-5 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full transition-all inline-block">
                    Kembali ke Master Warga
                </a>
            </div>
        </x-card>
    @else
        <div class="space-y-6 mt-6">
            <!-- 1. Duplicate NIKs Section -->
            @if($duplicates->count() > 0)
            <x-card class="space-y-4">
                <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837] flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-sm">🔁</span>
                        <span>Daftar NIK Terdaftar Ganda ({{ $duplicates->count() }} Kelompok)</span>
                    </h3>
                    <x-badge variant="amber">{{ $duplicates->count() }} Kelompok Duplikat</x-badge>
                </div>

                <div class="space-y-4">
                    @foreach($duplicates as $group)
                    <div class="p-4 bg-[#f7faf9] rounded-2xl border border-[#fed7aa] space-y-3">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <x-badge variant="rose">Duplikat</x-badge>
                                <span class="font-mono font-extrabold text-[#0c3837] text-sm">NIK: {{ $group['nik'] }}</span>
                            </div>
                            <span class="text-xs font-bold text-amber-800 bg-amber-100 px-3 py-1 rounded-full">
                                Ditemukan {{ $group['count'] }} Catatan Terpisah
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($group['records'] as $rec)
                            <div class="p-3 bg-white rounded-xl border border-[#e1ede8] flex items-start justify-between">
                                <div>
                                    <span class="font-bold text-[#0c3837] text-xs block">{{ $rec->nama_lengkap }}</span>
                                    <span class="text-[11px] text-[#64748b] block mt-0.5">KK: {{ $rec->no_kk }} · {{ $rec->tempat_lahir }}, {{ $rec->tanggal_lahir->format('d M Y') }}</span>
                                    <span class="text-[11px] text-[#64748b] block">RT {{ $rec->rt }}/RW {{ $rec->rw }} – {{ $rec->dusun ?? 'Pusat Desa' }}</span>
                                </div>
                                <a href="{{ route('kependudukan.edit', $rec) }}" 
                                   class="px-3 py-1 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] text-xs font-bold rounded-lg transition-colors shrink-0">
                                    Edit
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-card>
            @endif

            <!-- 2. Format Anomaly Section -->
            @if($anomali->count() > 0)
            <x-card class="space-y-4">
                <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837] flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-sm">🔍</span>
                        <span>Anomali Format NIK ({{ $anomali->count() }} Data)</span>
                    </h3>
                    <x-badge variant="rose">{{ $anomali->count() }} Format Rusak</x-badge>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
                    <table class="w-full text-left text-xs" id="table-anomali">
                        <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                            <tr>
                                <th class="py-3 px-4">NIK Bermasalah</th>
                                <th class="py-3 px-4">Nama Lengkap</th>
                                <th class="py-3 px-4 text-center">Panjang Digit</th>
                                <th class="py-3 px-4">Keterangan Masalah</th>
                                <th class="py-3 px-4 text-center">Aksi Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e1ede8]">
                            @foreach($anomali as $rec)
                            @php
                                $nik = (string) $rec->nik;
                                $problems = [];
                                if (strlen($nik) !== 16) $problems[] = 'Bukan 16 digit ('.strlen($nik).' digit)';
                                if (!ctype_digit($nik)) $problems[] = 'Mengandung karakter non-numerik';
                            @endphp
                            <tr class="hover:bg-[#f7faf9] transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-rose-600 whitespace-nowrap">
                                    {{ $rec->nik }}
                                </td>
                                <td class="py-3 px-4 font-bold text-[#0c3837] whitespace-nowrap">
                                    {{ $rec->nama_lengkap }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap font-mono">
                                    {{ strlen($nik) }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @foreach($problems as $p)
                                        <x-badge variant="rose" class="mr-1">{{ $p }}</x-badge>
                                    @endforeach
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <a href="{{ route('kependudukan.edit', $rec) }}" 
                                       id="btn-fix-{{ $rec->id }}"
                                       class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full transition-colors inline-block">
                                        Perbaiki Data
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
            @endif
        </div>
    @endif
</x-layouts.app>
