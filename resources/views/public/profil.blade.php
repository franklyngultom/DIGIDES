@extends('layouts.public')

@section('content')
<div x-data="{ activeTab: 'sejarah' }" class="space-y-12">

    <!-- ========================================================================= -->
    <!-- 1. DISTINCT PAGE HEADER / HERO (Differentiated from Home)                 -->
    <!-- ========================================================================= -->
    <section class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#082424] text-white py-14 sm:py-20 border-b border-[#1b5e5c]/40 relative overflow-hidden">
        <!-- Background Ambient Accents -->
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-emerald-500/10 blur-2xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-[#8bc3b8] mb-4">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-semibold">Profil Desa</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Mengenal Desa Lebih Dekat
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Profil & Struktur Pemerintah {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Dokumentasi resmi mengenai sejarah peradaban, visi misi pembangunan jangka menengah, struktur jajaran kepengurusan aparatur, wilayah geografis, dan kelembagaan desa.
                </p>
            </div>

            <!-- Profile Interactive Sub-Navigation Tabs -->
            <div class="flex flex-wrap items-center gap-2 pt-8 border-t border-white/10 mt-8">
                <button type="button" 
                        @click="activeTab = 'sejarah'"
                        :class="activeTab === 'sejarah' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Sejarah & Kilas Balik
                </button>
                <button type="button" 
                        @click="activeTab = 'visimisi'"
                        :class="activeTab === 'visimisi' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Visi & Misi Desa
                </button>
                <button type="button" 
                        @click="activeTab = 'aparatur'"
                        :class="activeTab === 'aparatur' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Struktur & Aparatur
                </button>
                <button type="button" 
                        @click="activeTab = 'geografis'"
                        :class="activeTab === 'geografis' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Wilayah & Geografis
                </button>
                <button type="button" 
                        @click="activeTab = 'lembaga'"
                        :class="activeTab === 'lembaga' ? 'bg-white text-[#0c3837] font-bold shadow-md' : 'bg-white/10 text-white hover:bg-white/20 font-medium'"
                        class="px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    Lembaga Kemasyarakatan
                </button>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. TAB CONTENTS CONTAINER                                                 -->
    <!-- ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- TAB 1: SEJARAH & KILAS BALIK -->
        <div x-show="activeTab === 'sejarah'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Asal Usul & Warisan Leluhur</span>
                    <h2 class="text-3xl font-black text-[#0c3837] leading-tight">
                        Jejak Sejarah Terbentuknya {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}
                    </h2>
                    <p class="text-sm text-[#475569] leading-relaxed text-justify">
                        {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }} bermula dari permukiman agraris yang dirintis oleh para tetua terdahulu di kawasan subur yang dialiri sumber mata air alami. Nama desa ini sarat akan filosofi ketenteraman, kesempurnaan hidup, serta keharmonisan gotong royong antar warga lintas generasi.
                    </p>
                    <p class="text-sm text-[#475569] leading-relaxed text-justify">
                        Seiring berjalannya waktu, wilayah ini berkembang pesat menjadi pusat pertanian komoditas unggulan dan perkebunan rakyat. Perpaduan adat istiadat yang kental serta keterbukaan terhadap kemajuan zaman menjadikan desa ini mampu bertransformasi menjadi desa percontohan tata kelola digital terpadu di tingkat regional.
                    </p>
                    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-[#0c3837] space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider block text-[#10b981]">Nilai Luhur Desa</span>
                        <p class="text-sm font-semibold italic">"Rukun Warga, Maju Bersama, Lestari Alam, dan Amanah dalam Pengabdian."</p>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden shadow-xl border-2 border-white aspect-[4/3] bg-[#e2f0ed]">
                        <img src="{{ asset('images/public/desa-alam.jpg') }}" alt="Pemandangan Sejarah Desa" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Linimasa Kepemimpinan -->
            <div class="bg-white p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-6">
                <div>
                    <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Rekam Jejak Kepemimpinan</span>
                    <h3 class="text-2xl font-bold text-[#0c3837]">Daftar Kepala Desa dari Masa ke Masa</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-2">
                        <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">Periode 2008 - 2014</span>
                        <h4 class="text-base font-bold text-[#0c3837]">H. Sutan Syahrir</h4>
                        <p class="text-xs text-[#64748b]">Perintisan infrastruktur jalan poros desa dan fasilitas balai pertemuan warga.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-2">
                        <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">Periode 2014 - 2020</span>
                        <h4 class="text-base font-bold text-[#0c3837]">Drs. H. M. Yusuf</h4>
                        <p class="text-xs text-[#64748b]">Pemberdayaan kelompok tani, pembentukan BUMDes, dan sarana kesehatan poskesdes.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2">
                        <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-bold">Periode 2020 - Sekarang</span>
                        <h4 class="text-base font-bold text-[#0c3837]">{{ $desa->nama_kades ?? 'H. Rahmat Hidayat, S.IP' }}</h4>
                        <p class="text-xs text-[#64748b]">Transformasi digital DIGIDES, kemandirian ekonomi desa (IDM Desa Mandiri), dan pelayanan persuratan mandiri.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 2: VISI & MISI DESA -->
        <div x-show="activeTab === 'visimisi'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-10">
            
            <!-- Visi Card -->
            <div class="bg-gradient-to-br from-[#0c3837] via-[#114443] to-[#082424] rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden border border-[#1b5e5c]/40">
                <div class="max-w-3xl space-y-4">
                    <span class="text-xs font-bold text-emerald-300 uppercase tracking-wider block">Visi Pembangunan Desa</span>
                    <h2 class="text-2xl sm:text-4xl font-black leading-tight text-white">
                        "Terwujudnya {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }} yang Maju, Religius, Sejahtera, Transparan, dan Berdaya Saing Berbasis Pelayanan Digital."
                    </h2>
                    <p class="text-sm text-[#8bc3b8] leading-relaxed">
                        Visi jangka menengah pemerintah desa untuk meletakkan fondasi kemajuan berkeadilan, di mana setiap warga dapat mengakses layanan publik dengan cepat, transparan, dan bermartabat.
                    </p>
                </div>
            </div>

            <!-- Misi 5 Pilar -->
            <div class="space-y-6">
                <div>
                    <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Langkah Konkret</span>
                    <h3 class="text-2xl font-black text-[#0c3837]">Misi Strategis Pemerintahan Desa</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            01
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Pelayanan Publik Digital</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Meningkatkan profesionalisme aparatur dan transparansi administrasi persuratan mandiri bebas calo melalui sistem DIGIDES terintegrasi.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            02
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Transparansi Anggaran</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Mendorong keterbukaan penuh atas alokasi Dana Desa (APBDes) melalui musyawarah pembangunan partisipatif dan publikasi berkala.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            03
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Kemandirian Ekonomi BUMDes</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Mengembangkan potensi pertanian rakyat, sentra UMKM, dan lini usaha BUMDes untuk menyerap tenaga kerja lokal dan meningkatkan PADes.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            04
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Infrastruktur & Lingkungan</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Peningkatan jalan lingkungan, drainase pemukiman bebas banjir, fasilitas sanitasi layak, serta pemeliharaan kelestarian alam desa.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            05
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Pendidikan, Agama & Sosial</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Memperkokoh kerukunan antar umat beragama, mendukung pendidikan usia dini/TPA, serta pengentasan kemiskinan dan penanganan stunting.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-sm">
                            06
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837]">Partisipasi Pemuda & Perempuan</h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Mengoptimalkan peran aktif Karang Taruna dan Tim Penggerak PKK dalam kreativitas wirausaha, olahraga, dan ketahanan keluarga.
                        </p>
                    </div>

                </div>
            </div>

        </div>

        <!-- TAB 3: STRUKTUR & APARATUR PEMERINTAH DESA -->
        <div x-show="activeTab === 'aparatur'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Bagan Tata Kelola</span>
                <h3 class="text-3xl font-black text-[#0c3837]">Jajaran Perangkat & Aparatur Desa</h3>
                <p class="text-xs sm:text-sm text-[#64748b]">Aparatur yang siap melayani kebutuhan administratif dan pembangunan warga.</p>
            </div>

            <!-- Top Hierarchy: Kepala Desa & Sekretaris Desa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Kepala Desa -->
                <div class="bg-white rounded-3xl p-6 border-2 border-[#10b981] shadow-lg text-center space-y-4">
                    <div class="w-32 h-32 mx-auto rounded-3xl overflow-hidden shadow-md border-2 border-emerald-100 bg-[#0c3837]">
                        <img src="{{ asset('images/public/kades-hero.jpg') }}" alt="Kepala Desa" class="w-full h-full object-cover object-top">
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full bg-[#e2f0ed] text-[#0c3837] text-[10px] font-bold uppercase tracking-wider">Pimpinan Desa</span>
                        <h4 class="text-lg font-black text-[#0c3837] mt-2">{{ $desa->nama_kades ?? 'H. Rahmat Hidayat, S.IP' }}</h4>
                        <p class="text-xs font-bold text-[#10b981]">Kepala Desa</p>
                        <p class="text-[11px] text-[#64748b] mt-1">NIP/SK: {{ $desa->nip_kades ?? '197508172005011003' }}</p>
                    </div>
                    <p class="text-xs text-[#64748b] border-t border-slate-100 pt-3">
                        Bertanggung jawab atas jalannya tata pemerintahan, pembangunan fisik & sosial, pembinaan kemasyarakatan, dan perwakilan desa.
                    </p>
                </div>

                <!-- Sekretaris Desa -->
                <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-md text-center space-y-4">
                    <div class="w-32 h-32 mx-auto rounded-3xl overflow-hidden shadow-md border-2 border-slate-100 bg-[#e2f0ed]">
                        <img src="{{ asset('images/public/pelayanan-frontdesk.jpg') }}" alt="Sekretaris Desa" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider">Sekretariat</span>
                        <h4 class="text-lg font-black text-[#0c3837] mt-2">Drs. Ahmad Fadillah</h4>
                        <p class="text-xs font-bold text-[#4fa394]">Sekretaris Desa</p>
                        <p class="text-[11px] text-[#64748b] mt-1">NIP/SK: 198204122009021004</p>
                    </div>
                    <p class="text-xs text-[#64748b] border-t border-slate-100 pt-3">
                        Memimpin pengelolaan administrasi persuratan, tata usaha, perencanaan anggaran, dan koordinasi staf teknis perizinan.
                    </p>
                </div>

            </div>

            <!-- Staf Kaur, Kasi, dan Kadus -->
            <div class="space-y-4">
                <h4 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-2">Kepala Seksi (Kasi) & Kepala Urusan (Kaur)</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($aparaturList as $ap)
                        @php
                            $namaAparat = $ap->penduduk?->nama ?? 'Aparatur Desa';
                            $fotoAparat = $ap->penduduk?->foto_url ?? null;
                        @endphp
                        <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs text-center space-y-3 hover:border-[#10b981] transition-colors">
                            <div class="w-20 h-20 mx-auto rounded-2xl overflow-hidden bg-[#e2f0ed] shadow-xs">
                                @if($fotoAparat)
                                    <img src="{{ $fotoAparat }}" alt="{{ $namaAparat }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#114443] to-[#0c3837] text-white text-lg font-bold">
                                        {{ substr($namaAparat, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0c3837]">{{ $namaAparat }}</h5>
                                <p class="text-xs font-semibold text-[#4fa394]">{{ $ap->jabatan }}</p>
                                <p class="text-[10px] text-[#64748b] mt-0.5">NIP: {{ $ap->nip ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback Aparatur -->
                        <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs text-center space-y-3">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-[#e2f0ed] flex items-center justify-center text-xl font-bold text-[#114443]">B</div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0c3837]">Bambang Irawan</h5>
                                <p class="text-xs font-semibold text-[#4fa394]">Kaur Keuangan</p>
                                <p class="text-[10px] text-[#64748b]">SK: 140/SK-KADES/2021</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs text-center space-y-3">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-[#e2f0ed] flex items-center justify-center text-xl font-bold text-[#114443]">S</div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0c3837]">Siti Maryam, S.Tr.Sos</h5>
                                <p class="text-xs font-semibold text-[#4fa394]">Kasi Pelayanan</p>
                                <p class="text-[10px] text-[#64748b]">SK: 141/SK-KADES/2021</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs text-center space-y-3">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-[#e2f0ed] flex items-center justify-center text-xl font-bold text-[#114443]">R</div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0c3837]">Rudi Hermanto</h5>
                                <p class="text-xs font-semibold text-[#4fa394]">Kasi Kesejahteraan</p>
                                <p class="text-[10px] text-[#64748b]">SK: 142/SK-KADES/2022</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs text-center space-y-3">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-[#e2f0ed] flex items-center justify-center text-xl font-bold text-[#114443]">H</div>
                            <div>
                                <h5 class="text-sm font-bold text-[#0c3837]">Hasan Basri</h5>
                                <p class="text-xs font-semibold text-[#4fa394]">Kasi Pemerintahan</p>
                                <p class="text-[10px] text-[#64748b]">SK: 143/SK-KADES/2022</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- TAB 4: WILAYAH & GEOGRAFIS -->
        <div x-show="activeTab === 'geografis'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-5">
                    <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Kondisi Topografi</span>
                    <h3 class="text-3xl font-black text-[#0c3837]">Batas Wilayah & Tata Guna Lahan</h3>
                    <p class="text-sm text-[#64748b] leading-relaxed">
                        {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }} terletak pada ketinggian rata-rata 35-60 meter di atas permukaan laut dengan iklim tropis basah yang sangat kondusif bagi sektor perkebunan kelapa sawit, karet, dan pertanian holtikultura.
                    </p>

                    <!-- Batas 4 Arah Mata Angin -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-2xl bg-white border border-[#e1ede8]">
                            <span class="text-[#64748b] block font-semibold">Batas Sebelah Utara:</span>
                            <span class="font-bold text-[#0c3837]">Desa Balai Jaya Kota</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-white border border-[#e1ede8]">
                            <span class="text-[#64748b] block font-semibold">Batas Sebelah Selatan:</span>
                            <span class="font-bold text-[#0c3837]">Kecamatan Bagan Sinembah</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-white border border-[#e1ede8]">
                            <span class="text-[#64748b] block font-semibold">Batas Sebelah Timur:</span>
                            <span class="font-bold text-[#0c3837]">Desa Pasir Putih Barat</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-white border border-[#e1ede8]">
                            <span class="text-[#64748b] block font-semibold">Batas Sebelah Barat:</span>
                            <span class="font-bold text-[#0c3837]">Wilayah Hutan Lindung Rakyat</span>
                        </div>
                    </div>
                </div>

                <!-- Peta Visual Card -->
                <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-xs space-y-4">
                    <div class="rounded-2xl overflow-hidden aspect-[16/10] bg-[#e2f0ed] relative">
                        <img src="{{ asset('images/public/desa-alam.jpg') }}" alt="Peta Wilayah" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-[#0c3837]/30 flex items-center justify-center">
                            <span class="px-4 py-2 rounded-xl bg-white/90 text-[#0c3837] font-bold text-xs shadow-md">
                                Peta Administratif Terpadu
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="p-3 rounded-2xl bg-[#f7faf9]">
                            <span class="text-[#64748b] block text-[10px]">Luas Wilayah</span>
                            <span class="font-bold text-[#0c3837]">2.450 Ha</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-[#f7faf9]">
                            <span class="text-[#64748b] block text-[10px]">Jumlah Dusun</span>
                            <span class="font-bold text-[#0c3837]">4 Dusun</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-[#f7faf9]">
                            <span class="text-[#64748b] block text-[10px]">Total RT / RW</span>
                            <span class="font-bold text-[#0c3837]">16 RT / 4 RW</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orbitasi & Jarak Tempuh -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs">
                <h4 class="text-base font-bold text-[#0c3837] mb-4">Orbitasi & Aksesibilitas Transportasi</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-[#e2f0ed] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#10b981] text-white flex items-center justify-center font-bold">1</div>
                        <div>
                            <span class="text-[#64748b] block">Jarak ke Kantor Kecamatan</span>
                            <span class="text-sm font-bold text-[#0c3837]">6.5 KM (12 Menit)</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#e2f0ed] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#10b981] text-white flex items-center justify-center font-bold">2</div>
                        <div>
                            <span class="text-[#64748b] block">Jarak ke Ibu Kota Kabupaten</span>
                            <span class="text-sm font-bold text-[#0c3837]">48 KM (1 Jam 15 Menit)</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#e2f0ed] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#10b981] text-white flex items-center justify-center font-bold">3</div>
                        <div>
                            <span class="text-[#64748b] block">Jarak ke Ibu Kota Provinsi</span>
                            <span class="text-sm font-bold text-[#0c3837]">165 KM (3.5 Jam)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 5: LEMBAGA KEMASYARAKATAN DESA -->
        <div x-show="activeTab === 'lembaga'" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Kemitraan Pembangunan</span>
                <h3 class="text-3xl font-black text-[#0c3837]">Lembaga Kemasyarakatan Desa (LKD)</h3>
                <p class="text-xs sm:text-sm text-[#64748b]">Mitra strategis pemerintah desa dalam menyalurkan aspirasi dan memberdayakan warga.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                        BPD
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">Badan Permusyawaratan Desa</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Lembaga permusyawaratan perwakilan warga desa yang bertugas membahas dan menyepakati rancangan Perdes, menampung dan menyalurkan aspirasi, serta mengawasi kinerja Kades.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-amber-700">9 Anggota Aktif</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        LPMD
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">Lembaga Pemberdayaan Masyarakat</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Mitra pemerintah desa dalam menyusun rencana pembangunan partisipatif, menggerakkan swadaya gotong royong, dan menumbuhkan prakarsa warga.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-emerald-700">12 Pengurus Aktif</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                        PKK
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">Tim Penggerak PKK Desa</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Pelopor 10 program pokok PKK untuk mewujudkan keluarga sejahtera, kesehatan balita & ibu hamil, pencegahan stunting, serta ketahanan pangan rumah tangga.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-rose-700">25 Kader Aktif</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                        KT
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">Karang Taruna Tunas Muda</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Wadah pengembangan generasi muda desa di bidang kepemudaan, olahraga, seni budaya, serta kegiatan kewirausahaan produktif.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-blue-700">40 Anggota Pemuda</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                        BUMDes
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">BUMDes Mandiri Sejahtera</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Pilar ekonomi desa yang mengelola unit simpan pinjam, sarana produksi pertanian, logistik pangan, dan pemasaran produk UMKM warga.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-teal-700">4 Unit Usaha Aktif</span>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold">
                        LINMAS
                    </div>
                    <h4 class="text-base font-bold text-[#0c3837]">Satuan Perlindungan Masyarakat</h4>
                    <p class="text-xs text-[#64748b] leading-relaxed">
                        Garda terdepan keamanan dan ketertiban lingkungan desa, pos ronda malam, kesiapsiagaan tanggap darurat, dan pengamanan kegiatan sosial.
                    </p>
                    <span class="inline-block text-[11px] font-semibold text-indigo-700">18 Personel Linmas</span>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
