<x-layouts.app title="Buku Register Administrasi Umum">
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
                <h1 class="text-3xl font-extrabold text-[#0c3837] tracking-tight mt-1">Buku Register Administrasi Umum</h1>
                <p class="text-sm text-[#64748b] mt-0.5">Pusat tata kelola kearsipan, surat keputusan, peraturan, aset kekayaan, tanah, dan anggaran resmi desa</p>
            </div>

            <!-- Quick Action or Year Filter -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('administrasi.index') }}" class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-[#e1ede8] shadow-xs">
                    <label for="filter-tahun" class="text-xs font-semibold text-[#0c3837]">Tahun:</label>
                    <select id="filter-tahun" name="tahun" onchange="this.form.submit()" class="text-xs font-bold text-[#114443] bg-transparent border-0 focus:ring-0 cursor-pointer">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- 8 Register Book Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Card 1: Buku Peraturan Desa -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 01</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Peraturan di Desa</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Perdes, Perkades, dan Peraturan Bersama Kades.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['peraturan_desa']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Total Dokumen</span>
                    </div>
                    <a href="{{ route('administrasi.peraturan-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Buku Keputusan Kades -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 02</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Keputusan Kades</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register Surat Keputusan (SK) Kepala Desa.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['keputusan_kades']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Total SK Terbit</span>
                    </div>
                    <a href="{{ route('administrasi.keputusan-kades.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Buku Inventaris & Aset -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 03</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Inventaris & Aset</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register aset barang, bangunan, fisik, kondisi & harga.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['inventaris_aset']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Total Item Aset</span>
                    </div>
                    <a href="{{ route('administrasi.inventaris-aset.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 4: Buku Tanah Desa -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 04</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Tanah di Desa</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register tanah kas desa, tanah bengkok, dan tanah warga.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['tanah_desa']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Bidang Tanah</span>
                    </div>
                    <a href="{{ route('administrasi.tanah-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 5: Buku Anggaran Desa -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 05</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Anggaran Desa</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register penetapan APBDes murni & perubahan.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['anggaran_desa']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Dokumen APBDes</span>
                    </div>
                    <a href="{{ route('administrasi.anggaran-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 6: Buku Lembaran Desa -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 06</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Lembaran Desa</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengundangan lembaran dan berita publikasi desa.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['lembaran_desa']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Publikasi Terdaftar</span>
                    </div>
                    <a href="{{ route('administrasi.lembaran-desa.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 7: Buku Agenda Surat -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f0ed] rounded-full opacity-40 group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 07</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Agenda Surat</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register surat masuk & keluar dinas operasional desa.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['buku_agenda']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">{{ $stats['buku_agenda']['masuk'] }} Masuk / {{ $stats['buku_agenda']['keluar'] }} Keluar</span>
                    </div>
                    <a href="{{ route('administrasi.buku-agenda.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Card 8: Buku Ekspedisi -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#e2f48f]/40 rounded-full group-hover:scale-110 transition-transform"></div>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#10b981]">Buku 08</span>
                    <h2 class="text-lg font-bold text-[#0c3837] mt-0.5">Buku Ekspedisi</h2>
                    <p class="text-xs text-[#64748b] mt-1 line-clamp-2">Register pengiriman fisik dan tanda bukti serah terima surat.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between">
                    <div>
                        <span class="text-2xl font-extrabold text-[#0c3837]">{{ $stats['buku_ekspedisi']['total'] }}</span>
                        <span class="text-[10px] text-[#64748b] block">Total Pengiriman</span>
                    </div>
                    <a href="{{ route('administrasi.buku-ekspedisi.index') }}" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <span>Buka</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
