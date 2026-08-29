<x-layouts.app title="Portal Keuangan Desa">
    <div class="space-y-6">

        <!-- Top Header & Filter Tahun -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#114443]">Dashboard</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Keuangan Desa</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Pengelolaan & Transparansi Keuangan Desa</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Tata kelola APBDes, Buku Kas Umum dengan saldo berjalan otomatis, dan monitoring realisasi anggaran</p>
            </div>

            <!-- Year Filter & Action -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('keuangan.index') }}" class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-2xl border border-[#e1ede8] shadow-xs">
                    <label for="tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select name="tahun" id="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-transparent focus:outline-none cursor-pointer">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </form>

                @can('keuangan.manage')
                <div class="flex items-center gap-2">
                    <a href="{{ route('keuangan.kas.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Catat Transaksi Kas</span>
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <!-- 4 Metric Cards (APBDes & Kas Overview) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Pagu Pendapatan -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Pagu Pendapatan</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] text-[#64748b] flex items-center justify-between">
                    <span>Realisasi: <strong class="text-emerald-700">Rp {{ number_format($realisasiPendapatan, 0, ',', '.') }}</strong></span>
                    <span class="font-bold text-[#10b981]">{{ $totalPendapatan > 0 ? round(($realisasiPendapatan / $totalPendapatan) * 100, 1) : 0 }}%</span>
                </div>
            </div>

            <!-- Card 2: Pagu Belanja -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Pagu Belanja</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    Rp {{ number_format($totalBelanja, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] text-[#64748b] flex items-center justify-between">
                    <span>Realisasi: <strong class="text-rose-700">Rp {{ number_format($realisasiBelanja, 0, ',', '.') }}</strong></span>
                    <span class="font-bold text-rose-600">{{ $totalBelanja > 0 ? round(($realisasiBelanja / $totalBelanja) * 100, 1) : 0 }}%</span>
                </div>
            </div>

            <!-- Card 3: Saldo Kas Umum -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Saldo Kas Tunai</span>
                    <div class="w-8 h-8 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    Rp {{ number_format($saldoKasUmum, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] text-[#64748b]">
                    <span>Buku Kas Umum (BKU) Tunai</span>
                </div>
            </div>

            <!-- Card 4: Saldo Kas Bank -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Saldo Rekening Bank</span>
                    <div class="w-8 h-8 rounded-xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    Rp {{ number_format($saldoKasBank, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] text-[#64748b]">
                    <span>Kas Desa di Bank (Buku Bank)</span>
                </div>
            </div>

        </div>

        <!-- Portal Menu Grid: 4 Modul Utama Keuangan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Modul 1: APBDes -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Anggaran Desa</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Master & Realisasi APBDes</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Pencatatan kode rekening pendapatan, 5 bidang belanja desa, serta pos pembiayaan.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('keuangan.apbdes.export-pdf', ['tahun' => $tahun]) }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('keuangan.apbdes.index', ['tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Modul 2: Rencana Anggaran Biaya (RAB) -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Kalkulasi Teknis</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Rancangan Anggaran (RAB)</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Penyusunan rincian material, upah HOK, sewa alat, dan operasional kegiatan desa.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <span class="text-xs font-extrabold text-[#114443]">
                        {{ $totalRabCount }} Dokumen
                    </span>
                    <a href="{{ route('keuangan.rab.index', ['tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Modul 3: Buku Kas Umum (BKU) -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku Register Kas</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Buku Kas Tunai (BKU)</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Pencatatan kas masuk/keluar tunai fisik brankas dengan saldo berjalan otomatis.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('keuangan.kas.export-pdf', ['type' => 'umum', 'tahun' => $tahun]) }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('keuangan.kas.index', ['type' => 'umum', 'tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Modul 4: Buku Kas Bank Desa -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Rekonsiliasi Bank</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Buku Kas Bank Desa</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Register mutasi rekening kas desa: setoran, penarikan, bunga, dan pajak bank.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('keuangan.kas.export-pdf', ['type' => 'bank', 'tahun' => $tahun]) }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('keuangan.kas.index', ['type' => 'bank', 'tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#0c3837]">Transaksi Kas Terakhir (Tahun {{ $tahun }})</h3>
                    <p class="text-xs text-[#64748b]">Aktivitas mutasi penerimaan dan pengeluaran kas desa</p>
                </div>
                <a href="{{ route('keuangan.kas.index', ['tahun' => $tahun]) }}" class="text-xs text-[#114443] hover:underline font-bold">Lihat Semua Kas &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">No. Bukti</th>
                            <th class="px-4 py-3">Uraian</th>
                            <th class="px-4 py-3">Sumber</th>
                            <th class="px-4 py-3 text-right">Penerimaan</th>
                            <th class="px-4 py-3 text-right">Pengeluaran</th>
                            <th class="px-4 py-3 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-[#f7faf9]/80 transition-colors">
                                <td class="px-4 py-3 font-semibold text-[#0c3837]">{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $tx->buku_kas_type === 'umum' ? 'bg-[#e2f0ed] text-[#114443]' : 'bg-blue-50 text-blue-800' }}">
                                        {{ $tx->buku_kas_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-[#0f172a]">{{ $tx->nomor_bukti }}</td>
                                <td class="px-4 py-3 text-[#334155] max-w-xs truncate">{{ $tx->uraian }}</td>
                                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">{{ $tx->sumber_dana }}</span></td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ $tx->penerimaan > 0 ? number_format($tx->penerimaan, 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-3 text-right font-bold text-rose-600">{{ $tx->pengeluaran > 0 ? number_format($tx->pengeluaran, 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-3 text-right font-extrabold text-[#0c3837]">Rp {{ number_format($tx->saldo, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400">Belum ada transaksi kas yang tercatat pada tahun {{ $tahun }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.app>
