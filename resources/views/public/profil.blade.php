@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-16">

    <!-- Header Title -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Mengenal Lebih Dekat
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-[#0c3837] tracking-tight">
            Profil & Struktur Pemerintah {{ $desa->nama_desa }}
        </h1>
        <p class="text-sm sm:text-base text-[#64748b]">
            Informasi sejarah, visi, misi, data wilayah, serta jajaran aparatur yang mengabdi untuk kemakmuran warga.
        </p>
    </div>

    <!-- Visi & Misi Hero Card -->
    <div class="bg-gradient-to-br from-[#0c3837] to-[#114443] rounded-[2.5rem] p-8 sm:p-12 text-white shadow-xl relative overflow-hidden border border-[#1b5e5c]/40">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            
            <div class="space-y-4">
                <span class="text-xs font-bold text-[#d4ed31] uppercase tracking-wider block">Visi Pembangunan Desa</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white leading-snug">
                    "Terwujudnya {{ $desa->nama_desa }} yang Maju, Religius, Sejahtera, dan Berbasis Teknologi Informasi Melayani."
                </h2>
                <p class="text-sm text-[#8bc3b8] leading-relaxed">
                    Menjadikan desa sebagai pelopor tata kelola modern yang mengedepankan kearifan lokal, pelayanan prima cepat tanggap, dan kemandirian ekonomi masyarakat.
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/15 space-y-4">
                <span class="text-xs font-bold text-[#d4ed31] uppercase tracking-wider block">Misi Utama</span>
                <ul class="space-y-3 text-xs sm:text-sm text-slate-100">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-[#10b981] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">1</span>
                        <span>Meningkatkan profesionalisme aparatur dalam pelayanan administrasi kependudukan dan persuratan mandiri.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-[#10b981] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">2</span>
                        <span>Mendorong transparansi pengelolaan anggaran dan pembangunan infrastruktur desa berbasis partisipasi warga.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-[#10b981] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">3</span>
                        <span>Mengembangkan potensi pertanian, pariwisata, dan UMKM desa melalui inovasi kelembagaan BUMDes.</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Sejarah & Geografis Wilayah -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-6 space-y-5">
            <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Sejarah & Wilayah</span>
            <h3 class="text-2xl sm:text-3xl font-black text-[#0c3837]">
                Kilas Balik & Kondisi Geografis Desa
            </h3>
            <p class="text-sm text-[#64748b] leading-relaxed text-justify">
                {{ $desa->nama_desa }} merupakan salah satu desa di wilayah Kecamatan {{ $desa->kecamatan }}, Kabupaten {{ $desa->kabupaten }}, Provinsi {{ $desa->provinsi }}. Memiliki bentang alam yang subur dan udara pegunungan yang sejuk, masyarakat desa mayoritas bergerak pada sektor pertanian pangan, perkebunan holtikultura, perdagangan, dan industri kreatif rumahan.
            </p>
            <p class="text-sm text-[#64748b] leading-relaxed text-justify">
                Dengan kode pos resmi {{ $desa->kode_pos }}, desa ini terus berbenah menjadi desa digital terdepan di Jawa Barat melalui integrasi sistem DIGIDES.
            </p>
        </div>

        <div class="lg:col-span-6">
            <div class="rounded-3xl overflow-hidden shadow-md border border-[#e1ede8] aspect-[16/10] bg-[#e2f0ed]">
                <img src="{{ asset('images/public/desa-alam.jpg') }}" alt="Bentang Alam Desa" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    <!-- Jajaran Aparatur Desa (Struktur Kepengurusan) -->
    <div class="space-y-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block mb-1">Pemerintahan Desa</span>
            <h3 class="text-3xl font-black text-[#0c3837]">Struktur & Jajaran Aparatur</h3>
            <p class="text-xs sm:text-sm text-[#64748b] mt-1">Perangkat desa yang siap melayani kebutuhan administratif dan pembangunan.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Kepala Desa Card -->
            <div class="bg-white rounded-3xl p-5 border-2 border-[#10b981] shadow-sm text-center space-y-3">
                <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-[#0c3837] shadow-sm">
                    <img src="{{ asset('images/public/kades-hero.jpg') }}" alt="{{ $desa->nama_kades }}" class="w-full h-full object-cover object-top">
                </div>
                <div>
                    <h4 class="text-base font-bold text-[#0c3837]">{{ $desa->nama_kades }}</h4>
                    <p class="text-xs font-semibold text-[#10b981]">Kepala Desa</p>
                    <p class="text-[10px] text-[#64748b] mt-1">NIP: {{ $desa->nip_kades ?? '-' }}</p>
                </div>
            </div>

            @forelse($aparaturList as $ap)
                @php
                    $namaAparat = $ap->penduduk?->nama ?? 'Aparatur Desa';
                    $fotoAparat = $ap->penduduk?->foto_url ?? null;
                @endphp
                <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-sm text-center space-y-3 hover:border-[#10b981] transition-colors">
                    <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-[#e2f0ed] shadow-xs">
                        @if($fotoAparat)
                            <img src="{{ $fotoAparat }}" alt="{{ $namaAparat }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#114443] to-[#0c3837] text-white text-xl font-bold">
                                {{ substr($namaAparat, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-[#0c3837]">{{ $namaAparat }}</h4>
                        <p class="text-xs font-semibold text-[#4fa394]">{{ $ap->jabatan }}</p>
                        <p class="text-[10px] text-[#64748b] mt-1">NIP/SK: {{ $ap->nip ?? '-' }}</p>
                    </div>
                </div>
            @empty
                <!-- Fallback standard staff -->
                <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-sm text-center space-y-3">
                    <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-[#0c3837] shadow-xs">
                        <img src="{{ asset('images/public/pelayanan-frontdesk.jpg') }}" alt="Sekretaris Desa" class="w-full h-full object-cover object-top">
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-[#0c3837]">Siti Rahmawati, S.Sos</h4>
                        <p class="text-xs font-semibold text-[#4fa394]">Sekretaris Desa</p>
                        <p class="text-[10px] text-[#64748b] mt-1">NIP: 198204122008012004</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-sm text-center space-y-3">
                    <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-[#0c3837] shadow-xs">
                        <img src="{{ asset('images/avatar-jack.jpg') }}" alt="Kasi Pelayanan" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-[#0c3837]">Jack Grealish</h4>
                        <p class="text-xs font-semibold text-[#4fa394]">Kasi Pelayanan & Persuratan</p>
                        <p class="text-[10px] text-[#64748b] mt-1">Staff Pelayanan</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-sm text-center space-y-3">
                    <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-[#0c3837] shadow-xs">
                        <div class="w-full h-full flex items-center justify-center bg-[#114443] text-[#d4ed31] font-bold text-xl">
                            A
                        </div>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-[#0c3837]">Ahmad Fauzi</h4>
                        <p class="text-xs font-semibold text-[#4fa394]">Kaur Keuangan</p>
                        <p class="text-[10px] text-[#64748b] mt-1">Perangkat Desa</p>
                    </div>
                </div>
            @endforelse

        </div>
    </div>

</div>
@endsection
