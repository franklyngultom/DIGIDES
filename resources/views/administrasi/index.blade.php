<x-layouts.app title="Administrasi Umum">
    <div class="space-y-8">
        <!-- Header Page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-[#e2f0ed] text-[#114443] rounded-full text-xs font-bold uppercase tracking-wider">
                        Permendagri No. 47/2016
                    </span>
                    <span class="text-xs text-[#64748b] font-medium">Tahun {{ $currentYear }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-[#0c3837] tracking-tight mt-1">Administrasi Umum</h1>
                <p class="text-sm text-[#64748b] mt-0.5">Pusat tata kelola 9 buku register administrasi umum dan administrasi kelembagaan desa</p>
            </div>

            <!-- Year Filter -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('administrasi.index') }}" class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-[#e1ede8] shadow-xs">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <label for="filter-tahun" class="text-xs font-semibold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-bold text-[#114443] bg-transparent border-0 focus:ring-0 cursor-pointer">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- 2 Tab Navigation: Administrasi Umum & Kelembagaan -->
        <div class="bg-white p-2 rounded-3xl border border-[#e1ede8] shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" role="tablist">
                <a href="{{ route('administrasi.index', ['tab' => 'umum', 'tahun' => $currentYear]) }}"
                   class="px-6 py-3.5 rounded-2xl text-xs sm:text-sm font-bold transition-all text-center flex items-center justify-center gap-2.5 {{ $activeTab === 'umum' ? 'bg-[#0c3837] text-emerald-300 shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <span>1. Bagian Administrasi Umum (9 Buku Register)</span>
                </a>

                <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan', 'tahun' => $currentYear]) }}"
                   class="px-6 py-3.5 rounded-2xl text-xs sm:text-sm font-bold transition-all text-center flex items-center justify-center gap-2.5 {{ $activeTab === 'kelembagaan' ? 'bg-[#0c3837] text-emerald-300 shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <span>2. Administrasi Kelembagaan (8 Lembaga Desa)</span>
                </a>
            </div>
        </div>

        @if($activeTab === 'umum')
        <!-- ========================================================= -->
        <!-- TAB 1: BAGIAN ADMINISTRASI UMUM (9 BUKU REGISTER) -->
        <!-- ========================================================= -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-[#0c3837]">9 Buku Register Administrasi Umum</h2>
                    <p class="text-xs text-[#64748b] mt-0.5">Sesuai Format Baku Buku Register Administrasi Pemerintahan Desa</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. Buku Peraturan Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-[#10b981]/10 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                Buku 01 • Perdes
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Peraturan Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Peraturan Desa (Perdes), Perkades, dan Peraturan Bersama.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['peraturan_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Dokumen</span>
                        </div>
                        <a href="{{ route('administrasi.peraturan-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 2. Buku Keputusan Kepala Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-emerald-100 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200">
                                Buku 02 • SK Kades
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Keputusan Kepala Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Surat Keputusan (SK) dan Penetapan Kepala Desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['keputusan_kades']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total SK Terbit</span>
                        </div>
                        <a href="{{ route('administrasi.keputusan-kades.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 3. Buku Inventaris Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-[#10b981]/10 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                Buku 03 • Aset Desa
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Inventaris & Kekayaan Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register aset barang, peralatan, gedung fisik, dan kondisi perolehan.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['inventaris_aset']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Item Aset</span>
                        </div>
                        <a href="{{ route('administrasi.inventaris-aset.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 4. Buku Aparat Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-emerald-100 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200">
                                Buku 04 • Aparatur
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Aparat Pemerintah Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register susunan aparatur, perangkat, NIP, masa jabatan, dan jam kerja.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['aparat_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ $statsUmum['aparat_desa']['aktif'] }} Aparat Aktif</span>
                        </div>
                        <a href="{{ route('administrasi.aparatur.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 5. Buku Tanah Kas Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-[#10b981]/10 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                Buku 05 • Tanah Kas
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Tanah Kas Desa (TKD)</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register bidang tanah milik aset kas desa dan peruntukan pemanfaatan.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['tanah_kas_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ number_format($statsUmum['tanah_kas_desa']['total_luas'], 0, ',', '.') }} m² TKD</span>
                        </div>
                        <a href="{{ route('administrasi.tanah-desa.index', ['jenis' => 'tanah_kas_desa']) }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 6. Buku Luas Tanah Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-emerald-100 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200">
                                Buku 06 • Luas Wilayah
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Luas Tanah di Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register seluruh hamparan letter C, sertifikat, dan tanah warga.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['luas_tanah_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ number_format($statsUmum['luas_tanah_desa']['total_luas'], 0, ',', '.') }} m² Total Luas</span>
                        </div>
                        <a href="{{ route('administrasi.tanah-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 7. Buku Agenda Surat -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-[#10b981]/10 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                Buku 07 • Surat Dinas
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Buku Agenda Surat</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register surat masuk & keluar dinas operasional pemerintah desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['buku_agenda']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ $statsUmum['buku_agenda']['masuk'] }} Masuk / {{ $statsUmum['buku_agenda']['keluar'] }} Keluar</span>
                        </div>
                        <a href="{{ route('administrasi.buku-agenda.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 8. Surat Ekspedisi -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-emerald-100 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-emerald-200">
                                Buku 08 • Ekspedisi
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Surat Ekspedisi</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengiriman fisik dan tanda bukti serah terima surat dinas.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['buku_ekspedisi']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Pengiriman</span>
                        </div>
                        <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 9. Lembaran / Berita Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-[#10b981]/10 text-[#0c3837] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                Buku 09 • Publikasi
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Lembaran / Berita Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengundangan lembaran desa dan berita publikasi resmi desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['lembaran_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Publikasi Terdaftar</span>
                        </div>
                        <a href="{{ route('administrasi.lembaran-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Buku</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        @elseif($activeTab === 'kelembagaan')
        <!-- ========================================================= -->
        <!-- TAB 2: BAGIAN ADMINISTRASI KELEMBAGAAN (8 LEMBAGA) -->
        <!-- ========================================================= -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-[#0c3837]">Administrasi Kelembagaan Desa</h2>
                    <p class="text-xs text-[#64748b] mt-0.5">Pusat tata kelola 8 Lembaga Desa: Anggota, Keputusan, Kegiatan, dan Agenda</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-100 text-[#0c3837] rounded-xl text-xs font-bold">
                        {{ $statsKelembagaan['total_lembaga'] }} Lembaga Aktif
                    </span>
                    <span class="px-3 py-1 bg-[#e2f0ed] text-[#114443] rounded-xl text-xs font-bold">
                        {{ $statsKelembagaan['total_anggota'] }} Total Pengurus
                    </span>
                </div>
            </div>

            <!-- 8 Institution Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($statsKelembagaan['institutions'] as $inst)
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#0c3837] text-emerald-300 border border-[#1b5e5c] flex items-center justify-center font-extrabold text-sm shadow-xs tracking-wide">
                                {{ $inst->singkatan ?? substr($inst->nama_lembaga, 0, 3) }}
                            </div>
                            <span class="px-2.5 py-1 bg-[#e2f0ed] text-[#114443] rounded-full text-[10px] font-extrabold uppercase tracking-wider border border-[#10b981]/20">
                                {{ ucfirst($inst->kategori) }}
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-[#0c3837] line-clamp-1">{{ $inst->nama_lembaga }}</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2 min-h-[32px]">{{ $inst->deskripsi ?? 'Lembaga resmi tingkat desa' }}</p>

                        <!-- 4 Pillars Mini Badges -->
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug, 'tab' => 'anggota']) }}" class="p-2 bg-[#f7faf9] hover:bg-[#e2f0ed] rounded-xl text-left transition-colors">
                                <span class="text-[10px] font-semibold text-[#64748b] block">Anggota</span>
                                <span class="text-xs font-extrabold text-[#0c3837]">{{ $inst->active_members_count }} Orang</span>
                            </a>
                            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug, 'tab' => 'keputusan']) }}" class="p-2 bg-[#f7faf9] hover:bg-[#e2f0ed] rounded-xl text-left transition-colors">
                                <span class="text-[10px] font-semibold text-[#64748b] block">Keputusan</span>
                                <span class="text-xs font-extrabold text-[#0c3837]">{{ $inst->decisions_count }} SK</span>
                            </a>
                            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug, 'tab' => 'kegiatan']) }}" class="p-2 bg-[#f7faf9] hover:bg-[#e2f0ed] rounded-xl text-left transition-colors">
                                <span class="text-[10px] font-semibold text-[#64748b] block">Kegiatan</span>
                                <span class="text-xs font-extrabold text-[#0c3837]">{{ $inst->activities_count }} Kegiatan</span>
                            </a>
                            <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug, 'tab' => 'agenda']) }}" class="p-2 bg-[#f7faf9] hover:bg-[#e2f0ed] rounded-xl text-left transition-colors">
                                <span class="text-[10px] font-semibold text-[#64748b] block">Agenda</span>
                                <span class="text-xs font-extrabold text-[#0c3837]">{{ $inst->agendas_count }} Jadwal</span>
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <span class="text-[11px] text-[#64748b] font-medium">{{ $inst->nomor_sk_pendirian ? 'SK Terverifikasi' : 'SK Belum Diisi' }}</span>
                        <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug]) }}" 
                           class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>Kelola</span>
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-layouts.app>
