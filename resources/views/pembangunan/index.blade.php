<x-layouts.app title="Portal Pembangunan & Kader Desa">
    <div class="space-y-6">

        <!-- Top Header & Filter Tahun -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#114443]">Dashboard</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Pembangunan & Kader Desa</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Pembangunan Infrastruktur & Pemberdayaan Masyarakat</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Monitoring RKP Desa, progres fisik proyek (0%, 50%, 100%), dan register Kader Pemberdayaan Masyarakat (KPM)</p>
            </div>

            <!-- Year Filter & Action -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('pembangunan.index') }}" class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-2xl border border-[#e1ede8] shadow-xs">
                    <label for="tahun" class="text-xs font-bold text-[#0c3837]">Tahun:</label>
                    <select name="tahun" id="tahun" onchange="this.form.submit()" class="text-xs font-semibold text-[#114443] bg-transparent focus:outline-none cursor-pointer">
                        @foreach($tahuns as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </form>

                @can('pembangunan.manage')
                <div class="flex items-center gap-2">
                    <a href="{{ route('pembangunan.proyek.create') }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Proyek Baru</span>
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <!-- 4 Metric Cards (Proyek & Kader Overview) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Total Proyek -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Total Proyek RKP</span>
                    <div class="w-8 h-8 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    {{ $totalProyek }} Kegiatan
                </div>
                <div class="mt-2 text-[11px] text-[#64748b]">
                    <span>{{ $proyekSelesai }} Selesai &bull; {{ $proyekProses }} Berjalan</span>
                </div>
            </div>

            <!-- Card 2: Alokasi Anggaran Proyek -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Pagu Anggaran Fisik</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    Rp {{ number_format($totalAnggaranProyek, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] text-[#64748b] flex items-center justify-between">
                    <span>Realisasi: <strong>Rp {{ number_format($totalRealisasiProyek, 0, ',', '.') }}</strong></span>
                    <span class="font-bold text-[#10b981]">{{ $totalAnggaranProyek > 0 ? round(($totalRealisasiProyek / $totalAnggaranProyek) * 100, 1) : 0 }}%</span>
                </div>
            </div>

            <!-- Card 3: Total Kader Desa -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Kader Pemberdayaan</span>
                    <div class="w-8 h-8 rounded-xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    {{ $totalKader }} Kader
                </div>
                <div class="mt-2 text-[11px] text-[#64748b]">
                    <span>Status Aktif Bertugas di Desa</span>
                </div>
            </div>

            <!-- Card 4: Kader Posyandu & Stunting -->
            <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider">Kader Khusus (KPM)</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                </div>
                <div class="text-xl font-extrabold text-[#0c3837]">
                    {{ $kaderPosyandu + $kaderStunting }} Kader
                </div>
                <div class="mt-2 text-[11px] text-[#64748b]">
                    <span>{{ $kaderPosyandu }} Posyandu &bull; {{ $kaderStunting }} KPM Stunting</span>
                </div>
            </div>

        </div>

        <!-- Portal 3 Core Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Card 1: Buku Proyek Pembangunan Fisik -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">RKP & Fisik Infrastruktur</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Kegiatan Pembangunan Fisik</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Pencatatan usulan proyek RKP Desa, pengerjaan TPK, tracking tahapan titik nol, 50%, 100%, dan berkas RAB proyek.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('pembangunan.proyek.export-pdf', ['tahun' => $tahun]) }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('pembangunan.proyek.index', ['tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Buku Inventaris Hasil Pembangunan -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Aset Pasca-Konstruksi</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Inventaris Hasil Pembangunan</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Pencatatan sarana fisik yang telah selesai diserahterimakan (BAST), status kondisi fisik, dan penetapan pihak pengelola.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('pembangunan.inventaris-hasil.export-pdf', ['tahun' => $tahun]) }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Buku Kader Pemberdayaan Masyarakat -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">SDM & Kelembagaan</span>
                    <h3 class="text-base font-bold text-[#0c3837] mt-0.5">Kader Pemberdayaan (KPM)</h3>
                    <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">
                        Register database kader desa (Posyandu, KPM Stunting, Guru PAUD) terhubung langsung ke NIK penduduk dan SK Kades.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <a href="{{ route('pembangunan.kader.export-pdf') }}" target="_blank" class="text-xs text-[#64748b] hover:text-[#114443] font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak</span>
                    </a>
                    <a href="{{ route('pembangunan.kader.index') }}" class="px-3.5 py-1.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Recent Projects Section -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#0c3837]">Proyek Pembangunan Berjalan (Tahun {{ $tahun }})</h3>
                    <p class="text-xs text-[#64748b]">Daftar kegiatan fisik terbaru dalam rencana pembangunan desa</p>
                </div>
                <a href="{{ route('pembangunan.proyek.index', ['tahun' => $tahun]) }}" class="text-xs text-[#114443] hover:underline font-bold">Lihat Semua Proyek &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($recentProyek as $p)
                    <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] flex flex-col justify-between space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $p->status_progres === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($p->status_progres === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $p->status_progres }} ({{ $p->persentase_selesai }}%)
                                </span>
                                <h4 class="font-bold text-sm text-[#0c3837] mt-1">{{ $p->nama_kegiatan }}</h4>
                                <p class="text-xs text-[#64748b] mt-0.5">{{ $p->lokasi }} &bull; Vol: {{ $p->volume }}</p>
                            </div>
                            <span class="text-xs font-bold text-[#114443] whitespace-nowrap">Rp {{ number_format($p->anggaran_biaya, 0, ',', '.') }}</span>
                        </div>

                        <!-- Progress Bar -->
                        <div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-[#10b981] h-full rounded-full transition-all duration-300" style="width: {{ $p->persentase_selesai }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-[#64748b] mt-1">
                                <span>Pelaksana: {{ $p->pelaksana_tpk }}</span>
                                <a href="{{ route('pembangunan.proyek.show', $p) }}" class="font-bold text-[#114443] hover:underline">Detail & Foto &rarr;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-8 text-slate-400 text-xs">
                        Belum ada kegiatan pembangunan yang tercatat pada tahun anggaran {{ $tahun }}.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-layouts.app>
