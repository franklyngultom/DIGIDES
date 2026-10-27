@extends('layouts.public')

@section('content')
<div class="space-y-12">

    <!-- Header Banner -->
    <section class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#082424] text-white py-14 sm:py-20 border-b border-[#1b5e5c]/40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-[#8bc3b8] mb-4">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-semibold">Indeks Desa Membangun</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Kementerian Desa, PDT, dan Transmigrasi RI
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Indeks Desa Membangun (IDM)
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Pengukuran komposit kemandirian desa melalui penguatan ketahanan sosial, ketahanan ekonomi, dan ketahanan ekologi lingkungan.
                </p>
            </div>
        </div>
    </section>

    <!-- Main IDM Status Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Big IDM Scorecard -->
        <div class="bg-gradient-to-br from-[#0c3837] via-[#114443] to-[#082424] rounded-[2.5rem] p-8 sm:p-12 text-white shadow-2xl border border-[#1b5e5c]/40 relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-xs font-bold text-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-[#10b981] animate-ping"></span>
                        Penetapan Resmi Tahun {{ $idm['tahun'] }}
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">
                        Status Kemandirian: <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-200 to-white">{{ $idm['status'] }}</span>
                    </h2>
                    <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed max-w-2xl">
                        {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }} berhasil mempertahankan predikat kasta tertinggi <strong>Desa Mandiri</strong> dengan kemampuan mandiri dalam menggerakkan pembangunan berkelanjutan dan kesejahteraan warga.
                    </p>
                    <div class="pt-2 flex items-center gap-3 text-xs text-emerald-200">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Peringkat Nasional: {{ $idm['ranking_nasional'] }}</span>
                    </div>
                </div>

                <div class="lg:col-span-4 flex justify-center lg:justify-end">
                    <div class="w-48 h-48 rounded-full bg-white/10 backdrop-blur-md border-4 border-emerald-400 flex flex-col items-center justify-center p-4 text-center shadow-2xl">
                        <span class="text-xs text-[#8bc3b8] uppercase font-bold tracking-wider">Nilai Skor IDM</span>
                        <span class="text-4xl font-black text-white mt-1">{{ number_format($idm['skor'], 4) }}</span>
                        <span class="text-[10px] text-emerald-300 font-bold mt-1 px-2.5 py-0.5 rounded-full bg-white/15">Sangat Maju</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3 Pilar Ketahanan IDM -->
        <div class="space-y-6">
            <div>
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Tiga Pilar Utama</span>
                <h3 class="text-2xl font-black text-[#0c3837]">Indikator Komposit Penyusun IDM</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($idm['pilar'] as $pilar)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#e1ede8] shadow-xs space-y-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                {{ $pilar['status'] }}
                            </span>
                            <span class="text-2xl font-black text-[#0c3837]">
                                {{ number_format($pilar['skor'], 4) }}
                            </span>
                        </div>
                        <h4 class="text-base font-bold text-[#0c3837] leading-snug">
                            {{ $pilar['nama'] }}
                        </h4>
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            {{ $pilar['deskripsi'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Perkembangan Skor Tahun ke Tahun -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#e1ede8] shadow-xs space-y-6">
            <div>
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Histori Kemajuan</span>
                <h3 class="text-2xl font-black text-[#0c3837]">Perkembangan IDM 5 Tahun Terakhir</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                @foreach($idm['histori'] as $histori)
                    <div class="p-4 rounded-2xl {{ $histori['tahun'] == 2026 ? 'bg-gradient-to-br from-[#0c3837] to-[#114443] text-white shadow-md' : 'bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8]' }} text-center space-y-2">
                        <span class="text-xs font-bold block {{ $histori['tahun'] == 2026 ? 'text-emerald-300' : 'text-[#64748b]' }}">
                            Tahun {{ $histori['tahun'] }}
                        </span>
                        <p class="text-2xl font-black">{{ number_format($histori['skor'], 4) }}</p>
                        <span class="text-[11px] font-semibold block {{ $histori['tahun'] == 2026 ? 'text-emerald-200' : 'text-[#10b981]' }}">
                            {{ $histori['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
