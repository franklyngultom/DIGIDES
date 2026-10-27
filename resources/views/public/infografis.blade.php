@extends('layouts.public')

@section('content')
<div x-data="{ infoTab: 'penduduk' }" class="space-y-12">

    <!-- Header Banner -->
    <section class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#082424] text-white py-14 sm:py-20 border-b border-[#1b5e5c]/40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-[#8bc3b8] mb-4">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-semibold">Infografis</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Transparansi & Statistik Terbuka
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Infografis Kependudukan & APBDes
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Sajian data visual kependudukan yang akurat, transparansi anggaran pendapatan dan belanja desa (APBDes), serta realisasi program bantuan sosial bagi masyarakat.
                </p>
            </div>

            <!-- Sub-Tabs Navigation -->
            <div class="flex flex-wrap items-center gap-2 pt-8 border-t border-white/10 mt-8">
                <button type="button" 
                        @click="infoTab = 'penduduk'"
                        :class="infoTab === 'penduduk' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Statistik Kependudukan
                </button>
                <button type="button" 
                        @click="infoTab = 'apbdes'"
                        :class="infoTab === 'apbdes' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Transparansi APBDes 2026
                </button>
                <button type="button" 
                        @click="infoTab = 'bansos'"
                        :class="infoTab === 'bansos' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Bantuan Sosial & Kesehatan
                </button>
            </div>
        </div>
    </section>

    <!-- Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- TAB 1: KEPENDUDUKAN -->
        <div x-show="infoTab === 'penduduk'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-10">
            
            <!-- Quick Cards Total, L, P, KK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs">
                    <span class="text-xs font-semibold text-[#64748b] uppercase tracking-wider block">Total Penduduk</span>
                    <p class="text-3xl font-black text-[#0c3837] mt-1">{{ number_format($totalPenduduk, 0, ',', '.') }}</p>
                    <span class="text-[11px] font-semibold text-[#10b981] mt-1 block">Warga Terdaftar</span>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs">
                    <span class="text-xs font-semibold text-[#64748b] uppercase tracking-wider block">Laki-Laki</span>
                    <p class="text-3xl font-black text-blue-600 mt-1">{{ number_format($totalLakiLaki, 0, ',', '.') }}</p>
                    <span class="text-[11px] text-[#64748b] mt-1 block">{{ round(($totalLakiLaki / max($totalPenduduk, 1)) * 100) }}% Komposisi</span>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs">
                    <span class="text-xs font-semibold text-[#64748b] uppercase tracking-wider block">Perempuan</span>
                    <p class="text-3xl font-black text-rose-500 mt-1">{{ number_format($totalPerempuan, 0, ',', '.') }}</p>
                    <span class="text-[11px] text-[#64748b] mt-1 block">{{ round(($totalPerempuan / max($totalPenduduk, 1)) * 100) }}% Komposisi</span>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs">
                    <span class="text-xs font-semibold text-[#64748b] uppercase tracking-wider block">Kepala Keluarga (KK)</span>
                    <p class="text-3xl font-black text-emerald-600 mt-1">1.120</p>
                    <span class="text-[11px] font-semibold text-emerald-600 mt-1 block">Rata-rata 3,4 Jiwa / KK</span>
                </div>
            </div>

            <!-- Demographics Detailed Visuals -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Kelompok Usia -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-4">
                    <div class="border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837]">Piramida Kelompok Usia</h3>
                        <p class="text-xs text-[#64748b]">Distribusi rentang umur warga desa.</p>
                    </div>
                    <div class="space-y-4 pt-2">
                        @foreach($demografiUsia as $label => $jumlah)
                            @php
                                $percent = round(($jumlah / max($totalPenduduk, 1)) * 100);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-[#0c3837]">{{ $label }}</span>
                                    <span class="text-[#10b981] font-bold">{{ $jumlah }} Jiwa ({{ $percent }}%)</span>
                                </div>
                                <div class="w-full bg-[#f1f5f4] rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#10b981] to-[#34d399] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Jenjang Pendidikan -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-4">
                    <div class="border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837]">Tingkat Pendidikan Terakhir</h3>
                        <p class="text-xs text-[#64748b]">Kualifikasi pendidikan masyarakat.</p>
                    </div>
                    <div class="space-y-3.5 pt-2">
                        @foreach($demografiPendidikan as $label => $jumlah)
                            @php
                                $percent = round(($jumlah / max($totalPenduduk, 1)) * 100);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-[#0c3837]">{{ $label }}</span>
                                    <span class="text-[#4fa394] font-bold">{{ $jumlah }} Jiwa</span>
                                </div>
                                <div class="w-full bg-[#f1f5f4] rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-[#4fa394] h-full rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Pekerjaan Warga -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-6">
                <div class="border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837]">Mata Pencaharian & Profesi Utama</h3>
                    <p class="text-xs text-[#64748b]">Sebaran profesi dan aktivitas ekonomi masyarakat desa.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($demografiPekerjaan as $profesi => $jumlah)
                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-[#0c3837] block">{{ $profesi }}</span>
                                <span class="text-[11px] text-[#64748b]">{{ round(($jumlah / max($totalPenduduk, 1)) * 100) }}% dari populasi</span>
                            </div>
                            <span class="text-base font-black text-[#114443]">{{ $jumlah }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- TAB 2: APBDES TRANSPARANSI -->
        <div x-show="infoTab === 'apbdes'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-10">
            
            <!-- APBDes Summary Hero Card -->
            <div class="bg-gradient-to-br from-[#0c3837] to-[#114443] rounded-3xl p-8 text-white shadow-xl border border-[#1b5e5c]/40">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md">
                        <span class="text-xs text-[#8bc3b8] uppercase tracking-wider block">Total Pendapatan Desa</span>
                        <p class="text-2xl sm:text-3xl font-black text-emerald-300 mt-1">Rp {{ number_format($apbdes['total_pendapatan'], 0, ',', '.') }}</p>
                        <span class="text-[11px] text-[#8bc3b8]">Tahun Anggaran {{ $apbdes['tahun'] }}</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md">
                        <span class="text-xs text-[#8bc3b8] uppercase tracking-wider block">Total Belanja Desa</span>
                        <p class="text-2xl sm:text-3xl font-black text-rose-300 mt-1">Rp {{ number_format($apbdes['total_belanja'], 0, ',', '.') }}</p>
                        <span class="text-[11px] text-[#8bc3b8]">Realisasi 98% Berjalan</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md">
                        <span class="text-xs text-[#8bc3b8] uppercase tracking-wider block">Surplus Anggaran</span>
                        <p class="text-2xl sm:text-3xl font-black text-emerald-300 mt-1">Rp {{ number_format($apbdes['surplus_defisit'], 0, ',', '.') }}</p>
                        <span class="text-[11px] text-[#8bc3b8]">SILPA Kas Desa Terjaga</span>
                    </div>
                </div>
            </div>

            <!-- APBDes Breakdown Pendapatan vs Belanja -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Pendapatan -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837]">Rincian Pendapatan Desa</h3>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">Pemasukan</span>
                    </div>
                    <div class="space-y-4 pt-2">
                        @foreach($apbdes['pendapatan'] as $item)
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-[#0c3837]">{{ $item['sumber'] }}</span>
                                    <span class="text-emerald-700 font-bold">Rp {{ number_format($item['jumlah'], 0, ',', '.') }} ({{ $item['persentase'] }}%)</span>
                                </div>
                                <div class="w-full bg-[#f1f5f4] rounded-full h-3 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Belanja -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837]">Rincian Pengeluaran Belanja</h3>
                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold">Pengeluaran</span>
                    </div>
                    <div class="space-y-4 pt-2">
                        @foreach($apbdes['belanja'] as $item)
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-[#0c3837]">{{ $item['bidang'] }}</span>
                                    <span class="text-rose-700 font-bold">Rp {{ number_format($item['jumlah'], 0, ',', '.') }} ({{ $item['persentase'] }}%)</span>
                                </div>
                                <div class="w-full bg-[#f1f5f4] rounded-full h-3 overflow-hidden">
                                    <div class="bg-rose-400 h-full rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        <!-- TAB 3: BANTUAN SOSIAL & KESEHATAN -->
        <div x-show="infoTab === 'bansos'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-10">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-2">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Program Keluarga Harapan</span>
                    <p class="text-3xl font-black text-[#0c3837]">{{ $bansos['pkh'] }}</p>
                    <span class="text-xs font-semibold text-emerald-600 block">Keluarga Penerima Manfaat</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-2">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">BLT Dana Desa</span>
                    <p class="text-3xl font-black text-[#0c3837]">{{ $bansos['blt_dd'] }}</p>
                    <span class="text-xs font-semibold text-emerald-600 block">Keluarga Miskin Ekstrem</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-2">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Bantuan Pangan (BPNT)</span>
                    <p class="text-3xl font-black text-[#0c3837]">{{ $bansos['bpnt'] }}</p>
                    <span class="text-xs font-semibold text-emerald-600 block">KPM Sembako Rutin</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-2">
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Jaminan KIS-PBI</span>
                    <p class="text-3xl font-black text-[#0c3837]">{{ $bansos['kis_pbi'] }}</p>
                    <span class="text-xs font-semibold text-emerald-600 block">Warga Berobat Gratis</span>
                </div>

            </div>

            <!-- Program Zero Stunting -->
            <div class="bg-gradient-to-r from-emerald-600 to-[#0c3837] rounded-3xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="space-y-2">
                    <span class="px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider">Capaian Posyandu & PKK</span>
                    <h3 class="text-2xl font-black">Program Intervensi Stunting & Gizi Balita</h3>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">
                        Pemberian makanan tambahan (PMT) rutin, imunisasi berkala, serta pemantauan tinggi badan balita telah menekan angka stunting hingga tersisa 3 balita dalam pendampingan intensif.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-white/10 backdrop-blur-md text-center shrink-0 border border-white/20">
                    <span class="text-4xl font-black text-emerald-300 block">97.8%</span>
                    <span class="text-xs font-semibold text-white">Balita Tumbuh Sehat & Normal</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
