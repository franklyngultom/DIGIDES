@extends('layouts.public')

@section('content')
<div x-data="{
        activeSlide: 0,
        autoSlideTimer: null,
        searchTab: 'layanan',
        visitorDropdown: false,
        slides: [
            {
                title: 'Selamat Datang di Portal Resmi',
                subtitle: '{{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}',
                tagline: 'Sistem digitalisasi tata kelola administrasi desa, pelayanan surat kependudukan mandiri, transparansi anggaran, serta penguatan potensi ekonomi warga terpadu 24 jam sehari.',
                badge: 'Mitra Terpercaya Pelayanan Masyarakat Desa',
                bg: '{{ asset('images/public/desa-alam.jpg') }}',
                actionText: 'Ajukan Surat Mandiri',
                actionUrl: '{{ route('public.layanan') }}'
            },
            {
                title: 'Pelayanan Publik Prima & Bebas Calo',
                subtitle: 'Layanan Persuratan Mandiri Online',
                tagline: 'Dapatkan dokumen surat resmi ber-QR Code valid seperti SKCK, Surat Keterangan Usaha, dan Domisili secara cepat dan transparan dari rumah.',
                badge: 'Mitra Terpercaya Warga',
                bg: '{{ asset('images/public/layanan-kiosk.jpg') }}',
                actionText: 'Katalog Layanan Surat',
                actionUrl: '{{ route('public.layanan') }}'
            },
            {
                title: 'Transparansi & Kemandirian Ekonomi',
                subtitle: 'Menuju Desa Mandiri Berkualitas',
                tagline: 'Pantau realisasi anggaran APBDes 2026, status capaian Indeks Desa Membangun (IDM), dan produk unggulan UMKM BUMDes secara terbuka.',
                badge: 'Indeks Desa Membangun (IDM)',
                bg: '{{ asset('images/public/kantor-desa.jpg') }}',
                actionText: 'Cek Transparansi APBDes',
                actionUrl: '{{ route('public.infografis') }}'
            }
        ],
        nextSlide() {
            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
        },
        prevSlide() {
            this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
        },
        startAutoSlide() {
            this.stopAutoSlide();
            this.autoSlideTimer = setInterval(() => {
                this.nextSlide();
            }, 7000);
        },
        stopAutoSlide() {
            if (this.autoSlideTimer) {
                clearInterval(this.autoSlideTimer);
                this.autoSlideTimer = null;
            }
        }
     }"
     x-init="startAutoSlide()"
     @mouseenter="stopAutoSlide()"
     @mouseleave="startAutoSlide()"
     class="space-y-16 lg:space-y-24">

    <!-- ========================================================================= -->
    <!-- SECTION 1: HERO SECTION                                                   -->
    <!-- ========================================================================= -->
    <section class="relative min-h-[85vh] lg:min-h-[92vh] flex items-center justify-center overflow-hidden">
        
        <!-- Crossfading Photographic Backgrounds -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 z-0">
                <img :src="slide.bg" 
                     :alt="slide.title" 
                     class="w-full h-full object-cover object-center filter brightness-90">
                <!-- Dual Gradient Vignette for perfect text readability -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837] via-[#0c3837]/65 to-black/75"></div>
                <div class="absolute inset-0 bg-[#082424]/30 mix-blend-multiply"></div>
            </div>
        </template>

        <!-- Slider Navigation Arrows -->
        <button type="button" 
                @click="prevSlide()"
                class="absolute left-3 sm:left-6 lg:left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/10 hover:bg-white/25 backdrop-blur-md border border-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer shadow-lg group"
                aria-label="Previous Slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button type="button" 
                @click="nextSlide()"
                class="absolute right-3 sm:right-6 lg:right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/10 hover:bg-white/25 backdrop-blur-md border border-white/20 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 cursor-pointer shadow-lg group"
                aria-label="Next Slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Main Center Typography & Action Flow -->
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white pt-24 pb-28 sm:pb-32">
            
            <!-- Top Status Pill (Cleanly integrated without overlapping) -->
            <div class="inline-flex flex-wrap items-center justify-center gap-2 px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-bold text-emerald-300 mb-5 shadow-sm">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#10b981] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#10b981]"></span>
                </span>
                <span x-text="slides[activeSlide].badge">Mitra Terpercaya Pelayanan Masyarakat Desa</span>
                <span class="text-white/40 hidden sm:inline">&bull;</span>
                <span class="text-white/90 font-medium hidden sm:inline">Kabupaten {{ !empty($desa->kabupaten) ? ucwords($desa->kabupaten) : 'Rokan Hilir' }}</span>
            </div>

            <!-- Headings -->
            <div class="space-y-3 sm:space-y-4">
                
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-wide text-emerald-200 drop-shadow-md"
                    x-text="slides[activeSlide].title">
                    Selamat Datang di Portal Resmi
                </h2>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white drop-shadow-[0_4px_16px_rgba(0,0,0,0.85)] max-w-4xl mx-auto leading-tight"
                    x-text="slides[activeSlide].subtitle">
                    {{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}
                </h1>

                <p class="text-xs sm:text-base lg:text-lg text-emerald-100/90 max-w-2xl mx-auto font-normal leading-relaxed drop-shadow-[0_2px_8px_rgba(0,0,0,0.8)] pt-2"
                   x-text="slides[activeSlide].tagline">
                    Sistem digitalisasi tata kelola administrasi desa, pelayanan surat kependudukan mandiri, transparansi anggaran, serta penguatan potensi ekonomi warga terpadu 24 jam sehari.
                </p>

                <!-- Call-To-Action Button Group -->
                <div class="flex flex-wrap items-center justify-center gap-3.5 pt-6">
                    <a :href="slides[activeSlide].actionUrl" 
                       class="inline-flex items-center justify-center px-6 sm:px-7 py-3 sm:py-3.5 rounded-full bg-gradient-to-r from-[#10b981] to-[#059669] hover:from-[#059669] hover:to-[#047857] text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-emerald-950/40 hover:shadow-2xl transition-all duration-200 hover:scale-103 active:scale-98">
                        <span x-text="slides[activeSlide].actionText">Ajukan Surat Mandiri</span>
                    </a>

                    <a href="{{ route('public.lacak-surat') }}" 
                       class="inline-flex items-center justify-center px-5 sm:px-6 py-3 sm:py-3.5 rounded-full bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/25 text-white font-bold text-xs sm:text-sm transition-all duration-200 hover:scale-103">
                        <span>Lacak Berkas Surat</span>
                    </a>

                    <a href="{{ route('public.profil') }}" 
                       class="inline-flex items-center justify-center px-5 sm:px-6 py-3 sm:py-3.5 rounded-full bg-black/25 hover:bg-black/40 backdrop-blur-md border border-white/15 text-emerald-200 font-semibold text-xs sm:text-sm transition-all duration-200 hover:scale-103">
                        <span>Profil Desa</span>
                    </a>
                </div>
            </div>

            <!-- Slide Dots -->
            <div class="flex items-center justify-center gap-2 pt-8 sm:pt-10">
                <template x-for="(slide, i) in slides" :key="i">
                    <button type="button" 
                            @click="activeSlide = i" 
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                            :class="activeSlide === i ? 'w-8 bg-emerald-400' : 'w-2 bg-white/40 hover:bg-white/70'"
                            :aria-label="'Go to slide ' + (i + 1)"></button>
                </template>
            </div>

            <!-- Integrated Live Visitors Indicator (Inside Hero - Never Collides with Search Bar) -->
            <div class="pt-6 flex justify-center">
                <div class="relative">
                    <button type="button" 
                            @click="visitorDropdown = !visitorDropdown"
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/30 hover:bg-black/50 backdrop-blur-md border border-white/15 text-xs text-white/90 transition-all hover:scale-102 cursor-pointer shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                        <span class="font-bold text-white">1.284</span>
                        <span class="text-emerald-200">Kunjungan Hari Ini</span>
                        <svg class="w-3.5 h-3.5 text-emerald-300 ml-1 transition-transform" :class="visitorDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Expandable Visitor Breakdown Modal -->
                    <div x-show="visitorDropdown" 
                         x-cloak
                         @click.away="visitorDropdown = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute left-1/2 -translate-x-1/2 top-10 w-72 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-2xl border border-[#e1ede8] text-[#0c3837] space-y-2 text-left z-30">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Statistik Pengunjung</span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif: 42
                            </span>
                        </div>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between py-1 border-b border-slate-50">
                                <span class="text-[#64748b]">Hari Ini:</span>
                                <span class="font-bold text-[#0c3837]">1.284</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-50">
                                <span class="text-[#64748b]">Kemarin:</span>
                                <span class="font-bold text-[#0c3837]">3.412</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-50">
                                <span class="text-[#64748b]">Bulan Ini:</span>
                                <span class="font-bold text-[#0c3837]">58.210</span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="text-[#64748b] font-semibold">Total Pengunjung:</span>
                                <span class="font-black text-[#10b981]">248.150</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 2: PORTAL SEARCH & TRACKING GATEWAY (Interactive & Zero Collision)-->
    <!-- ========================================================================= -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 sm:-mt-14 relative z-30">
        <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] p-5 sm:p-7 shadow-2xl shadow-emerald-950/10 border border-[#e1ede8]">
            
            <!-- Tab Switcher -->
            <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                <button type="button" 
                        @click="searchTab = 'layanan'"
                        :class="searchTab === 'layanan' ? 'bg-[#0c3837] text-white shadow-sm' : 'bg-slate-100 text-[#64748b] hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                    <span>Cari Layanan Surat</span>
                </button>

                <button type="button" 
                        @click="searchTab = 'lacak'"
                        :class="searchTab === 'lacak' ? 'bg-[#0c3837] text-white shadow-sm' : 'bg-slate-100 text-[#64748b] hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                    <span>Lacak Status Permohonan</span>
                </button>
            </div>

            <!-- Tab 1: Cari Layanan Surat -->
            <div x-show="searchTab === 'layanan'">
                <form action="{{ route('public.layanan') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#10b981]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="q" 
                               placeholder="Cari jenis surat (contoh: SKCK, Keterangan Usaha, Domisili, SKTM)..." 
                               class="w-full pl-11 pr-4 py-3.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs sm:text-sm text-[#0c3837] font-semibold focus:outline-none focus:border-[#10b981] focus:bg-white transition-all">
                    </div>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white font-extrabold text-xs sm:text-sm shadow-md transition-all duration-200 hover:scale-102 flex items-center justify-center cursor-pointer shrink-0">
                        <span>Cari Surat</span>
                    </button>
                </form>

                <!-- Popular Letter Pills -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                    <span class="font-bold text-[#64748b]">Layanan Cepat Populer:</span>
                    <a href="{{ route('public.layanan', ['q' => 'SKCK']) }}" class="px-3 py-1 rounded-full bg-[#e2f0ed] hover:bg-[#10b981] text-[#0c3837] hover:text-white font-semibold transition-colors">
                        Surat Pengantar SKCK
                    </a>
                    <a href="{{ route('public.layanan', ['q' => 'Usaha']) }}" class="px-3 py-1 rounded-full bg-[#e2f0ed] hover:bg-[#10b981] text-[#0c3837] hover:text-white font-semibold transition-colors">
                        Keterangan Usaha (SKU)
                    </a>
                    <a href="{{ route('public.layanan', ['q' => 'Domisili']) }}" class="px-3 py-1 rounded-full bg-[#e2f0ed] hover:bg-[#10b981] text-[#0c3837] hover:text-white font-semibold transition-colors">
                        Keterangan Domisili
                    </a>
                    <a href="{{ route('public.layanan') }}" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-[#64748b] font-semibold transition-colors ml-auto">
                        Lihat Semua Katalog &rarr;
                    </a>
                </div>
            </div>

            <!-- Tab 2: Lacak Status Permohonan -->
            <div x-show="searchTab === 'lacak'" x-cloak>
                <form action="{{ route('public.lacak-surat') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#10b981]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <input type="text" 
                                name="nomor" 
                                placeholder="Masukkan Nomor Registrasi Pengajuan atau Nomor Surat Resmi..." 
                                class="w-full pl-11 pr-4 py-3.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs sm:text-sm text-[#0c3837] font-semibold focus:outline-none focus:border-[#10b981] focus:bg-white transition-all">
                    </div>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-[#0c3837] hover:bg-[#082424] text-white font-extrabold text-xs sm:text-sm shadow-md transition-all duration-200 hover:scale-102 flex items-center justify-center cursor-pointer shrink-0">
                        <span>Lacak Berkas</span>
                    </button>
                </form>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-[#64748b]">
                    <span>💡 Masukkan kode registrasi yang didapatkan saat pengajuan online untuk memantau status validasi berkas.</span>
                    <a href="{{ route('public.lacak-surat') }}" class="font-bold text-[#10b981] hover:underline">
                        Panduan Pelacakan &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SECTION 3: 4 PILAR UTAMA KEMAJUAN DESA (Demografi, IDM, APBDes, Potensi)  -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Demografi -->
            <a href="{{ route('public.infografis') }}" class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200/70 shadow-xs flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/60">Terdata</span>
                    </div>
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Demografi Warga</span>
                    <p class="text-2xl font-black text-[#0c3837] mt-1">{{ number_format($totalPenduduk, 0, ',', '.') }} Jiwa</p>
                    <p class="text-xs text-[#64748b] mt-1">{{ number_format($totalLakiLaki, 0, ',', '.') }} L &bull; {{ number_format($totalPerempuan, 0, ',', '.') }} P</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-[#10b981] group-hover:text-[#0c3837]">
                    <span>Infografis Lengkap</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- Card 2: Status IDM -->
            <a href="{{ route('public.idm') }}" class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200/70 shadow-xs flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/60">Skor 0.8415</span>
                    </div>
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Status IDM 2026</span>
                    <p class="text-2xl font-black text-[#0c3837] mt-1">Desa Mandiri</p>
                    <p class="text-xs text-[#64748b] mt-1">Indeks Ketahanan Sosial Sangat Baik</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-amber-600 group-hover:text-[#0c3837]">
                    <span>Capaian Pilar IDM</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- Card 3: APBDes -->
            <a href="{{ route('public.infografis') }}" class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 border border-teal-200/70 shadow-xs flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-200/60">TA 2026</span>
                    </div>
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Transparansi APBDes</span>
                    <p class="text-2xl font-black text-[#0c3837] mt-1">Rp 1,85 M</p>
                    <p class="text-xs text-[#64748b] mt-1">Pendapatan & Belanja Terbuka</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-teal-600 group-hover:text-[#0c3837]">
                    <span>Rincian APBDes 2026</span>
                    <span>&rarr;</span>
                </div>
            </a>

            <!-- Card 4: Potensi & Listing -->
            <a href="{{ route('public.listing') }}" class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200/70 shadow-xs flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60">BUMDes & UMKM</span>
                    </div>
                    <span class="text-xs font-bold text-[#64748b] uppercase tracking-wider block">Potensi & Direktori</span>
                    <p class="text-2xl font-black text-[#0c3837] mt-1">6 Sektor Unggulan</p>
                    <p class="text-xs text-[#64748b] mt-1">Pertanian, Kriya, Wisata Desa</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between text-xs font-bold text-blue-600 group-hover:text-[#0c3837]">
                    <span>Direktori Lengkap</span>
                    <span>&rarr;</span>
                </div>
            </a>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 4: LAYANAN PERSURATAN INSTAN (Verified Test Text Match)           -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold text-[#10b981] tracking-wider uppercase mb-3">
                <span class="w-2 h-2 rounded-full bg-[#10b981]"></span>
                <span>Layanan Cepat & Mandiri</span>
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-[#0c3837] tracking-tight">
                Layanan Persuratan Instan
            </h2>
            <p class="text-sm sm:text-base text-[#64748b] mt-2">
                Pilih format dokumen permohonan yang Anda butuhkan dan ajukan secara online tanpa harus antre lama di loket kantor desa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredTemplates as $template)
                <div class="bg-white rounded-[2rem] p-5 sm:p-6 border border-[#e1ede8] shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <!-- Cover Image with Badges -->
                        <div class="rounded-2xl overflow-hidden h-48 w-full mb-5 bg-[#e2f0ed] relative shadow-inner">
                            <img src="{{ $template->image ?? asset('images/public/kantor-desa.jpg') }}" 
                                 alt="{{ $template->nama_surat }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-black text-[#0c3837] shadow-xs">
                                {{ $template->kode_surat ?? 'DIGIDES' }}
                            </span>
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-[11px] font-bold text-white bg-black/40 backdrop-blur-md px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>{{ $template->estimasi ?? '15 Menit' }}</span>
                            </div>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors line-clamp-1">
                            {{ $template->nama_surat }}
                        </h3>
                        <p class="text-xs text-[#64748b] mt-2 leading-relaxed line-clamp-3">
                            {{ $template->deskripsi ?? 'Permohonan surat resmi kependudukan yang diproses secara digital, aman, dan ber-QR Code.' }}
                        </p>

                        <!-- Highlights Pills -->
                        <div class="flex flex-wrap gap-1.5 mt-4 pt-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#f0f7f5] text-[#0c3837] text-[10px] font-semibold">Tersedia Online</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold">{{ $template->biaya ?? 'Gratis / Rp 0' }}</span>
                        </div>
                    </div>

                    <!-- Single Clean Action CTA -->
                    <div class="pt-5 mt-5 border-t border-[#f1f5f4]">
                        <a href="{{ route('public.layanan', ['q' => $template->kode_surat ?? $template->nama_surat]) }}" 
                           class="w-full py-2.5 px-4 rounded-xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-colors flex items-center justify-center group-hover:shadow-md">
                            <span>Ajukan Surat Sekarang</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Full Catalog Link -->
        <div class="text-center mt-10">
            <a href="{{ route('public.layanan') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#e2f0ed] hover:bg-[#10b981] hover:text-white text-sm font-bold text-[#0c3837] transition-all duration-200 shadow-xs hover:scale-102">
                <span>Lihat Seluruh Katalog Surat Kependudukan (15+ Format Dokumen)</span>
            </a>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 5: BERITA & PENGUMUMAN RESMI PEMERINTAH DESA                      -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold text-[#10b981] tracking-wider uppercase mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#10b981]"></span>
                    <span>Warta Terkini</span>
                </span>
                <h2 class="text-3xl font-black text-[#0c3837] tracking-tight">Kabar & Agenda {{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}</h2>
                <p class="text-sm text-[#64748b] mt-1">Kegiatan pembangunan, pengumuman resmi, dan aspirasi masyarakat desa.</p>
            </div>
            <a href="{{ route('public.berita') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#10b981] hover:text-[#0c3837] transition-colors shrink-0">
                <span>Lihat Semua Berita</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestNews as $news)
                <article class="bg-white rounded-3xl border border-[#e1ede8] overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
                    <div>
                        <!-- Thumbnail Image (Using the correct image_url accessor) -->
                        <div class="h-48 overflow-hidden bg-[#e2f0ed] relative">
                            <img src="{{ $news->image_url }}" 
                                 alt="{{ $news->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-[#0c3837]/90 backdrop-blur-md text-emerald-300 text-[10px] font-bold uppercase tracking-wider border border-white/10">
                                {{ $news->kategori ?? 'Berita' }}
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3 text-xs text-[#64748b]">
                                <span>{{ $news->published_at ? $news->published_at->format('d M Y') : date('d M Y') }}</span>
                                <span>&bull;</span>
                                <span>{{ $news->views_count ?? 120 }} kali dibaca</span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('public.berita.detail', $news->slug) }}">{{ $news->judul }}</a>
                            </h3>
                            <p class="text-xs text-[#64748b] mt-2 line-clamp-3 leading-relaxed">
                                {{ $news->ringkasan ?? strip_tags($news->konten) }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-3 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#64748b]">Oleh {{ $news->penulis ?? 'Pemerintah Desa' }}</span>
                        <a href="{{ route('public.berita.detail', $news->slug) }}" class="text-xs font-bold text-[#10b981] hover:text-[#0c3837] flex items-center gap-1 transition-colors">
                            <span>Baca Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-[#e1ede8]">
                    <p class="text-sm text-[#64748b]">Belum ada berita yang dipublikasikan saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 6: EXECUTIVE SAMBUTAN KEPALA DESA                                 -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#0c3837] via-[#114443] to-[#082424] rounded-[2.5rem] lg:rounded-[3rem] p-8 sm:p-12 lg:p-16 text-white relative shadow-2xl overflow-hidden border border-[#1b5e5c]/40">
            <!-- Ambient Lighting -->
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                
                <!-- Portrait -->
                <div class="lg:col-span-4 flex justify-center">
                    <div class="relative w-64 h-80 rounded-3xl overflow-hidden border-2 border-emerald-400/30 shadow-2xl bg-black/40 group">
                        <img src="{{ asset('images/public/kades-hero.jpg') }}" alt="Kepala Desa" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/95 via-black/70 to-transparent text-center">
                            <h4 class="text-sm font-bold text-white">{{ ucwords($desa->nama_kades ?? 'H. Rahmat Hidayat, S.IP') }}</h4>
                            <p class="text-[11px] text-emerald-300 font-semibold mt-0.5">Kepala Desa {{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 text-xs font-bold text-emerald-300 uppercase tracking-wider border border-white/10">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <span>Sambutan Kepala Desa</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight">
                        "Bersama Membangun Desa yang Maju, Berdaya Saing, dan Melayani Sepenuh Hati."
                    </h2>

                    <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                        Selamat datang di portal resmi <strong>{{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}</strong>. Website ini hadir sebagai bentuk komitmen nyata aparatur desa dalam menghadirkan keterbukaan informasi publik, kemudahan pelayanan surat menyurat secara mandiri tanpa pungli, serta wadah interaksi aktif antara pemerintah desa dengan seluruh lapisan masyarakat.
                    </p>

                    <p class="text-xs sm:text-sm text-white/80 leading-relaxed">
                        Kami mengajak seluruh warga untuk bersama-sama mengawal transparansi pembangunan desa, memaksimalkan potensi ekonomi UMKM, dan memanfaatkan teknologi digital untuk kesejahteraan bersama.
                    </p>

                    <div class="pt-3 flex flex-wrap gap-3.5">
                        <a href="{{ route('public.profil') }}" class="px-6 py-3 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs transition-all shadow-md hover:scale-102">
                            Struktur Organisasi & Visi Misi &rarr;
                        </a>
                        <a href="{{ route('public.kontak') }}" class="px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs transition-all hover:scale-102">
                            Hubungi Kantor Desa
                        </a>
                        <button type="button" @click="$dispatch('open-pengaduan')" class="px-6 py-3 rounded-full bg-rose-500/80 hover:bg-rose-600 text-white font-bold text-xs transition-all shadow-sm">
                            Sampaikan Aspirasi
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 7: QUICK HELPLINES & TRANSPARENCY CARDS                           -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="p-6 rounded-3xl bg-white border border-[#e1ede8] shadow-xs hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-[#0c3837]">Layanan Mandiri Warga</h4>
                <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">Daftarkan akun kependudukan warga untuk memantau proses verifikasi permohonan surat secara terpusat.</p>
                <div class="mt-4 pt-3 border-t border-slate-50">
                    <a href="{{ route('register') }}" class="text-xs font-bold text-[#10b981] hover:underline">Registrasi Akun Baru &rarr;</a>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-[#e1ede8] shadow-xs hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-[#0c3837]">PPID & Dokumen Publik</h4>
                <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">Unduh salinan dokumen Peraturan Desa (Perdes), Keputusan Kepala Desa, dan Laporan Kinerja secara terbuka.</p>
                <div class="mt-4 pt-3 border-t border-slate-50">
                    <a href="{{ route('public.ppid') }}" class="text-xs font-bold text-amber-600 hover:underline">Cek Dokumen PPID &rarr;</a>
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-[#e1ede8] shadow-xs hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-[#0c3837]">Pusat Pengaduan & Aspirasi</h4>
                <p class="text-xs text-[#64748b] mt-1.5 leading-relaxed">Sampaikan masukan, kendala pelayanan kantor, atau kerusakan fasilitas umum secara langsung kepada aparat desa.</p>
                <div class="mt-4 pt-3 border-t border-slate-50">
                    <button type="button" @click="$dispatch('open-pengaduan')" class="text-xs font-bold text-rose-600 hover:underline">Buat Laporan Sekarang &rarr;</button>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
