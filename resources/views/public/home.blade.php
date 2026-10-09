@extends('layouts.public')

@section('content')
<div class="space-y-16 lg:space-y-24">

    <!-- ========================================================================= -->
    <!-- SECTION 1: HERO SECTION (Reference Design Adaptation)                     -->
    <!-- ========================================================================= -->
    <section class="relative pt-6 lg:pt-10 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-[#0c3837] via-[#114443] to-[#082424] rounded-[2.5rem] lg:rounded-[3rem] p-8 sm:p-12 lg:p-16 text-white relative shadow-xl overflow-hidden border border-[#1b5e5c]/40">
                
                <!-- Background Ambient Blobs -->
                <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-[#10b981]/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-[#d4ed31]/10 blur-3xl pointer-events-none"></div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center relative z-10">
                    
                    <!-- Left Hero Text Content -->
                    <div class="lg:col-span-7 space-y-6 text-left">
                        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold text-[#d4ed31] tracking-wide uppercase">
                            <span class="w-2 h-2 rounded-full bg-[#10b981] animate-ping"></span>
                            Portal Resmi Pemerintah {{ $desa->nama_desa }}
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-[1.15]">
                            Mitra Terpercaya <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4ed31] via-[#34d399] to-[#10b981]">
                                Pelayanan & Administrasi
                            </span> <br>
                            Masyarakat Desa
                        </h1>

                        <p class="text-base sm:text-lg text-[#8bc3b8] max-w-xl leading-relaxed">
                            Nikmati kemudahan pembuatan surat pengantar, layanan kependudukan mandiri, keterbukaan informasi publik, dan perkembangan desa dalam satu sentuhan digital terpadu.
                        </p>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="{{ route('public.layanan') }}" 
                               class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-sm shadow-lg shadow-[#10b981]/25 hover:shadow-xl transition-all duration-200 hover:scale-102">
                                <span>Ajukan Surat Online</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            <a href="{{ route('public.profil') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white/10 hover:bg-white/15 backdrop-blur-sm border border-white/20 text-white font-semibold text-sm transition-all">
                                <span>Profil & Sejarah</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Hero Portrait of Kepala Desa (Reference Match) -->
                    <div class="lg:col-span-5 flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-sm">
                            <!-- Glow Backdrop -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#10b981]/30 to-transparent rounded-[2.5rem] blur-xl"></div>
                            
                            <!-- Main Portrait Frame -->
                            <div class="relative rounded-[2.5rem] overflow-hidden border-2 border-white/20 shadow-2xl bg-[#0c3837]/60 aspect-[3/4]">
                                <img src="{{ asset('images/public/kades-hero.jpg') }}" 
                                     alt="Kepala Desa {{ $desa->nama_desa }}" 
                                     class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500">
                                
                                <!-- Floating Officer Badge Overlay -->
                                <div class="absolute bottom-4 inset-x-4 p-4 rounded-2xl bg-white/90 backdrop-blur-md text-[#0c3837] shadow-lg border border-white/50 flex items-center justify-between">
                                    <div>
                                        <h2 class="text-sm font-black leading-tight">{{ $desa->nama_kades ?? 'H. Rahmat Hidayat, S.IP' }}</h2>
                                        <p class="text-[11px] font-semibold text-[#10b981]">Kepala Desa {{ $desa->nama_desa }}</p>
                                    </div>
                                    <span class="w-9 h-9 rounded-xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center font-bold text-xs shadow-xs">
                                        KADES
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- FLOATING SEARCH & FILTER BAR (Pill Container in Tosca/Mint Accent)         -->
        <!-- ========================================================================= -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-10 relative z-20">
            <div class="bg-gradient-to-r from-[#10b981] via-[#2dd4bf] to-[#10b981] p-3 sm:p-4 rounded-[2rem] shadow-xl border border-emerald-300/40">
                <form action="{{ route('public.layanan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                    
                    <!-- Field 1: Kategori / Tipe Surat -->
                    <div class="bg-white rounded-2xl px-4 py-2.5 flex items-center gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <label class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Kategori Surat</label>
                            <input type="text" name="q" placeholder="Cari jenis surat..." class="w-full text-xs font-bold text-[#0c3837] focus:outline-none placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Field 2: Perihal / Kebutuhan -->
                    <div class="bg-white rounded-2xl px-4 py-2.5 flex items-center gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <label class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Keperluan Warga</label>
                            <input type="text" placeholder="Contoh: SKCK, Usaha, Domisili" class="w-full text-xs font-bold text-[#0c3837] focus:outline-none placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Field 3: Lacak Pengajuan / NIK -->
                    <div class="bg-white rounded-2xl px-4 py-2.5 flex items-center gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <label class="block text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Lacak Berkas</label>
                            <input type="text" placeholder="Nomor Resi / Surat" class="w-full text-xs font-bold text-[#0c3837] focus:outline-none placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-2xl bg-[#0c3837] hover:bg-[#082424] text-white font-extrabold text-sm shadow-md transition-all duration-200 hover:scale-102 flex items-center justify-center gap-2 cursor-pointer">
                            <span>Cari Layanan</span>
                            <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 2: INSTANT SERVICES (3-Cards Grid from Reference Image)           -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-[#10b981] tracking-widest uppercase mb-2 block">Layanan Cepat & Mandiri</span>
            <h2 class="text-3xl sm:text-4xl font-black text-[#0c3837] tracking-tight">
                Layanan Persuratan Instan
            </h2>
            <p class="text-sm sm:text-base text-[#64748b] mt-2">
                Pilih format dokumen yang Anda butuhkan dan unduh secara mandiri setelah diverifikasi oleh petugas desa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Card 1: SKCK & Pengantar -->
            <div class="bg-white rounded-[2rem] p-5 border border-[#e1ede8] shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                <div>
                    <div class="rounded-2xl overflow-hidden h-52 w-full mb-5 bg-[#e2f0ed] relative">
                        <img src="{{ asset('images/public/kantor-desa.jpg') }}" 
                             alt="Surat Pengantar SKCK" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-bold text-[#0c3837] shadow-xs">
                            Est. 5 Menit
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        Surat Pengantar SKCK
                    </h3>
                    <p class="text-xs text-[#64748b] mt-2 leading-relaxed">
                        Pengantar permohonan Surat Keterangan Catatan Kepolisian untuk melamar pekerjaan, pendaftaran instansi, atau pendidikan.
                    </p>
                </div>
                <div class="pt-6 mt-4 border-t border-[#f1f5f4] flex items-center justify-between">
                    <a href="{{ route('public.layanan') }}" 
                       class="px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-colors">
                        Lihat Syarat
                    </a>
                    <a href="{{ route('public.layanan') }}" class="w-9 h-9 rounded-full bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center hover:bg-[#10b981] hover:text-white transition-colors">
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Keterangan Usaha (SKU) -->
            <div class="bg-white rounded-[2rem] p-5 border border-[#e1ede8] shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                <div>
                    <div class="rounded-2xl overflow-hidden h-52 w-full mb-5 bg-[#e2f0ed] relative">
                        <img src="{{ asset('images/public/layanan-kiosk.jpg') }}" 
                             alt="Surat Keterangan Usaha" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-bold text-[#0c3837] shadow-xs">
                            Est. 3 Menit
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        Surat Keterangan Usaha
                    </h3>
                    <p class="text-xs text-[#64748b] mt-2 leading-relaxed">
                        Legalitas surat keterangan usaha mikro warga untuk pengajuan KUR perbankan, izin operasional, atau bantuan UMKM desa.
                    </p>
                </div>
                <div class="pt-6 mt-4 border-t border-[#f1f5f4] flex items-center justify-between">
                    <a href="{{ route('public.layanan') }}" 
                       class="px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-colors">
                        Lihat Syarat
                    </a>
                    <a href="{{ route('public.layanan') }}" class="w-9 h-9 rounded-full bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center hover:bg-[#10b981] hover:text-white transition-colors">
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Keterangan Domisili -->
            <div class="bg-white rounded-[2rem] p-5 border border-[#e1ede8] shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                <div>
                    <div class="rounded-2xl overflow-hidden h-52 w-full mb-5 bg-[#e2f0ed] relative">
                        <img src="{{ asset('images/public/desa-alam.jpg') }}" 
                             alt="Surat Keterangan Domisili" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-bold text-[#0c3837] shadow-xs">
                            Est. 3 Menit
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        Surat Keterangan Domisili
                    </h3>
                    <p class="text-xs text-[#64748b] mt-2 leading-relaxed">
                        Surat bukti keterangan bertempat tinggal resmi di wilayah desa untuk keperluan administrasi perbankan atau pekerjaan.
                    </p>
                </div>
                <div class="pt-6 mt-4 border-t border-[#f1f5f4] flex items-center justify-between">
                    <a href="{{ route('public.layanan') }}" 
                       class="px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-colors">
                        Lihat Syarat
                    </a>
                    <a href="{{ route('public.layanan') }}" class="w-9 h-9 rounded-full bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center hover:bg-[#10b981] hover:text-white transition-colors">
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 3: SPLIT 2-COLUMN FEATURE SECTION (Reference Image Match)         -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 lg:p-14 border border-[#e1ede8] shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                
                <!-- Left Column: Service Facility Photo -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-[2rem] overflow-hidden shadow-lg border border-[#e1ede8] aspect-[3/4] bg-[#e2f0ed]">
                        <img src="{{ asset('images/public/pelayanan-frontdesk.jpg') }}" 
                             alt="Pelayanan Ramah Kantor Desa" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="text-xs font-bold text-[#d4ed31] uppercase tracking-wider">Standar Pelayanan Prima</span>
                            <h4 class="text-lg font-bold">Meja Pelayanan Terpadu Satu Pintu</h4>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Features & Highlights -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
                        <span class="w-2 h-2 rounded-full bg-[#10b981]"></span>
                        DIGIDES Terpadu v3
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-black text-[#0c3837] tracking-tight leading-tight">
                        Cari & Ajukan Surat Kebutuhan Anda Secara Cepat
                    </h2>

                    <p class="text-sm sm:text-base text-[#64748b] leading-relaxed">
                        Kami menyederhanakan birokrasi pemerintahan desa agar setiap warga memperoleh hak pelayanan yang adil, cepat, dan transparan tanpa hambatan jarak maupun waktu.
                    </p>

                    <!-- 4 Icon Badges Row (Matching reference bottom icons) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4">
                        
                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-center space-y-2 hover:bg-[#e2f0ed] transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <span class="block text-xs font-bold text-[#0c3837]">Notifikasi Cepat</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-center space-y-2 hover:bg-[#e2f0ed] transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#0c3837] text-[#10b981] flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="block text-xs font-bold text-[#0c3837]">Data Warga</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-center space-y-2 hover:bg-[#e2f0ed] transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="block text-xs font-bold text-[#0c3837]">Lacak 24 Jam</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-center space-y-2 hover:bg-[#e2f0ed] transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-[#0c3837] text-[#10b981] flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <span class="block text-xs font-bold text-[#0c3837]">Arsip Resmi</span>
                        </div>

                    </div>

                    <div class="pt-4">
                        <a href="{{ route('public.lacak-surat') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#114443] hover:bg-[#0c3837] text-white font-bold text-xs shadow-sm transition-colors">
                            <span>Pelajari Prosedur Pengajuan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 4: BERITA & PENGUMUMAN TERBARU                                    -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-[#10b981] tracking-widest uppercase mb-1 block">Warta & Kabar Desa</span>
                <h2 class="text-3xl font-black text-[#0c3837] tracking-tight">Berita & Pengumuman Terbaru</h2>
            </div>
            <a href="{{ route('public.berita') }}" 
               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#10b981] hover:text-[#0c3837] transition-colors">
                <span>Lihat Semua Berita</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestNews as $news)
                <article class="bg-white rounded-[2rem] overflow-hidden border border-[#e1ede8] shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="h-48 overflow-hidden bg-[#e2f0ed] relative">
                            <img src="{{ $news->image_url }}" 
                                 alt="{{ $news->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[11px] font-bold text-[#0c3837] shadow-xs">
                                {{ $news->kategori }}
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="text-[11px] font-semibold text-[#64748b] mb-2 flex items-center gap-2">
                                <span>{{ $news->published_at ? $news->published_at->isoFormat('D MMMM Y') : 'Terbaru' }}</span>
                                <span>•</span>
                                <span>{{ $news->penulis }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors leading-snug line-clamp-2">
                                <a href="{{ route('public.berita.detail', $news->slug) }}">
                                    {{ $news->judul }}
                                </a>
                            </h3>
                            <p class="text-xs text-[#64748b] mt-2 line-clamp-3 leading-relaxed">
                                {{ $news->ringkasan }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-2">
                        <a href="{{ route('public.berita.detail', $news->slug) }}" 
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-[#e1ede8]">
                    <p class="text-sm text-[#64748b]">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION 5: STATISTIK & DEMOGRAFI DESA                                     -->
    <!-- ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#0c3837] rounded-[2.5rem] p-8 sm:p-12 text-white shadow-lg">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold text-[#d4ed31] uppercase tracking-wider block mb-1">Transparansi Data</span>
                <h3 class="text-2xl sm:text-3xl font-black">Statistik Kependudukan & Wilayah</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-black text-[#d4ed31]">{{ number_format($totalPenduduk, 0, ',', '.') }}</span>
                    <span class="block text-xs text-[#8bc3b8] mt-1 font-semibold">Total Penduduk</span>
                </div>
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-black text-[#34d399]">{{ number_format($totalLakiLaki, 0, ',', '.') }}</span>
                    <span class="block text-xs text-[#8bc3b8] mt-1 font-semibold">Laki-Laki</span>
                </div>
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-black text-rose-300">{{ number_format($totalPerempuan, 0, ',', '.') }}</span>
                    <span class="block text-xs text-[#8bc3b8] mt-1 font-semibold">Perempuan</span>
                </div>
                <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-black text-amber-300">{{ $totalAparatur }}</span>
                    <span class="block text-xs text-[#8bc3b8] mt-1 font-semibold">Perangkat Desa</span>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
