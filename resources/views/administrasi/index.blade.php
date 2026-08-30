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
                   class="px-6 py-3.5 rounded-2xl text-xs sm:text-sm font-bold transition-all text-center flex items-center justify-center gap-2.5 {{ $activeTab === 'umum' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>1. Bagian Administrasi Umum (9 Buku Register)</span>
                </a>

                <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan', 'tahun' => $currentYear]) }}"
                   class="px-6 py-3.5 rounded-2xl text-xs sm:text-sm font-bold transition-all text-center flex items-center justify-center gap-2.5 {{ $activeTab === 'kelembagaan' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
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
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 01</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Peraturan Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Peraturan Desa (Perdes), Perkades, dan Peraturan Bersama.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['peraturan_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Dokumen</span>
                        </div>
                        <a href="{{ route('administrasi.peraturan-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 2. Buku Keputusan Kepala Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 02</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Keputusan Kepala Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Surat Keputusan (SK) dan Penetapan Kepala Desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['keputusan_kades']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total SK Terbit</span>
                        </div>
                        <a href="{{ route('administrasi.keputusan-kades.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 3. Buku Inventaris Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 03</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Inventaris & Kekayaan Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register aset barang, peralatan, gedung fisik, dan kondisi perolehan.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['inventaris_aset']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Item Aset</span>
                        </div>
                        <a href="{{ route('administrasi.inventaris-aset.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 4. Buku Aparat Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 04</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Aparat Pemerintah Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register susunan aparatur, perangkat, NIP, masa jabatan, dan jam kerja.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['aparat_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ $statsUmum['aparat_desa']['aktif'] }} Aparat Aktif</span>
                        </div>
                        <a href="{{ route('administrasi.aparatur.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 5. Buku Tanah Kas Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 05</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Tanah Kas Desa (TKD)</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register bidang tanah milik aset kas desa dan peruntukan pemanfaatan.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['tanah_kas_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ number_format($statsUmum['tanah_kas_desa']['total_luas'], 0, ',', '.') }} m² TKD</span>
                        </div>
                        <a href="{{ route('administrasi.tanah-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 6. Buku Luas Tanah Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 06</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Luas Tanah di Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register seluruh hamparan letter C, sertifikat, dan tanah warga.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['luas_tanah_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ number_format($statsUmum['luas_tanah_desa']['total_luas'], 0, ',', '.') }} m² Total Luas</span>
                        </div>
                        <a href="{{ route('administrasi.tanah-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 7. Buku Agenda Surat -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 07</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Buku Agenda Surat</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register surat masuk & keluar dinas operasional pemerintah desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['buku_agenda']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">{{ $statsUmum['buku_agenda']['masuk'] }} Masuk / {{ $statsUmum['buku_agenda']['keluar'] }} Keluar</span>
                        </div>
                        <a href="{{ route('administrasi.buku-agenda.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 8. Surat Ekspedisi -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 08</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Surat Ekspedisi</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengiriman fisik dan tanda bukti serah terima surat dinas.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['buku_ekspedisi']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Total Pengiriman</span>
                        </div>
                        <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 9. Lembaran / Berita Desa -->
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 09</span>
                        <h3 class="text-lg font-bold text-[#0c3837] mt-0.5">Lembaran / Berita Desa</h3>
                        <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengundangan lembaran desa dan berita publikasi resmi desa.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-extrabold text-[#0c3837]">{{ $statsUmum['lembaran_desa']['total'] }}</span>
                            <span class="text-[10px] text-[#64748b] block">Publikasi Terdaftar</span>
                        </div>
                        <a href="{{ route('administrasi.lembaran-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Buka Buku</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                    <span class="px-3 py-1 bg-[#d4ed31]/30 text-[#0c3837] rounded-xl text-xs font-bold">
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
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center font-extrabold text-sm shadow-xs">
                                {{ $inst->singkatan ?? substr($inst->nama_lembaga, 0, 3) }}
                            </div>
                            <span class="px-2.5 py-1 bg-[#e2f0ed] text-[#114443] rounded-full text-[10px] font-bold uppercase tracking-wider">
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
                           class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                            <span>Kelola</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-layouts.app>
